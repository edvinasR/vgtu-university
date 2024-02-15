<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
Use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Responses\UnauthorizedResponse;
use App\Http\Responses\SuccessfullResponse;

class AuthenticationController extends Controller
{
    /**
     * Method used to login user into system and send him accessToken
     * @param  \App\Http\Requests\UserLoginRequest  $request
     * @return \Illuminate\Http\Response
    */
    public function login(UserLoginRequest $request)
    {
        // Validation already done inside UserLoginRequest class
        // Global Exceptions handler will catch all the exceptions no need for try/catch
        $loginCredentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        if(!Auth::attempt($loginCredentials)) {
            return (new UnauthorizedResponse())->setMessage("Unauthorized")->send();
        }
        //If user logged in successfully fetching access token and sending back to user
        return (new SuccessfullResponse())->setData([
            "accessToken" => Auth::user()->createToken('coffeeShop')->accessToken,

        ])->send();
    }
    /**
     * Method used to register user into system and send him accessToken after successfull registration
     * @param  \App\Http\Requests\UserRegisterRequest  $request
     * @return \Illuminate\Http\Response
    */
    public function register(UserRegisterRequest $request)
    {
        // Validation already done inside UserRegisterRequest class
        // Global Exceptions handler will catch all the exceptions no need for try/catch
        $input = $request->getData();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return (new SuccessfullResponse())->setData([
            "accessToken" => $user->createToken('coffeeShop')->accessToken,
        ])->send();


    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return (new SuccessfullResponse())->setMessage("User logged out successfully")->send();
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
