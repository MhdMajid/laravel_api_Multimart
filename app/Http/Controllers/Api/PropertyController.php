<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Item; 
use App\Models\Property; 
use App\Models\Size; 
use App\Models\Color; 

class PropertyController extends Controller
{
    use RespondsWithHttpStatus;

    public function getProperty(Request $request){
        $validate = Validator::make($request->all(),[
            'id'=>'required|exists:items,id'
        ]);

        if($validate->fails()){
            return $this->failure('validation error',403);
        }
        try{
            $property = Property::where('item_id', $request->id)->first();
            $color = $property->color()->get();
            $size = $property->size()->get();

            return $this->success('success',[
            'property'=>['id'=>$property->id,'item_id'=>$property->item_id]
            ,'quantity'=>$property->quantity
            ,'colors'=>$color
            ,'sizes'=>$size
            ]
        );

        } catch(\Throwable $e){
            return $this->failure($e->getMessage());
        };
    }

    public function addColor(Request $request){
        $validate = Validator::make($request->all(),[
            'property_id'=>'required',
            'color'=>'required'
        ]);
        if($validate->fails()){
            return $this->failure('validation error',403);
        }
        try{
            $color = Color::create([
                'property_id'=>$request->property_id,
                'color'=>$request->color,
            ]);
            return $this->success('created successfully',$color);
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
    }

    public function addSize(Request $request){
        $validate = Validator::make($request->all(),[
            'property_id'=>'required',
            'size'=>'required',
        ]);
        if($validate->fails()){
            return $this->failure('validation error',403);
        }
        try{
            $size = Size::create([
                'property_id'=>$request->property_id,
                'size'=>$request->size
            ]);
            return $this->success('created successflly',$size);
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
        
    }

    public function editColor(Request $request ){
        $validate = Validator::make($request->all(),[
            'color_id'=>'required|exists:colors,id',
            'color'=>'required'
        ]);
        if($validate->fails()){
            return $this->failure('validation erorr',403);
        }
        try{

            $color = Color::where('id',$request->color_id)->update([
                'color'=>$request->color
            ]);
            return $this->success('updated successfully',$color);


        }catch(\Throwable $e){
            return $this->failure('eroor',$e->getMessage());
        }
    }

    public function editSize(Request $request){
        $validate = Validator::make($request->all(),[
            'size_id' =>'required|exists:sizes,id',
            'size'=>'required'
        ]);
        if($validate->fails()){
            return $this->failure('valifation erorr',403);
        }
        try{
            $size = Size::where('id',$request->size_id)->update([
                'size' => $request->size
            ]);
            return $this->success('Size updated successfully');
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
    }

    public function deletesize(Request $request){
        $validate = Validator::make($request->all(),[
            'size_id'=>'required|exists:sizes,id'
        ]);
        if($validate->fails()){
            return $this->failure('validation failed',403);
        }
        try{
            Size::find($request->size_id)->delete();
            return $this->success('Delete succeeded');
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
    }


    public function deleteColor(Request $request){
        $validate = Validator::make($request->all(),[
            'color_id'=>'required|exists:colors,id'
        ]);
        if($validate->fails()){
            return $this->failure('validation failed',403);
        }
        try{
            Color::find($request->color_id)->delete();
            return $this->success('Delete succeeded');
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
    }
    
    public function editQuantity(Request $request){
        $validate = Validator::make($request->all(),[
            'property_id'=>'required|exists:properties,id',
            'quantity'=>'required'
        ]);
        if($validate->fails()){
            return $this->failure('validation faild');
        }
        try{
            Property::find($request->property_id)->update([
                'quantity'=>$request->quantity
            ]);
            return $this->success('updated successfully');


        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
    }
    
}
