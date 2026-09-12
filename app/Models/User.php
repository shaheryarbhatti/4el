<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;   // gives ->hasRole(), ->assignRole()

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',        // extra profile fields used across the marketplace
        'avatar',
        'status',       // active | blocked
        // Saved default address (My Account > Profile; pre-fills checkout)
        'address_line',
        'city',
        'state',
        'postal_code',
        'country',
    ];

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

    /* ----------------------------------------------------------------------
     |  Convenience role helpers (thin wrappers around Spatie roles)
     |  Use these in code/views to keep intent obvious: $user->isVendor()
     * -------------------------------------------------------------------- */
    public function isAdmin(): bool    { return $this->hasRole('admin'); }
    public function isVendor(): bool   { return $this->hasRole('vendor'); }
    public function isCustomer(): bool { return $this->hasRole('customer'); }

    /**
     * A vendor has ONE vendor profile (store details, approval status).
     */
    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class);
    }

    /**
     * Products owned by this user (when they are a vendor/store).
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    /**
     * Orders placed by this user (as a customer).
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Order line items belonging to this user's store (as a vendor).
     */
    public function vendorOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'vendor_id');
    }
}
