<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\QrCode;

use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($unit) {
            $unit->slug = Str::slug($unit->title);
        });

        static::updating(function ($unit) {
            $unit->slug = Str::slug($unit->title);
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    protected function location(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (!isset($attributes['latitude']) || !isset($attributes['longitude'])) {
                    return null;
                }

                return [
                    'lat' => (float) $attributes['latitude'],
                    'lng' => (float) $attributes['longitude'],
                    'geojson' => json_decode($attributes['geojson'] ?? '{}')
                ];
            },
            set: function ($value) {
                if (!$value) {
                    return [
                        'latitude' => null,
                        'longitude' => null,
                        'geojson' => null
                    ];
                }

                return [
                    'latitude' => $value['lat'] ?? null,
                    'longitude' => $value['lng'] ?? null,
                    'geojson' => is_string($value['geojson'] ?? null)
                        ? $value['geojson']
                        : json_encode($value['geojson'] ?? [])
                ];
            }
        );
    }


    public function images(): HasMany
    {
        return $this->hasMany(UnitImage::class);
    }

    public function floorPlan(): HasMany
    {
        return $this->hasMany(UnitImage::class)->where('type', 'floor_plan');
    }


    public function generateQrCode()
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $this->slug,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            labelText: 'Sukna - ' . $this->title,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center,
            size: 600,
            margin: 10,
        );

        $result = $builder->build();
        $directory = storage_path('app/public/qr-codes');

        $filename = "unit-{$this->slug}.png";

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $result->saveToFile($directory . '/' . $filename);

        $this->qr_code = 'qr-codes/' . $filename;
        $this->save();

    }

    public function afterSalesServices()
    {
        return $this->belongsToMany(AfterSalesService::class, 'unit_after_sales_service');
    }

    public function additionalFeatures()
    {
        return $this->belongsToMany(AdditionalFeature::class, 'additional_feature_unit');
    }

    public function orders()
    {
        return $this->hasMany(UnitOrder::class);
    }

    // Morph relationship with Payment
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
