<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $tables = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'price',
        'description',
        'wishlist',
        'product_image'
    ];
}
