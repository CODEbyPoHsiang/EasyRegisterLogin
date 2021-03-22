<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

//檢查密碼需要使用到Hash套件
use Illuminate\Support\Facades\Hash;
//Google2fa 套件
use Google2FA;
//時間處理套件
use Carbon\Carbon;
// 檢查表單資料驗證套件
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        //1.檢查帳號密碼並驗證有無錯誤
        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

        //檢查帳號密碼有無錯誤
        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = [
            'success' => false,
            'message' => '您輸入的帳號密碼有錯誤或不存在，請重新輸入',
          ];
            return response()->json($response, 202);
        }

        //2.進行google2fa 顯示判斷(顯示QRcode或otp輸入框)
        // 刪除掉之前留存的login-token(舊)資料
        $user->tokens()->where('name', '=', 'login-token')->delete();
        // 如果登入者未設置二次驗證，則執行獲取google二次驗證key及掃QR_CODE
        if ($user["google2fa_secret"] == "") {
            //產生google2fa的key
            //寫法 1
            // $google2fa = app('pragmarx.google2fa');
            // $user["google2fa_secret"] =  ($google2fa->generateSecretKey(32));

            //寫法 2
            $user["google2fa_secret"] =  Google2FA::generateSecretKey(64);
            //生成QR code
            // 寫法 1
            // $QR_Image = $google2fa->getQRCodeInline(
            // 寫法2
            $QR_Image = Google2FA::getQRCodeInline(
            // config('app.name'),
                $user['email'],
                "TestMonitor",
                $user['google2fa_secret'],
            );
            //尚未掃描時的 response
            $response = [
            'success' => 'getcode',
            'google2fa_secret' => $user["google2fa_secret"],
            'email' => $user["email"],
            'QR_status' => false,
            'QR_code' => $QR_Image,
            'message' => '請掃描QR碼，並執行二次驗證操作',
            ];
            return response()->json($response, 200);
        } else {
            //掃描過後的response
            $response = [
            'success' => 'toConfirmTwoFa',
            'google2fa_secret' => $user["google2fa_secret"],
            'email' => $user["email"],
            'QR_status' => true,
            'user' => $user,
            'message' => '帳號密碼登入成功，請執行二次驗證操作',
          ];
            return response()->json($response, 200);
        }
    }

    // Google2FA，6位OTP碼驗證
    public function checkGoogle2faOTP1(Request $request)
    {
        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

        //執行GOOGLE2FA的安全碼驗證
        $google2fa_otp = $request->one_time_password;
        $google_2fa_secrect = $request->google2fa_secret;
        //驗證碼不得為空
        if ($google2fa_otp === null) {
            $response = [
            'success' => "optempty",
            'message' => '請填入二次驗證碼',
          ];
            return response()->json($response, 202);
        }

        //驗證資料庫內的google密碼跟二次驗證的驗證碼
        $login_2fa_status = Google2FA::verifyKey($google_2fa_secrect, $google2fa_otp);
        //驗證2次驗證的6位驗證碼是否正確
        if ($login_2fa_status === false) {
            $response = [
            'success' => false,
            'message' => '二次驗證碼輸入錯誤或逾時，請重新操作',
          ];
            return response()->json($response, 202);
        }
        // 通過驗證後，才獲取登入後的 login-token
        $token = $user->createToken('login-token')->plainTextToken;
        $user->google2fa_enable ="true";
        $user->google2fa_secret = $google_2fa_secrect;
        $user->save();

        $response = [
        'success' => true,
        'name' => $user["name"],
        'google2fa_secret' => $google_2fa_secrect,
        '2fa_login_status' => $login_2fa_status,
        'login_token' => $token,
        'message' => '二次驗證登入成功',
      ];
        return response($response, 200);
    }
    // Google2FA，6位OTP碼驗證
    public function checkGoogle2faOTP(Request $request)
    {
        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

        $google2fa_otp = $request->one_time_password;

        if ($user["google2fa_secret"] === "") {
            //執行GOOGLE2FA的安全碼驗證
            $google_2fa_secrect = $request->google2fa_secret;
            $user->google2fa_secret = $google_2fa_secrect;
        } else {
            $google_2fa_secrect = $user->google2fa_secret;
        }

        //驗證碼不得為空
        if ($google2fa_otp === null) {
            $response = [
            'success' => "optempty",
            'message' => '請填入二次驗證碼',
          ];
            return response()->json($response, 202);
        }

        //驗證資料庫內的google密碼跟二次驗證的驗證碼
        $login_2fa_status = Google2FA::verifyKey($google_2fa_secrect, $google2fa_otp);
        //驗證2次驗證的6位驗證碼是否正確
        if ($login_2fa_status === false) {
            $response = [
            'success' => false,
            'message' => '二次驗證碼輸入錯誤或逾時，請重新操作',
          ];
            return response()->json($response, 202);
        }
        // 通過驗證後，才獲取登入後的 login-token
        $token = $user->createToken('login-token')->plainTextToken;
        $user->save();

        $response = [
        'success' => true,
        'name' => $user["name"],
        'google2fa_secret' => $google_2fa_secrect,
        '2fa_login_status' => $login_2fa_status,
        'login_token' => $token,
        'message' => '二次驗證登入成功',
      ];
        return response($response, 200);
    }


    public function user($email)
    {
        $user = User::where('email', '=', $email)->first();
        return response($user);
    }

    public function remove_password($email)
    {
        return User::where('email', '=', $email)->update(["password"=>""]);
    }

    public function logout(Request $request)
    {
        // Auth::logout();
        // return response()->json(['message' => 'Logged out'], 200);
        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();

        // 會銷毀登入的使用者所有的token資料
        $user->tokens()->where('name', '=', 'login-token')->delete();

        // 登出時銷毀登入時login-token單一筆資料
        // $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        // logout_time 用來記錄登入時間
        $user->save();
    }
}
