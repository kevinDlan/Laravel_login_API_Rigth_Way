<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    /**
     * Create user
     * @param Request $request
     * @return User
     * 
     */

     public function createAccount(Request $request)
     {
         try{
            $validate_user = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validate_user->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Validation Failed',
                        'error' => $validate_user->errors(),
                    ],
                    401
                );
            }

            // create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // return response
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken('API Token')->plainTextToken,
                ],
                200
            );

         }catch(\Throwable $th){ 
            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                ],500);
        }
    }


    /**
     * Login user and create token
     * @param Request $request
     * @return User
     * 
     */
    public function login(Request $request){
        try
        {
          $validate_user = Validator::make($request->all(), 
          [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
          ]);

          if($validate_user->fails())
          {
            return response()->json(
              [
                'status' => false,
                'message' => 'Validation Failed',
                'error' => $validate_user->errors(),
              ],
              401
            );
          }

          if(!auth()->attempt($request->only(['email', 'password'])))
          {
            return response()->json(
              [
                'status' => false,
                'message' => 'Username and email does not match with our records',
              ],
              401
            );
          }

          $user = User::where('email', $request->email)->first();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User login Successfully',
                    'token' => $user->createToken('API Token')->plainTextToken,
                ],
                200
            );

        }catch(\Throwable $th){ 
            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                ],500);
        }
    }
}
