<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Validator;

class LoginController extends BaseController
{
    /**
     * Login API
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validate request inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'data' => $validator->errors()
            ], 200); 
        }

        // Attempt authentication
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => ['error' => 'Invalid email or password']
            ], 200); 
        }

        // Successful login
        $user = Auth::user();
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully.',
            'data' => $success
        ], 200);
    }
}
