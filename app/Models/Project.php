<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
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

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            $project->slug = Str::slug($project->title);
        });

        static::updating(function ($project) {
            $project->slug = Str::slug($project->title);
        });
    }

    protected static function booted()
    {
        // static::created(function ($project) {
        //     $project->generateQrCode();
        // });

        // static::updated(function ($project) {
        //     // Regenerate QR if slug changes
        //     if ($project->wasChanged('slug')) {
        //         $project->generateQrCode();
        //     }
        // });
    }

    protected $casts = [
        'status' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'location' => 'array',
        'images' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(ProjectType::class, 'property_type_id', 'id');
    }

    public function facilities()
    {
        return $this->hasMany(ProjectFacility::class);
    }

    public function operationalServices()
    {
        return $this->hasMany(ProjectOperationalService::class);
    }

    public function warranties()
    {
        return $this->hasMany(ProjectWarranty::class);
    }

    public function favoritedBy()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function landmarks()
    {
        return $this->hasMany(ProjectLandmark::class);
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

        $filename = "project-{$this->slug}.png";

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $result->saveToFile($directory . '/' . $filename);

        $this->qr_code = 'qr-codes/' . $filename;
        $this->save();

    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_path ? Storage::url($this->qr_code_path) : null;
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    // public function setLocationAttribute($value)
    // {
    //     if (is_array($value)) {
    //         $this->attributes['location'] = json_encode($value);
    //     } else {
    //         $this->attributes['location'] = $value;
    //     }
    // }
}
