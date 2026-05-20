<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'location',
        'contact',
        'business_type',
        'opening_hours',
        'logo_path',
        'cover_path',
        'local_network_url',
        'is_active',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_active' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class)->orderBy('display_order');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the URL for a specific table, considering local network overrides.
     */
    public function getTableUrl($tableId)
    {
        $baseUrl = $this->local_network_url ?: config('app.url');
        
        // Ensure baseUrl doesn't have a trailing slash
        $baseUrl = rtrim($baseUrl, '/');
        
        return "{$baseUrl}/r/{$this->id}/t/{$tableId}";
    }
}
