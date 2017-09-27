<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'username' => 'required',
          'password' => 'required',
      ]);
      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()]);
      }
      // grab credentials from the request
      $credentials = $request->only('username', 'password');

      try {
          // attempt to verify the credentials and create a token for the user
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'invalid_credentials'], 401);
          }
      } catch (JWTException $e) {
          // something went wrong whilst attempting to encode the token
          return response()->json(['error' => 'could_not_create_token'], 500);
      }

      // all good so return the token
      return response()->json(compact('token'));
    }
}
