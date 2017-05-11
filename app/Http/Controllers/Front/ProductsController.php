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
use App\Models\Files;
use DB;
use Datatables;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller {

    public $attributeSetCategory;
    public $category;

    public function __construct() {
        $this->product = new Product();
        $this->ProductSku = new ProductSku();
        $this->category = new Category();
        $this->occasions = new Occasions();
        $this->productConditions = new ProductConditions();
        $this->attributeSetCategory = new AttributeSetCategory();
        $this->ProductAttribute = new ProductAttribute();
        $this->Files = new Files();
        
    }

    public function index() {
        $productStatus = getMasterEntityOptions('product_status');
        $productMOS = getMasterEntityOptions('product_mode_of_selling');
        $categories = $this->category->getNestedData();
        return view('front.sell.product.index', compact('productStatus', 'productMOS', 'categories'));
    }

    public function datatableList(Request $request) {
        
        $search     = $request->input('search');
        $IsSearch   = '';
        
        if(!empty($search['value']))
        {
            $IsSearch = $search['value'];
        }
        
        $products = Product::getAllProducts($IsSearch);
        //dd($products);
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
                        ->addColumn('stock_available', function ($product) use ($hasPermission) {
                            $stock_available = '';
                            $stock_available .= $product->productSkus->sum('quantity').'</br> (As on '.date('d M, Y H:i:s').')';
                            return $stock_available;
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

    public function detail(Request $request,$slug) {
        try {            
            $where = ['product_slug' => $slug];
            //$productData = Product::with(['productAuction', 'productOriginAddress'])->where(['id' => $id])->first();
            $product = Product::getProductDetails($where);
            $productnonvariant = Product::getProductDetailsnonvariant($where);
            //dd($productnonvariant);
            $categories = $this->category->getCategoriesById($product->category_id);
            $category['category_name']=$categories->text;
            $category['category_slug']=$categories->category_slug;
            $attributes=[];
            foreach(@$product->productSkusVariantAttribute as $skus){
                foreach(@$skus->productVariantAttribute as $productVariantAttr){  
                    $attributes[$productVariantAttr->attribute->attribute_slug][]=array('values'=>$productVariantAttr->attributeValue->attribute_values,'price'=>$skus->final_price,'sku_id'=>$skus->id);
                }
                
            }
            if (empty($product)) {
                \Flash::error(trans('message.failure'));
                return redirect()->route('homepage');
            }
            $value = $request->cookie('recent_view');
            //dd($value);
            
            $value[$product->id]=$product->id;
            
            $cookie = cookie('recent_view', $value, 8640000,'/','');
            
            $sliders['Featured_Products'] = $this->product->getFeaturedProducts();
            //dd($product);

            return response()->view('front.products.detail', compact('product', 'attributes', 'sliders','category','productnonvariant'))->withCookie($cookie);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function create($step, $productId) {
        switch ($step) {
            case 'step_one':
                if (loggedinUserType() == 'Buyer') {
                    \Flash::error("Access denied! Seller can only add product!");
                    return redirect()->route('listingProduct');
                }
                return $this->createStepOne('step_one', $productId);
                break;
            case 'step_two':
                return $this->createStepTwo('step_two', $productId);
                break;
            case 'step_three':
                return $this->createStepThree('step_three', $productId);
                break;
            case 'step_four':
                return $this->createStepFour('step_four', $productId);
                break;
        }
    }

    private function createStepOne($step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;
        
        //dd($id);

        $warrantyType = getMasterEntityOptions('warranty_type');
        $warrantyDurationType = getMasterEntityOptions('warranty_duration_type');
        $returnAcceptanceDays = getMasterEntityOptions('return_acceptance_days');
        $productType = getMasterEntityOptions('product_type');
        $allCategories = Category::where('parent_id', 0)->pluck('text', 'id')->all();
        $occasions = ['' => 'Select occassion'] + $this->occasions->getOccasions()->pluck('name', 'id')->toArray();
        $countries = getAllCountries()->pluck('country_name', 'id')->toArray();

        /* Update step 1 */
        $productData = [];
        $productData['occassion_id'] = NULL;
        $productConditions = ['' => 'Select product condition'];
        $categoryIds = [];
        if ($updateFlag) {
            $productData = Product::with(['productAuction', 'productOriginAddress'])->where(['id' => $id])->first();
            $productConditions += $this->productConditions->getProductConditionsByCategory($productData['category_id'])->pluck('name', 'id')->toArray();

            /* category sub category drodowns */
            $categories = Category::where(['id' => $productData['category_id']])->first()->ancestorsAndSelf()->get();
            foreach ($categories AS $category) {
                $categoryIds[] = $category['id'];
            }
            arsort($categoryIds);
            array_pop($categoryIds);
            asort($categoryIds);
            $categoryIds = array_values($categoryIds);
        }

        if (Session::has('message'))
        {

        }

        //dd($productData);

        return view('front.sell.product.create', compact('productData', 'updateFlag', 'productId', 'step', 'warrantyType', 'warrantyDurationType', 'returnAcceptanceDays', 'productType', 'allCategories', 'occasions', 'countries', 'productConditions', 'categoryIds'));
    }

    private function createStepTwo($step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;
        $productData = Product::find($id);
        
        return view('front.sell.product.create', compact('updateFlag', 'productId', 'step', 'productData'));
    }

    private function createStepThree($step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;

        $productShippingLengthClass = getMasterEntityOptions('product_shipping_length_class');
        $productShippingWeightClass = getMasterEntityOptions('product_shipping_weight_class');

        /* --- Get product detail --- */
        $productFindObj = Product::find($id);
        $files = $productFindObj->Files;
        
        if(count($files) == 0)
        {
           \Flash::error(trans('message.product.file_error'));
           return redirect()->route('createProduct', ['step' => 'step_two','productId'=>$productId]);
        }
        
        $productData = $productFindObj->toArray();
        //dd($productFindObj);
        // variant attribute section only visible by excluding below option (Its only for Buy it now option)
        $variantAttrAllowed = FALSE;
        if ($productData['mode_of_selling'] != "Make an offer" && $productData['mode_of_selling'] != "Auction") {
            $variantAttrAllowed = TRUE;
        }

        /* --- Get all parent categories along with self --- */
        $categories = Category::where(['id' => $productData['category_id']])->first()->ancestorsAndSelf()->get();
        $categoryIds = [];
        foreach ($categories AS $category) {
            $categoryIds[] = $category['id'];
        }
        arsort($categoryIds);
        array_pop($categoryIds);

        /* --- Get attribute values by attributes by attribute sets --- */
        $attributeSetCategories = $this->attributeSetCategory->getCategoryAttributes($categoryIds);

        $attributeVariantOptions = [];
        $attributeNonVariantOptions = [];

        $attributeOptionValues = [];

        $maxPossibleSkuCombination = 1;

        foreach ($attributeSetCategories AS $attributeSets) {
            foreach ($attributeSets['Attributes'] AS $attributes) {

                if ($attributes['variation_allowed'] == 1) {

                    $maxPossibleSkuCombination *= count($attributes['AttributeValues']);

                    foreach ($attributes['AttributeValues'] AS $attributeValue) {

                        $attributeVariantOptions
                                [$attributes['attribute_set_id']]
                                [$attributes['id']]
                                [$attributes['attribute_name']]
                                [$attributeValue['id']] = $attributeValue['attribute_values'];
                    }
                } else {

                    if ($attributes['catelog_input_type'] != 'text') {
                        foreach ($attributes['AttributeValues'] AS $attributeValue2) {
                            $attributeNonVariantOptions
                                    [$attributes['attribute_set_id']]
                                    [$attributes['catelog_input_type']]
                                    [$attributes['id']]
                                    [$attributes['attribute_name']]
                                    [$attributeValue2['id']] = $attributeValue2['attribute_values'];
                        }
                    } else {
                        $attributeNonVariantOptions
                                [$attributes['attribute_set_id']]
                                [$attributes['catelog_input_type']]
                                [$attributes['id']] = $attributes['attribute_name'];
                    }
                }
            }
        }

        /*
          |---------------------------------------------------------------------------------
          | Update attributes (Fetch variant and non-variant attributes to filled into form)
          |---------------------------------------------------------------------------------
         */
        if ($updateFlag) {
            // Get product non variant attributes detail
            $productNonVariantAttributesTmp = Product::select('id')->where('id', $id)->with(['productNonVariantAttribute'])->first()->toArray();
            $productNonVariantAttributes = $productNonVariantAttributesTmp['product_non_variant_attribute'];
            // Get product variant attributes detail
            $productSkuVariantAttributes = Product::where('id', $id)->with(['productSkus.productVariantAttribute'])->first();
        }

        $productSkuVariantAttributesTmp = $productSkuVariantAttributes->toArray();
        if (empty($productSkuVariantAttributesTmp['product_skus'])) {
            // default one row of sku at add time
            $productSkuVariantAttributes['productSkus'][0] = ['id' => 0, 'is_default' => 'Yes', 'available_in_bulk' => 'No', 'quantity' => 0, 'sku' => '', 'additional_price' => 0, 'product_id' => $id, 'productVariantAttribute' => []];
        }

        //dd($productSkuVariantAttributes['productSkus']);

        return view('front.sell.product.create', compact('variantAttrAllowed', 'productData', 'updateFlag', 'productId', 'step', 'productDetails', 'attributeVariantOptions', 'attributeNonVariantOptions', 'maxPossibleSkuCombination', 'productShippingLengthClass', 'productShippingWeightClass', 'productNonVariantAttributes', 'productSkuVariantAttributes'));
    }

    private function createStepFour($step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;

        $allRelatedProductIds = Product::select('id', 'name')->where('user_id', loggedinUserId())->get()->toArray();
        $productFindObj = Product::find($id);
        
        $files = $productFindObj->Files;
        
        if(count($files) == 0)
        {
           \Flash::error(trans('message.product.file_error'));
           //return redirect()->route('createProduct', ['step' => 'step_two','productId'=>$productId ,'type' => 'step_three' ]);
           return redirect()->route('createProduct', ['step' => 'step_two','productId'=>$productId]);
        }

        $productData = $productFindObj->toArray();

        $selectedRelatedProductIds = $productData['related_product_ids'] != "" ? explode(',', $productData['related_product_ids']) : [];

        return view('front.sell.product.create', compact('updateFlag', 'productId', 'step', 'productData', 'allRelatedProductIds', 'selectedRelatedProductIds'));
    }

    public function store(Request $request, $step, $productId) {
        switch ($step) {
            case 'step_one': return $this->storeStepOne($request, 'step_one', $productId);
                break;
            case 'step_two': return $this->storeStepTwo($request, 'step_two', $productId);
                break;
            case 'step_three': return $this->storeStepThree($request, 'step_three', $productId);
                break;
            case 'step_four': return $this->storeStepFour($request, 'step_four', $productId);
                break;
        }
    }

    private function storeStepOne(Request $request, $step, $productId) {

        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = FALSE;
        $inputs = [];
        $auctionInputs = [];

        if ($id != 0) {
            $productFindObj = Product::find($id);
            $updateFlag = (empty($productFindObj)) ? FALSE : TRUE;
        }

        /* ---------- Custom validation messages ---------- */
        $customValidationMessage = [
            'category_id.*.required' => 'The category field is required.',
            'ckeditor.required' => 'The description field is required.'
        ];

        /* ---------- Validation check ---------- */
        $productUnique = ($updateFlag) ? ',' . $id : '';
        $validations = [
            'category_id.*' => 'required',
            'name' => 'required|min:5|max:100|unique:products,name' . $productUnique,
            'manufacturer' => 'required|max:100',
            'ckeditor' => 'required',
            'mode_of_selling' => 'required',
            'start_datetime' => 'date|required_if:auction_type,By price',
            'end_datetime' => 'date|required_if:auction_type,By price',
            'product_origin' => 'required',
            'warranty_applicable' => 'required',
            'warranty_type' => 'required_if:warranty_applicable,Yes',
            'warranty_duration' => 'integer|required_if:warranty_applicable,Yes',
            'warranty_duration_type' => 'required_if:warranty_applicable,Yes',
            'return_applicable' => 'required',
            'return_acceptance_days' => 'required_if:return_applicable,Yes',
            'product_type' => 'required',
            //'occassion_id' => 'required',
            'billing_address_1' => 'max:100|required_if:product_origin,No',
            'billing_address_2' => 'max:100|required_if:product_origin,No',
            'billing_country' => 'required_if:product_origin,No',
            'billing_postal_code' => 'max:10|required_if:product_origin,No',
            'billing_state' => 'required_if:product_origin,No',
            'billing_city' => 'required_if:product_origin,No',
            'model'         => 'required',
            'product_condition_id' => 'required',
        ];

        $inputs = $request->all();
        /* ---------- Mode of selling options validation check ---------- */
        switch ($request->input("mode_of_selling")) {
            case 'Buy it now':
                $validations += [
                    'bin.base_price' => 'numeric|required_if:mode_of_selling,Buy it now',
                    'bin.max_order_quantity' => 'integer|required_if:mode_of_selling,Buy it now'
                ];

                $inputs['base_price'] = $inputs['bin']['base_price'];
                $inputs['max_order_quantity'] = $inputs['bin']['max_order_quantity'];
                // make an offer fields
                $inputs['min_reserved_price'] = 0;
                $inputs['max_product_price'] = 0;
                // auction
                $inputs['auction_id'] = 0;
                break;
            case 'Make an offer':
                $validations += [
                    'maf.min_reserved_price' => 'numeric|required_if:mode_of_selling,Make an offer',
                    'maf.max_product_price' => 'numeric|required_if:mode_of_selling,Make an offer|min:'.$inputs['maf']['min_reserved_price']
                ];

                $inputs['min_reserved_price']   = $inputs['maf']['min_reserved_price'];
                $inputs['max_product_price']    = $inputs['maf']['max_product_price'];
                // buy it now fields
                $inputs['base_price'] = 0;
                $inputs['max_order_quantity'] = 0;
                // auction
                $inputs['auction_id'] = 0;
                break;

            case 'Auction':
                
                if(!isset($inputs['auction']))
                {
                    $validations += ['auction' => 'required'];
                }else{
                    $auctionType = $inputs['auction']['auction_type'];
                    $auctionPrefix = ($auctionType == 'By price') ? 'auction_by_price' : 'auction_by_time';
                    $validations += [
                        $auctionPrefix . '.min_reserved_price' => 'numeric|required_if:auction.auction_type,' . $auctionType,
                        $auctionPrefix . '.min_bid_increment' => 'numeric|required_if:auction.auction_type,' . $auctionType,
                        $auctionPrefix . '.start_datetime' => 'date|required_if:auction.auction_type,' . $auctionType,
                        $auctionPrefix . '.end_datetime' => 'date|required_if:auction.auction_type,' . $auctionType
                    ];
                }
                
                $validations += ($auctionType == 'By price') ? [$auctionPrefix . '.max_product_price' => 'numeric|required_if:auction.auction_type,By price'] : [];

                /* ----- Auction inputs ----- */
                if ($auctionType == 'By price') {
                    $auctionInputs['max_product_price'] = $inputs['auction_by_price']['max_product_price'];
                    
                    $bid_price = ($inputs[$auctionPrefix]['min_reserved_price']) + $inputs[$auctionPrefix]['min_reserved_price']*($inputs[$auctionPrefix]['min_bid_increment']/100);
                    
                    if(($bid_price > 0) && !empty($auctionInputs['max_product_price']) &&  ($auctionInputs['max_product_price'] < $bid_price) )
                    {
                        $validations[$auctionPrefix . '.max_product_price'] =  'numeric|required|min:'.$bid_price;
                        $customValidationMessage += [
                            $auctionPrefix . '.max_product_price.min' => ' Max Product Price is greater than incremented price ('.$bid_price.')'
                        ];
                    }
                }

                $auctionInputs['mode'] = $auctionType;
                $auctionInputs['min_bid_increment'] = $inputs[$auctionPrefix]['min_bid_increment'];
                $auctionInputs['min_reserved_price'] = $inputs[$auctionPrefix]['min_reserved_price'];
                $auctionInputs['start_datetime'] = $inputs[$auctionPrefix]['start_datetime'];
                $auctionInputs['end_datetime'] = $inputs[$auctionPrefix]['end_datetime'];

                // make an offer fields
                $inputs['min_reserved_price'] = 0;
                $inputs['max_product_price'] = 0;
                // buy it now fields
                $inputs['base_price'] = 0;
                $inputs['max_order_quantity'] = 0;
                break;

            case 'Buy it now and Make an offer':
                $validations += [
                    'bin_maf.base_price' => 'numeric|required_if:mode_of_selling,Buy it now and Make an offer',
                    'bin_maf.max_order_quantity' => 'integer|required_if:mode_of_selling,Buy it now and Make an offer',
                    'bin_maf.min_reserved_price' => 'numeric|required_if:mode_of_selling,Buy it now and Make an offer',
                    'bin_maf.max_product_price' => 'numeric|required_if:mode_of_selling,Buy it now and Make an offer'
                ];

                $inputs['base_price'] = $inputs['bin_maf']['base_price'];
                $inputs['max_order_quantity'] = $inputs['bin_maf']['max_order_quantity'];
                $inputs['min_reserved_price'] = $inputs['bin_maf']['min_reserved_price'];
                $inputs['max_product_price'] = $inputs['bin_maf']['max_product_price'];
                // auction
                $inputs['auction_id'] = 0;
                break;

            case 'Buy it now and Auction':
                // buy it now
                $validations += [
                    'bin_auction.base_price' => 'numeric|required_if:mode_of_selling,Buy it now and Auction',
                    'bin_auction.max_order_quantity' => 'integer|required_if:mode_of_selling,Buy it now and Auction',
                ];

                $inputs['base_price'] = $inputs['bin_auction']['base_price'];
                $inputs['max_order_quantity'] = $inputs['bin_auction']['max_order_quantity'];

                // auction
                $auctionType = $inputs['bin_auction']['auction_type'];
                $auctionPrefix = ($auctionType == 'By price') ? 'bin_auction_by_price' : 'bin_auction_by_time';
                $validations += [
                    $auctionPrefix . '.min_reserved_price' => 'numeric|required_if:bin_auction.auction_type,' . $auctionType,
                    $auctionPrefix . '.min_bid_increment' => 'numeric|required_if:bin_auction.auction_type,' . $auctionType,
                    $auctionPrefix . '.start_datetime' => 'date|required_if:bin_auction.auction_type,' . $auctionType,
                    $auctionPrefix . '.end_datetime' => 'date|required_if:bin_auction.auction_type,' . $auctionType
                ];
                $validations += ($auctionType == 'By price') ? [$auctionPrefix . '.max_product_price' => 'numeric|required_if:bin_auction.auction_type,By price'] : [];

                /* ----- Auction inputs ----- */
                if ($auctionType == 'By price') {
                    $auctionInputs['max_product_price'] = $inputs['bin_auction_by_price']['max_product_price'];

                    $bid_price = ($inputs[$auctionPrefix]['min_reserved_price']) + $inputs[$auctionPrefix]['min_reserved_price']*($inputs[$auctionPrefix]['min_bid_increment']/100);
                    
                    if(($bid_price > 0) && !empty($auctionInputs['max_product_price']) &&  ($auctionInputs['max_product_price'] < $bid_price) )
                    {
                        $validations[$auctionPrefix . '.max_product_price'] =  'numeric|required|min:'.$bid_price;
                        $customValidationMessage += [
                            $auctionPrefix . '.max_product_price.min' => ' Max Product Price is greater than incremented price ('.$bid_price.')'
                        ];
                    }
                }

                $auctionInputs['mode'] = $auctionType;
                $auctionInputs['min_bid_increment'] = $inputs[$auctionPrefix]['min_bid_increment'];
                $auctionInputs['min_reserved_price'] = $inputs[$auctionPrefix]['min_reserved_price'];
                $auctionInputs['start_datetime'] = $inputs[$auctionPrefix]['start_datetime'];
                $auctionInputs['end_datetime'] = $inputs[$auctionPrefix]['end_datetime'];

                // make an offer fields
                $inputs['min_reserved_price'] = 0;
                $inputs['max_product_price'] = 0;
                break;
        }

        /* ---------- Validates inputs ---------- */
        $a = $this->validate($request, $validations, $customValidationMessage);
        // Start transaction!
        DB::beginTransaction();
        try {
            /* ---------- Assign proper category id ---------- */
            $categoryIds = $inputs['category_id'];
            
            if (last($categoryIds) == 0) {
                array_pop($categoryIds);
            }
            $categoryId = last($categoryIds);

            $inputs['category_id'] = $categoryId;
            $inputs['description'] = $inputs['ckeditor'];

            /* --------------------------------------------------------------- */
            /* ---------------- Create/Update product step-1 ----------------- */
            /* --------------------------------------------------------------- */

            $inputs['status'] = $updateFlag ? $productFindObj->status : 'Draft';

            /* ---------- START: Create/Update Billing Address - seller ---------- */
            if ($inputs['product_origin'] == 'No') {
                $requestAddress = [
                    'address_1' => $request->input('billing_address_1'),
                    'address_2' => $request->input('billing_address_2'),
                    'country_id' => $request->input('billing_country'),
                    'postal_code' => $request->input('billing_postal_code'),
                    'state_id' => $request->input('billing_state'),
                    'city_id' => $request->input('billing_city'),
                    'user_id' => loggedinUserId(),
                    'address_type' => 'Billing'
                ];

                if ($inputs['user_address_id'] != "") {
                    // update address
                    $prevUserAddressId = decrypt($inputs['user_address_id']);
                    $userAddress = UserAddress::updateUserAddress(['id' => $prevUserAddressId], $requestAddress);
                    $inputs['user_address_id'] = $prevUserAddressId;
                } else {
                    // create address
                    $userAddress = UserAddress::createAddress($requestAddress);
                    $inputs['user_address_id'] = $userAddress->id;
                }
            } else {
                // if yes then get existing billing address id
                $userAddress = UserAddress::getUserAddress(['address_type' => 'Billing']);
                $inputs['user_address_id'] = $userAddress->id;
            }
            /* ---------- END: Create/Update Billing Address - seller ---------- */

            if ($inputs['product_condition_id'] == "") {
                unset($inputs['product_condition_id']);
            }

            if ($inputs['occassion_id'] == "") {
                unset($inputs['occassion_id']);
            }else{
                $inputs['occassion_id'] = implode(',', $inputs['occassion_id']);
            }   

            
            /* ---------- Create/Update product ---------- */
            if (!$updateFlag) {
                $productInfo = Product::createProduct($inputs);
            } else {
                $productFindObj->update($inputs);
            }
            
            // consider $id for update
            $productId = ($updateFlag) ? $id : $productInfo->id;

            /* -------------- Create auction setting -------------- */
            if (!empty($auctionInputs)) {
                $auctionInputs['product_id'] = $productId;
                //dd($productId);
                $auctionInputs['start_datetime']    = date('Y-m-d',strtotime($auctionInputs['start_datetime']));
                $auctionInputs['end_datetime']      = date('Y-m-d',strtotime($auctionInputs['end_datetime']));

                //dd($auctionInputs);
                if ($inputs['auction_id'] != '0') {
                    // update auction in edit mode
                    $auctionId = decrypt($inputs['auction_id']);
                    if($auctionId != '0')
                    {
                        $prodAuctionFindObj = ProductAuction::find($auctionId);
                        $prodAuctionFindObj->update($auctionInputs);
                    }else{
                        $auctionData = ProductAuction::createProductAuction($auctionInputs);
                        $auctionId = $auctionData->id;    
                    }
                } else {
                    // create auction
                    $auctionData = ProductAuction::createProductAuction($auctionInputs);
                    $auctionId = $auctionData->id;
                }

                // update auction id in products table
                $prodDataObj = Product::find($auctionInputs['product_id']);
                $prodDataObj->update(['auction_id' => $auctionId]);
            } else if ($request->input('auction_id') != '0') {
                $aId = decrypt($request->input('auction_id'));
                // delete existing product auction
                if ($aId != '0')
                    ProductAuction::findOrFail(decrypt($request->input('auction_id')))->delete();
            }

            // If we reach here, then data is valid and working. Commit the queries!
            
            DB::commit();
            

            if ($inputs['submit_type'] == 'Next') {
                \Flash::success(trans('message.product.create_success'));
                return response()->json(['status' => 'success', 'redirectUrl' => route('createProduct', ['step_two', encrypt($productId)])]);
            } else {
                \Flash::success(trans('message.product.draft_success'));
                return response()->json(['status' => 'success', 'redirectUrl' => route("listingProduct")]);
            }
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    private function storeStepTwo(Request $request, $step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;

        /* ---------- Custom validation messages ---------- */
        $customValidationMessage = [];

        /* ---------- Validation check ---------- */
        $validations = ['meta_title' => 'max:50', 'meta_keywords' => 'max:200', 'meta_description' => 'max:150'];

        /* ---------- Validates inputs ---------- */
        $this->validate($request, $validations, $customValidationMessage);

        // Start transaction!
        DB::beginTransaction();
        
        try {
            // update some fields in products table
            $productFindObj = Product::find($id);
            $productFindObj->update($request->only(['meta_title', 'meta_keywords', 'meta_description']));

            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();
            
            if ($request->input('submit_type') == 'Next') {
                \Flash::success(trans('message.product.update_success'));
                return response()->json(['status' => 'success', 'redirectUrl' => route('createProduct', ['step_three', encrypt($id)])]);
            } else {
                \Flash::success(trans('message.product.draft_success'));
                return response()->json(['status' => 'success', 'redirectUrl' => route("listingProduct")]); 
            }
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    private function storeStepThree(Request $request, $step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;

        /* ---------- Custom validation messages ---------- */
        $customValidationMessage = [];
        $allData = $request->all();
        
            


        //dd($request);
//unset($request->input('product_sku.0.attributes'));
//\Illuminate\Support\Facades\Input::replace(['product_sku[0][attributes]' => '']);
        
        //dd($allData);
        $same = 0;
        $attribute_array=array();
        
        /* ---------- Validation check ---------- */
        $validations = [
            'product_listing_price'             => 'required|numeric',
            'variation_allowed'                 => 'required',
            'sku_prefix'                        => 'min:3|max:30|unique:products,sku_prefix,' . $id . '|required_if:variation_allowed,Yes',
            'variant_attributes'                => 'required_if:variation_allowed,Yes',
            'product_sku.*.attributes.*.*'      => 'required_if:variation_allowed,Yes',
            'product_sku.*.sku'                 => 'distinct|required_if:variation_allowed,Yes',
            //'nonvariant_attributes.*' => 'required',
            'product_sku.*.quantity'            => 'numeric|required_if:variation_allowed,Yes',
            'product_sku.*.additional_price'    => 'numeric|required_if:variation_allowed,Yes',
            'shipping_type'                     => 'required',
            'sku_prefix_no'                     => 'min:3|max:30|unique:products,sku_prefix,' . $id . '|required_if:variation_allowed,No',
            'quantity_no'                       => 'numeric|required_if:variation_allowed,No',
        ];
        //dd($request->input('variant_attributes'));
        $new_data = array();
        if( isset($allData['product_sku'][0]['attributes']) && !empty($allData['product_sku']))
        {
            foreach ($allData['product_sku'] as $key => $value) 
            {   foreach ($value['attributes'] as $at_key => $at_value) 
                {
                    $new_data[$key][$at_key] = $at_value;
                }
            }

            $input = array_map("unserialize", array_unique(array_map("serialize", $new_data)));

            if(count($input) != count($new_data))
            {   
                
                $allData['variant_attributes'] = [];
                $request->replace($allData);
                $customValidationMessage['variant_attributes.required_if'] = 'Product attribute combination should be unique.Please check below your all attribute combination';
            }
        }

        //dd($customValidationMessage);
        /* ---------- Validates inputs ---------- */
        $this->validate($request, $validations, $customValidationMessage);



        /*if($request->input('variation_allowed') == 'Yes')
        {
            if( isset($allData['product_sku']) && count(@$allData['product_sku'])>0){
                foreach ($allData['product_sku'] as $key => $value){
                        
                        $attr_str=implode(',', $value['attributes']);
                        if(in_array($attr_str,$attribute_array)){
                            //$request->input('product_sku[0][attributes]','');
                            //unset($allData['product_sku'][$key]['attributes']);
                            //dd($allData['product_sku'][$key]);
                            $same = 1;
                        }
                        else{
                            $attribute_array[]=$attr_str;
                        }
                }
            }
        }*/
        

        // Start transaction!
        DB::beginTransaction();
        try {

            /*
              |--------------------------------------------------------------------------------------------------------------------------------------------
              | START: VARIANT ATTRIBUTE (Unique product attribute combination validation(sku) check and insert/update records) for variation_allowed = Yes
              |--------------------------------------------------------------------------------------------------------------------------------------------
             */
            //dd($request->all());
            
            $skus = $request->input("product_sku");

            $uploadedFile = '';
            if(!empty($request->file('product_sku')))
            {
               $uploadedFile = $request->file('product_sku');
            }
            
            if (!empty($skus) && $request->input('variation_allowed') == 'Yes') {
                
                $validationSku = [];
                $i = 0;
                $skuUniqueCombinationFlag = FALSE;

                /*
                  |--------------------------------------------------------------------------------------------------------------------------------------------
                  | START: SKUs for loop - iterate each row and insert/update in product_skus table
                  |--------------------------------------------------------------------------------------------------------------------------------------------
                 */
                // EDIT case: fetch all sku ids
                
                $editProductAllSkuIds = ProductSku::where(['product_id' => $id])->select(DB::raw("GROUP_CONCAT(id SEPARATOR ',') as `product_sku_ids` "))->first();

                $editProductAllSkuIds = (isset($editProductAllSkuIds['product_sku_ids']) && $editProductAllSkuIds['product_sku_ids'] != "") ? explode(",", $editProductAllSkuIds['product_sku_ids']) : [];
                $exceptProductSkuIds = [];
                //dd($skus);
                foreach ($skus AS $sku_key => $skuRow) {
                    
                    $attributes         = $skuRow['attributes'];
                    $attributeSetId     = $skuRow['attribute_set_id'];
                    $new_uploaded_file_name = '';
                    if(isset($skuRow['old_image']))
                    {
                        $new_uploaded_file_name = $skuRow['old_image'];
                    }

                    $upload_result = array();
                    
                    if(!empty($uploadedFile))
                    {   
                        /*dd($uploadedFile);*/
                        if(isset($uploadedFile[$sku_key]))
                        {
                            $upload_result = uploadImage($uploadedFile[$sku_key], true, $new_uploaded_file_name, '', 'products');
                            $new_uploaded_file_name = $upload_result['path'];
                        }
                        //$this->uploadImage($uploadedFile[$sku_key]['image'],$productId);
                    }

                    $skuData = [
                        'image'             => $new_uploaded_file_name,
                        'is_default'        => (isset($skuRow['is_default']) && $skuRow['is_default'] == 'Yes') ? 'Yes' : 'No',
                        'quantity'          => $skuRow['quantity'],
                        'additional_price'  => $skuRow['additional_price'],
                        'final_price'       => $request->input('product_listing_price') + $skuRow['additional_price'],
                        'available_in_bulk' => ($skuRow['available_in_bulk'] == 'Yes') ? 'Yes' : 'No',
                        'product_id'        => $id,
                        'sku'               => $skuRow['sku'],
                    ];

                    // EDIT case: sku update
                    
                    $skuUpdateId = ($skuRow['product_sku_id'] != '0' && $skuRow['product_sku_id'] != "") ? decrypt($skuRow['product_sku_id']) : 0;
                    
                    if ($skuUpdateId == 0) {
                        // create sku
                        $skuInfo = ProductSku::createProductSku($skuData);
                        $skuUpdateFlag = FALSE;
                    } else {
                        // update sku
                        ProductSku::where('id', $skuUpdateId)->update($skuData);
                        $skuUpdateFlag = TRUE;
                        $exceptProductSkuIds[] = $skuUpdateId;
                    }

                    /*
                      |--------------------------------------------------------------------------------------------------------------------------------------------
                      | START: Variant attributes combination - SKU wise attributes for loop - iterate each row and insert/update in product_attributes table
                      |--------------------------------------------------------------------------------------------------------------------------------------------
                     */
                    // EDIT case: fetch all product attribute ids
                    $editProductAllAttributeIds = [];
                    $exceptProductAttributeIds = [];
                    if ($skuUpdateFlag) {
                        $editProductAllAttributeIds = ProductAttribute::where(['product_id' => $id, 'product_sku_id' => $skuUpdateId])->select(DB::raw("GROUP_CONCAT(attribute_id SEPARATOR ',') as `product_attribute_ids` "))->first();
                        $editProductAllAttributeIds = (isset($editProductAllAttributeIds['product_attribute_ids']) && $editProductAllAttributeIds['product_attribute_ids'] != "") ? explode(",", $editProductAllAttributeIds['product_attribute_ids']) : [];
                    }

                    foreach ($attributes AS $attributeId => $attributeValueId) {

                        $tempCheck = isset($validationSku[$i]) ? $validationSku[$i] . $attributeId . $attributeValueId : $attributeId . $attributeValueId;

                        if ($i > 0 && in_array($tempCheck, $validationSku)) {
                            $skuUniqueCombinationFlag = TRUE;
                            break;
                        } else {
                            $validationSku[$i] = $tempCheck;
                        }

                        // CHECK VALIDATION: allow only unique product attribute combination per row(SKU)
                        if ($skuUniqueCombinationFlag) {
                            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => "Please provide unique attribute combination per each SKU row while creating SKUs for product!!"]]);
                            exit;
                        }

                        // EDIT case: sku wise attribute update
                        if (!empty($editProductAllAttributeIds) && in_array($attributeId, $editProductAllAttributeIds)) {
                            // update sku wise all attributes
                            $skuAttributeData = [
                                'attribute_value_id' => $attributeValueId,
                                'attribute_set_id' => $attributeSetId[$attributeId]
                            ];
                            ProductAttribute::where(['product_id' => $id, 'product_sku_id' => $skuUpdateId, 'attribute_id' => $attributeId])->update($skuAttributeData);
                            $exceptProductAttributeIds[] = $attributeId;
                        } else {
                            // create sku wise all attributes
                            $skuAttributeData = [
                                'attribute_id' => $attributeId,
                                'attribute_value_id' => $attributeValueId,
                                'product_sku_id' => (!$skuUpdateFlag) ? $skuInfo->id : $skuUpdateId,
                                'product_id' => $id,
                                'attribute_set_id' => $attributeSetId[$attributeId]
                            ];
                            ProductAttribute::createProductAttribute($skuAttributeData);
                        }
                    }

                    // Find differ ids by comparing all product sku wise attribute ids with update sku wise attribute ids and then REMOVE all old entries(deleted) from product_attributes table
                    if (!empty($editProductAllAttributeIds)) {
                        $editProductAllAttributeIds = collect($editProductAllAttributeIds);
                        $diffAttributeIdsToDelete = $editProductAllAttributeIds->diff($exceptProductAttributeIds);
                        $diffAttributeIdsToDelete = $diffAttributeIdsToDelete->all();
                        if (!empty($diffAttributeIdsToDelete)) {
                            // delete from product_attributes table
                            ProductAttribute::where(['product_id' => $id, 'product_sku_id' => $skuUpdateId])->whereIn('attribute_id', $diffAttributeIdsToDelete)->delete();
                        }
                    }
                    $i++;
                }

                // Find differ ids by comparing all product sku ids with update sku ids and then REMOVE all old entries(deleted) from product_skus, product_attributes table
                if (!empty($editProductAllSkuIds)) {
                    $editProductAllSkuIds = collect($editProductAllSkuIds);
                    $diffSkuIdsToDelete = $editProductAllSkuIds->diff($exceptProductSkuIds);
                    $diffSkuIdsToDelete = $diffSkuIdsToDelete->all();
                    if (!empty($diffSkuIdsToDelete)) {
                        // delete from product_skus table
                        ProductSku::whereIn('id', $diffSkuIdsToDelete)->delete();
                        // delete from product_attributes table
                        ProductAttribute::whereIn('product_sku_id', $diffSkuIdsToDelete)->delete();
                    }
                }
            }else{
                // check record exist in product_skus table
                $getProductSkuDetils = ProductSku::getProductSkuDetails($id);
                
                if($getProductSkuDetils)
                {   
                    ProductSku::where(['product_id' => $id])->delete();
                    //ProductSku::findOrFail(['product_id' => $id])->delete();
                    ProductAttribute::where('product_sku_id','!=',0)->where('product_sku_id','!=',NULL)->delete();
                    //ProductAttribute::where('product_sku_id','!=',0)->where('product_sku_id','!=',NULL)->findOrFail(['product_id' => $id])->delete();
                }

                //dd($getProductSkuDetils);
            }

            /*
              |---------------------------------------------------------------------------------------------
              | START: Default Sku and quantity field entry in product_skus table for variation_allowed = No
              |---------------------------------------------------------------------------------------------
             */
              //dd(decrypt($skus[0]['product_sku_id']));
            if(!empty($skus))
            {
                if(isset($skus[0]['product_sku_id']))
                {   
                    $sku_id = decrypt($skus[0]['product_sku_id']);
                    if($sku_id == 0)
                    {
                       $skus = array(); 
                    }
                }
            }
            
            if (empty($skus) && $request->input('variation_allowed') == 'No') {
                
                ProductSku::createProductSku([
                    'image' => "",
                    'quantity' => $request->input('quantity_no'),
                    'is_default' => 'Yes',
                    'additional_price' => 0,
                    //'final_price' => 0,
                    'final_price' => $request->input('product_listing_price'),
                    'available_in_bulk' => 'No',
                    'product_id' => $id,
                    'sku' => $request->input('sku_prefix_no'),
                ]);
            }
            
            /*
              |---------------------------------------------------------------------------------------
              | START: NON-VARIANT ATTRIBUTE (insert/update records)
              |---------------------------------------------------------------------------------------
             */
            $nonvariantAttributes = $request->input("nonvariant_attributes");
            $nonvariantAttributeSetId = $request->input("nonvariant_attribute_set_id");

            if (!empty($nonvariantAttributes)) {
                $editNonVariantAttributeIds = ProductAttribute::where(['product_id' => $id, 'product_sku_id' => 0])->select(DB::raw("GROUP_CONCAT(attribute_id SEPARATOR ',') as `product_nonvariant_attribute_ids` "))->first();
                $editNonVariantAttributeIds = (isset($editNonVariantAttributeIds['product_nonvariant_attribute_ids']) && $editNonVariantAttributeIds['product_nonvariant_attribute_ids'] != "") ? explode(",", $editNonVariantAttributeIds['product_nonvariant_attribute_ids']) : [];

                foreach ($nonvariantAttributes AS $nonVarinatAttributeId => $nonVariantAttributeValue) {
                    if (!empty($editNonVariantAttributeIds) && in_array($nonVarinatAttributeId, $editNonVariantAttributeIds)) {
                        $nonVariantAttributeData = [
                            'attribute_value' => $nonVariantAttributeValue,
                            'attribute_set_id' => $nonvariantAttributeSetId[$nonVarinatAttributeId]
                        ];
                        ProductAttribute::where(['product_id' => $id, 'product_sku_id' => 0, 'attribute_id' => $nonVarinatAttributeId])->update($nonVariantAttributeData);
                    } else {
                        $nonVariantAttributeData = [
                            'attribute_id' => $nonVarinatAttributeId,
                            'attribute_value' => $nonVariantAttributeValue,
                            'product_sku_id' => 0,
                            'product_id' => $id,
                            'attribute_set_id' => $nonvariantAttributeSetId[$nonVarinatAttributeId]
                        ];
                        ProductAttribute::createProductAttribute($nonVariantAttributeData);
                    }
                }
            }

            /*
              |---------------------------------------------------------------------------------------
              | START: Update some fields in products table
              |---------------------------------------------------------------------------------------
             */
            $updateProductData = [
                'product_listing_price' => $request->input('product_listing_price'),
                'variation_allowed' => $request->input('variation_allowed'),
                'sku_prefix' => $request->input('sku_prefix_no'),
            ];
            Product::updateProduct(['id' => $id], $updateProductData);

            /*
              |---------------------------------------------------------------------------------------
              | START: Create product shipping detail
              |---------------------------------------------------------------------------------------
             */
            $shippingData = $request->only(['parcel_dimension_length', 'parcel_dimension_width', 'parcel_dimension_height', 'length_class', 'parcel_weight', 'weight_class', 'shipping_type']);
            ProductShippingDetail::createProductShippingDetail($shippingData + ['product_id' => $id]);


            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();
            
            if ($request->input('submit_type') == 'Next') {
                \Flash::success(trans('message.product.update_success'));
                return response()->json(['status' => 'success', 'redirectUrl' => route('createProduct', ['step_four', encrypt($id)])]);
            } else {
                \Flash::success(trans('message.product.draft_success'));
                return response()->json(['status' => 'success', 'redirectUrl' => route("listingProduct")]);
            }
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    private function storeStepFour(Request $request, $step, $productId) {
        $id = ($productId == '0') ? $productId : decrypt($productId);
        $updateFlag = ($id == 0) ? FALSE : TRUE;

        // Start transaction!
        DB::beginTransaction();
        try {
            // update some fields in products table
            $productFindObj = Product::find($id);
            $productFindObj->update([
                'related_product_ids' => !empty($request->input('related_product_ids')) ? implode(',', $request->input('related_product_ids')) : '',
                'promotion_applicable' => $request->input('promotion_applicable'),
                'status' => ($request->input('submit_type') == 'Save as draft') ? 'Draft' : 'Active'
            ]);

            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();
            \Flash::success(trans('message.product.update_success'));
            return response()->json(['status' => 'success', 'redirectUrl' => route("listingProduct")]);
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    public function uploadImage(Request $request, $productId, $type='') {
        $id = decrypt($productId);
        //dd($request->file());
        if(!empty($type))
        {
            $data = uploadImage($request->file(), true, '', '', 'products');
        }else{
            //dd($request->file());
            $data = uploadImage($request->file(), true, '', '', 'products');
            //dd($data);
        }
        
        $product = $this->product->find($id);
        $product->Files()->create($data);
        return $data;
        /* $files = $request->file();
          foreach ($files as $image) {
          //$image = $request->file('image');
          $imageFileName = time() . '.' . $image->getClientOriginalExtension();
          $s3 = \Storage::disk('s3');
          $filePath = '/products/' . $imageFileName;
          $s3->put($filePath, file_get_contents($image), 'public');
        } */
    }

    public function getComparedProduct(Request $request, $category_slug = '', $product_id = '', $type = '') {

        if (isset($category_slug) && !empty($category_slug)) {
            $product = '';
            if (isset($product_id) && !empty($product_id)) {
                $product_id = decrypt($product_id);
                if ($product_id != 0) {
                    $product = $this->product->findorfail($product_id);
                }
            }
            //fetch category by slug
            $categories = $this->category->where('category_slug', $category_slug)->where('id', '!=', 0)->where('status', '=', "Active")->first();
            

            //get and set cookie
            $value = $request->cookie('compare');            
            
            

                        if(isset($type) && !empty($type) && $type=='close'){
                            unset($value[$categories->id][$product->id]);
                        }
                        else if(isset($type) && !empty($type) && $type=='clearall'){                            
                            unset($value[$categories->id]);
                        }
                        else{
                            $value[$categories->id][$product->id]=$product->id;
                        }
                        
                        
                
            

            if (isset($type) && !empty($type) && $type == 'close') {
                unset($value[$categories->id][$product->id]);
            } else if (isset($type) && !empty($type) && $type == 'clearall') {
                unset($value[$categories->id]);
            } else {
                if(isset($value[$categories->id]) && !empty($value[$categories->id])){
                            
                             if(count($value[$categories->id])>4){
                                 ?>
                                <script>toastr.error('You can not add more than 4 products');</script>
                             <?php
                             unset($value[$categories->id][$product->id]);
                             
                            }                             
                            else{
                                $value[$categories->id][$product->id] = $product->id;
                            }                            
                }
                
            }

            $cookie = cookie('compare', $value, 8640000, '/', '');
            

                        //fetch product by category id stored in 
                        $category_products= collect([]);
                        if(isset($value[$categories->id])){
                            $category_products=$this->product->whereIn('id',$value[$categories->id])->get();
                        }        
                        
                        return response()->view('front.products.getComparedProduct',compact('category_products','category_slug'))->withCookie($cookie);
        }
        else{            
                return $this->compare($request,'','','','');
        }
    }

    public function compare(Request $request, $category_slug = '', $arg1 = '', $arg2 = '', $arg3 = '', $arg4 = '') {
        if (
                (isset($arg1) && !empty($arg1)) ||
                (isset($arg2) && !empty($arg2)) ||
                (isset($arg3) && !empty($arg3)) ||
                (isset($arg4) && !empty($arg4))
          )
          {            
            //dd([$arg1,$arg2,$arg3,$arg4]);
            $products = $this->product
                            ->with([ 'productSkus' => function($query) {
                                    $query->where('is_default', 'Yes');
                                }])
                               //->with('productSkus.productVariantAttribute')
                               ->with('productSkus.productVariantAttribute.attribute.AttributeSet')
                               //->with('productSkus.productVariantAttribute.attribute')
                               ->with('productSkus.productVariantAttribute.attributeValue')                            
                               ->with('productNonVariantAttribute.attribute.AttributeSet')
                               ->whereIn('product_slug',[$arg1,$arg2,$arg3,$arg4])->get();
                $variantAttributes=[];
                $nonVariantAttributes=[];
                
                //echo '<pre>';print_r($products);die;
                foreach($products as $productindex=>$product){
                    foreach($product->productSkus as $productSkuIndex=>$productSku){
                        foreach($productSku->productVariantAttribute as $productVariantAttributeIndex=>$productVariantAttribute){
                            //$products[$productindex]->productSkus[$productSkuIndex]->productVariantAttribute[$productVariantAttributeIndex]->AttributeSet=$productVariantAttribute->attribute;
                            //dd($productVariantAttribute->attribute->AttributeSet);                                 
                            //$variantAttribute[$productVariantAttribute->attribute->AttributeSet->attribute_set_name][$productVariantAttribute->attribute->attribute_name]=$productVariantAttribute->attribute;                            
                            if(isset($productVariantAttribute->attributeValue->attribute_values) &&!empty(isset($productVariantAttribute->attributeValue->attribute_values) )){
                                $variantAttributes[$productVariantAttribute->attribute->AttributeSet->attribute_set_name][$productVariantAttribute->attribute->attribute_name][$product->id]=$productVariantAttribute->attributeValue->attribute_values;
                            }
                            
                            
                            //$productVariantAttribute->attributeValue->attribute_values
                            //dd($products);
                            //dd($productVariantAttribute->attribute->AttributeSet);
                            //dd($productVariantAttribute->attribute);
                        }                        
                    }
                }
            


            foreach ($products as $productindex => $product) {
                foreach ($product->productNonVariantAttribute as $productNonVariantAttributeIndex => $productNonVariantAttribute) {
                    if (is_integer((int) $productNonVariantAttribute->attribute_value) === true) {
                        $att_val = \App\Models\AttributeValues::where('id', $productNonVariantAttribute->attribute_value)->first();
                        if (count($att_val) > 0) {
                            $nonVariantAttributes[$productNonVariantAttribute->attribute->AttributeSet->attribute_set_name][$productNonVariantAttribute->attribute->attribute_name][$product->id] = \App\Models\AttributeValues::where('id', $productNonVariantAttribute->attribute_value)->first()->attribute_values;
                        } else {
                            $nonVariantAttributes[$productNonVariantAttribute->attribute->AttributeSet->attribute_set_name][$productNonVariantAttribute->attribute->attribute_name][$product->id] = $productNonVariantAttribute->attribute_value;
                        }
                    } else {
                        $nonVariantAttributes[$productNonVariantAttribute->attribute->AttributeSet->attribute_set_name][$productNonVariantAttribute->attribute->attribute_name][$product->id] = $productNonVariantAttribute->attribute_value;
                    }
                }
            }


            //dd($variantAttributes);
            //dd($nonVariantAttributes);
            //dd($products[0]->productSkus[0]->productVariantAttribute[0]);
            
            return view('front.products.compareProductListing', compact('products', 'variantAttributes', 'nonVariantAttributes', 'category_slug'));
        } else {
            $cookieValue = $request->cookie('compare');

            $categories = $this->category->whereIn('id', array_keys($cookieValue))->get();

            foreach ($categories as $index => $category) {
                $categories[$index]->product = $this->product
                        ->with([ 'productSkus' => function($query) {
                                $query->where('is_default', 'Yes');
                            }])
                        ->whereIn('id', $cookieValue[$category->id])
                        ->get();
            }

            return view('front.products.compareCategoryListing', compact('categories'));
        }
    }

    public function ClearComparedCategory(Request $request, $category_slug, $product_id = '') {
        $categories = $this->category->where('category_slug', $category_slug)->where('id', '!=', 0)->where('status', '=', "Active")->first();

        $value = $request->cookie('compare');
        if (isset($product_id) && !empty($product_id)) {
            $product = $this->product->findorfail(decrypt($product_id));
            unset($value[$categories->id][$product->id]);
        } else {
            unset($value[$categories->id]);
        }

        if (isset($value[$categories->id]) && count($value[$categories->id]) < 1) {
            unset($value[$categories->id]);
        }

        $cookie = cookie('compare', $value, 8640000, '/', '');
        return redirect()->route('compare')->withCookie($cookie);
    }

    public function removeProduct(Request $request, $category_slug, $product_id = '') {

        $categories = $this->category->where('category_slug', $category_slug)->where('id', '!=', 0)->where('status', '=', "Active")->first();

        $value = $request->cookie('compare');

        $product = $this->product->findorfail(decrypt($product_id));
        //dd($value[$categories->id]);
        if (isset($product_id) && !empty($product_id)) {
            $product = $this->product->findorfail(decrypt($product_id));
            unset($value[$categories->id][$product->id]);
        } else {
            unset($value[$categories->id]);
        }

        if (isset($value[$categories->id]) && count($value[$categories->id]) < 1) {
            unset($value[$categories->id]);
        
}
        $cookie = cookie('compare', $value, 8640000, '/', '');

        if (isset($value[$categories->id])) {
            $products = $this->product->whereIn('id', $value[$categories->id])->get()->toArray();
            $slugs = array_column($products, 'product_slug');
            return redirect(URL('/compare/' . $category_slug . '/' . implode('/', $slugs)))->withCookie($cookie);
        } else {
            return redirect()->route('compare')->withCookie($cookie);
        }
    }
    
    public function getMakeAnOffer($productId,$sellerId){
        $offerData      =  \App\Models\Offers::with('offerDetails')->where('buyer_id',\Auth::user()->id)->where('seller_id',  decrypt($sellerId))->where('product_id',  decrypt($productId))->first();
        
        $loginSellerUserData  = array();
        $loginSellerUserData['login_image']     = '';
        $loginSellerUserData['seller_image']    = '';
        
        $loginSellerUserData['offer_url']           =  URL('/saveAnOffer/'.$productId.'/'.$sellerId);
        $loginSellerUserData['retract_offer_url']   =  URL('/retractOffer/'.$productId.'/'.$sellerId);

        if(!empty(\Auth::user()->image))
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/".\Auth::user()->id.'/'.\Auth::user()->image );
        }else if(\Auth::user()->gender == 'Male')
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/user-male.png" );
        }else if(\Auth::user()->gender == 'Female')
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/user-female.png" );
        }

        $sellerData = \App\Models\User::checkUserData(['id'=>decrypt($sellerId)]);
        $sellerData['id'] = $sellerId;
        if(!empty($sellerData['image']))
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/".decrypt($sellerId).'/'.$sellerData['image'] );
        }else if(\Auth::user()->gender == 'Male')
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/user-male.png" );
        }else if(\Auth::user()->gender == 'Female')
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/user-female.png" );
        }

        /* Retract offer button logic */

        $time           = '';
        $counterOffer   = '';
        if(!empty($offerData))
        {
            $endDetails         = end($offerData->offerDetails);
            $endId              = end($endDetails);
            $counterOffer       = $endId->counter_offer;
            $lastOfferTime      = $endId->created_at;
            $currentDateTime    = date('Y-m-d H:i:s');
            $diff               = strtotime($currentDateTime) - strtotime($lastOfferTime);
            $time               = date('H:i:s', $diff);
        }
        
        $loginSellerUserData['show_retract_button'] = 'Yes';

        if(!empty($time) && (strtotime($time) > strtotime('00:15:00')))
        {
            $loginSellerUserData['show_retract_button'] = 'No';
        }

        return view('front.products.partials.make_an_offer', compact('categories','productId','sellerData','offerData','loginSellerUserData','counterOffer'));
    }

    public function saveAnOffer(\App\Http\Requests\offerRequest $request,$productId,$sellerId){
        
        try{
                $inputData = $request->input();
                
                $data=$request->except('_token');
                $sellerId=  decrypt($sellerId);
                
                $is_offer =  \App\Models\Offers::where('buyer_id',\Auth::user()->id)
                                                ->where('product_id',decrypt($productId))
                                                ->where('seller_id',  $sellerId)
                                                ->first();

                $where      = ['id' => decrypt($productId)];
                $product    = Product::getProductDetails($where);
                
                $offer_status = 'Submitted';
                if( ($inputData['amount'] < $product->min_reserved_price) || ( $inputData['offer_quantity'] > $product->max_order_quantity ))
                {
                    $offer_status = 'Rejected';
                }

                if( (isset($is_offer) && empty($is_offer)) || is_null($is_offer) ){
                    
                    $offer=array();
                    $offer['offer_thread_reference_no'] =   strtotime(\Carbon\Carbon::now()->toDateTimeString());
                    $offer['status']                    =   'open';
                    $offer['buyer_id']                  =   \Auth::user()->id;
                    $offer['seller_id']                 =   $sellerId;
                    $offer['product_id']                =   decrypt($productId);
                    $offer['remaining_offer']           =   config('project.offer_available_limit') - 1;
                    
                    $offerData              =  \App\Models\Offers::create($offer);
                    $data['offers_id']      =   $offerData->id;
                    $data['user_type']      =  'buyer';
                    $data['offer_status']   =  $offer_status;
                    \App\Models\OfferDetails::create($data);
                }
                else{

                    $offer=array();
                    $where = ['id'=>$is_offer->id ];
                    $offer['remaining_offer'] =   intval($is_offer->remaining_offer) - 1;
                    \App\Models\Offers::updateOffer($where,$offer);
                    
                    $data['offers_id']=$is_offer->id;
                    $data['user_type']='buyer';
                    \App\Models\OfferDetails::create($data);
                }
                
                /* Send Offer email To Seller */
                $sellerData = \App\Models\User::checkUserData(['id'=>$sellerId]);
                $toEmail    = 'bahadur.deol@indianic.com';//$sellerData['email'];
                $toName     = $sellerData['first_name'].' '.$sellerData['last_name'];
                $fromName   = 'InSpree';
                $fromEmail  = 'offer@inspree.com';
                $subject    = 'InSpree New Product Offer Generated';
                $body       = 'InSpree New Product Offer Generated.<br/><br/> Thanks,<br/>InSpree Team';

                sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body);


                \Flash::success("Offer Sent Succesfully!!");
                $return_data['status']          = 'success';
                $return_data['offer_status']    = $offer_status;
                $return_data['amount']          = $inputData['amount'];
                $return_data['created']         = date('M d, Y');
                $return_data['remaining']       = $offer['remaining_offer'];

                return response()->json($return_data);
        }
        catch(\Exception $e){
            \Flash::success("There is some error");
            //echo $e->getMessage();die;
        }
    }

    public function changeProductStatus(Request $request,$type = 'Draft')
    {   
        $updateProductId = $request['product_select_multiple'];
        if(!empty($updateProductId))
        {
            $updateProductData = [
                'status' => $type,
            ];
            Product::updateProduct($updateProductId, $updateProductData,'update');
            \Flash::success('Product status update successfully');
            return response()->json(['status' => 'success']);
        }else{
            \Flash::error(trans('message.failure'));
            return response()->json(['status' => 'error']);
        }
        exit;
    }

    public function retractOffer(Request $request,$productId,$sellerId)
    {
        $return_data['status']    = 'error';
        
        try{    
                $product_id = decrypt($productId);
                $seller_id  =  decrypt($sellerId);

                $buyerId    = \Auth::user()->id;

                $where      = ['product_id' => $product_id,'buyer_id' => $buyerId,'seller_id' => $seller_id];

                $offerId    = \App\Models\Offers::getOfferById($where);
                $endDetails = end($offerId->offerDetails);
                $endId      = end($endDetails);
                $lastOfferTime = $endId->created_at;
                
                $currentDateTime = date('Y-m-d H:i:s');

                $diff   = strtotime($currentDateTime) - strtotime($lastOfferTime);
                $t      = date('H:i:s', $diff);
                if(strtotime($t) > strtotime('00:15:00'))
                {
                               
                }else{
                    if(!empty($offerId))
                    {   
                        $where                  = ['id' => $endId->id];
                        $data['offer_status']   = 'Cancelled';
                        $retractLatestOffer     = \App\Models\OfferDetails::retractOffer($where,$data);
                    }else
                    {
                        \Flash::success("There is some error");
                    }
                    
                    \Flash::success("Your offer has been canceled successfully!");
                    $return_data['status']      = 'success';
                }
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
        }

        return response()->json($return_data);
        exit;
    }
    public function sellBidsListing(){
        //dd(ProductAuction::getProductBids());
        $productStatus = getMasterEntityOptions('product_status');
        $productMOS = getMasterEntityOptions('product_mode_of_selling');

        $categories = $this->category->getNestedData();
        return view('front.sell.product.bidsProductsListing', compact('productStatus', 'productMOS', 'categories'));
        
    }
    public function getBids($productId,$sellerId){            
        $productId=(int)decrypt($productId);
        $auctionBids=\App\Models\Auction::where('productId',$productId)->orderBy('createdAt','desc')->get();
        $product=$this->product->with('auction')->where('id',  $productId)->has('auction')->first();  
        
        if(isset($product) && !empty($product)){
            return view('front.products.partials.place_bid',compact('auctionBids','product'))->render();
        }
        else{
            return 'No Auction';
        }
        
    }
    
    public function bidsProductDatatableList(Request $request){
                    //$search = $request->input('search');
                    $IsSearch = '';

                    if(!empty($search['value']))
                    {
                        $IsSearch = $search['value'];
                    }
                    $bids=\App\Models\Auction::select('*')->where('sellerId',\Auth::id())->orderBy('createdAt')->get();
                    
                    $products = Product::getAuctionProducts($IsSearch,array(),\Auth::id());

                    
                    $currentTime=\Carbon\Carbon::now();
                    $currentTime=date('Y/m/d H:i:s',  strtotime($currentTime));
                    return Datatables::of($products)
//                                    ->addColumn('action', function ($product) {
//                                        $action = '';
//                                        $action .= '<a href="' . route('createProduct', ['step_one', encrypt($product->id)]) . '" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
//                                        //$action .= ($hasPermission['delete'] && $product->status != "Active") ? '&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-danger btn-xs fa fa-trash-o deleteProduct" data-toggle="modal" data-placement="top" title="Delete" data-product_delete_remote="">D</a>' : '';
//                                        return $action;
//                                    })
                                    ->addColumn('image', function ($product)  {
                                        $image = '';
                                        $image .= '<img src="'.getImageFullPath(@$product->files[0]->path,'products','thumbnail').'"/>';
                                        return $image;
                                    })
                                    ->addColumn('bidders', function ($product) use($bids) {
                                        return  '<a href="'.URL('/sell/bids/detail/'.$product->id).'" style="color:blue">'.$bids->where('productId',$product->id)->groupBy('user_id')->count().'</a>';  
                                    })
                                    ->addColumn('bidWonBy', function ($product) use($bids,$currentTime) {
                                        $product->auction->end_datetime=date('Y/m/d H:i:s',  strtotime($product->auction->end_datetime));
                                        if($currentTime <= $product->auction->end_datetime){
                                            return '<a style="color:blue" href="'.URL('/product/'.$product->product_slug).'">Bid Open</a>';
                                        }        
                                        $bid_username=@$bids->where('productId',$product->id)->sortByDesc('bid_amount')->first()->username;
                                        if(isset($bid_username) && !empty($bid_username)){
                                            return  $bid_username;
                                        }
                                        return  'Not any bid yet';
                                        
                                    })
                                    ->addColumn('bidWonAmount', function ($product) use($bids,$currentTime) {
                                        $bid_amount=@$bids->where('productId',$product->id)->sortByDesc('bid_amount')->first()->bid_amount;
                                        if(isset($bid_amount) && !empty($bid_amount)){
                                            return  '$'.$bid_amount;
                                        }
                                        return  'Not any bid yet';
                                    })
                                    ->addColumn('product_sold', function ($product) use($bids,$currentTime) {
                                        return  'yes';
                                    })
                                   ->editColumn('expiration', function($product) use($currentTime){
                                        $product->auction->end_datetime=date('Y/m/d H:i:s',  strtotime($product->auction->end_datetime));
                                        if($currentTime <= $product->auction->end_datetime){
                                            return date('Y/m/d H:i:s',  strtotime($product->auction->end_datetime));
                                        }
                                        return "Auction ended";
                                        
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
     public function buyBidsListing(){
        //dd(ProductAuction::getProductBids());
        
        //$productStatus = getMasterEntityOptions('product_status');
        //$productMOS = getMasterEntityOptions('product_mode_of_selling');

        //$categories = $this->category->getNestedData();
         //die;
        return view('front.buy.product.bidsProductsListing');
        
    }
    public function buyBidsProductDatatableList(Request $request){
                    $data = $request->all();

                    $bids=\App\Models\Auction::select('productId','user_id','createdAt','datetime')->where('user_id',\Auth::id())->groupBy('productId');
                            
                    
                    
                    
                    /*$condition1= isset($data['period']) && !empty($data['period']);
                    $condition2= isset($data['status']) && !empty($data['status']);
                    if( $condition1 )
                    {
                        if($data['period']=='24'){
                            //echo date('d/m/Y HH',strtotime("-1 days"));die;
                            $bids=$bids->where('datetime','>=',date('d/m/Y HH',strtotime("-1 days")));
                            dd($bids->get());
                        }
//                        elseif($data['period']=='week'){}
//                        elseif($data['period']=='month'){}
//                        elseif($data['period']=='year'){}
                    } */   
                    
                    
                    $bids=$bids->get()->toArray();
                    
                    $productIds=array_column($bids,'productId');
                    
                    $products = Product::getAuctionProducts($data,$productIds);
                    
                    $bids=\App\Models\Auction::select('productId','user_id','bid_amount')->where('user_id',\Auth::id())->orderBy('createdAt')->get();
                    
                    $currentTime=\Carbon\Carbon::now();
                    $currentUserId=  \Auth::id();
                    GLOBAL $status;
                    GLOBAL $max_bid_amount;
                    return Datatables::of($products)
                                    
                                    ->addColumn('name', function ($product) use($bids) {
                                        $name='';
                                        $name .= '<a style="color:#30588c" href="'.URL('/product/'.$product->product_slug).'">'.$product->name.'</a>';
                                        $name .= '<br/>seller : '.$product->user->username;
                                        GLOBAL $max_bid_amount;
                                        $max_bid_amount=@$bids->where('productId',(int)$product->id)->sortByDesc('bid_amount')->first()->bid_amount;
                                        $name .= '<br/>Your max bid: $'.@$bids->where('productId',(int)$product->id)->sortByDesc('bid_amount')->first()->bid_amount;
                                        return $name;
                                    })                                    
                                    ->addColumn('image', function ($product)  {
                                        $image = '';
                                        $image .= '<img src="'.getImageFullPath(@$product->files[0]->path,'products','thumbnail').'"/>';
                                        return $image;
                                    })
                                    ->addColumn('bids', function ($product) use($bids) {
                                        return '<a style="color:blue" href='.URL('/buy/bids/'.$product->id.'/history').'>'.$bids->where('productId',$product->id)->count().'</a>';
                                    })
                                    ->addColumn('status', function ($product) use($bids,$currentTime,$currentUserId,$status) {
                                        
                                        //$status='';
                                        GLOBAL $status;
                                        if($product->auction->auction_status=='close'){
                                                if($product->auction->auction_winner_id==$currentUserId){
                                                    $status="Won";
                                                }
                                                else{
                                                    $status="Lose";
                                                }
                                        }
                                        else{
                                            $top_bid_amount=$bids->where('productId',$product->id)->sortbyDesc('bid_amount')->first()->bid_amount;
                                            $max_product_price=$product->auction->max_product_price;
                                            if(
                                                $product->auction->mode=='By price' && 
                                                $product->auction->max_product_price!=0 && 
                                                $top_bid_amount>$max_product_price
                                                    ||
                                                ($product->auction->mode=='By time' && $currentTime >= $product->auction->end_datetime)
                                            ){
                                                $winnerId=\App\Models\Auction::where('productId',(int)$product->id)->orderBy('createdAt','desc')->first()->user_id;
                                                $product->auction->auction_status='close';
                                                $product->auction->auction_winner_id=$winnerId;
                                                $product->auction->update();                                                 

                                                if($product->auction->auction_winner_id==$currentUserId){
                                                    $status="Won";
                                                }
                                                else{
                                                    $status="Lose";
                                                }                                                
                                            }
                                            else{
                                                $latestBidUserId=\App\Models\Auction::where('productId',(int)$product->id)->orderBy('createdAt','desc')->first()->user_id;
                                                if($latestBidUserId!=$currentUserId){
                                                    $status='Out Bid';
                                                }
                                            }
                                            
                                            
                                        }
                                        return $status;
                                    })                                    
                                    ->addColumn('action', function ($product) use ($bids,$currentUserId,$currentTime,$status) {
                                        GLOBAL $status;
                                        
                                        $action = '';
                                        
                                        if($product->mode_of_selling == "Buy it now and Auction"){                                            
                                            //$action .='<a href="'.URL('/product/'.$product->product_slug).'" onclick="addtocartSku(81,1)" style="color:#30588c">Buy It Now</a><br/>';
                                            $action .='<a href="javascript:redirectToCheckout()" onclick="addtocartSku('.$product->productSkus->first()->id.',1)" class="buy_it_now_link" style="color:#30588c">Buy It Now</a><br/>';
                                        }
                                        if($status=="Out Bid"){
                                            $action .= '<a href="'.URL('/product/'.$product->product_slug).'" style="color:#30588c">Increase Max Bid</a><br/>';
                                        }
                                        
                                        $lastBid=$bids->where('productId',$product->id)->sortByDesc('createdAt')->first();
                                        
                                        //$action.=$lastBid;
                                        //$cond1= ($currentTime >= $product->auction->end_datetime);
                                        //$cond2 = ($lastBid->user_id==$currentUserId);
                                        
                                        //if($cond1 && $cond2){
                                        if($status=="Won"){
                                            $action.='<a href="#" style="color:#30588c">Pay Now</a>';
                                        }
                                        return $action;
                                        
                                    })                                    
                                    ->editColumn('base_price', function ($product) use ($bids)  {
                                        GLOBAL $max_bid_amount;
                                        if(isset($max_bid_amount) && !empty($max_bid_amount)){
                                            $base_price=$max_bid_amount." + 50 Shipping";
                                        }
                                        else{
                                            $base_price=$product->auction->min_reserved_price." + 50 Shipping";
                                        }
                                        
                                        return $base_price;
                                    })
                                    //->editColumn('base_price', '{!! $base_price." + 50 Shipping" !!}')
                                    ->editColumn('expiration', function($product){
                                        return date('Y/m/d H:i:s',  strtotime($product->auction->end_datetime));
                                    })                                   
                                    ->make(true);

    }
    
    public function bidsHistory($productId){
        $auctionBids=\App\Models\Auction::getAuctionBids($productId);
        $product = $this->product->with('auction')->where('id',$productId)->has('auction')->first();                
        return view('front.products.partials.bid_history',compact('auctionBids','product'))->render();
    }
    public function sellBidsDetail($productId){
        $auctionBids=\App\Models\Auction::where('productId','=', (int) $productId)->orderBy('createdAt','desc')->get();        
        $product = $this->product->with('auction')->where('id',$productId)->has('auction')->first();
        return view('front.products.partials.sellBidsDetail',compact('auctionBids','product'))->render();
    }
    public function sellerstore(Request $request,$manufacturer='',$page=''){
            $data=$request->all();
            $manufacturer=urldecode($manufacturer);
            if(isset($data['categories']) && !empty($data['categories'])){
                $data['categories']=explode('_',$data['categories']);
            }
            else{
                $data['categories']=array();
            }
            
            
            
            $products = $this->product->getProductByManufacturer($manufacturer,($page - 1) * config('project.category_product_limit'), config('project.category_product_limit'),$data);
            $sellerDetail=\App\Models\SellerDetail::with('seller')->where('user_id',$products->first()->user_id)->first();
            
            
            if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.seller_store._partials.decendentsProductListing', compact( 'products'))->render(),                                    
                            'nextPage' => $page + 1,
                            'previousPage' => $page - 1,
                ]);
            }
            
            
            $product_categories = $this->product->getProductByManufacturer($manufacturer);
            $products_count=$product_categories->count();
            if ($products_count > config('project.category_product_limit')) {
                $pageData = $page * config('project.category_product_limit');
            } else {
                $pageData = $products_count;
            }       
            
            
            return view('front.seller_store.decendents_category', compact('products', 'page', 'pageData', 'products_count','product_categories','data','sellerDetail'));
        
       
            
    }
    public function occasionProductListing(Request $request,$occasion='',$page=''){
            
            $data=$request->all();            
            $occasion=urldecode($occasion);
            $occasionDetail=$this->occasions->getOccasionByName($occasion);
            $products=$this->product->getProductByOccasion(@$occasionDetail->id,@$data['q']);
            //$products = $this->product->getProductByManufacturer($manufacturer,($page - 1) * config('project.category_product_limit'), config('project.category_product_limit'),$data);
            
            
            
            if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.seller_store._partials.decendentsProductListing', compact( 'products'))->render(),                                    
                            'nextPage' => $page + 1,
                            'previousPage' => $page - 1,
                ]);
            }
            
            
            
            $products_count=0;
            $pageData=0;
            if(isset($products) && $products->count()>0){
                    $products_occasion=$this->product->getProductByOccasion($occasionDetail->id);                    
                    $products_count=$products_occasion->count();
                    if ($products_count > config('project.category_product_limit')) {
                        $pageData = $page * config('project.category_product_limit');
                    } else {
                        $pageData = $products_count;
                    }
            }
            

            //return view('front.occasions.occasionListing', compact('products', 'page', 'pageData', 'products_count','product_categories','data','sellerDetail'));
            
            return view('front.occasions.occasionListing', compact('products', 'page', 'pageData', 'products_count','product_categories','data','occasionDetail'));

    }

    public function removeImage(Request $request, $productId) {
        $id         = decrypt($productId);

        $data = $request->all();

        $moduleName = 'products';
        $path       = public_path() . '/images/' . $moduleName . '/';
        $old_image  = '';
        if(isset($data['filenamenew']) && !empty($data['filenamenew']))
        {
            $old_image = $data['filenamenew'];
        }

        if (isset($old_image) && !empty($old_image) && $data['type'] == 'delete') 
        {
            $main       = $path . '/main/' . $old_image;
            $small      = $path . '/small/' . $old_image;
            $thumbnail  = $path . '/thumbnail/' . $old_image;
            $video      = $path . '/video/' . $old_image;
            
            if (file_exists($main)) {
                unlink($main);
            }
            if (file_exists($small)) {
                unlink($small);
            }
            if (file_exists($thumbnail)) {
                unlink($thumbnail);
            }

            if (file_exists($video)) {
                unlink($video);
            }

            $where = ['path' => $old_image,'imageable_id' =>$id,'imageable_type'=>'App\Models\Product'];
            $removeFile = $this->Files->deleteProductfile($where);
            
            return response()->json(['status' => 'success', 'redirectUrl' => route('createProduct', ['step_two', encrypt($id)])]);
        }

        return response()->json(['status' => 'error', 'messages' => ['global_form_message' => 'There is some error, Please try again later']]);
    }



    public function  previewProduct() 
    {
                $classifiedController=new ClassifiedController();
		return $classifiedController->create('preview');
    }
}

