<?php

namespace App\Livewire\Developer\Components;

use Livewire\Component;
use App\Services\NafathService;
use Illuminate\Support\Facades\Log;

class NafathAuth extends Component
{
    public $user;
    public $userType;
    public $showPopup = false;
    public $nafathRequestId = null;
    public $nafathStatus = 'PENDING'; // pending, WAITING, APPROVED, REJECTED, EXPIRED
    public $isCheckingNafathStatus = false;
    public $nationalId = '';
    public $isRequired = true;
    public $title = 'التحقق من الهوية';
    public $description = 'يرجى التحقق من هويتك باستخدام نفاذ لمتابعة استخدام النظام';
    public $statusCheckCount = 0;
    public $maxStatusChecks = 100; // Maximum 100 checks (5 minutes at 3-second intervals)
    public $transId = null;
    public $random = null;

    protected $listeners = [
        'showNafathPopup' => 'show',
        'hideNafathPopup' => 'hide',
        'checkNafathRequirement' => 'checkRequirement'
    ];

    protected $nafathService;

    public function __construct()
    {
        // parent::__construct();
        $this->nafathService = new NafathService();
    }

    public function mount($user = null, $userType = 'developer', $isRequired = true, $autoCheck = true)
    {
        $this->user = auth()->user();
        $this->userType = $userType;
        $this->isRequired = $isRequired;
        $this->nationalId = $this->user->national_id ?? '';
        
        if ($autoCheck) {
            $this->checkRequirement();
        }
    }

    public function checkRequirement()
    {
        if (empty($this->user->national_id)) {
            $this->showPopup = true;
            return true;
        }
        return false;
    }

    public function show($title = null, $description = null, $isRequired = null)
    {
        if ($title) $this->title = $title;
        if ($description) $this->description = $description;
        if ($isRequired !== null) $this->isRequired = $isRequired;
        
        $this->showPopup = true;
        $this->nafathStatus = 'PENDING';
        $this->nafathRequestId = null;
        $this->isCheckingNafathStatus = false;
        $this->statusCheckCount = 0;
        $this->resetErrorBag();
    }

    public function hide()
    {
        if (!$this->isRequired || (!empty($this->user->national_id))) {
            $this->showPopup = false;
            $this->isCheckingNafathStatus = false;
            $this->stopStatusCheck();
        }
    }

    public function initiateNafathAuth()
    {
        $this->validate([
            'nationalId' => 'required|string|size:10|regex:/^[0-9]+$/'
        ], [
            'nationalId.required' => 'رقم الهوية الوطنية مطلوب',
            'nationalId.size' => 'رقم الهوية الوطنية يجب أن يكون 10 أرقام',
            'nationalId.regex' => 'رقم الهوية الوطنية يجب أن يحتوي على أرقام فقط'
        ]);

        try {
            $response = $this->nafathService->createMfaRequest(
                $this->nationalId,
                'DigitalServiceEnrollmentWithoutBio'
            );
            
            if ($response['success']) {
                $this->nafathRequestId = $response['requestId'];
                $this->transId = $response['data']['transId'] ?? null;
                $this->random = $response['data']['random'] ?? null;
                
                $this->nafathStatus = 'WAITING';
                $this->statusCheckCount = 0;
                $this->startStatusCheck();
                
                if (empty($this->user->national_id)) {
                    $this->user->update(['national_id' => $this->nationalId]);
                }
            } else {
                $errorMessage = $response['error']['message'] ?? 'فشل في إنشاء طلب المصادقة';
                $this->addError('nafath', $errorMessage);
            }
        } catch (\Exception $e) {
            $this->addError('nafath', 'حدث خطأ أثناء المصادقة: ' . $e->getMessage());
        }
    }

    public function startStatusCheck()
    {
        $this->isCheckingNafathStatus = true;
        $this->dispatch('start-nafath-status-check');
    }

    public function stopStatusCheck()
    {
        $this->isCheckingNafathStatus = false;
        $this->dispatch('stop-nafath-status-check');
    }

    public function checkNafathStatus()
    {
        if (!$this->nafathRequestId || !$this->isCheckingNafathStatus || !$this->transId || !$this->random) {
            return;
        }

        // Prevent infinite checking
        $this->statusCheckCount++;
        if ($this->statusCheckCount > $this->maxStatusChecks) {
            $this->nafathStatus = 'EXPIRED';
            $this->isCheckingNafathStatus = false;
            $this->stopStatusCheck();
            $this->addError('nafath', 'انتهت مهلة انتظار الموافقة على الطلب');
            return;
        }

        try {
            $response = $this->nafathService->getMfaRequestStatus(
                $this->nationalId,
                $this->transId,
                $this->random
            );

            if ($response['success']) {
                $status = $response['data']['status'] ?? 'pending';
                $this->nafathStatus = $status;

                if ($status === 'COMPLETED') {
                    $this->handleSuccessfulVerification($response);
                } elseif (in_array($status, ['REJECTED', 'EXPIRED'])) {
                    $this->handleFailedVerification($status);
                }
            } else {
                $errorMessage = $response['error']['message'] ?? 'خطأ في فحص حالة الطلب';
                $this->addError('nafath', $errorMessage);
                $this->isCheckingNafathStatus = false;
                $this->stopStatusCheck();
            }
        } catch (\Exception $e) {
            Log::error('Nafath status check error: ' . $e->getMessage());
            // Continue checking on errors unless we've hit max attempts
            if ($this->statusCheckCount >= $this->maxStatusChecks) {
                $this->isCheckingNafathStatus = false;
                $this->stopStatusCheck();
                $this->addError('nafath', 'حدث خطأ أثناء فحص حالة المصادقة');
            }
        }
    }

    protected function handleSuccessfulVerification($response)
    {
        // Update user record
        $this->user->update([
            'national_id' => $this->nationalId,
            // 'nafath_verified' => true,
            // 'nafath_verified_at' => now()
        ]);
        
        // Update developer record if exists
        if ($this->user->developer) {
            $this->user->developer->update([
                'national_id' => $this->nationalId,
                // 'nafath_verified' => true,
                // 'nafath_verified_at' => now()
            ]);
        }
        
        $this->isCheckingNafathStatus = false;
        $this->stopStatusCheck();
        
        // Dispatch success event
        $this->dispatch('nafath-verified', [
            'user' => $this->user,
            'userType' => $this->userType
        ]);
        
        session()->flash('nafath-success', 'تم التحقق بنجاح من خلال نفاذ');
        
        if ($this->isRequired) {
            $this->showPopup = false;
        }
    }

    protected function handleFailedVerification($status)
    {
        $this->isCheckingNafathStatus = false;
        $this->stopStatusCheck();
        
        $errorMessage = $status === 'EXPIRED' 
            ? 'انتهت صلاحية طلب التحقق' 
            : 'فشل التحقق من نفاذ';
            
        $this->addError('nafath', $errorMessage);
    }

    public function resetNafath()
    {
        $this->nafathStatus = 'pending';
        $this->nafathRequestId = null;
        $this->transId = null;
        $this->random = null;
        $this->isCheckingNafathStatus = false;
        $this->statusCheckCount = 0;
        $this->stopStatusCheck();
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.developer.components.nafath-auth');
    }
}