<?php
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use App\Utils\Base64ImageDecoder;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;

if (!function_exists('getUser')) {
    function getUser($param) {
        $user = User::where('id', $param)
                ->orWhere('email', $param)
                ->orWhere('username', $param)
                ->first();

        $wallet = Wallet::where('user_id', $user->id)->first();
        $user->profile_picture = $user->profile_picture ? url('storage/' . $user->profile_picture) : "";
        $user->ktp = $user->ktp ? url('storage/' . $user->ktp) : "";
        $user->balance = $wallet ? $wallet->balance : 0;
        $user->card_number = $wallet->card_number;
        $user->pin = $wallet->pin;

        return $user;
    }
}

if (!function_exists('pinChecker')) {
    function pinChecker($pin) {
        $userId = auth()->user()->id;
        $wallet = Wallet::where('user_id', $userId)->first();

        if (!$wallet) {
            return false;
        }

        if ($wallet->pin == $pin) {
            return true;
        }

        return false;
    }
}

if (!function_exists('uploadBase64Image')) {

    function uploadBase64Image($base64Image) {
        $decoder = new Base64ImageDecoder($base64Image, $allowedFormats = ['jpeg', 'png', 'jpg']);

        $decodedContent = $decoder->getDecodedContent();
        $format = $decoder->getFormat();
        $image = Str::random(10).'.'.$format; //qwertyui12.jpg
        Storage::disk('public')->put($image, $decodedContent);

        return $image;
    }
}
