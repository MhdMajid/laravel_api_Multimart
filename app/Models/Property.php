<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Size; 
use App\Models\Color; 

class Property extends Model
{
    use HasFactory;

    protected $fillable =['quantity','item_id','size','color'];

    public function color(){
        return $this->hasMany(Color::Class , 'property_id');
    }

    public function size(){
        return $this->hasMany(Size::Class , 'property_id');
    }

}

