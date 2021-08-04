<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category_id',
        'description',
        'stripped_description',
        'date_and_time',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function upload(){
        return $this->hasMany(ImageUpload::class, 'product_id');
    }
}
