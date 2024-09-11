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

class Category extends Model
{
    use HasFactory;
       protected $tables = "category";

    //    public function get_category_by_category_id($id)
    //    {
    //     $res = Category::find($id);
    //     if($res)
    //     {
    //         return $res;
    //     }
    //     return false;
    //    }

    public function get_all_category()
    {
        $res = Category::get();
        return $res;
    }




}