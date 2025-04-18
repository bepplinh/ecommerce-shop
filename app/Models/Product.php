<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'category_id',
        'brand_id',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withPivot('stock')->withTimestamps();
    }
}
