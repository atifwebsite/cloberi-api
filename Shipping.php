<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SendGrid\Mail\From;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
// use Validator;

class Shipping extends Model
{
    use HasFactory;
       protected $tables = "shippings";

       public function check_shipping_validation($request)
       {
           $validate = Validator::make($request->all(),[
               'name' => 'required',
               'email' => 'required|email',
               'phone_no' => 'required|digits:10',
               'country_id' => 'required',
               'state_id' => 'required',
               'city_id' => 'required',
               'postal_code' => 'required',
               'address' => 'required'
           
           ]);
           if($validate->fails())
           {
               return $validate->messages();
           }else
           {
            return false;
           }
       }

    //    save shipping addredd:
    public function save_shipping_address($request)
    {
        $shipped_address_arr = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'created_at' => date('Y-m-d H:i:s')
         ];
         $response = Shipping::insert($shipped_address_arr);
         return $response;

    }

    public function get_user_profile()
    {
         $res = DB::table('ec_customers')->get();
        //  return $res;
        $data = [];
         foreach($res as $key=> $row)
       
         {
            $data[] = [
                    "first_name"=> $row->name ? $row->name : null,
                    "last_name"=> $row->last_name ? $row->last_name : null,
                    "email"=> $row->email?$row->email:null,
                    "phone" => $row->phone ? $row->phone :null,
                    "gender"=> $row->gender ? $row->gender : null,
                    "date_of_birth"=> $row->dob ? $row->dob : null,
                    "image"=> $row->image ? $row->image : null,
                    "facebook"=> $row->facebook,
                    "twitter"=> $row->twitter,
                    "linkedin"=> $row->linkdin,
                    "instagram"=> $row->instagram,
                    "pinterest"=> $row->pinterest,
                    "youtube"=> $row->youtube
            ];
         }
        //  $res = array_map('data',$data);
         
         return $data;
    }


    public function get_address_details_by_id($request, $id)
    {
        //  return $request;
        if($id)
        {
            //  return $request->name;
              $res =  Shipping::find($id);
              if($res)
              {         
                    $res->name = $request->name;
                    $res->email = $request->email;
                    $res->phone_no = $request->phone_no;
                    $res->country_id = $request->country_id;
                    $res->state_id = $request->state_id;
                    $res->city_id = $request->city_id;
                    $res->postal_code = $request->postal_code;
                    $res->address = $request->address;
                    $res->updated_at = date('Y-m-d H:i:s');
                    $res->save();
                 return true;

                // return $res;
              }else
              {
                    return false;
              }
             
        }else
        {
            return false;
        }

    }

}