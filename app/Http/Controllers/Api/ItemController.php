<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\RespondsWithHttpStatus;
use App\Models\Wish_list;
use Illuminate\Support\Facades\File;
use App\Models\Payment_detail;
use App\Models\Shopping_cart;
use App\Models\rating;
use App\Models\Category;
use App\Models\Property; 
use App\Models\Size; 
use App\Models\Color;

class ItemController extends Controller
{
    use RespondsWithHttpStatus;

    


    public function addItem(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'category_id' => 'required|exists:categories,id',
                'name'=>'required',
                'price'=>'required',
                'item_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'description'=>'required',
            ]);
            if($validatedItem->fails()){
                    return $this->failure('validation Error',403);
            }
        try{
            $name = $request->name;
            $category_id = $request->category_id;
            $price = $request->price;
            $description = $request->description;
            $is_discount = $request->is_discount;
            $discount = $request->discount;
            $category_name = DB::table('categories')->where('id',$category_id)->first()->name;
            $image = $request->file('item_image');
            $image->move(public_path('images'),$name.".".$image->getClientOriginalExtension());
         
            $data = Item::create([
                'name' => $name,
                'category_id'=>$category_id,
                'category_name'=>$category_name,
                'price'=>$price,
                'product_image'=> $name.".".$image->getClientOriginalExtension(),
                'description'=>$description ?? '-',
                'is_discount'=>$is_discount ?? false,
                'discount'=>$discount ?? 0.0,
            ]);
            $propety = Property::create([
                'item_id'=>$data->id,
                'quantity'=>5
            ]);
            $color = Color::create([
                'property_id' =>$propety->id,
                'color' => 'yellow'
            ]);
            $color = Color::create([
                'property_id' =>$propety->id,
                'color' => 'white'
            ]);
            $color = Color::create([
                'property_id' =>$propety->id,
                'color' => 'red'
            ]);
            $size = Size::create([
                'property_id' =>$propety->id,
                'size' => 'large'
            ]);
            $size = Size::create([
                'property_id' =>$propety->id,
                'size' => 'XLarge'
            ]);
            return $this->success(
                'The item has been created successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the ItemaddItem, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function updateItem(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'id'=>'required|exists:items,id',
                'category_id' => 'required|exists:categories,id',
                'name'=>'required',
                'price'=>'required',
                // 'item_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'description'=>'required',
                
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            $id = $request->id;
            $old_data = Item::find($id);
            $name = $request->name;
            $category_id = $request->category_id;
            $price = $request->price;
            
           // dd($request->has('item_image'));
            $description = $request->description;
            $is_discount = $request->is_discount;
            $discount = $request->discount;
            if($request->has('item_image')){
                $image = $request->file('item_image');
                $image->move(public_path('images'),$name.".".$image->getClientOriginalExtension());
            }
            $data = Item::where('id',$id)->update([
                'name' =>$name, 
                'category_id'=>$category_id,
                'price'=>$price,
                'product_image'=>$request->has('item_image') ? $name.".".$image->getClientOriginalExtension() : $old_data->product_image,
                'description'=>$description ?? null,
                'is_discount'=>$is_discount ?? null,
                'discount'=>$discount ?? null,
            ]);
            return $this->success(
                'The item has been updated successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the ItemaddItem, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function deleteItem(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                
                'id'=>'required|exists:items,id',
                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            
            $item_id=$request->id;
            $old_data =  Item::find($item_id);
            

            $image_path = $old_data->product_image;  
            if(File::exists(public_path('images/'.$image_path))) {
                File::delete(public_path('images/'.$image_path));

            }
            Item::where('id',$item_id)->delete();
            return $this->success(
                'The item has been deleted successfully',
            
            );

        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function addPropertyToItem(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                
                'item_id' => 'required|exists:items,id',
                'name'=>'required',
                'quantity'=>'required',
                
                
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
            try{
                $data = Property::create([
                    'name'=>$request->name,
                    'item_id'=>$request->item_id,
                    'quantity'=>$request->quantity,
                ]);
                return $this->success(
                    'The property has been created successfully',
                    $data,
                );
            }catch(\Throwable $e){
                return $this->failure($e->getMessage());
            }
            
    }
    public function getProperties(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'item_id' => 'required|exists:items,id',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
            try{
               $data = Item::find($request->item_id)->properties;
                return $this->success(
                    'success',
                    $data,
                );
            }catch(\Throwable $e){
                return $this->failure($e->getMessage());
            }
            
    }

    public function getItemsRandomly(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'number_of_items' => 'required|integer',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
            
            try{
                
               $data = Item::inRandomOrder()->limit($request->number_of_items)->get();
                return $this->success(
                    'success',
                    $data,
                );
            }catch(\Throwable $e){
                return $this->failure($e->getMessage());
            }
            
    }

    public function getAllItems(){
       
        try{
            // $data = Item::with('rating')->get()->map(function($item){
            //     $item->average_rating = $item->averageRating();
            // });
            $data = Item::get();
            // $data = Item::first();

            
            foreach ( $data as  $item) {
                $item->average_rating = $item->averageRating();
            }
            // dd($data);
            return $this->success(
                'The Items has been obtained successfully',
                $data
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());

        }
    }

    public function getItemsByCategory(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'category_id' => 'required',
        ]);
        if($validate->fails()){
            // $validtionMessages = $validateCategory->errors();
                return $this->failure('validation Error',403);
        }
        try{
            if($request->category_id ==0){
                $data =    Item::get();
            }else{
                $data = Item::where('category_id',$request->category_id)->get();
            }

            return $this->success(
                'The items has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function getWishList(){
        
        try{
            
            $data = Wish_list::where('user_id',Auth::user()->id)->get();
            $items = [];
            foreach($data as $item_id){
                $item = Item::where('id',$item_id->item_id)->first();
                $item->wish_list_id=$item_id->id;
                array_push($items, $item);
            }
            
            // $items = Item::whereIn('id',$ids)->get();
            return $this->success(
                'The wish list items have been obtained successfully',
                $items,
            );
            return $this->success(
                'The item has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function addToWishList(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'item_id' => 'required|exists:items,id',
        ]);
        if($validate->fails()){
            // $validtionMessages = $validateCategory->errors();
                return $this->failure('validation Error',403);
        }try{
            
            $data = Wish_list::create([
                'user_id'=>Auth::user()->id,
                'item_id'=>$request->item_id,
            ]);
            return $this->success(
                'The Items has been added to wish list successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
            
    }
    
    public function deleteFromWishList(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'wishlist_item_id' => 'required|exists:wish_lists,id',
        ]);
        if($validate->fails()){
            // $validtionMessages = $validateCategory->errors();
                return $this->failure('validation Error',403);
        }
        try{
            Wish_list::where('id',$request->wishlist_item_id)->delete();
            return $this->success(
                'The Item has been deleted from wish list successfully',
                
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
            
    }

    public function getItem(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'item_id' => 'required|exists:items,id',
        ]);
        if($validate->fails()){
            // $validtionMessages = $validateCategory->errors();
                return $this->failure('validation Error',403);
        }
        try{
            $data = Item::where('id',$request->item_id)->first();
            $data->rating =  $data->averageRating();
            
            if(auth()->check()){
                $rating =rating::where('item_id',$request->item_id)
                ->where('user_id',Auth::user()->id)
                ->get('rating');
                $data->rating_user =$rating;
            }
            return $this->success(
                'The Item has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function getPopuler(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'number_of_items' => 'required|integer',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            
            $data = Wish_list::selectRaw('distinct  *')->limit($request->number_of_items)->get();
            $items = [];
            foreach($data as $item_id){
                $item = Item::where('id',$item_id->item_id)->first();
                array_push($items, $item);
            }
            return $this->success(
                'The Item has been obtained successfully',
                $items,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function getBestSeller(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'number_of_items' => 'required|integer',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            
            $data = Payment_detail::selectRaw('count(item_id) as count,item_id')->groupBy('item_id')->orderBy('count','desc')->limit($request->number_of_items)->latest()->get();
            $items = [];
            foreach($data as $item_id){
                $item = Item::where('id',$item_id->item_id)->first();
                array_push($items, $item);
            }
            
            return $this->success(
                'The Item has been obtained successfully',
                $items,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function getNewItems(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'number_of_items' => 'required|integer',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            
            $data = Item::orderBy('created_at','desc')->limit($request->number_of_items)->latest()->get();
            
            
            return $this->success(
                'The Item has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function wishlistCount(){
        try{
            
            $data = Wish_List::where('user_id',Auth::user()->id)->count();
            
            return $this->success(
                'The Item has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function cartItemCount(){
        try{
            
            $data = Shopping_cart::where('user_id',Auth::user()->id)->count();
            
            return $this->success(
                'The Item has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }
}
                                                                                                                                                                                                                                                                                                                                                    