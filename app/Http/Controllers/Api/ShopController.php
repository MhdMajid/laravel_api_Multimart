<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use App\Models\Shop;

class ShopController extends Controller
{
    public function getAllShop(Request $request)
    {
        try{
       $data = Shop::get();
            return $this->success('Success',$data);

        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }
    
    public function getShop(Request $request)
    {
        try{
       $data = Shop::where('user_id',Auth::user()->id)->where('state','active')->first();
            return $this->success('Success',$data);

        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }

    public function getShopById(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'shop_id'=>'required',
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        try{
       $data = Shop::find(shop_id);
            return $this->success('Success',$data);

        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }
    
    public function createShop(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'user_id'=>'required|exsits:users,id',
            'name'=>'required',
            'address'=>'required',
            'image'=>'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        $image = $request->file('image');
        $image->move(public_path('images_shops'),$name.".".$image->getClientOriginalExtension());

        try{
            Shop::create([
                'name'=>$request->name,
                'user_id'=>Auth::user()->id,
                'address'=>$request->address,
                'image'=>$request->name.".".$image->getClientOriginalExtension()
            ]);
            return $this->success('Success created');
        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }

    public function updateImage(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'shop_id'=>'required',
            'image'=>'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        $image = $request->file('image');
        $image->move(public_path('images_shops'),$name.".".$image->getClientOriginalExtension());
        $data = Shop::find($request->shop_id);
        $image_path = $data->image;
        if(File::exists(public_path('images_shops/'.$image_path))) {
            File::delete(public_path('images_shops/'.$image_path));
        }
        try{
            Shop::find($request->shop_id)->update([
                'image'=>$request->name.".".$image->getClientOriginalExtension()
            ]);
            return $this->success('Success updated');

        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
        

    }

    public function editName(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'shop_id'=>'required',
            'name'=>'required',
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        try{
            Shop::find($request->shop_id)->update([
                'name'=>$request->name
            ]);
            return $this->success('Success updated');


        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }

    public function editAddress(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'shop_id'=>'required',
            'address'=>'required',
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        try{
            Shop::find($request->shop_id)->update([
                'address'=>$request->address
            ]);
            return $this->success('Success updated');


        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }

    public function stateShop(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'shop_id'=>'required',
            'state'=>'required',
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        try{
            Shop::find($request->shop_id)->update([
                'state'=>$request->state
            ]);
            return $this->success('Success');


        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }

    public function deleteShop(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'shop_id'=>'required',
        ]);
        if($validate->fails()){
            return $this->failure('validation failed');
        }
        try{
            Shop::find('shop_id')->delete();
            return $this->success('Success deleted');


        }catch(Exception $e){
            return $this->failure($e->getMessage());
        }
    }

}



