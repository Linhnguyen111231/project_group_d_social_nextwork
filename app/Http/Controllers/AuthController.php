<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' =>'required',
            'password'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_error'=>$validator->errors()->messages()
            ]);
        }
        // Check email
        $user = User::where('email', $request['email'])->first();

        // Check password
        if(!$user || !Hash::check($request['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        $token = $user->createToken('myapptoken', ['user_name'=> $user->name])->plainTextToken;

       
         return response()->json([
            'token'=> $token,
            'user'=> base64_encode($user->name),
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout()
    {
        if (Auth::check()) {
            // Lấy tất cả token xác thực API cho người dùng hiện tại
            $tokens = Auth::user()->tokens;
    
            // Lặp qua danh sách token và xóa từng token
            foreach ($tokens as $token) {
                $token->delete();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function posts()
    {
        return Post::all();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
