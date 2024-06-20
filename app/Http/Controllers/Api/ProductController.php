<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use RespondsWithHttpStatus;
    public function getAllProducts(){
       
        try{
            
            $data = Product::get();
            return $this->success(
                'The products has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }
    public function addProduct(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                
                'name'=>'required'

                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            $name = $request->name;
            $data = Product::create([
                'name'=>$name,
            ]);
            return $this->success(
                'The product has been created successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function updateProduct(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                'id' => 'required|exists:products,id',
                'name'=>'required'

                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            $product_id = $request->id;
            $name = $request->name;
             Product::where('id',$product_id)->update([
                'name'=>$name,
            ]);
            $data = Product::find($product_id);
            return $this->success(
                'The product has been updated successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function deleteProduct(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                'id' => 'required|exists:products,id',
                

                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            $product_id=$request->id;
            Product::where('id',$product_id)->delete();
            return $this->success(
                'The product has been deleted successfully',
            
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }
}
