<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\RespondsWithHttpStatus;
use App\Models\Property;
use App\Models\Wish_list;
use Illuminate\Support\Facades\File;
use App\Models\Payment_detail;
use App\Models\Shopping_cart;

class SellerController extends Controller
{
    use RespondsWithHttpStatus;

    public function addSeller(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'name'=>'required',
                'price'=>'required',
                'phone_number'=>'required',
                'product_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'type'=>'required',
                'details'=>'required',
               
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                
                    return $this->failure('validation Error',403);
            }
        try{
            
            $name = $request->name;
            $price = $request->price;
            $details = $request->details;
            $phone_number = $request->phone_number;
            $type = $request->type;
            $image = $request->file('product_image');
            $image->move(public_path('images'),$name.".".$image->getClientOriginalExtension());

            $data = Seller::create([
                'name'=>$name,
                'price'=>$price,
                'phone_number'=>$phone_number,
                'details'=>$details ?? '-',
                'product_image'=> $name.".".$image->getClientOriginalExtension(),
                'user_id' => Auth::user()->id,
                'type'=>$type,

            ]);
            return $this->success(
                'The selles has been created successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the ItemaddItem, try again');
            return $this->failure($e->getMessage());

        }
    }



    // update seller
    public function updatSeller(Request $request){
        $validatedItem= Validator::make($request->all(), 
            [
                'id'=>'required',
                'name'=>'required',
                'price'=>'required',
                'phone_number'=>'required',
                // 'product_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'type'=>'required',
                'details'=>'required',
               
            ]);
            if($validatedItem->fails()){
                return $this->failure('validation Error',403);
            }
        try{
            
            $name = $request->name;
            $price = $request->price;
            $details = $request->details;
            $phone_number = $request->phone_number;
            $type = $request->type;
            if($request->has('item_image')){
                $image = $request->file('item_image');
                $image->move(public_path('images'),$name.".".$image->getClientOriginalExtension());
            }

            $data = Seller::where('id',$id)->update([
                'name'=>$name,
                'price'=>$price,
                'phone_number'=>$phone_number,
                'details'=>$details ,
                'product_image'=> $name.".".$image->getClientOriginalExtension(),
                'user_id' => Auth::user()->id,
                'type'=>$type,

            ]);
            return $this->success(
                'The selles has been updated successfully',
                $data,
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());

        }
    }


    // get seller by id
    public function getAllSellerById(){
       
        try{
            $data = Seller::where('user_id', Auth::user()->id)->get();
            return $this->success(
                'The seller by id has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }


    // get all seller
    public function getAllseller(){
       
        try{
            
            $data = Seller::get();
            return $this->success(
                'The seller has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }


// --------------------------------------------------
    public function deleteSeller(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                
                'id'=>'required',
                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            
            $seller_id=$request->id;
            $old_data =  Seller::find($seller_id);
            

            $image_path = $old_data->product_image;  
            if(File::exists(public_path('images/'.$image_path))) {
                File::delete(public_path('images/'.$image_path));

            }
            Seller::where('id',$seller_id)->delete();
            return $this->success(
                'The seller has been deleted successfully',
            
            );

        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }



    // get one seller
    public function getoneseller(Request $request){
        $validate= Validator::make($request->all(), 
        [
            'id' => 'required',
        ]);
        if($validate->fails()){
            // $validtionMessages = $validateCategory->errors();
                return $this->failure('validation Error',403);
        }
        try{
            
            $data = Seller::where('id',$request->id)->first();
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
