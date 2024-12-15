<?php

namespace App\Filament\Resources\UnitResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UnitResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

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
            ->with(['images:id,unit_id,image_path', 'additionalFeatures', 'afterSalesServices', 'project:id,AdLicense'])
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        $transformedquery = tap($query, function ($item) {

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

            if ($item->project && $item->project->AdLicense) {
                $item->license = $item->project->AdLicense;
                $item->operationalServices = $item->project->operationalServices;
            }
        });

        $transformer = static::getApiTransformer();

        return new $transformer($transformedquery);
    }
}
