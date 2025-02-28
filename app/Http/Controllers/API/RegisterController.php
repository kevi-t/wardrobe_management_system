<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
    /**
     * Register API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'county' => 'required',
            'location' => 'required',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ], 
        
        ['email.unique' => 'This email is already registered.',]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 200);
        }
    
        try {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully.',
                'data' => $success
            ], 200);
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration Error.',
                'data' => ['error' => 'Something went wrong. Please try again.']
            ], 200);
        }
    }
    
}