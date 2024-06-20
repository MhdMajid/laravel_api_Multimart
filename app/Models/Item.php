<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Payment_detail;
use App\Models\Wish_list;
use App\Models\Order_item;
use App\Models\Shopping_cart;
use App\Models\rating;


class Item extends Model
{
    use HasFactory;
    Protected $fillable  = ['category_id','name','category_name','price','product_image','description','is_discount','discount'];
    public function Payment_details():HasMany {
        return $this->HasMany(Category::class,'Item_id','id');
    }
    public function Wish_lists():HasMany {
        return $this->HasMany(Wish_list::class,'Item_id','id');
    }
    public function Order_items():HasMany {
        return $this->HasMany(Order_item::class,'Item_id','id');
    }
    public function Shopping_carts():HasMany {
        return $this->HasMany(Shopping_cart::class,'Item_id','id');
    }

    public function properties():HasMany {
        return $this->hasOne(Property::class,'Item_id','id');
    }

    public function ratings():HasMany {
        return $this->HasMany(rating::class);
    }

    // public function cat() {
    //     return $this->belongsTo(Category::class, 'Category_id');
    // }

    public function averageRating(){
        return $this->ratings()->avg('rating');
    }

    // public function userRating(){
    //     return $this->ratings()->get('rating');
    // }


}
