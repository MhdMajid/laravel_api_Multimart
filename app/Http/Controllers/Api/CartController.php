<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shopping_cart;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\RespondsWithHttpStatus;

class CartController extends Controller
{
    
    use RespondsWithHttpStatus;
    public function addToCart(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'item_id' => 'required|exists:items,id',
            'count' ,
        ]);
        if($validate->fails()){
                return $this->failure('validation Error',403);
        }try{
            
            $data = Shopping_cart::create([
                'user_id'=>Auth::user()->id,
                'item_id'=>$request->item_id,
                'count'=>$request->count == null ? '1' : $request->count,
            ]);
            return $this->success(
                'The Items has been added to cart successfully',
                $data,
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());

        }
            
    }
    public function deleteFromCart(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'cart_item_id' => 'required|exists:shopping_carts,id',
        ]);
        if($validate->fails()){
                return $this->failure('validation Error',403);
        }
        try{
            Shopping_cart::where('id',$request->cart_item_id)->delete();
            return $this->success(
                'The Item has been deleted from cart successfully',
                
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
            
    }

    public function countCart(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'cart_item_id' => 'required|exists:shopping_carts,id',
            'count' => 'required',
        ]);
        if($validate->fails()){
                return $this->failure('validation Error',403);
        }
        try{
            Shopping_cart::where('id',$request->cart_item_id)->update([
                'count' => $request->count
            ]);
            return $this->success(
                'The Item count updating successfully',
                
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
            
    }

    public function deleteCart(){
        try{
            Shopping_cart::where('user_id',Auth::user()->id)->delete();
            return $this->success(
                'The cart has been deleted successfully',
                
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
            
    }
    public function getCart(){
        try{
            $data = Shopping_cart::where('user_id',Auth::user()->id)->get();
            $items = [];
            $totalSum=0.0;
            foreach($data as $item_id){
                $item = Item::where('id',$item_id->item_id)->first();
                $item->cart_id= $item_id->id;
                $item->count = $item_id->count;
                $totalSum+=($item->price)*($item_id->count);
                array_push($items, $item);
            }
            
            return $this->success(
                ["itmes"=>$items,"total"=>$totalSum],
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }
    
}
