<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'category_id',
        'restaurant_id',
        'name',
        'description',
        'price',
        'photo_path',
        'is_available',
        'display_order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
