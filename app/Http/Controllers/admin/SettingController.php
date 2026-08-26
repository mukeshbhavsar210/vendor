<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateProduct;
use App\Models\Brand;
use App\Models\Color;
use App\Models\DiscountCoupon;
use App\Models\Page;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use App\Models\State;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class SettingController extends Controller {

    public function brand_index(Request $request){
        $brands = Brand::latest('id');
        if ($request->get('keyword')){
            $brands = $brands->where('name', 'like', '%'.$request->keyword.'%');
        }

        $brandTotal = Brand::count();
        $brands = $brands->paginate(10);

        $data = [
            'title'         => 'Brands',
            'button_name'   => 'Create Brand',
            'modal_id'      => 'createBrandModal',
            'form_id'       => 'Brands',
            'method_id'     => '',
            'refresh'       => route('brands.index'),
            'button_route'  => null,
            'brands'        => $brands,
            'total'         => $brandTotal,

            'formConfig' => [
                'action' => route('brands.store'),
                'modal_size' => null,
                'method' => 'POST',
                'button' => 'Create Brand',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Brand Name',
                        'placeholder' => 'Enter Brand name',
                        'slug_create' => 'slug-source',
                        'class' => 'slug-source',                        
                        'data'  => [
                            'target' => '#slug'
                        ],
                        'col' => 'col-md-12 col-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'slug',
                        'label' => 'Brand Slug',
                        'placeholder' => 'Enter Brand slug',
                        'id'    => 'slug',
                        'col' => 'col-md-6 col-6 d-none'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'description',
                        'label' => 'Description',
                        'placeholder' => 'Enter Brand name',
                        'col' => 'col-md-12 col-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'discount',
                        'label' => 'Discount',
                        'placeholder' => 'Enter Brand name',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'brand_order',
                        'label' => 'Brand Order',
                        'options' => [
                            1 => 1,
                            2 => 2,
                            3 => 3,
                            4 => 4,
                            5 => 5,
                            6 => 6,
                            7 => 7,
                        ],
                        'col' => 'col-md-3 col-3'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'Status',
                        'options' => [
                            1 => 'Active',
                            0 => 'Block',
                        ],
                        'col' => 'col-md-3 col-3'
                    ],                    
                    [
                        'type' => 'file',
                        'name' => 'logo',
                        'label' => 'Brand Logo',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'model',
                        'label' => 'Brand Model',
                        'col' => 'col-md-6 col-6'
                    ],
                ]
            ]
        ];       
        return view('admin.settings.brands', $data);
    }
    
    public function brand_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->description = $request->description;
            $brand->discount = $request->discount;
            $brand->brand_order = $request->brand_order;
            $brand->save();

            $brandId = $brand->id;
            $brandName = Str::slug($brand->name);

            // Init Image Manager
            $manager = new ImageManager(new Driver());

            // Create directory if not exists
            $path = public_path('uploads/brands/');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }            

            // ✅ IMAGE (180x200)
            if ($request->hasFile('model')) {
                $model = $request->file('model');
                $imageName = $brandId.'_'.$brandName.'_model.'.$model->getClientOriginalExtension();
                $img = $manager->read($model->getRealPath());
                //$img->scale(width: 180);
                $img->cover(200, 250); // exact crop like fit()
                $img->save($path.$imageName);
                $brand->model = $imageName;
            }

            // ✅ LOGO (100x50)
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = $brandId.'_'.$brandName.'_logo.'.$logo->getClientOriginalExtension();
                $logoImg = $manager->read($logo->getRealPath());
                $logoImg->scale(height: 60);                
                $logoImg->save($path.$logoName);
                $brand->logo = $logoName;
            }

            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function brand_destroy($id, Request $request){
        $brand = Brand::find($id);

        if (empty($brand)) {
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $path = public_path('uploads/brands/');
            // ✅ Delete Image
            if (!empty($brand->model)) {
                $imagePath = $path . $brand->model;
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // ✅ Delete Logo
            if (!empty($brand->logo)) {
                $logoPath = $path . $brand->logo;
                if (File::exists($logoPath)) {
                    File::delete($logoPath);
                }
            }
            // ✅ Delete DB record
            $brand->delete();
            $request->session()->flash('success', 'Brand deleted successfully');

            return response([
                'status' => true,
                'message' => 'Brand deleted successfully',
            ]);
        }

    //Shipping
    public function shipping_index(Request $request){
        $states = State::get();
        $shippings = ShippingCharge::select('shipping_charges.*','states.name')->leftJoin('states','states.id','shipping_charges.state_id')->get();
        $shippingTotal = ShippingCharge::count();
        //$shippings = $shippings->paginate(10);

        $data['states'] = $states;
        $data['shippings'] = $shippings;

        $data = [
            'title'         => 'Shipping',
            'button_name'   => 'Add State',
            'modal_id'      => 'createStateModal',
            'form_id'       => 'Shipping',
            'method_id'  => '',
            'refresh'       => route('shipping.index'),
            'button_route'  => null,
            'shippings'     => $shippings,
            'states'     => $states,
            'total'         => $shippingTotal,

            'formConfig' => [
                'action' => route('shipping.store'),
                'modal_size' => null,
                'method' => 'POST',
                'button' => 'Create Shipping',
                'fields' => [
                    [
                        'type' => 'select',
                        'name' => 'state_id',
                        'label' => 'State',
                        'options' => $states->pluck('name', 'id')->toArray() + [
                            'rest_of_state' => 'Rest of the state'
                        ],
                        'col' => 'col-md-12 col-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'amount',
                        'label' => 'Amount',
                        'placeholder' => 'Enter amount',
                        'col' => 'col-md-12 col-12'
                    ]                    
                ]
            ]
        ];       

        return view('admin.settings.shipping.index', $data);
    }

    public function shipping_index2(){
        $states = State::get();
        $data['states'] = $states;
        $shippings = ShippingCharge::select('shipping_charges.*','states.name')->leftJoin('states','states.id','shipping_charges.state_id')->get();
        $data['shippings'] = $shippings;
        return view('admin.settings.shipping.index', $data);
    }

    public function shipping_store(Request $request){
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        if($validator->passes()){
            $count = ShippingCharge::where('state_id',$request->state)->count();
            if($count > 0){
                session()->flash('error','Shipping already added as you selected state.');
                return response()->json([
                    'status' => true,
                ]);
            }

            $shipping = new ShippingCharge();
            $shipping->state_id = $request->state_id;
            $shipping->amount = $request->amount;
            $shipping->save();
            session()->flash('success','Shipping added successfully');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function shipping_destroy($id){
        $shippingCharge = ShippingCharge::find($id);
        if ($shippingCharge == null) {
            session()->flash('error','Shipping not found');

            return response()->json([
                'status' => true,
            ]);
        }
        $shippingCharge->delete();
        session()->flash('success','Shipping deleted successfully');
        return response()->json([
            'status' => true,
        ]);

    }

    //Colors
    public function color_index(Request $request){
        $colors = Color::latest('id');
      
        $colorsTotal = Color::count();
        $colors = $colors->paginate(10);

        $data = [
            'title'         => 'Colors',
            'button_name'   => 'Create Color',
            'form_id'       => 'Colors',
            'method_id'     => '',
            'modal_id'      => 'createColorModal',
            'refresh'       => route('colors.index'),
            'button_route'  => null,
            'colors'        => $colors,
            'total'         => $colorsTotal,

            'formConfig' => [
                'action' => route('colors.store'),
                'modal_size' => null,
                'method' => 'POST',
                'button' => 'Create Color',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Color Name',
                        'placeholder' => 'Enter Color name',
                        'col' => 'col-md-12 col-12'
                    ],
                    [
                        'type' => 'color',
                        'name' => 'code',
                        'label' => 'Code',
                        'placeholder' => 'Drag Color code',
                        'col' => 'col-md-12 col-12'
                    ]
                ]
            ]
        ];       

        return view('admin.settings.colors', $data);
    }

    public function color_store(Request $request ){
        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            // 'code' => 'required|unique:code',
        ]);

        if($validator->passes()){
            $color = new Color();
            $color->name = $request->name;
            $color->code = $request->code;
            $color->save();

            return response()->json([
                'status' => true,
                'message' => 'Color added successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function color_destroy($id, Request $request){
        $color = Color::find($id);
        if(empty($color)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $color->delete();
        $request->session()->flash('success', 'Color deleted successfully');

        return response([
            'status' => true,
            'message' => 'Color deleted successfully',
        ]);
    }

    //Discount
    public function coupon_index(Request $request){
        $discountCoupons = DiscountCoupon::latest('id');

        if(!empty($request->get('keyword'))){
            $discountCoupons = $discountCoupons->where('name', 'like', '%'.$request->get('keyword').'%');
            $discountCoupons = $discountCoupons->orWhere('code', 'like', '%'.$request->get('keyword').'%');
        }        
       
        $discountTotal = DiscountCoupon::count();
        $discountCoupons = $discountCoupons->paginate(10);

        $data = [
            'title'         => 'Discount Coupon',
            'button_name'   => 'Create Discount',            
            'refresh'       => route('coupons.index'),
            'button_route'  => null,
            'discountCoupons' => $discountCoupons,
            'total'         => $discountTotal,

            'modals' => [
                'discount' => [
                    'title'      => 'Create Discount',
                    'modal_id'   => 'discountModal',
                    'form_id'    => 'discountForm',
                    'method_id'  => '',
                    'formConfig' => [
                        'action' => '',
                        'modal_size' => 'modal-lg',
                        'method' => 'POST',
                        'button' => 'Submit',
                        'fields' => [
                            [
                                'type' => 'text',
                                'name' => 'code',
                                'label' => 'Code',                                                                   
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'text',
                                'name' => 'name',
                                'id' => 'name',
                                'label' => 'Name',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'text',
                                'name' => 'max_uses',
                                'id' => 'max_uses',
                                'label' => 'Max Uses',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'text',
                                'name' => 'max_uses_user',
                                'id' => 'max_uses_user',
                                'label' => 'Max Users Use',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'text',
                                'name' => 'discount_amount',
                                'id' => 'discount_amount',
                                'label' => 'Discount Amount',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'text',
                                'name' => 'min_amount',
                                'id' => 'min_amount',
                                'label' => 'Min Amount',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'date',
                                'name' => 'starts_at',
                                'label' => 'Starts At',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'date',
                                'name' => 'expires_at',
                                'label' => 'Expires At',
                                'col' => 'col-md-4 col-6'
                            ],
                            [
                                'type' => 'select',
                                'name' => 'type',
                                'label' => 'Discount Type',
                                'options' => [
                                    'Percent' => 'Percent',
                                    'Fixed' => 'Fixed',
                                ],
                                'col' => 'col-md-2 col-6'
                            ],
                            [
                                'type' => 'select',
                                'name' => 'status',
                                'label' => 'Status',
                                'options' => [
                                    1 => 'Active',
                                    0 => 'Block',
                                ],
                                'col' => 'col-md-2 col-12'
                            ],
                            [
                                'type' => 'textarea',
                                'name' => 'description',
                                'summer_class' => '',
                                'label' => 'Description',
                                'id'    => 'description',
                                'col' => 'col-md-8 col-12'
                            ],
                            [
                                'type' => 'file',
                                'name' => 'image',
                                'label' => 'Image',
                                'col' => 'col-md-4 col-12'
                            ],  
                        ]                  
                    ]
                ]
            ]
        ];       

        return view('admin.settings.coupon.index', $data);
    }  

    public function showChangePasswordForm(){
        return view("admin.change-password");
    }

    public function processChangePassword(Request $request){
        $validator = Validator::make(request()->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:new_password',
        ]);

        $id = Auth::guard('admin')->user()->id;

        $admin = User::where('id',$id)->first();

        if($validator->passes()){
            if(!Hash::check($request->old_password,$admin->password)){
                session()->flash('error','Your old password is incorrect.');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id',$id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            session()->flash('success','You have successfully changed your password.');
            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

     public function coupon_index2(Request $request){
        $discountCoupons = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))){
            $discountCoupons = $discountCoupons->where('name', 'like', '%'.$request->get('keyword').'%');
            $discountCoupons = $discountCoupons->orWhere('code', 'like', '%'.$request->get('keyword').'%');
        }

        $discountCoupons = $discountCoupons->paginate(10);

        return view('admin.settings.coupon.index',compact('discountCoupons'));
    }

    public function coupon_store(Request $request){
        $validator = Validator::make(request()->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',            
        ]);

        if ($validator->passes()){
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Discount coupon added successfully.';

            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function coupon_edit(Request $request, $id){
        $coupon = DiscountCoupon::find($id);

        if($coupon == null){
            session()->flash('error','Records not found');
            return redirect()->route('coupons.index');
        }
        $data['coupon'] = $coupon;
        return view('admin.settings.coupon.edit', $data);
    }

    public function coupon_update(Request $request, $id){
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null){
            session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
            ]);
        }

        $validator = Validator::make(request()->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()){
            //expiring date must be great than start date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry date must be greator than start date.']
                    ]);
                }
            }

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Discount coupon update successfully.';

            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function coupon_destroy(Request $request, $id){
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null){
            session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
            ]);
        }

        $discountCode->delete();

        session()->flash('success', 'Discount coupon deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    //User
    public function users_index(Request $request){
        $users = User::latest('id');
        //$brands = Brand::latest('id');

        if(!empty($request->get('keyword'))){
            $users = $users->where('name','like','%'.$request->get('keyword').'%');
            $users = $users->orWhere('email','like','%'.$request->get('keyword').'%');
        }
        
        $usersTotal = User::count();
        $users = $users->paginate(10);

        $data = [
            'title'         => 'Users',
            'button_name'   => 'Create User',
            'form_id'       => 'Users',
            'modal_id'      => 'createUserModal',
            'method_id'  => '',
            'refresh'       => route('users.index'),
            'button_route'  => null,
            'users'         => $users,
            'total'    => $usersTotal,

            'formConfig' => [
                'action' => route('users.store'),
                'modal_size' => 'modal-lg',
                'method' => 'POST',
                'button' => 'Create User',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'User Name',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'password',
                        'label' => 'Password',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'phone',
                        'label' => 'Phone',                        
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'mobile',
                        'label' => 'Mobile',                        
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'image',
                        'label' => 'User Photo',
                        'col' => 'col-md-6 col-6'
                    ],   
                    [
                        'type' => 'date',
                        'name' => 'birthdate',
                        'label' => 'Birthdate',                        
                        'col' => 'col-md-3 col-6'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'gender',
                        'label' => 'Gender',
                        'options' => [
                            'Male' => 'Male',
                            'Female' => 'Female',
                        ],
                        'col' => 'col-md-3 col-6'
                    ],                                    
                    [
                        'type' => 'select',
                        'name' => 'role',
                        'label' => 'Role',
                        'options' => [
                            1 => 'User',
                            0 => 'Admin',
                        ],
                        'col' => 'col-md-3 col-6'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'Status',
                        'options' => [
                            1 => 'Active',
                            0 => 'Block',
                        ],
                        'col' => 'col-md-3 col-6'
                    ]
                ]
            ]
        ];       

        return view('admin.settings.users.index', $data);
    }   
    
    public function users_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
        ]);

        if($validator->passes()){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->alternate_mobile = $request->alternate_mobile;
            $user->birthdate = $request->birthdate;
            $user->gender = $request->gender;
            $user->role = $request->role;
            $user->image = $request->image;            
            $user->status = $request->status;
            $user->password = Hash::make($request->password);
            $user->save();

            $message = 'User added successfully';

            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function users_edit(Request $request, $id){
        $user = User::find($id);
        if($user == null){
            $message = 'User not found';
            session()->flash('error',$message);
            return redirect()->route('users.index');
        }

        return view('admin.settings.users.edit', [
            'user' => $user
        ]);
    }

    public function users_update(Request $request, $id){
        $user = User::find($id);
        if($user == null){
            $message = 'User not found';
            session()->flash('error',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'mobile' => 'required',
        ]);

        if($validator->passes()){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->alternate_mobile = $request->alternate_mobile;
            $user->status = $request->status;
            if($request->password != ''){
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $message = 'User added successfully';
            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function users_destroy($id){
        $user = User::find($id);
        if($user == null){
            $message = 'User not found';
            session()->flash('error',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }
        $user->delete();
        $message = 'User deleted successfully';
            session()->flash('error',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
    }


    //Vendors
    public function vendors_index(Request $request){
        $vendors = Vendor::latest('id');        

        if(!empty($request->get('keyword'))){
            $vendors = $vendors->where('name','like','%'.$request->get('keyword').'%');
            $vendors = $vendors->orWhere('email','like','%'.$request->get('keyword').'%');
        }
        
        $vendorsTotal = Vendor::count();
        $vendors = $vendors->paginate(10);

        $data = [
            'title'         => 'Vendors',
            'button_name'   => 'Create Vendor',
            'form_id'       => 'Vendors',
            'modal_id'      => 'createVendorModal',
            'method_id'     => '',
            'refresh'       => route('vendors.index'),
            'button_route'  => null,
            'vendors'       => $vendors,
            'total'         => $vendorsTotal,

            'formConfig' => [
                'action' => route('vendors.store'),
                'modal_size' => 'modal-lg',
                'method' => 'POST',
                'button' => 'Create User',
                'fields' => [                    
                    [
                        'type' => 'text',
                        'name' => 'business_name',
                        'label' => 'Business Name',
                        'slug_create' => 'slug-source',
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#slug_2'
                        ],
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'slug',
                        'label' => 'slug',                        
                        'id'    => 'slug_2',
                        'col' => 'col-md-12 col-12 d-none'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'phone',
                        'label' => 'Phone',                        
                        'col' => 'col-md-3 col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'mobile',
                        'label' => 'Mobile',                        
                        'col' => 'col-md-3 col-6'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'description',
                        'label' => 'Description',
                        'summer_class' => '',
                        'col' => 'col-md-12 col-6'
                    ],                    
                    [
                        'type' => 'file',
                        'name' => 'image',
                        'label' => 'Company Logo',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'cover_image',
                        'label' => 'Company Logo',
                        'col' => 'col-md-6 col-6'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'Status',
                        'options' => [
                            1 => 'Active',
                            0 => 'Block',
                        ],
                        'col' => 'col-md-3 col-6 d-none'
                    ]
                ]
            ]
        ];       

        return view('admin.settings.vendors.index', $data);
    }   
    
    public function vendors_store(Request $request){
        $validator = Validator::make($request->all(), [
            'business_name' => 'required',            
        ]);

        if($validator->passes()){
            $vendor = new Vendor;
            $vendor->business_name = $request->business_name;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->mobile = $request->mobile;
            $vendor->role = $request->role;
            $vendor->image = $request->image;            
            $vendor->status = $request->status;
            $vendor->password = Hash::make($request->password);
            $vendor->save();

            $message = 'Vendor added successfully';

            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function vendors_edit(Request $request, $id){
        $vendor = Vendor::find($id);
        if($vendor == null){
            $message = 'Vendor not found';
            session()->flash('error',$message);
            return redirect()->route('vendors.index');
        }

        return view('admin.settings.vendors.edit', [
            'vendor' => $vendor
        ]);
    }

    public function vendors_update(Request $request, $id){
        $vendor = Vendor::find($id);
        if($vendor == null){
            $message = 'Vendor not found';
            session()->flash('error',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = Validator::make($request->all(), [
            'business_name' => 'required',
            'email' => 'required|email|unique:vendors,email,'.$id.',id',            
        ]);

        if($validator->passes()){
            $vendor->business_name = $request->business_name;
            $vendor->email = $request->email;
            $vendor->mobile = $request->mobile;
            $vendor->status = $request->status;
            $vendor->save();

            $message = 'Vendor added successfully';
            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function vendors_destroy($id){
        $vendor = Vendor::find($id);
        if($vendor == null){
            $message = 'Vendor not found';
            session()->flash('error',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }
        $vendor->delete();
        $message = 'Vendor deleted successfully';
            session()->flash('error',$message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
    }


    //Affliate Product
    public function affiliate_index(Request $request){
        $affiliates = AffiliateProduct::latest('id');

        if(!empty($request->get('keyword'))){
            $affiliates = $affiliates->where('name','like','%'.$request->get('keyword').'%');
            $affiliates = $affiliates->orWhere('email','like','%'.$request->get('keyword').'%');
        }
        
        $affiliatesProductsTotal = AffiliateProduct::count();
        $affiliates = $affiliates->paginate(10);

        $data = [
            'title'         => 'Create Affiliate Product',
            'button_name'   => 'Create Service',
            'form_id'       => 'Affiliate',
            'modal_id'      => 'createAffiliateProductModal',
            'method_id'     => '',
            'refresh'       => route('affiliate_services.index'),
            'button_route'  => null,
            'affiliates'    => $affiliates,
            'total'         => $affiliatesProductsTotal,

            'formConfig' => [
                'action' => route('affiliate_services.store'),
                'modal_size' => 'modal-lg',
                'method' => 'POST',
                'button' => 'Create Affiliate Product',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'title',
                        'label' => 'Product Name',                        
                        'col' => 'col-md-8 col-12'
                    ],  
                    [
                        'type' => 'file',
                        'name' => 'image',
                        'label' => 'Product Photo',
                        'col' => 'col-md-4 col-6'
                    ],                       
                    [
                        'type' => 'text',
                        'name' => 'affiliate_url',
                        'label' => 'Affiliate Url',
                        'col' => 'col-md-8 col-12'
                    ], 
                    [
                        'type' => 'select',
                        'name' => 'affiliate_platform',
                        'label' => 'Platform',
                        'options' => [
                            'Amazon' => 'Amazon',
                            'Flipkart' => 'Flipkart',
                            'Meesho' => 'Meesho',
                        ],
                        'col' => 'col-md-4 col-12'
                    ],                       
                    [
                        'type' => 'text',
                        'name' => 'price',
                        'label' => 'Price',                        
                        'col' => 'col-md-4 col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'discounted_percentage',
                        'label' => 'Discount',
                        'col' => 'col-md-4 col-6'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'Status',
                        'options' => [
                            1 => 'Active',
                            0 => 'Block',
                        ],
                        'col' => 'col-md-4 col-6'
                    ]
                ]
            ]
        ];       

        return view('admin.settings.affiliate.index', $data);
    }   
    
    public function affiliate_store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',                        
        ]);

        if($validator->passes()){
            $affiliate = new AffiliateProduct;
            $affiliate->title = $request->title;            
            $affiliate->image = $request->image;
            $affiliate->affiliate_platform = $request->affiliate_platform;
            $affiliate->affiliate_url = $request->affiliate_url;
            $affiliate->price = $request->price;            
            $affiliate->discounted_percentage = $request->discounted_percentage;
            $affiliate->status = $request->status;
            $affiliate->save();

            $affiliateId = $affiliate->id;
            $affiliateTitle = Str::slug($affiliate->title);

            // Init Image Manager
            $manager = new ImageManager(new Driver());

            // Create directory if not exists
            $path = public_path('uploads/affiliate_products/');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }            

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $affiliateId.'_'.$affiliateTitle.'.'.$image->getClientOriginalExtension();
                $img = $manager->read($image->getRealPath());
                $img->cover(400, 400);
                $img->save($path.$imageName);
                $affiliate->image = $imageName;
            }           

            $affiliate->save();

            $message = 'Affiliate Product added successfully';

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function affiliate_edit(Request $request, $id){
        $affiliate = AffiliateProduct::find($id);

        if($affiliate == null){
            $message = 'Affilicate Product not found';
            session()->flash('error',$message);
            return redirect()->route('affiliate_services.index');
        }        

        return view('admin.settings.affiliate.edit', [
            'affiliate' => $affiliate
        ]);
    }

    public function affiliate_update(Request $request, $id){
        $affiliate = AffiliateProduct::find($id);

        if($affiliate == null){
            $message = 'affiliate not found';
            session()->flash('error',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',            
        ]);

        if($validator->passes()){
            $affiliate->title = $request->title;            
            $affiliate->image = $request->image;
            $affiliate->affiliate_platform = $request->affiliate_platform;
            $affiliate->affiliate_url = $request->affiliate_url;
            $affiliate->price = $request->price;
            $affiliate->discount_price = $request->discount_price;
            $affiliate->discounted_percentage = $request->discounted_percentage;
            $affiliate->status = $request->status;            
            $affiliate->save();

            $message = 'Affiliate Product updated successfully';
            session()->flash('success',$message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function affiliate_destroy($id) {
        $affiliate = AffiliateProduct::find($id);

        if (!$affiliate) {
            return response()->json([
                'status' => false,
                'message' => 'Affiliate product not found'
            ]);
        }

        $affiliate->delete();

        return response()->json([
            'status' => true,
            'message' => 'Affiliate product deleted successfully'
        ]);
    }


    //Pages
    public function page_index(Request $request){
        $pages = Page::latest('id');
        if($request->keyword != ''){
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }

        $pageTotal = Page::count();
        $pages = $pages->paginate(10);

        $data = [
            'title'         => 'Pages',
            'button_name'   => 'Create Page',
            'form_id'       => 'Pages',
            'modal_id'      => 'createPageModal',
            'method_id'  => '',
            'refresh'       => route('pages.index'),
            'button_route'  => null,
            'pages'         => $pages,
            'total'         => $pageTotal,

            'formConfig' => [
                'action' => route('pages.store'),
                'modal_size' => 'modal-lg',
                'method' => 'POST',
                'button' => 'Create Page',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Page Title',                        
                        'slug_create' => 'slug-source',
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#slug'
                        ],
                        'col' => 'col-md-9 col-9'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'slug',
                        'label' => 'Page Slug',                        
                        'id'    => 'slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'Status',
                        'options' => [
                            1 => '1',
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                            7 => '7',
                            8 => '8',
                        ],
                        'col' => 'col-md-3 col-3'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'content',
                        'summer_class' => 'summernote',
                        'label' => 'Content',                        
                        'id'    => 'slug',
                        'col' => 'col-md-12 col-12'
                    ]                    
                ]
            ]
        ];       
        return view('admin.settings.pages.index', $data);
    }  

    public function page_create(){
        return view("admin.settings.pages.create");
    }

    public function page_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $page = new Page;
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->menu_order = $request->menu_order;
        $page->save();

        $message = 'Page added successfully.';
        //session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function page_edit($id){
        $page = Page::find($id);
        if ($page == null){
            session()->flash('error','Page not found');
            return redirect()->route('pages.index');
        }

        return view('admin.settings.pages.edit',[
            'page' => $page
        ]);
    }

    public function page_update(Request $request, $id){
        $page = Page::find($id);

        if($page == null) {
            session()->flash('error','Page not found');
            return response()->json([
                'status' => true,
            ]);
        };

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
        ]);
        
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->menu_order = $request->menu_order;
        $page->save();

        $message = 'Page updated successfully.';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function page_destroy($id){
        $page = Page::find($id);
        if($page == null) {
            session()->flash('error','Page not found');
            return response()->json([
                'status' => true,
            ]);
        };
        $page->delete();
        $message = 'Page deleted successfully.';
        //session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function rating_index(Request $request, ){
        $reviews = Review::with(['product.product_images', 'user'])->latest('id');   
        $total = Review::count();
        $reviews = $reviews->paginate(10);

        return view('admin.settings.ratings', [
            'reviews' => $reviews,
            'total'   => $total,
        ]);
    }

    public function approve($id) {
        $review = Review::findOrFail($id);
        $review->status = 1;
        $review->save();

        return back()->with('success', 'Review approved successfully!');
    }

    public function reject($id) {
        $review = Review::findOrFail($id);
        $review->status = 0;
        $review->save();

        return back()->with('success', 'Review rejected successfully!');
    }

    public function review_delete($id, Request $request){
        $review = Review::find($id);
        if(empty($review)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $review->delete();
        $request->session()->flash('success', 'Review deleted successfully');

        return response([
            'status' => true,
            'message' => 'Review deleted successfully',
        ]);
    }

}