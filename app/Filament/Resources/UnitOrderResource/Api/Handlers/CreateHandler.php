<?php
namespace App\Filament\Resources\UnitOrderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UnitOrderResource;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = UnitOrderResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}