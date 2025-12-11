<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
//validator
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = getUser(auth()->user()->id);

        return response()->json($user);
    }

    //by username
    public function getByUsername(Request $request, $username)
    {
        $user = User::select('id', 'name', 'username', 'verified', 'profile_picture')
            ->where('username', 'LIKE', '%'.$username.'%')
            ->where('id', '<>', auth()->user()->id)
            ->get();

        $user = $user->map(function ($item) {
            $item->profile_picture = $item->profile_picture ? url('storage/'.$item->profile_picture) : "";
            return $item;
        });

        return response()->json($user);
    }

    //update user profile
    public function update(Request $request)
    {
        try {
                $user = User::find(auth()->user()->id);

                $data = $request->only(['name', 'username', 'email', 'password', 'ktp', 'profile_picture']);

                //check username same
                if ($request->username != $user->username) {
                    //check username exist
                    $isExistUsername = User::where('username', $request->username)->exists();
                    if ($isExistUsername) {
                        return response()->json(['message' => 'Username already taken'], 409);
                    }
                }

                //check email same
                if ($request->email != $user->email) {
                    //check email exist
                    $isExistEmail = User::where('email', $request->email)->exists();
                    if ($isExistEmail) {
                        return response()->json(['message' => 'Email already taken'], 409);
                    }
                }

                //check password
                if ($request->password) {
                    $data['password'] = bcrypt($request->password);
                }

                //check ktp
                if ($request->ktp) {
                    $ktp = uploadBase64Image($request->ktp);
                    $data['ktp'] = $ktp;
                    $data['verified'] = true;
                    if ($user->ktp) {
                        //delete old ktp
                        Storage::delete('public/'.$user->ktp);
                    }
                }

                //check profile picture
                if ($request->profile_picture) {
                    $profilePicture = uploadBase64Image($request->profile_picture);
                    $data['profile_picture'] = $profilePicture;
                    if ($user->profile_picture) {
                        //delete old profile picture
                        Storage::delete('public/'.$user->profile_picture);
                    }
                }

                $user->update($data);

                $updatedUser = getUser($user->id);

                return response()->json([
                    'message' => 'Update profile success',
                    'data' => $updatedUser
                ], 200);

            } catch (\Throwable $th) {
                return response()->json(['message' => 'Update profile failed', 'error' => $th->getMessage()], 500);
            }
    }

    //check email exist
    public function isEmailExist(Request $request)
    {
        $validator = Validator::make($request->only(['email']), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        $isExistEmail = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $isExistEmail]);
    }
}

