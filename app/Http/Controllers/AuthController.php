<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * Register and store user to database
     *
     * @param \App\Http\Requests\LoginAuthRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginAuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials, $request->remember)) {
            return new UserResource(auth()->user());
        }

        return response([
            'message' => 'Login failed.',
        ], 401);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response([
            'message' => 'Logout success!!'
        ]);
    }

    /**
     * Display the auth profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return new UserResource(auth()->user());
    }

    /**
     * Answer a question about current auth has abilities do something.
     *
     * @return \Illuminate\Http\Response
     */
    public function can(Request $request, $ability)
    {
        $request->validate(['params' => 'required|string']);

        $params = explode(',', (string)$request->params);
        $arguments = [];

        foreach ($params as $param) {
            if (str_contains($param, ':')) {
                $query = explode(':', $param);
                $model = "App\Models\\{$query[0]}";
                $arguments[] = $model::find($query[1]);
            } else {
                $arguments[] = "App\Models\\{$param}";
            }
        }

        // dd($ability, $arguments);
        if (auth()->user()->can($ability, $arguments)) {
            return response(['message' => 'Wow, you can do it!']);
        }

        return response(['message' => 'Opp, you can not do it!'], 403);
    }
}