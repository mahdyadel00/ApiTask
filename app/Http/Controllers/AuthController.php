<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class AuthController extends Controller
{
    protected function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|string|min:6',
                'mobile'    => 'required',
                'gender'    => 'required',
                'address'   => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Login invalid');
        } else {

            $user = User::create([
                'name'        => $request->name,
                'mobile'      => $request->mobile,
                'email'       => $request->email,
                'address'     => $request->address,
                'password'    => Hash::make($request->password),
                'gender'      => $request->gender,
                'api_token'   => Str::random(60),
            ]);

            return $this->sendResponse(new UserResource($user), 'Register Complate');
        }
    } // End of Register

    protected function login(Request $request)
    {

        $rules = [
            'email'    => 'required|email|string|max:191|exists:users,email',
            'password' => 'required'
        ];
        $message = ['email.exists' => "Email not found on our users",];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Login invalid');
        }

        $user = User::where('email', $request->email)->first();

        if (isset($user)) {

            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

                return $this->sendResponse(new UserResource($user), 'Welcome to login this application');
            } else {
                return $this->sendError('Login  Unauthorised password or email error');
            }
        } else {

            return $this->sendError('This email not record please register now');
        }
    } //End of Login

    protected function checkEmail(Request $request)
    {

        if (Auth::guest()) {
            $validator = validator()->make($request->all(), [
                'email'  => 'required|string|email|max:191|exists:users'
            ]);

            if ($validator->fails()) {
                $errorData = $validator->errors();
                return $this->sendError($errorData->first(), $errorData);
            }

            $user = User::where('email', $request->email)->first();
            if ($user != null) {
                $code = rand(111111, 999999);
                $updateUser = $user->update(['pin_code' => $code]);
                if ($updateUser) {
                    return $this->sendResponse(new UserResource($user), 'Your code has been sent successfully, please check your email now!');
                } else {
                    return $this->sendError('Sorry, an error has occurred, please try again!');
                }
            } else {
                return $this->sendError('Sorry, there is no account associated with this email!');
            }
        } else {
            return $this->sendError('User is logged in');
        }
    } //End Of CheckEmail

    protected function checkCode(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'pin_code' => 'required|exists:users|integer',
        ]);

        if ($validator->fails()) {
            $errorData = $validator->errors();
            return $this->sendError($errorData->first(), $errorData);
        } else {
            $user = User::where('pin_code', $request->pin_code)->where('pin_code', '!=', 0)->first();
            if ($user) {
                return $this->sendResponse(new UserResource($user), 'The code is correct');
            } else {
                return $this->sendError('The code is invalid');
            }
        }
    } //End Of Check Code

    protected function resetPassword(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'pin_code' => 'required|exists:users|integer',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            $errorData = $validator->errors();
            return $this->sendError($errorData->first(), $errorData);
        } else {
            $user = User::where('pin_code', $request->pin_code)->where('pin_code', '!=', 0)->first();
            if ($user) {
                $user->update([
                    "password" => bcrypt($request->password),
                    "pin_code" => null
                ]);
                if ($user->save()) {
                    return $this->sendResponse(new UserResource($user), 'The password has been reset successfully');
                } else {
                    return $this->sendError('Sorry, an error has occurred, please try again!');
                }
            } else {
                return $this->sendError('Sorry, this code is invalid!');
            }
        }
    }

    protected function clearCache()
    {
        //Artisan::call('storage:link');
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        /*    Artisan::call('telescope:clear');
        // Artisan::call('telescope:prune');*/

        return $this->sendResponse( '', 'Successfully Clard Caching');
    }
}
