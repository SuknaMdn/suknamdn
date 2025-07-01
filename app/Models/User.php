<?php

namespace App\Models;

use App\Livewire\Developer\Dashboard\Orders\Order;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar, HasName, HasMedia
{
    use InteractsWithMedia;
    use HasUuids, HasRoles;
    use HasApiTokens, HasFactory, Notifiable;
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'username',
        'email',
        'firstname',
        'country_code',
        'phone',
        'lastname',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $dates = ['deleted_at'];

        /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a user, check for soft deleted records with same phone
        static::creating(function ($user) {
            // Check if there's a soft deleted user with the same phone
            $existingUser = static::withTrashed()->where('phone', $user->phone)->first();

            if ($existingUser && $existingUser->trashed()) {
                // Restore the soft deleted user instead of creating new one
                $existingUser->restore();

                // Update the restored user with new data if needed
                $existingUser->update([
                    'username' => $user->username,
                    'password' => $user->password,
                    // Add other fields you want to update
                ]);

                // Return the restored user (this prevents the new user creation)
                return $existingUser;
            }
        });
    }

    /**
     * Find user by phone including soft deleted ones
     */
    public static function findByPhoneWithTrashed($phone)
    {
        return static::withTrashed()->where('phone', $phone)->first();
    }

    /**
     * Restore or create user by phone
     */
    public static function restoreOrCreate($phone, $attributes = [])
    {
        $user = static::withTrashed()->where('phone', $phone)->first();

        if ($user && $user->trashed()) {
            // Restore soft deleted user
            $user->restore();

            // Update with new attributes if provided
            if (!empty($attributes)) {
                $user->update($attributes);
            }

            return $user;
        }

        // Create new user if none exists
        return static::create(array_merge(['phone' => $phone], $attributes));
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@sukna.sa') && $this->hasVerifiedEmail();
        }
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatars')?->first()?->getUrl() ?? $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? null;
    }

    // Define an accessor for the 'name' attribute
    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('filament-shield.super_admin.name'));
    }

    public function isDeveloper()
    {
        return $this->hasRole('developer');
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function favorites()
    {
        return $this->morphedByMany(Project::class, 'favoritable', 'favorites');
    }

    public function developer()
    {
        return $this->hasOne(Developer::class);
    }

    public function address() {
        return $this->hasMany(Address::class);
    }

    public function favoritesProjects()
    {
        return $this->morphedByMany(Project::class, 'favoritable', 'favorites');
    }

    public function favoritesUnits()
    {
        return $this->morphedByMany(Unit::class, 'favoritable', 'favorites');
    }

    public function orders(){
        return $this->hasMany(UnitOrder::class, 'user_id');
    }

    // دالة للحصول على المستخدمين الذين اشتروا وحدات تابعة لمشاريع مطور معين
    public static function usersWhoPurchasedFromDeveloper($developerId)
    {
        return self::whereHas('orders.unit.project.developer', function ($query) use ($developerId) {
            $query->where('user_id', $developerId);
        })->get();
    }

    public function unitOrders()
    {
        return $this->hasMany(UnitOrder::class, 'user_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }
    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class, 'user_id');
    }
}
