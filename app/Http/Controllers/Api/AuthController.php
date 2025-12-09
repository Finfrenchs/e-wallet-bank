<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Wallet;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'pin' => 'required|digits:6'
        ]);

        //cek validator error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        //cek email exists or not
        $user = User::where('email', $request->email)->exists();

        if ($user) {
            return response()->json(['message' => 'Email already taken'], 409);
        }

        DB::beginTransaction(); // start transaction

        try {
            $profilePicture = null;
            $ktp = null;

            if ($request->profile_picture) {
                $profilePicture = $this->uploadBase64Image($request->profile_picture);
            }

            if ($request->ktp) {
                $ktp = $this->uploadBase64Image($request->ktp);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->email,
                'password' => bcrypt($request->password),
                'profile_picture' => $profilePicture,
                'ktp' => $ktp,
                'verified' => ($ktp) ? true : false
            ]);

            //create wallet data for user
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'pin' => $request->pin,
                'card_number' => $this->generateCardNumber(16)
            ]);

            DB::commit(); //commit transaction
            $token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]); //generate token after register
            $userResponse = getUser($request->email);
            $userResponse->token = $token;
            $userResponse->token_expires_in = auth('api')->factory()->getTTL() * 60; //in seconds
            $userResponse->token_type = 'bearer';

            return response()->json($userResponse);

        } catch (\Throwable $th) {
            DB::rollBack(); //rollback transaction (if error occurs and cancel all process in transaction)
            return response()->json(['message' => $th->getMessage()], 500); //getMessage() method is for get error message from $th
        }
    }

    //login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        //cek validator error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        try {
            $token = JWTAuth::attempt($credentials);
            //attempt to login
            if (!$token) {
                return response()->json(['message' => 'Login credentials are invalid'], 401);
            }

            $userResponse = getUser($request->email);
            $userResponse->token = $token;
            $userResponse->token_expires_in = auth('api')->factory()->getTTL() * 60; //in seconds
            $userResponse->token_type = 'bearer';

            return response()->json($userResponse);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    //generate card number
    private function generateCardNumber($lenght) {
        $result = '';
        //looping
        for ($i=0; $i < $lenght; $i++) {
            $result .= mt_rand(0, 9);
        }

        //cek looping finish and data available
        $wallet = Wallet::where('card_number', $result)->exists();

        if ($wallet) {
            return $this->generateCardNumber($lenght);
        }
        //if no data
        return $result;
    }

    //this mathod for handle base64 image
    private function uploadBase64Image($base64Image)
    {
        $decoder = new Base64ImageDecoder($base64Image, $allowedFormats = ['jpeg', 'png', 'jpg']);

        $decodedContent = $decoder->getDecodedContent();
        $format = $decoder->getFormat();
        $image = Str::random(10).'.'.$format; //qwertyui12.jpg
        Storage::disk('public')->put($image, $decodedContent);

        return $image;
    }

    //logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
