<?php

namespace App\Observers;

use App\Models\UnitOrder;
use App\Models\OrderInstallment;

class UnitOrderObserver
{
    public function created(UnitOrder $unitOrder): void
    {
        $unit = $unitOrder->unit;

        if (!$unit?->project?->enables_payment_plan) {
            return; // توقف إذا لم تكن الميزة مفعلة
        }

        $projectMilestones = $unit->project->paymentMilestones;
        if ($projectMilestones->isEmpty()) return;

        $totalAmount = $unit->total_amount;
        $installments = [];

        foreach ($projectMilestones as $milestone) {
            $amount = ($totalAmount * $milestone->percentage) / 100;
            $installments[] = [
                'unit_order_id'              => $unitOrder->id,
                'project_payment_milestone_id' => $milestone->id,
                'amount'                     => $amount,
                'status'                     => 'pending',
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ];
        }

        if (!empty($installments)) {
            OrderInstallment::insert($installments);
        }
    }

    /**
     * Handle the UnitOrder "updated" event.
     */
    public function updated(UnitOrder $unitOrder): void
    {
        //
    }

    /**
     * Handle the UnitOrder "deleted" event.
     */
    public function deleted(UnitOrder $unitOrder): void
    {
        //
    }

    /**
     * Handle the UnitOrder "restored" event.
     */
    public function restored(UnitOrder $unitOrder): void
    {
        //
    }

    /**
     * Handle the UnitOrder "force deleted" event.
     */
    public function forceDeleted(UnitOrder $unitOrder): void
    {
        //
    }
}
