<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\CategoryModel;
use App\Models\Cart;
use App\Models\User;
use Hash;
use File;
use App;
use URL;
use Image;
use Carbon\Carbon;
use Str;
use Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller as Controller;
use Helper;
use DB;
class ApiController extends Controller
{
    public function getCategory()
    {
    try {
        // Fetch categories where category_id = 0 and status = 1
        $categories = CategoryModel::where('category_id', 0)
            ->where('status', 1)
            ->get(['id', 'name', 'mobile_image', 'status']); // Only required fields

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No categories found.',
                'data' => []
            ], 404);
        }

        // Attach full image URL using APP_URL dynamically
        $categories = $categories->map(function ($item) {
            $item->mobile_image = $item->mobile_image
                ? url('uploads/category/' . $item->mobile_image)
                : null;
            return $item;
        });

        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully.',
            'data' => $categories
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function getProduct(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
    
            // ✅ Base query with joins for category & subcategory
            $query = DB::table('products as p')
                ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
                ->leftJoin('categories as sc', 'p.subcategory_id', '=', 'sc.id')
                ->select(
                    'p.id',
                    'p.name',
                    'p.status',
                    'p.category_id',
                    'p.price',
                    'p.discount_price',
                    'p.subcategory_id',
                    'c.name as category_name',
                    'sc.name as subcategory_name'
                )
                ->where('p.status', 1)
                ->whereNull('p.deleted_at');;
    
            // ✅ If category_id provided → filter by that category or subcategory
            if (!empty($categoryId)) {
                $query->where(function ($q) use ($categoryId) {
                    $q->where('p.category_id', $categoryId)
                      ->orWhere('p.subcategory_id', $categoryId);
                });
            }
    
            $products = $query->get();
    
            // ✅ Handle no data
            if ($products->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No products found.',
                    'data' => []
                ], 404);
            }
    
            // ✅ Get all variants with joined color and size names
            $productIds = $products->pluck('id');
    
            $variants = DB::table('product_variations as pv')
                ->leftJoin('colors as col', 'pv.color_id', '=', 'col.id')
                ->leftJoin('sizes as s', 'pv.size_id', '=', 's.id')
                ->whereIn('pv.product_id', $productIds)
                ->select(
                    'pv.id',
                    'pv.product_id',
                    'pv.image',
                    'pv.color_id',
                    'col.name as color_name',
                    'pv.size_id',
                    's.name as size_name'
                )
                ->get()
                ->groupBy('product_id');
    
            // ✅ Map variants to products & format image URLs
            $baseUrl = url('uploads/product_variations');
    
            $products = $products->map(function ($product) use ($variants, $baseUrl) {
                $product->variants = $variants->get($product->id, collect())->map(function ($variant) use ($baseUrl) {
                    $variant->image = $variant->image
                        ? $baseUrl . '/' . $variant->image
                        : null;
                    return $variant;
                })->values();
    
                return $product;
            });
    
            // ✅ Return final response
            return response()->json([
                'status' => true,
                'message' => 'Products fetched successfully.',
                'data' => $products
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductDetail(Request $request)
    {
        try {
            // ✅ Validate that product_id is provided
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);
    
            $productId = $request->product_id;
    
            // ✅ Fetch product with category & subcategory names
            $product = DB::table('products as p')
                ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
                ->leftJoin('categories as sc', 'p.subcategory_id', '=', 'sc.id')
                ->select(
                    'p.id',
                    'p.name',
                    'p.description',
                    'p.price',
                    'p.discount_price',
                    'p.status',
                    'p.category_id',
                    'p.subcategory_id',
                    'c.name as category_name',
                    'sc.name as subcategory_name'
                )
                ->where('p.id', $productId)
                ->where('p.status', 1)
                ->first();
    
            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found.',
                    'data' => null
                ], 404);
            }
    
            // ✅ Get product variants with color & size names
            $variants = DB::table('product_variations as pv')
                ->leftJoin('colors as col', 'pv.color_id', '=', 'col.id')
                ->leftJoin('sizes as s', 'pv.size_id', '=', 's.id')
                ->where('pv.product_id', $product->id)
                ->select(
                    'pv.id',
                    'pv.product_id',
                    'pv.image',
                    'pv.color_id',
                    'col.name as color_name',
                    'pv.size_id',
                    's.name as size_name'
                )
                ->get()
                ->map(function ($variant) {
                    $variant->image = $variant->image
                        ? url('uploads/product_variations/' . $variant->image)
                        : null;
                    return $variant;
                });
    
            $product->variants = $variants;
    
            // ✅ Return full product detail response
            return response()->json([
                'status' => true,
                'message' => 'Product details fetched successfully.',
                'data' => $product
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editProfile(Request $request)
    {
        try {
    // dd($request);
            // ✅ Find user by ID
            $user = User::find($request->id);
    
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.'
                ], 404);
            }
    
            // ✅ Update fields if provided
            if ($request->has('name')) {
                $user->full_name = $request->name;
            }
    
            if ($request->has('email')) {
                $user->email = $request->email;
            }
    
            if ($request->has('mobile')) {
                $user->mobile = $request->mobile;
            }
            
            if ($request->has('address')) {
                $user->address = $request->address;
            }
    
            
            if ($request->hasFile('profileImage')) {
                $file = $request->file('profileImage');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/users'), $filename);
    
                $user->photo = $filename;
            }
    
            // ✅ Save user
            $user->save();
    
            // ✅ Return response with full image URL
            // $profileImageUrl = $user->profile_image 
            //     ? url('uploads/profile/' . $user->photo)
            //     : null;
    
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.',
                'user   ' => $user
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function addToCart(Request $request)
    {

        $userId = $request->user_id;
        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $sizeName = $request->size_name;

        try {
            // ✅ Check if this product variant and size is already in cart
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('size_name', $sizeName)
                ->first();

            if ($cartItem) {

                return response()->json([
                    'status' => true,
                    'message' => 'This Item Already in Cart'
                ]);
            } else {
                // ✅ Add new cart item
                $newCartItem = Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'size_name' => $sizeName,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Item added to cart successfully',
                    'cart' => $newCartItem,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: '.$e->getMessage(),
            ], 500);
        }
    }
    
    public function showCart(Request $request, $id)
    {
        try {
            // ✅ Validate that the user exists
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }
    
            // ✅ Fetch user's cart items (assuming you have a Cart model)
            $cartItems = Cart::select('carts.*','products.name as product_name','products.discount_price as price','product_variations.image as product_image','colors.name as color_name')
                ->leftjoin('products','products.id','carts.product_id')
                ->leftjoin('product_variations','product_variations.id','carts.variant_id')
                ->leftjoin('colors','colors.id','product_variations.color_id')
                ->where('carts.user_id', $id)
                ->get();
    
            // ✅ Check if the cart is empty
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Cart is empty',
                    'data' => []
                ], 200);
            }
            
            $cartItems->transform(function ($item) {
                $item->product_image = $item->product_image
                    ? url('uploads/product_variations/' . $item->product_image) // if using storage/app/public/
                    : null; // fallback image
                return $item;
            });
    
            // ✅ Return response
            return response()->json([
                'status' => true,
                'message' => 'Cart data retrieved successfully',
                'data' => [
                    'items' => $cartItems,
                ],
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function cartRemove(Request $request)
    {
        Cart::where('id', $request->cart_id)->delete();
        return response()->json(['status' => true, 'message' => 'Item removed']);
    }


}