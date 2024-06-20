<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function Catigories():HasMany {
        return $this->HasMany(Category::class,'product_id','id');
    }
}

