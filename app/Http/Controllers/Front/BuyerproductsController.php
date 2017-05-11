<?php
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductShippingDetail;
use App\Models\ProductAttribute;
use App\Models\ProductAuction;
use App\Models\Category;
use App\Models\occasions;
use App\Models\ProductConditions;
use App\Models\AttributeSetCategory;
use App\Models\UserAddress;
use DB;
use Datatables;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;

class BuyerproductsController extends Controller
{
    public $attributeSetCategory;
    public $category;

    public function __construct() {
        $this->product 				= new Product();
        $this->category 			= new Category();
        $this->occasions 			= new Occasions();
        $this->productConditions 	= new ProductConditions();
        $this->attributeSetCategory = new AttributeSetCategory();
    }

    public function index() {
        $productStatus = getMasterEntityOptions('product_status');
        $productMOS = getMasterEntityOptions('product_mode_of_selling');
        $categories = $this->category->getNestedData();
        return view('front.buy.product.index', compact('productStatus', 'productMOS', 'categories'));
    }

    public function datatableList(Request $request) {
        
        $search     = $request->input('search');
        $IsSearch   = '';
        
        if(!empty($search['value']))
        {
            $IsSearch = $search['value'];
        }

        $products = Product::getAllProducts($IsSearch);
        
        $hasPermission['update'] = TRUE; // FALSE;
        $hasPermission['delete'] = TRUE; // FALSE;

        return Datatables::of($products)
                        ->addColumn('action', function ($product) use ($hasPermission) {
                            $action = '';
                            $action .= ($hasPermission['update']) ? '<a href="' . route('createProduct', ['step_one', encrypt($product->id)]) . '" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>' : '';
                            //$action .= ($hasPermission['delete'] && $product->status != "Active") ? '&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-danger btn-xs fa fa-trash-o deleteProduct" data-toggle="modal" data-placement="top" title="Delete" data-product_delete_remote="">D</a>' : '';
                            return $action;
                        })
                        ->addColumn('image', function ($product) use ($hasPermission) {
                            $image = '';
                            $image .= '<img src="'.getImageFullPath(@$product->files[0]->path,'products','thumbnail').'"/>';
                            return $image;
                        })
                        ->editColumn('description', '{!! str_limit($description, 40) !!}')
                        ->filter(function ($query) use ($request) {

                            if ($request->has('status') && trim($request->input('status')) != "") {
                                if ($request->input('status') != "All")
                                    $query->where('status', trim($request->input('status')));
                            }

                            if ($request->has('mode_of_selling') && $request->input('mode_of_selling') != "") {
                                if ($request->input('mode_of_selling') != "All")
                                    $query->where('mode_of_selling', $request->input('mode_of_selling'));
                            }

                            if ($request->has('category_id') && $request->input('category_id') != "") {
                                if ($request->input('category_id') != 0)
                                    $query->where('category_id', $request->input('category_id'));
                            }
                        })
                        ->make(true);

    }
}
