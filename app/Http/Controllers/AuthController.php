<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    //
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = new UserResource(User::where('email', $request->email)->first());

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $imagePath =  null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/images', $imageName);
        }



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'roles' => $request->role,
            'password' => Hash::make($request->password),
            'profile_photo_path' => $imagePath,
        ]);

        return $this->success(['user' => $user],"User created successfully", 201);
    }

    public function index()
    {
        $users = User::all()->sortByDesc('created_at');
        return UserResource::collection($users);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        if($request->file('image')){
            $image = $request->file('image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/images', $imageName);

            $user->update([
                'profile_photo_path' => $imagePath,
            ]);
        }
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'roles' => $request->role,
            ]);
        } catch (Throwable $exception) {
            return $this->error(['name' => $request->name, 'email' => $request->email, 'role' =>$request->role], $exception->getMessage(), 500);
        }


        return $this->success($user, "User updated successfully, log back in to see your changes", 200);
    }

    public function changePassword(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if(!Hash::check($request->input('oldPassword'), $user->password)) {
            return $this->error([],'old password does not match', 401);
        }

        $user->update([
            'password' => Hash::make($request->input('newPassword')),
        ]);

        return $this->success($user, "Password updated successfully", 200);

    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have succesfully been logged out and your token has been removed'
        ]);
    }
}
