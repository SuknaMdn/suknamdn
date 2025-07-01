<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainpageController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        
        // أولاً: جلب جميع الطلبات بدون فلترة للتشخيص
        $allOrders = $user->unitOrders()
            ->with([
                'installments.milestone',
                'unit:id,title,unit_number,project_id',
                'unit.project:id,title,completion_percentage,enables_payment_plan'
            ])
            ->latest()
            ->get();
        
        // معلومات التشخيص
        $debugInfo = [];
        foreach ($allOrders as $order) {
            $installmentsCount = $order->installments->count();
            $projectEnablesPayment = $order->unit->project->enables_payment_plan ?? false;
            
            $debugInfo[] = [
                'order_id' => $order->id,
                'status' => $order->status,
                'installments_count' => $installmentsCount,
                'project_enables_payment_plan' => $projectEnablesPayment,
                'unit_title' => $order->unit->title ?? 'N/A',
                'project_title' => $order->unit->project->title ?? 'N/A',
                'passes_status_filter' => $order->status === 'processing',
                'passes_project_filter' => $projectEnablesPayment,
                'passes_installments_filter' => $installmentsCount > 0,
            ];
        }
        
        // جلب الطلبات التي لديها أقساط مع حالات مختلفة
        $orders = $user->unitOrders()
            ->where('status', 'processing')
            ->whereHas('unit.project', fn($q) => $q->where('enables_payment_plan', true))
            ->whereHas('installments') // هذا السطر سيضمن وجود أقساط فقط
            ->with([
                'installments.milestone',
                'unit:id,title,unit_number,project_id',
                'unit.project:id,title,completion_percentage'
            ])
            ->latest()
            ->get();
        
        // إعداد المخرجات
        $data = $orders->map(function ($order) {
            $installments = $order->installments;
            $totalCount = $installments->count();
            
            // استبعاد الطلبات التي لا تحتوي على أقساط
            if ($totalCount === 0) {
                return null;
            }
            
            $paidCount = $installments->where('status', 'paid')->count();
            
            $nextInstallment = $installments
                ->whereIn('status', ['pending', 'due', 'overdue'])
                ->sortBy('id')
                ->first();
            
            $nextMilestoneDetails = null;
            if ($nextInstallment) {
                $nextMilestoneDetails = $nextInstallment->milestone?->completion_milestone;
            } elseif ($totalCount > 0 && $paidCount === $totalCount) {
                $nextMilestoneDetails = "جميع الدفعات مكتملة";
            }
            
            return [
                'unit_number' => $order->unit->title . ' ' . $order->unit->unit_number,
                'project_title' => $order->unit->project->title,
                'order_id' => $order->id,
                'order_status' => $order->status,
                'payment_plan_details' => [
                    'total_installments_count' => $totalCount,
                    'payment_progress_percentage' => round(($paidCount / $totalCount) * 100),
                    'remaining_installments_count' => $totalCount - $paidCount,
                    'next_installment_milestone' => $nextMilestoneDetails,
                    'project_completion_percentage' => $order->unit->project->completion_percentage,
                ]
            ];
        })->filter();
        
        return response()->json([
            'active_reservations' => $data,
            // 'debug_info' => $debugInfo, // معلومات التشخيص
            // 'total_orders_found' => $allOrders->count(),
            // 'filtered_orders_count' => $orders->count(),
            // 'final_data_count' => $data->count(),
        ]);
    }
}