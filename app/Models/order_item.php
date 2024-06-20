<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Items;

class order_item extends Model
{
    use HasFactory;
    protected $fillable = ['item_id','quantity', 'order_id',];
   
}
