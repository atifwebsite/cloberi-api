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

class Brand extends Model
{
    use HasFactory;
       protected $tables = "brand";

    //    public function get_category_by_category_id($id)
    //    {
    //     $res = Brand::find($id);
    //     if($res)
    //     {
    //         return $res;
    //     }
    //     return false;
    //    }

    public function get_all_brand()
    {
        $res = Brand::get();
        return $res;
    }




}