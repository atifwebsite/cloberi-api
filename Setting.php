<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SendGrid\Mail\From;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class Setting extends Model
{
    use HasFactory;
    protected $table = "login";

    // for validations :
        public function check_register_validation($request)
        {
            $validate = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:ec_customers',
                'phone' => 'required|digits:10|unique:ec_customers',
                'password' => 'required'
            ]);
            if($validate->fails())
            {
                return $validate->messages();
            }

        }

        public function update_user_validation($request)
        {
            $validate = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:ec_customers',
                'phone' => 'required|digits:10|unique:ec_customers',
            ]);
            if($validate->fails())
            {
                return $validate->messages();
            }

        }
        // update users :
        public function update_user($request)
        {
            if($request)
            {
                $user_id = $request->id;
                $update_arr = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $reponse = DB::table('ec_customers')->where('id',$user_id)->update($update_arr);
                return $reponse;

            }else{
                return false;
            }

        }

        // delete user:
        public function delete_user($request)
        {
            $response = DB::table('ec_customers')->where('id',$request['id'])->delete();
            if($response)
            {
                return true;

            }else{
                return false;
            }
        }
    public function config()
    {
        $url = url('');

        $data = [
            // app_config will act as key 
            "app_config" => [
                "login_mandatory" => true,
                "intro_skippable" => false,
                "privacy_policy_url" => $url . "/privacy-policy",
                "terms_condition_url" => $url . "/terms-and-conditions",
                "support_url" => $url . "/support",
                "seller_system" => true,
                "color_system" => true,
                "pickup_point_system" => false,
                "wallet_system" => true,
                "coupon_system" => true
            ],

            // android_version will act as key 
            "android_version" => [
                "apk_version" => "1.2.3",
                "apk_code" => "123",
                "apk_file_url" => $url . "/app.apk",
                "whats_new" => "Bug fixes and performance improvements",
                "update_skippable" => true
            ],

            // ios_version will act as key 
            "ios_version" => [
                "ipa_version" => "1.2.3",
                "ipa_code" => "123",
                "ipa_file_url" => $url . "/app.ipa",
                "whats_new" => "Bug fixes and performance improvements",
                "update_skippable" => true
            ],

            // addons will act as key  and data also as a key
            "addons" => [
                [
                    "id" => 1,
                    "name" => "Sample Addon",
                    "addon_identifier" => "com.example.addon",
                    "purchase_code" => "PC123456",
                    "version" => "1.0.0",
                    "status" => true,
                    "image" => $url . "/image.png",
                    "data" => [
                        "title" => "Addon Title",
                        "sub_title" => "Addon SubTitle",
                        "sticker" => $url . "/sticker.png",
                        "refund_with_shipping_cost" => true,
                        "refund_request_time" => 48

                    ]
                ]
            ],
            "languages" => [
                [

                    "id" => 1,
                    "name" => "English",
                    "code" => "en",
                    "text_direction" => "LTR",
                    "flag_image" => $url . "/flag.png"
                ]
            ],
            "currencies" => [
                [

                    "id" => 1,
                    "name" => "US Dollar",
                    "symbol" => "$",
                    "code" => "USD",
                    "exchange_rate" => (float) 1.0,
                ]
            ],

            "pages" => [
                [

                    "id" => 1,
                    "type" => "privacy_policy",
                    "title" => "Privacy Policy",
                    "link" => $url . "/privacy",
                ]
            ],
            "currency_config" => [
                "currency_symbol_format" => "symbol",
                "decimal_separator" => ".",
                "no_of_decimals" => "2"
            ]
        ];
        return $data;
    }

    public function GetLoginOtp($request)
    {
        $data = DB::table('ec_customers')->select('*')
            ->where('phone', $request['phone'])->first();
        $array = (array) $data;
        return $array;

    }

    public function saveLoginOtp($user_id, $otp)
    {

        $no_of_otp = DB::table('otp')->where('user_id', $user_id)->count();
        if ($no_of_otp >= 1) {
            $delete_old_otp = DB::table('otp')->where('user_id', $user_id)->delete();
            if ($delete_old_otp) {
                $res = DB::table('otp')->insert([
                    'user_id' => $user_id,
                    'verified_otp' => $otp,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return $res;

            }
        } else {
            $res = DB::table('otp')->insert([
                'user_id' => $user_id,
                'verified_otp' => $otp,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $res;

        }

    }

    //  get login details :
    public function verify_login_otp($user_id, $data = null)
    {
        $phone = $data['phone'];
        $otp = $data['otp'];
        $otpRecord = DB::table('otp')->select('verified_otp')
            ->where("user_id", $user_id)->first();

        if ($otpRecord->verified_otp != $otp || empty($otpRecord)) {
            return false;
            // return $msg = [
            //     'message' => 'Invalid OTP.'
            // ];

        }
        if ($otpRecord->verified_otp == $otp) {
            $delete_old_otp = DB::table('otp')->where('user_id', $user_id)->delete();
            if ($delete_old_otp) {
                $data = DB::table('ec_customers')->select('*')
                    ->where('id', $user_id)->first();
                    $user = Auth::user();
                    $token = $user->createToken('MyApp')->plainTextToken;
                     $data = [
                        "token" => $token,
                        "first_name" => $data->name,
                        "last_name" => "khan",
                        "image" => $data->image,
                        "phone" => $data->phone,
                        "email" => $data->email,
                        "favourites" => ["item1", "item2"],
                        "notifications" => ["notification1", "notification2"],
                        "date_of_birth" => $data->dob,
                        "gender" => "Male"
                    ];
                $array = (array) $data;
                return $array;

            }

        }

    }

    public function register($data)
    {

      $data = DB::table('ec_customers')->insert([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'password'  => Hash::make($data['password']),
        'status' => 1,
        'created_at' => date('Y-m-d H:i:s')
      ]);
    return true;
    }

    // save coupon:
    public function save_coupon($data = null)
    {

        $res = DB::table('coupon')->insert([
            'coupon_code' => $data['coupon_code'],
            'trx_id' => $data['trx_id'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if($res)
        {
            $percent =  (100 / 100)* $data['coupon_code'];
            $new_total = (100 -$percent);
            $data_arr = [
                'discount' => $data['coupon_code'],
                'new_total' => $new_total
            ];
            return $data_arr;
        }
    }
    // delete copon :
    public function delete_coupon($data = null)
    {
        $res = DB::table('coupon')->where('id',$data['coupon_id'])->where('trx_id',$data['trx_id'])->delete();
        if($res)
        {
            return true;
        }

    }
}