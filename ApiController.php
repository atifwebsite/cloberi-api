<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\FormValidate;
use App\Models\User;
use App\Models\Category;
use App\Models\Products;
Use App\Models\Shipping;
Use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Customer;
use Validator;

class ApiController extends Controller
{
    protected $settings, $verify,$customer,$shipping,$category,$products,$brand;
    public function __construct()
    {
        $this->settings = new Setting();
        $this->verify = new Setting();
        $this->customer = new Customer();
        $this->shipping = new Shipping();
        $this->category = new Category();
        $this->products = new Products();
        $this->brand = new Brand();
    }


    public function update_user(Request $request)
    {
        $check = $this->settings->update_user_validation($request);
        if($check)
        {
            return response()->json(['status' => false,'data' =>$check]);
        }else
        {
            $response = $this->settings->update_user($request);
            if($response)
            {
                return response()->json(['status' => true, 'message' => 'User Updated successfully..']);
            }else
            {
                return response()->json(['status' => false, 'message' => 'Something went wrong..']);

            }

        }

    }
    // delete user:
    public function delete_user(Request $request)
    {
        $response = $this->settings->delete_user($request);
        if($response)
        {
            return response()->json(['status' => true, 'message' => 'User Deleted Successfully']);

        }else{
            return response()->json(['status' => false,'message' => 'Invalid User Id']);

        }
    }
    // get users profile :
    public function get_profile()
    {
        $data = $this->customer->get_user_profile();
        return response()->json(['status' => true, 'message' => 'User get Succesfully..','data' => $data]);
    }

    public function register(Request $request)
    {
        $res = $this->settings->check_register_validation($request);
        if($res)
        {
            return response()->json(['status' => false, 'message' => $res]);

        } else{
            $res = $this->settings->register($request);
            if($res)
            {
                return response()->json(['status' => true, 'message' => 'User Register Successfully..']);

            }else{
                return response()->json(['status' => false, 'message' => 'Something went wrong...']);

            }
        }
    }

    public function configs()
    {

        return response()->json(['success' => true, 'message' => __('responsemessage.success'), 'data' => $this->settings->config()], 200);
    }

    public function getLoginOtp(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'phone' => 'numeric|digits:10',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->messages(), 400);
        } else {
            $otp = random_int(100000, 999999);
            $res = $this->settings->getLoginOtp($request, $otp);
            if ($res) {
                $this->settings->saveLoginOtp($res['id'], $otp);

                return response()->json(['status' => true, 'message' => 'Otp Genrate Successfully.'], 200);
            } else {

                return response()->json(['success' => false, 'message' => 'Invalid Mobile Number ']);

            }

        }

    }

    // verify login otp :
    public function VerifyLoginOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->messages()]);
        } else {
            $data = $this->settings->GetLoginOtp($request);
            if($data)
            {
                $verify = $this->settings->verify_login_otp($data['id'],$request);
                if($verify)
                {
                    return response()->json(['status' => true, 'message' => 'Otp Verifiy Successfully..' , 'data' => $verify]);
                }else
                {
                    return response()->json(['status' => false,'message' => 'Invalid Otp']);
                }


            }else{
                return response()->json(['success' => true, 'message' => 'Invalid Mobile Number' ]);

            }

        }

    }

    // apply coupon:
    public function applyCoupon(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'coupon_code' => 'required',
            'trx_id' => 'required'
        ]);
        if($validation->fails())
        {
            return response()->json(['status' => false, 'message' => $validation->messages()]);
        } else{
          $data =  $this->settings->save_coupon($request);
          if($data)
          {
            return response()->json(['status' => true, 'message' => 'Coupon Applied Successfully..' , 'data' => $data]);
          }


        }

    }

    // delete coupon :
    public function DeleteCoupon(Request $request)
    {

        $validation = Validator::make($request->all(),[
            'coupon_id' => 'required',
            'trx_id' => 'required'
        ]);
        if($validation->fails())
        {
            return response()->json(['status' => false, 'message' => $validation->messages()]);
        }
        else
        {
            $data =  $this->settings->delete_coupon($request);
            if($data)
            {
              return response()->json(['status' => true, 'message' => 'Coupon Deleted Successfully..']);
            }else
            {
                return response()->json(['status' => false, 'message' => 'Coupon Id or trx Id is Invalid..']);

            }

        }

    }

    // for shipping address :
        public function shipping_address(Request $request,$id)
        {
            $validate = $this->shipping->check_shipping_validation($request);
            if($request->isMethod('POST'))
            {
                if($validate)
                {
                    return response()->json(['status' => false,'message' => $validate]);
                }

                if($request->isMethod('POST'))
                {
                    $response = $this->shipping->save_shipping_address($request);
                    if($response)
                    {
                        return response()->json(['status'=> true,'message'=> 'Shipping Address Saved Successfully'],200);

                    }

                }
                if($request->isMethod('PATCH'))
                {
                    if($id)
                    {
                        echo $id;die;

                        // $response = $this->shipping->Update_shipping_address($request);
                        // if($response)
                        // {
                        //     return response()->json(['status'=> true,'message'=> 'Shipping Address Updated Successfully'],200);

                        // }

                    }

                }


            }

        }


        public function update_shipping_address(Request $request, $id)
        {
            if($request->isMethod('PUT'))
            {

                $address_id = (int)($id);
                $res = $this->shipping->get_address_details_by_id( $request,$address_id);

                if($res)
                {
                    return response()->json(['status'=> true,'message'=> 'Record Updated Successfully..'],200);
                }else
                {
                    return response()->json(['status'=> false,'message'=> 'Record Not Found..'],200);
                }

            }
            else
            {
                return response()->json(['status'=> false,'message'=> 'Method Not Found..'],200);
            }

        }
    // category related:
    public function get_products_by_category_id($id)
    {
        if($id)
        {
            $response = $this->products->get_products_by_category_id($id);
            if($response)
            {
                return response()->json(['status' => true, 'message' => 'Category get successfully','data' => $response],200);
            }else
            {
                return response()->json(['status'=> false,'message'=> 'Record Not Found..'],400);

            }

        }else
        {
            return response()->json(['status'=> false,'message'=> 'Please Enter Category Id..'],400);

        }

    }

    // get all category :
    public function get_all_category()
    {
        $response = $this->category->get_all_category();
        if($response)
        {
            return response()->json(['status' => true, 'message' => 'Category get successfully','data' => $response],200);
        }else
        {
            return response()->json(['status'=> false,'message'=> 'Record Not Found..'],400);

        }

    }


    public function get_all_brand()
    {
        $response = $this->brand->get_all_brand();
        if($response)
        {
            return response()->json(['status' => true, 'message' => 'Brand get successfully','data' => $response],200);
        }else
        {
            return response()->json(['status'=> false,'message'=> 'Record Not Found..'],400);

        }

    }
}