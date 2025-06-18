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

        // Find the order and check ownership
        $order = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
        ->first();

        // Return 404 if order not found
        if (!$order) {
            return static::sendNotFoundResponse();
        }

        // Check if user owns this order
        if ($order->user_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to view this order'
            ], Response::HTTP_FORBIDDEN);
        }

        // Transform the data into structured format
        $transformedData = [
            'data' => [
                'id' => $order->id,
                'financial_data' => [
                    'payment' => [
                        'plan' => $order->payment_plan,
                        'method' => $order->payment_method,
                        'status' => $order->payment_status,
                        'payment_id' => $order->payment_id,
                        'status' => $order->status,
                        'amount' => $order->payment->amount,
                        'unit_price' => formatToArabic($order->unit->unit_price),
                    ],
                    'tax' => [
                        'exemption_status' => $order->tax_exemption_status
                    ]
                ],
                'property_data' => [
                    'unit_title' => $order->unit->title,
                    'unit_building_number' => $order->unit->building_number,
                    'project_name' => $order->unit->project->title,
                    'unit_type' => $order->unit->unit_type,
                    'unit_area' => $order->unit->total_area,
                ],
                'metadata' => [
                    'user_id' => $order->user_id,
                    'note' => $order->note,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at
                ]
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
