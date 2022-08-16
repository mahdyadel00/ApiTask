<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class UserController extends Controller
{

    protected function getProfile()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $this->sendResponse(new UserResource($user), 'User Data successfully');
        }
        return $this->sendError('The User not found');
    } //End of get profile

    protected function updateProfile(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $rules =  [
                'name' => ['string', 'max:255'],
                'gender' => ['string', 'max:255'],
                'address' => ['string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'mobile' => ['required', 'numeric', 'min:11', Rule::unique('users')->ignore($user->id)],
                'password' => ['sometimes']
            ];

            if (!is_null($request->password)) {
                $rules['password'] = ['confirmed', 'string', 'min:6'];
            }

            $validator = validator()->make($request->all(), $rules);

            if ($validator->fails()) {
                $errorData = $validator->errors();
                return $this->sendError($errorData->first(), $errorData);
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);
            if (!is_null($request->password)) {
                $user->update(['password' => bcrypt($request->password)]);
            }
            return $this->sendResponse(new UserResource($user), 'The data has been successfully updated');

        } else {
            return $this->sendError('User is not logged in');
        }
    }
}
