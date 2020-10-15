<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mailing\NotificationContract;
use App\Notifications\SignUpNotification;
use App\User;
use EmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class AuthController extends Controller
{
    public function register (Request $request, User $user, NotificationContract $notificationContract){
        $validator =  Validator::make($request->all(),[
            'email' => 'bail|email|required',
            'password' => 'bail|required|alpha_num',
            'nickname' => 'bail|required',
        ]);
        try {
            if ($validator->fails()){
                $data = [
                    'status' => false,
                    'error' =>"Kindly Ensure All Fields are Filled",
                ];
                return response($data,422);
            }
            $checkUser = $user->checkUser($request->email);
            if ($checkUser){
                $data = [
                    'status' => false,
                    'error' =>"Email Already Exist",
                ];
                return response($data,422);
            }
            //register User
            $user = $user->createUser($request);
            $notificationContract->sendRegistrationEmail($user);
            $data = [
                'status' => true,
                'message' => "Registration was Successful. A mail has been sent to email, kindly follow through"
            ];
            return response($data,200);
        }
        catch (\Exception $exception){
            $data = [
                'status' => false,
                'error' => 'Action Could not be Performed'
            ];
            return response($data, 500);
        }
    }

    public function verifyAccount($token, User $user){
        try {
            $user = $user->fetchUser($token);
            if ($user){
                $user->isVerified = 1;
                $user->token = Str::random(15);
                $user->save();
                $data = [
                    'status' => true,
                    'message' => "Account Verification was Successful. Kindly Login now"
                ];
                return response($data,200);
            }
            $data = [
                'status' => false,
                'error' => "Oops!, Token Expired"
            ];
            return response($data,401);
        }
        catch (\Exception $exception){
            $data = [
                'status' => false,
                'error' => 'Action Could not be Performed'
            ];
            return response($data, 500);
        }
    }

    public function login (Request $request){
        $validator =  Validator::make($request->all(),[
            'email' => 'bail|required',
            'password' => 'bail|required',
        ]);
        try {
            if ($validator->fails()){
                $data = [
                    'status' => false,
                    'error' =>"Kindly Ensure All Fields are Filled",
                ];
                return response($data,422);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $access_token = Auth::user()->createToken('userToken')->accessToken;
                $data = [
                    'user' => Auth::user(),
                    'token' => $access_token,
                    'status' => true,
                    'message' => 'You Have Successfully Login'
                ];
                return response($data,200);
            }

            // handle unauthenticated user
            $data = [
                'status' => false,
                'error' => 'Invalid Login Details'
            ];
            return response($data,401);
        }
        catch (\Exception $exception){
            $data = [
                'status' => false,
                'error' => 'Action Could not be Performed'
            ];
            return response($data, 500);
        }
    }

    public function error(){
        $data = [
            'status' => false,
            'error' => 'Unauthorized Access'
        ];
        return response($data, 401);
    }

    public function logout(){
        try{
            Auth::user()->token()->revoke();
            $data = [
                'status' => true,
                'message' => "Logout Successfull"
            ];
            return response($data, 200);
        }
        catch (\Exception $exception){
            $data = [
                'status' => false,
                'error' => 'Action Could not be Performed'
            ];
            return response($data, 500);
        }
    }

    public function requestVerification($token){
        try {
            $user = User::where('token', $token)->first();
            if ($user) {
                $user->notify(new SignUpNotification($user));
                $data = [
                    'status' => true,
                    'message' => " A mail has been sent to your email, kindly follow through"
                ];
                return response($data, 200);
            }
            else{
                $data = [
                    'status' => false,
                    'error' => "Invalid Credentials"
                ];
                return response($data,401);
            }
        }
        catch(\Exception $exception){
            $data = [
                'status' => false,
                'error' => 'Action Could not be Performed'
            ];
            return response($data, 500);
        }
    }
}
