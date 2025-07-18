<?php
namespace App\Filament\Resources\AddressResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\AddressResource;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = AddressResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        if ($request->has('is_default') && $request->boolean('is_default') === true) {
            static::getModel()
                ::where('user_id', $model->user_id)
                ->where('id', '!=', $model->id)
                ->update(['is_default' => false]);
        }
        
        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}