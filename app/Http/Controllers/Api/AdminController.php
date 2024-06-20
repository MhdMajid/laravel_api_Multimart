<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Http\Traits\RespondsWithHttpStatus;


class AdminController extends Controller

{

    use RespondsWithHttpStatus;

    public function createAdmin(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:admins,email',
                'username'=>'required|unique:admins,username',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return $this->failure($validateUser->errors(),401);
                
            }

            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'username'=>$request->username,
                'password' => Hash::make($request->password)
            ]);
            $data = [
                'token' => $admin->createToken("API TOKEN")->plainTextToken
            ];
            return $this->success('Admin Created Successfully',$data);
            // return response()->json([
            //     'status' => true,
            //     'message' => 'Admin Created Successfully',
                
            // ], 200);

        } catch (\Throwable $th) {
            return $this->failure($validateUser->errors(),500);
            //return response()->json([
              //  'status' => false,
                //'message' => $th->getMessage()
            //], 500);
        }
    }

    function checkAdminAuth($username,$password){
        if(Admin::where('username',$username)->exists()){
            $currentPassword = Admin::where('username',$username)->value('password');
            if(Hash::check($password, $currentPassword)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function loginAdmin(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return $this->failure('validation error',401);
               // return response()->json([
                   // 'status' => false,
                  //  'message' => 'validation error',
                   // 'errors' => $validateUser->errors()
              //  ], 401);
            }


            if(!$this->checkAdminAuth($request->username,$request->password)){
                return $this->failure('Username & Password does not match with our record.',401);
              //  return response()->json([
                    //'status' => false,
                  //  'message' => 'Username & Password does not match with our record.',
             //   ], 401);
            }

            $admin = Admin::where('username', $request->username)->first();

            return response()->json([
                'status' => true,
                'message' => 'Admin Logged In Successfully',
                'token' => $admin->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function Adminlogout(Request $request){
                $request->user()->currentAccessToken()->delete();
                return $this->success('Admin logged out  Successfully',"you are logged out");
       // return response()->json([
         //   'status' => true,
           // 'message' => 'User logged out  Successfully',
          //  'data' => "you are logged out"
        //], 200);
    }
}
