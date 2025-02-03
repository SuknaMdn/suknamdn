<?php

namespace App\Filament\Resources\ProjectResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ProjectResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Models\Project;
class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ProjectResource::class;
    public static bool $public = true;

    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->with([
                'developer:id,name,logo',
                'propertyType:id,name',
                'city:id,name',
                'state:id,name',
                'facilities:id,title,icon,project_id',
                'operationalServices:id,title,icon,project_id',
                'warranties:id,title,content,icon,project_id',
                'landmarks:id,title,distance,project_id',
            ])
            ->where('is_active', true)
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        // Check if images is a string (JSON encoded) or already an array
        if ($query->images) {
            if (is_string($query->images)) {
                // Decode the JSON string into an array and transform each image path into a full URL
                $query->images = collect(json_decode($query->images))->map(function ($image) {
                    return asset('storage/' . $image);  // Prepend the base URL for images
                });
            } elseif (is_array($query->images)) {
                // If it's already an array, just map the image paths
                $query->images = collect($query->images)->map(function ($image) {
                    return asset('storage/' . $image);  // Prepend the base URL for images
                });
            }
        }

        // Transform facilities icons
        if ($query->facilities) {
            $query->facilities->transform(function ($facility) {
                if ($facility->icon) {
                    $facility->icon = asset('storage/' . $facility->icon);
                }
                return $facility;
            });
        }

        // Transform operational services icons
        if ($query->operationalServices) {
            $query->operationalServices->transform(function ($service) {
                if ($service->icon) {
                    $service->icon = asset('storage/' . $service->icon);
                }
                return $service;
            });
        }

        // Transform warranties icons
        if ($query->warranties) {
            $query->warranties->transform(function ($warranty) {
                if ($warranty->icon) {
                    $warranty->icon = asset('storage/' . $warranty->icon);
                }
                return $warranty;
            });
        }

        if ($query->mediaPDF) {
            $query->mediaPDF = asset('storage/' . $query->mediaPDF);  // Adjust path as needed
        }

        // Handle QR code
        if ($query->qr_code) {
            $query->qr_code = asset('storage/' . $query->qr_code);  // Adjust path as needed
        }

        // Calculate available units and total units
        $totalUnits = $query->units()->count();
        $availableUnits = $query->units()->where('status', 1)->where('case', 0)->count();

        // Add available units and total units to the project
        $query->available_units = $availableUnits;
        $query->total_units = $totalUnits;

        // Add starting price to the project
        $startingPrice = $query->units()->min('total_amount');
        $query->starting_from = $startingPrice ? number_format($startingPrice, 2, '.', ',') : null;

        if (auth('api')->check()) {
            $user = auth('api')->user();
            $query->is_favorite = $user->favorites()
                ->where('favoritable_id', $query->id)
                ->where('favoritable_type', Project::class)
                ->exists();
        } else {
            $query->is_favorite = false;
        }

        $transformer = static::getApiTransformer();

        return new $transformer($query);
    }
}
