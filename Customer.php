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

class Customer extends Model
{
    use HasFactory;
       protected $tables = "ec_customers";

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


}