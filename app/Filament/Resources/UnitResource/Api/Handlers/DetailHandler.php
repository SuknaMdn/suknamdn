<?php

namespace App\Filament\Resources\UnitResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UnitResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Models\Unit;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = UnitResource::class;
    public static bool $public = true;


    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->with([
                'images:id,unit_id,image_path,type',
                'additionalFeatures',
                'afterSalesServices',
                // --== START: تعديلات مهمة ==--
                // تحميل المشروع مع الحقول الجديدة وخطة الدفعات (القالب)
                'project:id,title,AdLicense,developer_id,enables_payment_plan,architect_office_name,construction_supervisor_office,project_ownership,ad_license_qr,city_id,state_id,address',
                'project.city:id,name',
                'project.state:id,name', 
                'project.paymentMilestones:id,project_id,name,percentage',
                'project.operationalServices',
                'project.developer'
                // --== END: تعديلات مهمة ==--
            ])
            // ->with(['images:id,unit_id,image_path', 'additionalFeatures','project.operationalServices', 'afterSalesServices', 'project:id,AdLicense,developer_id'])
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        // --== START: حساب خطة الدفع المقترحة ديناميكيًا ==--
        $proposedPaymentPlan = [];

        if ($query->project && $query->project->enables_payment_plan && $query->project->paymentMilestones->isNotEmpty()) {
            $totalAmount = $query->total_amount;
            
            foreach ($query->project->paymentMilestones as $index => $milestone) {
                $calculatedAmount = ($totalAmount * $milestone->percentage) / 100;

                $proposedPaymentPlan[] = [
                    'index' => $index + 1,
                    'name' => $milestone->name,
                    'percentage' => $milestone->percentage,
                    'amount' => round($calculatedAmount, 2)
                ];
            }
        }
        // --== END: حساب خطة الدفع المقترحة ==--

        $transformedquery = tap($query, function ($item) use ($proposedPaymentPlan) {

            $item->unit_price = rtrim(rtrim(number_format($item->unit_price, 2, '.', ''), '0'), '.');
            $item->property_tax = rtrim(rtrim(number_format($item->property_tax, 2, '.', ''), '0'), '.');
            $item->total_amount = rtrim(rtrim(number_format($item->total_amount, 2, '.', ''), '0'), '.');
            // إضافة خطة الدفع المقترحة إلى الرد النهائي
            $item->proposed_payment_plan = $proposedPaymentPlan;

            if ($item->qr_code) {
                $item->qr_code = asset('storage/' . $item->qr_code);
            }

            if ($item->images->isNotEmpty()) {
                $item->images = $item->images->map(function ($image) {
                    $image->image_path = asset('storage/' . $image->image_path);
                    return $image;
                });
            }

            if ($item->additionalFeatures->isNotEmpty()) {
                $item->additionalFeatures = $item->additionalFeatures->map(function ($additionalFeature) {
                    if (!filter_var($additionalFeature->icon, FILTER_VALIDATE_URL)) {
                        $additionalFeature->icon = asset('storage/' . $additionalFeature->icon);
                    }
                    return $additionalFeature;
                });
            }

            if ($item->afterSalesServices->isNotEmpty()) {
                $item->afterSalesServices = $item->afterSalesServices->map(function ($afterSalesService) {
                    if (!filter_var($afterSalesService->icon, FILTER_VALIDATE_URL)) {
                        $afterSalesService->icon = asset('storage/' . $afterSalesService->icon);
                    }
                    return $afterSalesService;
                });
            }

            if ($item->project) {
                $item->license = $item->project->AdLicense;
                $item->project_name = $item->project->title;
                $item->enables_payment_plan = $item->project->enables_payment_plan;
                if ($item->project->operationalServices) {
                    $item->operationalServices = $item->project->operationalServices->map(function ($service) {
                        if ($service->icon && !filter_var($service->icon, FILTER_VALIDATE_URL)) {
                            $service->icon = asset('storage/' . $service->icon);
                        }
                        return $service;
                    });
                } else {
                    $item->operationalServices = collect([]);
                }

                $item->developer = $item->project->developer;

                if ($item->developer && $item->developer->logo && !filter_var($item->developer->logo, FILTER_VALIDATE_URL)) {
                    $item->developer->logo = asset('storage/' . $item->developer->logo);
                }

                $item->developer_phone = $item->developer->phone;

                $item->architect_office_name = $item->project->architect_office_name ?? null;
                $item->construction_supervisor_office = $item->project->construction_supervisor_office ?? null;
                $item->address = [
                    'city' => $item->project->city->name ?? null,
                    'state' => $item->project->state->name ?? null,
                    'street' => $item->project->address ?? null,
                ];
                $item->project_ownership = $item->project->project_ownership;
                $item->ad_license_qr = asset('storage/' . $item->project->ad_license_qr) ?? null;
                $item->threedurl = $item->project->threedurl ? $item->project->threedurl : null;
            }

            $item->makeHidden('project');


        });

        $transformer = static::getApiTransformer();

        return new $transformer($transformedquery);
    }
}
