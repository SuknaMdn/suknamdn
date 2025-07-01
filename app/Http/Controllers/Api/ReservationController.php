<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitOrder;

class ReservationController extends Controller
{
    // هذا يغذي كل الشاشات التفصيلية للحجز
    public function __invoke(Request $request, UnitOrder $unitOrder)
    {
        // حماية: التأكد من أن المستخدم يملك هذا الحجز
        if ($request->user()->id !== $unitOrder->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // تحميل كل العلاقات المطلوبة مرة واحدة
        $unitOrder->load([
            'unit' => fn ($q) => $q->select('id', 'project_id', 'unit_number', 'unit_type', 'total_area', 'unit_price', 'total_amount'),
            'unit.project' => fn ($q) => $q->with('developer:id,name')->select('id', 'developer_id', 'title', 'completion_percentage', 'architect_office_name'),
            'installments.milestone' => fn ($q) => $q->select('id', 'name', 'percentage', 'order'), // `milestone` هي العلاقة داخل `OrderInstallment`
        ]);

        return response()->json($unitOrder);
    }
}
