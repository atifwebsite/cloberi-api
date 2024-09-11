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

class Products extends Model
{
    use HasFactory;
       protected $tables = "products";




       public function get_products_by_category_id($id)
       {
        $res = Products::where('category_id',$id)->get();
        if($res)
        {
            return $res;
        }
        return false;
       }

}