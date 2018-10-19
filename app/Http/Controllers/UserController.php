<?php

   namespace App\Http\Controllers;

   use App\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Support\Facades\Validator;
   use JWTAuth;
   use Tymon\JWTAuth\Exceptions\JWTException;

   class UserController extends Controller
   {
       public function login(Request $request)
       {
           $credentials = $request->only('email', 'password');

           try {
               if (! $token = JWTAuth::attempt($credentials)) {
                   return response()->json(['error' => 'invalid_email_or_password'], 400);
               }
           } catch (JWTException $e) {
               return response()->json(['error' => 'could_not_create_token'], 500);
           }
            $user = User::where('email', $request->get('email'))->first();
            //we send back the tocken to be able to use it and access area method
           return response()->json(compact('user','token'),201);
       }

       public function register(Request $request)
       {
               $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               'password' => 'required|string|min:6',
           ]);

           if($validator->fails()){
                   return response()->json($validator->errors()->toJson(), 400);
           }

           $user = User::create([
               'name' => $request->get('name'),
               'email' => $request->get('email'),
               'password' => Hash::make($request->get('password')),
           ]);

           $token = JWTAuth::fromUser($user);

           return response()->json(compact('user','token'),201);
       }
   }
