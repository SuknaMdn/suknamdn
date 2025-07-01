<?php
namespace App\Livewire\Developer\Dashboard\Projects;

use Livewire\Component;
use App\Models\Project;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Routing\Annotation\Route;
#[Layout('components.layouts.developer')]
#[Title('تعديل المشروع')]
#[Route('developer/projects/{project}/edit', name: 'developer.projects.edit')]
class ProjectForm extends Component
{
    use WithFileUploads;

    public Project $project;
    // الخصائص الجديدة
    public $paymentMilestones = [];

    protected function rules()
    {
        return [
            'project.title' => 'required|string|max:255',
            'project.description' => 'nullable|string',
            'project.enables_payment_plan' => 'boolean',
            'project.completion_percentage' => 'nullable|required_if:project.enables_payment_plan,true|integer|min:0|max:100',
            'project.architect_office_name' => 'nullable|string',
            'project.contractor_name' => 'nullable|string',
            'project.supervisor_name' => 'nullable|string',
            'paymentMilestones.*.name' => 'required_if:project.enables_payment_plan,true|string|max:255',
            'paymentMilestones.*.percentage' => 'required_if:project.enables_payment_plan,true|integer|min:1|max:100',
            'paymentMilestones.*.completion_milestone' => 'required_if:project.enables_payment_plan,true|string|max:500',
        ];
    }

    protected $messages = [
        'project.title.required' => 'عنوان المشروع مطلوب',
        'project.completion_percentage.required_if' => 'نسبة الإنجاز مطلوبة عند تفعيل نظام البيع على الخارطة',
        'project.completion_percentage.integer' => 'نسبة الإنجاز يجب أن تكون رقم صحيح',
        'project.completion_percentage.min' => 'نسبة الإنجاز لا يمكن أن تكون أقل من 0',
        'project.completion_percentage.max' => 'نسبة الإنجاز لا يمكن أن تتجاوز 100',
        'paymentMilestones.*.name.required_if' => 'اسم الدفعة مطلوب',
        'paymentMilestones.*.percentage.required_if' => 'نسبة الدفعة مطلوبة',
        'paymentMilestones.*.percentage.min' => 'نسبة الدفعة يجب أن تكون أكبر من 0',
        'paymentMilestones.*.percentage.max' => 'نسبة الدفعة لا يمكن أن تتجاوز 100',
        'paymentMilestones.*.completion_milestone.required_if' => 'شرط الاستحقاق مطلوب',
    ];

    public function mount(string $slug)
    {
        $this->project = Project::where('slug', $slug)->firstOrFail();
        
        // تحميل الدفعات (كما في السابق)
        if ($this->project->exists) {
            $this->paymentMilestones = $this->project->paymentMilestones()
                ->orderBy('order')
                ->get()
                ->map(function ($milestone) {
                    return [
                        'name' => $milestone->name,
                        'percentage' => $milestone->percentage,
                        'completion_milestone' => $milestone->completion_milestone,
                    ];
                })->toArray();
        } else {
            $this->addMilestoneRow();
        }
    }


    public function updatedProjectEnablesPaymentPlan($value)
    {
        // عند تفعيل نظام الدفع، تأكد من وجود دفعة واحدة على الأقل
        if ($value && empty($this->paymentMilestones)) {
            $this->addMilestoneRow();
        }
    }

    public function addMilestoneRow()
    {
        dd('Adding a new milestone row');
        try {
            $this->paymentMilestones[] = [
                'name' => '',
                'percentage' => '',
                'completion_milestone' => ''
            ];
            
            // Clear any previous errors
            $this->resetErrorBag('paymentMilestones');
                        
            // Optional: Add success message
            $this->dispatch('milestone-added', ['message' => 'تم إضافة دفعة جديدة']);
            
        } catch (\Exception $e) {
            $this->addError('paymentMilestones', 'حدث خطأ أثناء إضافة الدفعة');
        }
    }

    public function removeMilestoneRow($index)
    {
        try {
            // التأكد من وجود الفهرس
            if (!isset($this->paymentMilestones[$index])) {
                $this->addError('paymentMilestones', 'الدفعة المحددة غير موجودة');
                return;
            }

            // التأكد من وجود دفعة واحدة على الأقل
            if (count($this->paymentMilestones) <= 1) {
                $this->addError('paymentMilestones', 'يجب أن يحتوي المشروع على دفعة واحدة على الأقل');
                return;
            }

            // حذف الدفعة
            unset($this->paymentMilestones[$index]);
            
            // إعادة ترقيم المصفوفة
            $this->paymentMilestones = array_values($this->paymentMilestones);
            
            // Clear any previous errors
            $this->resetErrorBag('paymentMilestones');
            
            // Optional: Add success message
            session()->flash('milestone_removed', 'تم حذف الدفعة بنجاح');
            
        } catch (\Exception $e) {
            $this->addError('paymentMilestones', 'حدث خطأ أثناء حذف الدفعة');
        }
    }

    // إضافة دالة للتحقق من debugging
    public function testClick()
    {
        session()->flash('test', 'الزر يعمل بشكل صحيح!');
    }

    public function save()
    {
        $this->validate();

        // تأكد من أن نسبة الدفعات الإجمالية لا تتجاوز 100%
        if ($this->project->enables_payment_plan && !empty($this->paymentMilestones)) {
            $totalPercentage = collect($this->paymentMilestones)->sum('percentage');
            
            if ($totalPercentage > 100) {
                $this->addError('paymentMilestones', 'مجموع نسب الدفعات لا يمكن أن يتجاوز 100%. المجموع الحالي: ' . $totalPercentage . '%');
                return;
            }

            if ($totalPercentage < 100) {
                $this->addError('paymentMilestones', 'مجموع نسب الدفعات يجب أن يساوي 100%. المجموع الحالي: ' . $totalPercentage . '%');
                return;
            }
        }

        try {
            // حفظ بيانات المشروع الأساسية
            $this->project->save();

            // حفظ/تحديث جدول الدفعات
            if ($this->project->enables_payment_plan && !empty($this->paymentMilestones)) {
                // احذف الدفعات القديمة
                $this->project->paymentMilestones()->delete();
                
                // إضافة الدفعات الجديدة
                foreach ($this->paymentMilestones as $index => $milestoneData) {
                    $this->project->paymentMilestones()->create([
                        'name' => $milestoneData['name'],
                        'percentage' => $milestoneData['percentage'],
                        'completion_milestone' => $milestoneData['completion_milestone'],
                        'order' => $index + 1,
                    ]);
                }
            } else {
                // إذا تم إلغاء تفعيل الميزة، احذف الدفعات المرتبطة
                $this->project->paymentMilestones()->delete();
            }

            session()->flash('success', 'تم حفظ المشروع بنجاح!');
            return redirect()->to(route('developer.projects.index'));

        } catch (\Exception $e) {
            $this->addError('general', 'حدث خطأ أثناء حفظ المشروع. يرجى المحاولة مرة أخرى.');
            
        }
    }

    public function render()
    {
        return view('livewire.developer.dashboard.projects.project-form');
    }
}