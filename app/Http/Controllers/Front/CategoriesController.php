<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AdvertisementCategory;
use App\Models\Product;
use App\Models\AttributeSetCategory;
use App\Models\ProductConditions;

class CategoriesController extends Controller {

    public $categories;
    public $productConditions;

    public function __construct() {
        $this->categories = new Category();
        $this->product = new Product();
        $this->attributeSetCategory = new AttributeSetCategory();
        $this->productConditions = new ProductConditions();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = $this->categories->getChildNode()[0]['children'];
        return view('front.categories.index', compact('categories'));
    }

    public function getCategoriesByCharacter($ch) {
        $categories = $this->categories->getCategoriesByCharacter($ch);
        $ch = strtoupper($ch);
        return view('front.categories.categoriesByCharacter', compact('categories', 'ch'));
    }

    public function categoriesListing(Request $request, $category_slug, $page = 1) {

        $requestdata=$request->all();
        $categories = $this->categories->getCategoriesBySlug($category_slug);
        if( !isset($categories) || empty($categories)){
            return view('errors.404');
        }
        $categoryIds = array_column($categories->getDescendantsAndSelf()->toArray(), 'id');
        //dd($categories);
//        echo '<pre>';print_r($_COOKIE);die;
        if (isset($_COOKIE['product_id']) && !empty($_COOKIE['product_id'])) {
            $recentViewProducts = $this->product->getRecentlyViewedProduct($_COOKIE['product_id']);
        }
        if (!$request->ajax()) {
            $breadcrumbs[] = array('text' => $categories->text, 'category_slug' => $categories->category_slug);
            $categorieIdsForAttribute[] = $categories->id;
            if ($categories->depth > 1) {
                $categories_levels = $categories->replicate();
                for ($i = $categories->depth; $i > 1; $i--) {
                    $categories_levels = $categories_levels->parent;
                    $breadcrumbs[] = array('text' => $categories_levels->text, 'category_slug' => $categories_levels->category_slug);
                    $categorieIdsForAttribute[] = $categories_levels->id;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
            }
            $attributeSetCategories = $this->attributeSetCategory->getProductAttributes($categorieIdsForAttribute);
        }
        //dd($breadcrumbs);
        //dd($categorieIdsForAttribute);

        /*
          DEPARTMENT(TOP LEVEL CATEGORY) CATEGORY LISTING
         */
        
        if ($categories->depth > 1) {            
            //dd($attributeSetCategories);
            $products = $this->product->getProductsByCategory($categoryIds,false, ($page - 1) * config('project.category_product_limit'), config('project.category_product_limit'),$requestdata);            
            $products = $products->shuffle();

            //echo '<pre>';print_r($products);die;
            $products_count = $this->product->getProductsByCategoryCount($categoryIds);
            if ($products_count > config('project.category_product_limit')) {
                $pageData = $page * config('project.category_product_limit');
            } else {
                $pageData = $products_count;
            }

            if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.categories._partials.decendentsProductListing', compact('categories', 'products', 'page', 'pageData', 'products_count'))->render(),
                            'nextPage' => $page + 1,
                            'previousPage' => $page - 1,
                ]);
            }
            $cookie = $request->cookie('compare');
            $category_products=[];
            if(isset($cookie[$categories->id])){
                $category_products=$this->product->whereIn('id',$cookie[$categories->id])->get();
            }
            return view('front.categories.decendents_category', compact('categories', 'breadcrumbs', 'products', 'page', 'pageData', 'products_count', 'attributeSetCategories','category_slug','category_products','requestdata'));
        }
        /*
          ELSE FOR SUB CATEGORY PRODUCT LISTING
         */ else {                         
            $products = $this->product->getProductsByCategory($categoryIds,false, ($page - 1) * config('project.department_product_limit'), config('project.department_product_limit'),$requestdata);
            //dd($products[0]->productSkusVariantAttribute[0]->productVariantAttribute);
            $products = $products->shuffle();
            $products_chunk = $products->chunk(9);

            if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.categories._partials.departmentProductListing', compact('products_chunk'))->render(),
                            'nextPage' => $page + 1,
                ]);
            }
            
            $cookie = $request->cookie('compare');
            $category_products=[];
            if(isset($cookie[$categories->id])){
                $category_products=$this->product->whereIn('id',$cookie[$categories->id])->get();
            }

            return view('front.categories.main_category', compact('categories', 'breadcrumbs', 'products_chunk','category_slug','attributeSetCategories','requestdata','category_products'));
        }
    }

    public function getdynamicchildajax(Request $request) {
        $dropdown = $dropdown2 = '';
        $input = $request->all();
        $response = ['category_dropdown' => '', 'product_conditions' => ''];
        if($input['category_id'] == 0){
            echo json_encode($response);exit;
        }
        
        $all_children = $this->categories->getChildNameid($input['category_id']);
        if (count($all_children) > 0) {
            $dropdown .= '<div class="form-group parent_div">'
                    . '<label class="control-label col-md-2"></label>'
                    . '<div class="selectbox col-md-4"><div class="cssselect">';
            $dropdown .= '<select name="category_id[]" class="form-control childSelect categoryChange"><option value="0">-- Sub category --</option>';
            foreach ($all_children as $k => $rows) {
                $dropdown .= '<option value="' . $rows['id'] . '">' . $rows['text'] . '</option>';
            }
            $dropdown .= '</select>';
            $dropdown .= '</div></div></div>';
            $response['category_dropdown'] = $dropdown;
        }
        
        
        if($input['selected_category_ids'] != "" && $input['selected_category_ids'] != 0){
            $conditions = $this->productConditions->getProductConditionsByCategory($input['selected_category_ids']);
            $dropdown2 .= '<select name="product_condition_id" class="form-control selectpicker" style="display:block !important;"><option value="">Select product condition</option>';
            foreach ($conditions as $k => $rows) {
                $dropdown2 .= '<option value="' . $rows['id'] . '">' . $rows['name'] . '</option>';
            }
            $dropdown2 .= '</select>';
            $response['product_conditions'] = $dropdown2;
        }
        
        echo json_encode($response);exit;
    }

}
