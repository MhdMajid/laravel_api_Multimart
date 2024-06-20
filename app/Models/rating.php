<?php

namespace App\Models;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rating extends Model
{
    use HasFactory;
    protected $fillable = [ 'user_id' ,'item_id', 'rating' ];

    public function item(){
        return $this->belongsTo(Item::class);
    }


}
