<?php

namespace App\Filament\Resources\UnitOrderResource\Api\Handlers;

use App\Filament\Resources\UnitOrderResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UnitOrder;
use App\Http\Resources\UnitOrderResource as UnitOrderTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = UnitOrderResource::class;

    public function handler(Request $request)
    {
        $id = $request->route('id');
        $user = $request->user();

        // Get the query builder instance
        $query = static::getEloquentQuery();

        // --== START: الاستعلام المحسن لجلب كل البيانات الجديدة ==--
        // الاستعلام لتحميل كل البيانات اللازمة
        $order = UnitOrder::with([
            'unit:id,project_id,title,unit_number,unit_type,total_area,unit_price,total_amount,building_number,property_tax',
            'unit.project:id,developer_id,title,completion_percentage,enables_payment_plan',
            'installments.milestone:id,name,percentage,completion_milestone'
        ])
        ->where('user_id', $user->id)
        ->find($id);
        // --== END: الاستعلام المحسن ==--

        // Return 404 if order not found
        if (!$order) {
            return static::sendNotFoundResponse();
        }

        // Check if user owns this order
        if ($order->user_id !== $user->id) {
            return static::sendForbiddenResponse();
        }

        $paymentPlanDetails = null;

        // التحقق من أن المشروع يدعم خطط الدفع
        if ($order->unit?->project?->enables_payment_plan) {            
            $installments = $order->installments; // الوصول إلى الكولكشن
            $totalCount = $installments->count();
            $paidCount = $installments->where('status', 'paid')->count();
            
            $nextInstallment = $installments
                ->whereIn('status', ['pending', 'due', 'overdue'])
                ->sortBy('id')
                ->first();

            $nextMilestoneDetails = null;
            if ($nextInstallment) {
                $nextMilestoneDetails = $nextInstallment->milestone?->completion_milestone;
                // البحث عن index للدفعة القادمة في مجموعة الدفعات الأصلية
                $nextInstallmentIndex = $installments->values()->search(function ($inst) use ($nextInstallment) {
                    return $inst->id === $nextInstallment->id;
                });
                
            } elseif ($totalCount > 0 && $paidCount === $totalCount) {
                $nextMilestoneDetails = "جميع الدفعات مكتملة";
            }

            $paymentPlanDetails = [
                'payment_progress_percentage' => ($totalCount > 0) ? round(($paidCount / $totalCount) * 100) : 0,
                'remaining_installments_count' => $totalCount - $paidCount,
                'next_installment_milestone' => $nextMilestoneDetails,
                'next_installment' => $nextInstallment ? [
                    'index' => $nextInstallmentIndex !== false ? $nextInstallmentIndex + 1 : null,
                    'id' => $nextInstallment->id,
                    'name' => $nextInstallment->milestone?->name,
                    'amount' => number_format((float)$nextInstallment->amount, 0, '.', ''),
                    'status' => $nextInstallment->status,
                    'percentage' => $nextInstallment->milestone?->percentage,
                    'receipt_url' => $nextInstallment->receipt_url ? asset('storage/' .$nextInstallment->receipt_url) : null,
                ] : ($totalCount > 0 && $paidCount === $totalCount ? "جميع الدفعات مكتملة" : null),

                'project_completion_percentage' => $order->unit->project->completion_percentage,
                'istisna_contract_url' => $order->istisna_contract_url,
                'price_quote_url' => $order->price_quote_url,
                'installments' => $installments->values()->map(function ($inst, $index) {
                    return [
                        'index' => $index + 1,
                        'id' => $inst->id,
                        'name' => $inst->milestone?->name,
                        'amount' => $inst->amount,
                        'status' => $inst->status,
                        'percentage' => $inst->milestone?->percentage,
                        'receipt_url' => $inst->receipt_url ? asset('storage/' . $inst->receipt_url) : null,
                    ];
                }),

            ];
        }

        // Transform the data into structured format
        $transformedData = [
            'data' => [
                'id' => $order->id,
            
                'financial_data' => [
                    'payment' => [
                        'plan' => $order->payment_plan,
                        'method' => $order->payment_method,
                        'payment_status' => $order->payment_status, // new field
                        'payment_id' => $order->payment_id,
                        'amount' => $order->payment->amount,
                        'unit_price' => number_format((float)$order->unit->unit_price, 0, '.', ''),
                        'property_tax' => $order->unit->property_tax,  // new field
                    ],
                    'tax' => [
                        'exemption_status' => $order->tax_exemption_status
                    ],
                    // --== START: قسم جديد لخطة الدفع ==--
                    'payment_plan_details' => $paymentPlanDetails,// سيكون null إذا لم يكن المشروع بيع على الخارطة
                    // --== END: قسم جديد لخطة الدفع ==--
                ],
                'property_data' => [
                    'unit_title' => $order->unit->title,
                    'unit_building_number' => $order->unit->building_number,
                    'project_name' => $order->unit->project->title,
                    'project_enables_payment_plan' => $order->unit->project->enables_payment_plan,
                    'architect_office_name' => $order->unit->project->architect_office_name,
                    'construction_supervisor_office' => $order->unit->project->construction_supervisor_office,
                    'unit_type' => $order->unit->unit_type,
                    'unit_area' => $order->unit->total_area,
                    'completion_percentage' => $order->unit->project->completion_percentage,
                    'developer_name' => $order->unit->project->developer->name,
                ],
                'metadata' => [
                    'order_status' => $order->status,
                    'user_id' => $order->user_id,
                    'note' => $order->note,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at
                ],
                
            ]
        ];

        return response()->json($transformedData);
    }

    /**
     * Send forbidden response
     */
    protected static function sendForbiddenResponse()
    {
        return response()->json([
            'message' => 'You are not authorized to view this order'
        ], Response::HTTP_FORBIDDEN);
    }
}
