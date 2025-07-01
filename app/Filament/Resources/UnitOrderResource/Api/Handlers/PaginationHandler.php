<?php
namespace App\Filament\Resources\UnitOrderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\UnitOrderResource;
use Illuminate\Support\Facades\Auth;
use App\Models\UnitOrder;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/{uuid}/orders';
    public static string | null $resource = UnitOrderResource::class;

    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();
        $user = Auth::user();

        $query = QueryBuilder::for(UnitOrder::class)
        ->where('user_id', $user->id)
        ->with([
            'unit:id,project_id,title,building_number',
            'unit.project:id,title,completion_percentage,enables_payment_plan',
            'installments.milestone:id,completion_milestone'
        ])
        ->latest()
        ->where('user_id', $user->id)
        ->with('unit')
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        // change some fields
        $query->getCollection()->transform(function ($item) {
            
            $item->unit_title = $item->unit->title;
            $item->unit_building_number = $item->unit->building_number;
            $item->project_name = $item->unit->project->title;
            // ---== المنطق الحسابي الجديد ==---
            if ($item->unit?->project?->enables_payment_plan) {
                $item->project_enables_payment_plan = $item->unit->project->enables_payment_plan;
                $installments = $item->installments;
                $totalCount = $installments->count();
                $paidCount = $installments->where('status', 'paid')->count();
                $nextInstallment = $installments->whereIn('status', ['pending', 'due', 'overdue'])->sortBy('id')->first();
                
                $nextMilestoneDetails = null;
                if ($nextInstallment) {
                    $nextMilestoneDetails = $nextInstallment->milestone?->completion_milestone;
                } elseif ($totalCount > 0 && $paidCount === $totalCount) {
                    $nextMilestoneDetails = "جميع الدفعات مكتملة";
                }

                $item->payment_progress_percentage = ($totalCount > 0) ? round(($paidCount / $totalCount) * 100) : 0;
                $item->remaining_installments_count = $totalCount - $paidCount;
                $item->next_installment_milestone = $nextMilestoneDetails;
                $item->project_completion_percentage = $item->unit->project->completion_percentage;
                
            }

            return $item;
        });
        
        // hide some fields
        $query->getCollection()->makeHidden(['user_id', 'unit_id','created_at','unit','installments']);

        return static::getApiTransformer()::collection($query);
    }
}
