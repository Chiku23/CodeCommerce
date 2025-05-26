<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'brand_id',
        'category_id',
        'description',
        'is_featured',
        'status',
        'quantity',
        'sku',
        'cost_price',
        'compare_at_price',
        'price',
    ];
}
