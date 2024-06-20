<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\RespondsWithHttpStatus; 
use App\Models\Address;

class UserController extends Controller
{
    use RespondsWithHttpStatus;

    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email|unique:users,email',
                'gender' => 'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function updateUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [   
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                'gender' => 'required',
                
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            // $User = Auth::user();
            
            $user = User::where('id',$id )->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'gender' => $request->gender,
            ]);
            if ($request->has('password')) {
                $User->password = Hash::make($request->password);
                $User->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'User updated Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateUserByAdmin(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [   'user_id' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'role' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                'gender' => 'required',
                
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            // $User = Auth::user();
        
           
            $user = User::where('id',$request->user_id )->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'gender' => $request->gender,
            ]);
            if ($request->has('password')) {
                $User->password = Hash::make($request->password);
                $User->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'User updated Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function creaTUserByAdmin(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [   
                'first_name' => 'required',
                'last_name' => 'required',
                'role' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                'gender' => 'required',
                'password' => 'required',

                
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            // $User = Auth::user();
        
           
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => Hash::make($request->password)

            ]);
            if ($request->has('password')) {
                $User->password = Hash::make($request->password);
                $User->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'User updated Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out  Successfully',
            'data' => "you are logged out"
        ], 200);
    }

    public function addAddress(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                'street_name'=>'required',
                'details'=>'required'
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
            try {
                $data = Address::create([
                    'user_id'=>Auth::user()->id,
                    'street_name'=>$request->street_name,
                    'details'=>$request->details
                ]);
                return $this->success(
                    'The address has been created successfully',
                    $data,
                );
            } catch (\Throwable $e) {
                //throw $th;
                return $this->failure($e->getMessage());
            }
    }

    public function getAddress(){
        try {
            $data = User::find(1)->Addresses;
            return  $this->success(
                'The addresses has been obtained successfully',
                $data,
            );
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage());
        }
        
    }

    public function deleteAddress(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                'address_id'=>'required|exists:addresses,id',
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try {
            Address::where('id',$request->address_id)->delete();
            return  $this->success(
                'The addresses has been deleted successfully',
                
            );
        } catch (\Throwable $e) {
             return $this->failure($e->getMessage());
        }
    }

    public function getAllUser(){
       
        try{
            
            $data = User::get();
            return $this->success(
                'The User has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }

    public function getUserById(Request $request){
       
        try{
          
            $data = User::where('id', Auth::user()->id)->get();
            return $this->success(
                'The user by id has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }

    public function getUserByadmin(Request $request){
       
        try{
            $validatedItem= Validator::make($request->all(), 
            [
                'user_id'=>'required',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',403);
            }
            $data = User::where('id', $request->user_id)->first();
            return $this->success(
                'The user by id has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }

    public function deleteuser(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                
                'id'=>'required',
                
            ]);
            if($validateCategory->fails()){
                    return $this->failure('validation Error',403);
            }
        try{
            
            $User_id=$request->id;
           
            User::where('id',$User_id)->delete();
            return $this->success(
                'The user has been deleted successfully',
            
            );

        }catch(\Throwable $e){
            return $this->failure($e->getMessage());

        }
    }

    public function checkUser (){
        $rolr = auth()->user()->role;
        return response()->json([
            'role'=>$rolr,
            'state'=>200,    
        ]);
    }

    public function checkHasShop (){
       try{
        $shop = Shop::where('user_id',Auth::user()->id);
        if( $shop->exists()){
            return response()->json([
                'find'=>$shop->first(),
                'state'=>200,    
            ]);
        }
        return response()->json([
            'find'=>'not find shop',
            'state'=>404,    
        ]);
       }catch(\Throwable $e){
        return response()->json([
            'find'=>'not_found',
            'state'=>404, 
        ]);
       }
    }




}