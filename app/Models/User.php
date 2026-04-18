<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'phone',
        'address_line_1',
        'address_line_2',
        'district',
        'city',
        'postal_code',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the full formatted address for the user.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $addressParts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->district,
            $this->postal_code
        ]);
        
        return implode(', ', $addressParts);
    }

    /**
     * Check if user has a complete address.
     *
     * @return bool
     */
    public function hasCompleteAddress(): bool
    {
        return !empty($this->address_line_1) && 
               !empty($this->city) && 
               !empty($this->district);
    }

    /**
     * Get the full URL for the user's avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(storage_path('app/public/avatars/' . $this->avatar))) {
            return asset('storage/avatars/' . $this->avatar) . '?v=' . time();
        }
        return asset('images/default-avatar.png');
    }
}
