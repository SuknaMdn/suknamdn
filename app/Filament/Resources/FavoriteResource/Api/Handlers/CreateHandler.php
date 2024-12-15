<?php
namespace App\Filament\Resources\FavoriteResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\FavoriteResource;
use Illuminate\Validation\ValidationException;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = FavoriteResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        try {

            $validationData = $request->validate([
                'favoritable_id' => 'required',
                'favoritable_type' => 'required|string',
                'user_id' => 'required|exists:users,id',
            ]);

            $model = new (static::getModel());

            $model->fill($request->all());

            $model->save();

            return static::sendSuccessResponse($model, "Successfully Create Resource");
        } catch (ValidationException $e) {
            // Return validation error response
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the resource',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

