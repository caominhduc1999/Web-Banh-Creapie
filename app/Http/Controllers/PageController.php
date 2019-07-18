<?php

namespace App\Http\Controllers;

use App\Bill;
use App\BillDetail;
use App\Cart;
use App\Customer;
use App\Product;
use App\ProductType;
use App\Slide;
use App\User;
use Illuminate\Http\Request;
use Session;
use Hash;
use Auth;

class PageController extends Controller
{
    //
    public function getIndex()
    {
        $slide = Slide::all();
        $new_product = Product::where('new','=','1')->paginate(4);
        $total_new_product = Product::where('new','=','1')->get();
        $sanphamkhuyenmai = Product::where('promotion_price','!=','0')->paginate(8);
        $total_sanphamkhuyenmai = Product::where('promotion_price','!=','0')->get();
        return view('page.trangchu',['slide'=>$slide,
                                            'new_product'=>$new_product,
                                            'total_new_product'=>$total_new_product,
                                            'sanphamkhuyenmai'=>$sanphamkhuyenmai,
                                            'total_sanphamkhuyenmai'=>$total_sanphamkhuyenmai]);
//        return view('page.trangchu',compact('slide'));
    }

    public function getLoaiSp($type)
    {
        $sp_theoloai = Product::where('id_type', '=',$type)->paginate(3);
        $sp_khac = Product::where('id_type', '!=',$type)->paginate(3);
        $loai = ProductType::all();
        $loai_sp = ProductType::where('id','=',$type)->first();
        return view('page.loai_sanpham', ['sp_theoloai'=>$sp_theoloai, 'sp_khac'=>$sp_khac, 'loai'=>$loai, 'loai_sp'=>$loai_sp]);
    }

    public function getChitiet(Request $request)
    {
        $sanpham = Product::where('id','=',$request->id)->first();
        $sanphamtuongtu = Product::where('id_type','=',$sanpham->id_type)->paginate(3);
        return view('page.chitiet_sanpham',['sanpham'=>$sanpham, 'sanphamtuongtu'=>$sanphamtuongtu]);
    }

    public function getLienHe()
    {
        return view('page.lienhe');
    }

    public function getGioiThieu()
    {
        return view('page.gioithieu');
    }

    public function getAddtoCart(Request $request, $id)
    {
        $product = Product::find($id);
        $oldCart = Session('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $request->session()->put('cart', $cart);        // lưu dữ liệu trong session: $request->session()->put('key', 'value');
        return redirect()->back();
    }

    public function getDelItemCart($id)
    {
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0)
        {
            Session::put('cart', $cart);
        }
        else
        {
            Session::forget('cart');
        }

        return redirect()->back();
    }

    public function getCheckout()
    {
        return view('page.dat_hang');
    }

    public function postCheckout(Request $request)
    {
        $cart = Session::get('cart');

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->gender = $request->gender;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->phone_number = $request->phone;
        $customer->note = $request->note;
        $customer->save();

        $bill = new Bill();
        $bill->id_customer = $customer->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $request->payment;
        $bill->note = $request->note;
        $bill->save();

        foreach ($cart->items as $key => $value)
        {
            $bill_detail = new BillDetail();
            $bill_detail->id_bill = $bill->id;
            $bill_detail->id_product = $key;
            $bill_detail->quantity = $value['qty'];
            $bill_detail->unit_price = $value['price']/$value['qty'];
            $bill_detail->save();
        }
        Session::forget('cart');
        return redirect()->back()->with('thongbao', 'Đặt hàng thành công');

    }

    public function getLogin()
    {
        return view('page.dangnhap');
    }

    public function getSignin()
    {
        return view('page.dangki');
    }

    public function postSignin(Request $request)
    {
        $this->validate($request,
            [
                'email'         =>'required|email|unique:users,email',
                'password'      =>'required|min:6|max:20',
                'full_name'      =>'required',
                'phone'         =>'required',
                're_password'   =>'required|same:password',
            ],
            [
                'email.required'        =>'Vui lòng nhập email',
                'email.email'           =>'Không đúng định dạng email',
                'email.unique'          =>'Email đã tồn tại',
                'password.required'     =>'Vui lòng nhập email',
                'password.min'          =>'Mật khẩu có độ dài ít nhất 6 ký tự',
                'password.max'          =>'Mật khẩu có độ dài nhiều nhất 20 ký tự',
                'full_name.required'     =>'Vui lòng nhập tên',
                'phone.required'        =>'Vui lòng nhập số điện thoại',
                're_password.required'  =>'Vui lòng nhập lại mật khẩu',
                're_password.same'      =>'Mật khẩu không khớp',
            ]);
        $user = new User();
        $user->email = $request->email;
        $user->full_name = $request->full_name;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        Session::flash('thongbao', 'Tạo tài khoản thành công');
        return redirect()->back()->withInput();
    }

    public function postLogin(Request $request)
    {
        $this->validate($request,
            [
                'password'  =>'required|min:6|max:20',
                'email'     =>'required|email'
            ],
            [
                'password.required' =>'Vui lòng nhập mật khẩu',
                'email.required'    =>'Vui lòng nhập email',
                'email.email'       =>'Không dúng định dạng email',
                'password.min'      =>'Mật khẩu ít nhất 6 ký tự',
                'password.max'      =>'Mật khẩu nhiều nhất 20 ký tự',

            ]);

        $credentials = array('email'=>$request->email,'password'=>$request->password);
        if (Auth::attempt($credentials))
        {
            return redirect()->back()->with(['flag'=>'success','message'=>'Đăng nhập thành công']);
        }
        else
        {
            return redirect()->back()->with(['flag'=>'danger','message'=>'Đăng nhập thất bại']);

        }
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('trangchu');
    }

    public function getSearch(Request $request)
    {
        $product = Product::where('name','like','%'.$request->key.'%')
                            ->orWhere('unit_price','=','%'.$request->key.'%')->get();
        return view('page.search',['product'=>$product]);
    }
}
