<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('ajax_promo/{promo_code}/{user_id}', 'PromoController@getDetails')->name('frontend.promodetails.get');

Route::group(['namespace' => 'Front','middleware' => 'web'], function () {
    Route::auth();
    /* Route::get('/', function () {return view('welcome');}); */

<<<<<<< Updated upstream
    /*
     * Auth required routes
     */
    
    Route::group(['middleware' => ['auth']], function() {
        //logout user
        Route::get('logout', 'Auth\AuthController@logout')->name('logoutUser');
        /*
         * Seller - Product
         */
        Route::group(['prefix' => 'sell'], function () {

            Route::get('dashboard', 'DashboardController@seller')->name('sellerDashboard');
            
            Route::get('bids', 'ProductsController@sellBidsListing');
            Route::post('bidsProductsDatatableList', ['uses' => 'ProductsController@bidsProductDatatableList', 'as' => 'productsBidsDatatableList']);
            Route::get('bids/detail/{productId}', 'ProductsController@sellBidsDetail');
                
            Route::group(['prefix' => 'product'], function () {
                // listing of all products
                Route::get('index', 'ProductsController@index')->name('listingProduct');                
                Route::post('productsDatatableList', ['uses' => 'ProductsController@datatableList', 'as' => 'productsDatatableList']);
                
                // create product all steps
                Route::get('create/{step}/{productId}', 'ProductsController@create')->name('createProduct');

                //store/update products
                Route::post('store/{step}/{productId}', 'ProductsController@store')->name('postProduct');
                
                //upload product images
                Route::post('image/uploader/{productId}', ['uses' => 'ProductsController@uploadImage'])->name('uploadImage');
                Route::post('image/remover/{productId}', ['uses' => 'ProductsController@removeImage'])->name('removeProductImage');
                Route::post('updatestatus/{type}', 'ProductsController@changeProductStatus')->name('updateStatus');
                
                Route::get('/preview-product/create/', 'ProductsController@previewProduct');
                
            });
            
            /*Offer Route*/
            Route::get('offers', 'OfferController@index')->name('offers');
            Route::post('offerDatatableList', ['uses' => 'OfferController@datatableList', 'as' => 'offerDatatableList']);
            Route::get('offer_details/{id}', 'OfferController@offerDetails')->name('offerDetails');
            Route::post('offerDetailsList/{id}', ['uses' => 'OfferController@datatableOfferList', 'as' => 'offerDetailsList']);
            Route::get('sellerAcceptResponce/{id}', ['uses' => 'OfferController@offerAcceptSeller', 'as' => 'sellerAcceptResponce']);
            Route::get('sellerRejectResponce/{id}', ['uses' => 'OfferController@offerRejectSeller', 'as' => 'sellerRejectResponce']);
            Route::get('getCounterOffer/{id}', ['uses' => 'OfferController@getCounterOffer', 'as' => 'getCounterOffer']);
            Route::post('sendCounterOffer/{id}', ['uses' => 'OfferController@sendCounterOffer', 'as' => 'sendCounterOffer']);

            /*
            * Seller - Classified Product
            */
            Route::group(['prefix' => 'classified_product'], function () {
                // listing of all classified products
                Route::get('/', 'ClassifiedController@index')->name('listingClassifiedProduct');
                Route::post('classifiedDatatableList', ['uses' => 'ClassifiedController@datatableList', 'as' => 'classifiedDatatableList']);
                Route::get('create', 'ClassifiedController@create')->name('createClassifiedProduct');
                Route::post('image/uploader/{productId}', ['uses' => 'ClassifiedController@uploadImage'])->name('uploadClassifiedImage');
                Route::post('store', 'ClassifiedController@store')->name('postClassifiedProduct');
                Route::get('edit/{id}', 'ClassifiedController@edit')->name('editClassifiedProduct');
                Route::post('editStore/{id}', 'ClassifiedController@editStore')->name('editSaveClassifiedProduct');
                Route::post('removeimage', 'ClassifiedController@removeimage')->name('removeImage');
                Route::post('removevideo', 'ClassifiedController@removevideo')->name('removeVideo');
                Route::get('classifiedRequester/{id}', 'ClassifiedController@classifiedRequester')->name('classifiedRequester');
                Route::post('datatableRequesterList/{id}', ['uses' => 'ClassifiedController@datatableRequesterList', 'as' => 'datatableRequesterList']);
                Route::get('messageBuyer/{email}/{id}', ['uses' => 'ClassifiedController@messageBuyer', 'as' => 'messageBuyer']);
                Route::post('sendMessageBuyer/{email}/{id}', 'ClassifiedController@sendMessageBuyer')->name('sendMessageBuyer');
                Route::get('feedbackBuyer/{cid}/{bid}', ['uses' => 'ClassifiedController@feedbackBuyer', 'as' => 'feedbackBuyer']);
                Route::post('sendFeedbackBuyer/{cid}/{bid}', 'ClassifiedController@sendFeedbackBuyer')->name('sendFeedbackBuyer');
                Route::post('updateclassifiedstatus/{type}', 'ClassifiedController@changeProductStatus')->name('updateClassifiedStatus');
            });
        });
                    
        Route::group(['prefix' => 'buy'], function () {
            Route::get('dashboard', 'DashboardController@buyer')->name('buyerDashboard');
            Route::get('manageorder', 'OrderController@index')->name('manageorder');
            Route::post('orderDatatableList', ['uses' => 'OrderController@datatableList', 'as' => 'orderDatatableList']);
            Route::get('orderDatatableList', ['uses' => 'OrderController@datatableList', 'as' => 'orderDatatableList']);
            Route::get('orderdetail/{id}', 'OrderController@orderdetail')->name('orderdetail');
            Route::get('orderinvoice/{id}', 'OrderController@orderinvoice')->name('orderInvoice');
            Route::get('cancel_order', 'OrderController@cancel_order')->name('cancel_order');
            Route::post('cancel_order', 'OrderController@cancel_order')->name('cancel_order');
            Route::get('bids/{id}/history', 'ProductsController@bidsHistory');
            Route::get('bids', 'ProductsController@buyBidsListing');            
            Route::post('buyBidsProductsDatatableList', ['uses' => 'ProductsController@buyBidsProductDatatableList', 'as' => 'buyBidsProductDatatableList']);
            Route::group(['prefix' => 'product'], function () {
                   Route::get('index', 'BuyerproductsController@index')->name('listingOrder');
                Route::post('buyerProductsDatatableList', ['uses' => 'BuyerproductsController@datatableList', 'as' => 'buyerProductsDatatableList']);
            });
            /* Buyer Offers */
            Route::get('buyerOffers', 'OfferController@buyerIndex')->name('buyerOffers');
            Route::post('buyerOfferDatatableList', ['uses' => 'OfferController@buyerOfferDatatableList', 'as' => 'buyerOfferDatatableList']);
            Route::get('getBuyerCounterOffer/{id}/{type}', ['uses' => 'OfferController@getBuyerCounterOffer', 'as' => 'getBuyerCounterOffer']);
            Route::post('buyerSendCounterOffer/{id}', ['uses' => 'OfferController@buyerSendCounterOffer', 'as' => 'buyerSendCounterOffer']);

            /* Advertisements */
            Route::get('advertisements/{id?}', 'AdvertisementController@index')->name('advertisements');    
            Route::post('addDatatableList', ['uses' => 'AdvertisementController@datatableList', 'as' => 'addDatatableList']);
            Route::post('changeAddStatus', ['uses' => 'AdvertisementController@changeAddStatus', 'as' => 'changeAddStatus']);
            Route::get('postAdd', ['uses' => 'AdvertisementController@postAdd', 'as' => 'postAdd']);
            Route::post('storeAdd', ['uses' => 'AdvertisementController@storeAdd', 'as' => 'storeAdd']);
            Route::get('editAd/{id}', ['uses' => 'AdvertisementController@editAd', 'as' => 'editAd']);
            Route::post('storeEdit/{id}', ['uses' => 'AdvertisementController@storeEdit', 'as' => 'storeEdit']);

        });
        
        
        Route::get('profile', 'ProfileController@index')->name('getProfile');
        Route::get('profile/business', 'ProfileController@business')->name('getBusiness');
        Route::get('profile/address', 'ProfileController@address')->name('getAddress');
        Route::post('profile/store-business-info', 'ProfileController@storeBusinessInfo')->name('storeBusinessInfo');
        Route::get('profile/followers', 'ProfileController@followers')->name('getFollowers');
        Route::get('profile/rating', 'ProfileController@rating')->name('getRating');
        Route::patch('profile/store', 'ProfileController@store')->name('storePersonalInfo');
        Route::post('profile/store-image', 'ProfileController@storeImage')->name('storeImage');
        Route::delete('profile/delete-address/{id}', 'ProfileController@deleteAddress')->name('deleteAddress');
        Route::get('profile/change-password/{id}', 'ProfileController@changePassword')->name('getPasswordPopup');
        Route::get('profile/edit-address/{id?}', 'ProfileController@getEditAddressPopup')->name('getEditAddressPopup');
        Route::post('forum/post-edit-address', 'ProfileController@storeEditAddress')->name('storeEditAddress');
        Route::post('forum/profile-change-password', 'ProfileController@storeChangePassword')->name('profileChangePassword');
        
        /*
         * Forum topic create and store
         */

        Route::post('forum/store', 'ForumController@store')->name('forumStore');
        Route::post('forum/add-comment', 'ForumController@storeComment')->name('forumStoreComment');
        Route::post('forum/report-flag', 'ForumController@storereportFlag')->name('forumReportFlag');
        Route::post('forum/update-like', 'ForumController@updateLike')->name('updateLike');
        
        //Mingle
        Route::get('mingle/messages/{user?}', 'MingleController@messages')->name('messages');
        Route::get('mingle/sync', 'MingleController@mingleSync')->name('mingleSync');
        Route::post('mingle/update-follow', 'MingleController@mingleFollow')->name('mingleFollow');
        Route::post('mingle/update-unfollow', 'MingleController@mingleUnFollow')->name('mingleUnFollow');
        Route::post('mingle/send-invitation', 'MingleController@mingleInvitation')->name('mingleInvitation');
        Route::post('mingle/update-status', 'MingleController@mingleStatus')->name('mingleStatus');
        Route::get('type/{type}/{page?}', 'MingleController@type')->name('getMingleType');
        Route::post('mingle/post-mingle-sync', 'MingleController@storeMingleSync')->name('storeMingleSync');
        Route::get('mingle/{page?}/{limit?}', 'MingleController@index')->name('getConnect');
        Route::post('mingle/search-mingle', 'MingleController@searchMingle')->name('searchMingle');
        
        //end
        
        Route::get('category/getdynamicchildajax', 'CategoriesController@getdynamicchildajax')->name('getdynamicchilddropdown');
        Route::get('checkout', 'ShoppingcartController@checkout')->name('checkout');
        Route::get('cart_to_order', 'ShoppingcartController@cart_to_order')->name('cart_to_order');
        Route::post('cart_to_order', 'ShoppingcartController@cart_to_order')->name('cart_to_order');
        Route::get('payment', 'ShoppingcartController@payment')->name('payment');
        Route::get('makepayment', 'PaymentController@charge')->name('makepayment');  
        Route::post('makepayment', 'PaymentController@charge')->name('makepayment');  
    });
=======
/*
* Auth required routes
*/

Route::group(['middleware' => ['auth']], function() {
//logout user
    Route::get('logout', 'Auth\AuthController@logout')->name('logoutUser');
/*
* Seller - Product
*/
Route::group(['prefix' => 'sell'], function () {

    Route::get('dashboard', 'DashboardController@seller')->name('sellerDashboard');

    Route::get('bids', 'ProductsController@sellBidsListing');
    Route::post('bidsProductsDatatableList', ['uses' => 'ProductsController@bidsProductDatatableList', 'as' => 'productsBidsDatatableList']);
    Route::get('bids/detail/{productId}', 'ProductsController@sellBidsDetail');

    Route::group(['prefix' => 'product'], function () {
// listing of all products
        Route::get('index', 'ProductsController@index')->name('listingProduct');                
        Route::post('productsDatatableList', ['uses' => 'ProductsController@datatableList', 'as' => 'productsDatatableList']);
>>>>>>> Stashed changes

// create product all steps
        Route::get('create/{step}/{productId}', 'ProductsController@create')->name('createProduct');

//store/update products
        Route::post('store/{step}/{productId}', 'ProductsController@store')->name('postProduct');

//upload product images
        Route::post('image/uploader/{productId}', ['uses' => 'ProductsController@uploadImage'])->name('uploadImage');

        Route::post('updatestatus/{type}', 'ProductsController@changeProductStatus')->name('updateStatus');
    });

    /*Offer Route*/
    Route::get('offers', 'OfferController@index')->name('offers');
    Route::post('offerDatatableList', ['uses' => 'OfferController@datatableList', 'as' => 'offerDatatableList']);
    Route::get('offer_details/{id}', 'OfferController@offerDetails')->name('offerDetails');
    Route::post('offerDetailsList/{id}', ['uses' => 'OfferController@datatableOfferList', 'as' => 'offerDetailsList']);
    Route::get('sellerAcceptResponce/{id}', ['uses' => 'OfferController@offerAcceptSeller', 'as' => 'sellerAcceptResponce']);
    Route::get('sellerRejectResponce/{id}', ['uses' => 'OfferController@offerRejectSeller', 'as' => 'sellerRejectResponce']);
    Route::get('getCounterOffer/{id}', ['uses' => 'OfferController@getCounterOffer', 'as' => 'getCounterOffer']);
    Route::post('sendCounterOffer/{id}', ['uses' => 'OfferController@sendCounterOffer', 'as' => 'sendCounterOffer']);

/*
* Seller - Classified Product
*/
Route::group(['prefix' => 'classified_product'], function () {
// listing of all classified products
    Route::get('/', 'ClassifiedController@index')->name('listingClassifiedProduct');
    Route::post('classifiedDatatableList', ['uses' => 'ClassifiedController@datatableList', 'as' => 'classifiedDatatableList']);
    Route::get('create', 'ClassifiedController@create')->name('createClassifiedProduct');
    Route::post('image/uploader/{productId}', ['uses' => 'ClassifiedController@uploadImage'])->name('uploadClassifiedImage');
    Route::post('store', 'ClassifiedController@store')->name('postClassifiedProduct');
    Route::get('edit/{id}', 'ClassifiedController@edit')->name('editClassifiedProduct');
    Route::post('editStore/{id}', 'ClassifiedController@editStore')->name('editSaveClassifiedProduct');
    Route::post('removeimage', 'ClassifiedController@removeimage')->name('removeImage');
    Route::post('removevideo', 'ClassifiedController@removevideo')->name('removeVideo');
    Route::get('classifiedRequester/{id}', 'ClassifiedController@classifiedRequester')->name('classifiedRequester');
    Route::post('datatableRequesterList/{id}', ['uses' => 'ClassifiedController@datatableRequesterList', 'as' => 'datatableRequesterList']);
    Route::get('messageBuyer/{email}/{id}', ['uses' => 'ClassifiedController@messageBuyer', 'as' => 'messageBuyer']);
    Route::post('sendMessageBuyer/{email}/{id}', 'ClassifiedController@sendMessageBuyer')->name('sendMessageBuyer');
    Route::get('feedbackBuyer/{cid}/{bid}', ['uses' => 'ClassifiedController@feedbackBuyer', 'as' => 'feedbackBuyer']);
    Route::post('sendFeedbackBuyer/{cid}/{bid}', 'ClassifiedController@sendFeedbackBuyer')->name('sendFeedbackBuyer');
    Route::post('updateclassifiedstatus/{type}', 'ClassifiedController@changeProductStatus')->name('updateClassifiedStatus');
});
});

Route::group(['prefix' => 'buy'], function () {
    Route::get('dashboard', 'DashboardController@buyer')->name('buyerDashboard');
    Route::get('manageorder', 'OrderController@index')->name('manageorder');
    Route::post('orderDatatableList', ['uses' => 'OrderController@datatableList', 'as' => 'orderDatatableList']);
    Route::get('orderDatatableList', ['uses' => 'OrderController@datatableList', 'as' => 'orderDatatableList']);
    Route::get('orderdetail/{id}', 'OrderController@orderdetail')->name('orderdetail');
    Route::get('orderinvoice/{id}', 'OrderController@orderinvoice')->name('orderInvoice');
    Route::get('cancel_order', 'OrderController@cancel_order')->name('cancel_order');
    Route::post('cancel_order', 'OrderController@cancel_order')->name('cancel_order');
    Route::get('bids/{id}/history', 'ProductsController@bidsHistory');
    Route::get('bids', 'ProductsController@buyBidsListing');            
    Route::post('buyBidsProductsDatatableList', ['uses' => 'ProductsController@buyBidsProductDatatableList', 'as' => 'buyBidsProductDatatableList']);
    Route::group(['prefix' => 'product'], function () {
        Route::get('index', 'BuyerproductsController@index')->name('listingOrder');
        Route::post('buyerProductsDatatableList', ['uses' => 'BuyerproductsController@datatableList', 'as' => 'buyerProductsDatatableList']);
    });
    /* Buyer Offers */
    Route::get('buyerOffers', 'OfferController@buyerIndex')->name('buyerOffers');
    Route::post('buyerOfferDatatableList', ['uses' => 'OfferController@buyerOfferDatatableList', 'as' => 'buyerOfferDatatableList']);
    Route::get('getBuyerCounterOffer/{id}/{type}', ['uses' => 'OfferController@getBuyerCounterOffer', 'as' => 'getBuyerCounterOffer']);
    Route::post('buyerSendCounterOffer/{id}', ['uses' => 'OfferController@buyerSendCounterOffer', 'as' => 'buyerSendCounterOffer']);

    /* Advertisements */
    Route::get('advertisements/{id?}', 'AdvertisementController@index')->name('advertisements');    
    Route::post('addDatatableList', ['uses' => 'AdvertisementController@datatableList', 'as' => 'addDatatableList']);
    Route::post('changeAddStatus', ['uses' => 'AdvertisementController@changeAddStatus', 'as' => 'changeAddStatus']);
    Route::get('postAdd', ['uses' => 'AdvertisementController@postAdd', 'as' => 'postAdd']);
    Route::post('storeAdd', ['uses' => 'AdvertisementController@storeAdd', 'as' => 'storeAdd']);
    Route::get('editAd/{id}', ['uses' => 'AdvertisementController@editAd', 'as' => 'editAd']);
    Route::post('storeEdit/{id}', ['uses' => 'AdvertisementController@storeEdit', 'as' => 'storeEdit']);

});


Route::get('profile', 'ProfileController@index')->name('getProfile');
Route::get('profile/business', 'ProfileController@business')->name('getBusiness');
Route::get('profile/address', 'ProfileController@address')->name('getAddress');
Route::post('profile/store-business-info', 'ProfileController@storeBusinessInfo')->name('storeBusinessInfo');
Route::get('profile/followers', 'ProfileController@followers')->name('getFollowers');
Route::get('profile/rating', 'ProfileController@rating')->name('getRating');
Route::patch('profile/store', 'ProfileController@store')->name('storePersonalInfo');
Route::post('profile/store-image', 'ProfileController@storeImage')->name('storeImage');
Route::delete('profile/delete-address/{id}', 'ProfileController@deleteAddress')->name('deleteAddress');
Route::get('profile/change-password/{id}', 'ProfileController@changePassword')->name('getPasswordPopup');
Route::get('profile/edit-address/{id?}', 'ProfileController@getEditAddressPopup')->name('getEditAddressPopup');
Route::post('forum/post-edit-address', 'ProfileController@storeEditAddress')->name('storeEditAddress');
Route::post('forum/profile-change-password', 'ProfileController@storeChangePassword')->name('profileChangePassword');

/*
* Forum topic create and store
*/

Route::post('forum/store', 'ForumController@store')->name('forumStore');
Route::post('forum/add-comment', 'ForumController@storeComment')->name('forumStoreComment');
Route::post('forum/report-flag', 'ForumController@storereportFlag')->name('forumReportFlag');
Route::post('forum/update-like', 'ForumController@updateLike')->name('updateLike');

//Mingle
Route::get('mingle/messages/{user?}', 'MingleController@messages')->name('messages');
Route::get('mingle/sync', 'MingleController@mingleSync')->name('mingleSync');
Route::post('mingle/update-follow', 'MingleController@mingleFollow')->name('mingleFollow');
Route::post('mingle/update-unfollow', 'MingleController@mingleUnFollow')->name('mingleUnFollow');
Route::post('mingle/send-invitation', 'MingleController@mingleInvitation')->name('mingleInvitation');
Route::post('mingle/update-status', 'MingleController@mingleStatus')->name('mingleStatus');
Route::get('type/{type}/{page?}', 'MingleController@type')->name('getMingleType');
Route::post('mingle/post-mingle-sync', 'MingleController@storeMingleSync')->name('storeMingleSync');
Route::get('mingle/{page?}/{limit?}', 'MingleController@index')->name('getConnect');
Route::post('mingle/search-mingle', 'MingleController@searchMingle')->name('searchMingle');

//end

Route::get('category/getdynamicchildajax', 'CategoriesController@getdynamicchildajax')->name('getdynamicchilddropdown');
Route::get('checkout', 'ShoppingcartController@checkout')->name('checkout');
Route::get('cart_to_order', 'ShoppingcartController@cart_to_order')->name('cart_to_order');
Route::post('cart_to_order', 'ShoppingcartController@cart_to_order')->name('cart_to_order');
Route::get('payment', 'ShoppingcartController@payment')->name('payment');
Route::get('makepayment', 'PaymentController@charge')->name('makepayment');  
Route::post('makepayment', 'PaymentController@charge')->name('makepayment');  
});

/*
* Seller - Forum
*/
Route::group(['prefix' => 'forum'], function () {

    Route::get('create', 'ForumController@create')->name('forumCreate');
    Route::get('{fid}/{type}/report', 'ForumController@getReportFlag')->name('getReportFlag');

    Route::get('mostrecent/{pid?}', 'ForumController@mostrecent')->name('forumMostRecent');
    Route::get('popular/{pid?}', 'ForumController@popular')->name('forumPopular');
    Route::get('{id}', 'ForumController@topic')->name('forumTopic');
    Route::get('{id}/topics/{pid}', 'ForumController@topic')->name('forumTopicPage');
    Route::get('{pid}/{id}/comments/{pageid}', 'ForumController@getTopic')->name('forumTopicCommentPage');
Route::get('{pid}/{id}', 'ForumController@getTopic')->name('getTopic'); //departmentId/topicId
});
//end

Route::get('/', 'HomeController@index')->name('homepage');
/*
*  Authentication Routes
*/
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@postLogin')->name("postLogin");

/*
*  Registration Routes...
*/
Route::get('individual-register', 'Auth\AuthController@showIndividualRegistrationForm')->name('individualRegister');
Route::post('individual-register', 'Auth\AuthController@postIndividualRegister')->name('individualRegister');

Route::get('business-register', 'Auth\AuthController@showBusinessRegistrationForm')->name('businessRegister');
Route::post('business-register', 'Auth\AuthController@postBusinessRegister')->name('businessRegister');

Route::get('account-verify/{verification_code}', 'Auth\AuthController@accountVerify')->name('accountVerify');

/*
*  Password Reset Routes...
*/
/* Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');
*/
Route::get('forgot-password', 'Auth\AuthController@showForgotPasswordForm')->name('forgotPassword');
Route::post('forgot-password', 'Auth\AuthController@postForgotPassword')->name('postForgotPassword');

/*
* Reset Password
*/
Route::get('reset-password/{token}', 'Auth\AuthController@showResetPasswordForm')->name('resetPassword');
Route::post('reset-password', 'Auth\AuthController@postResetPassword')->name('postResetPassword');


// socialite
Route::get('social/auth/redirect/{provider}', 'Auth\AuthController@redirectToProvider')->name('socialAuthRedirectProvider');
//Route::get('social/auth/{provider}', 'Auth\AuthController@handleProviderCallback');
Route::get('callback/{provider}', 'Auth\AuthController@handleProviderCallback');

// sign in from admin route
Route::get('signinFromAdmin/{userId}', 'Auth\AuthController@signinFromAdmin');

// google recaptcha
Route::get('refereshCaptcha', 'Auth\AuthController@refereshCaptcha')->name('refereshCaptcha');

/*
*  CMS pages
*/
Route::get("general-terms-and-conditions", function () {
    $content = App\Models\TermAndCondition::where(['slug' => 'general-terms-and-conditions', 'status' => 'Published'])->first();
    $content = (!empty($content)) ? $content->terms_conditions : trans('message.no_content_found');
    $cms_breadcrumb = 'General terms and conditions';
    return response()->view('front.auth.register.cms', compact('content', 'cms_breadcrumb'));
})->name('generalTC');

Route::get("privacy-policy", function () {
    $content = App\Models\TermAndCondition::where(['slug' => 'privacy-policy', 'status' => 'Published'])->first();
    $content = (!empty($content)) ? $content->terms_conditions : trans('message.no_content_found');
    $cms_breadcrumb = 'Privacy Policy';
    return response()->view('front.auth.register.cms', compact('content', 'cms_breadcrumb'));
})->name('privacyPolicy');



//category pages    
Route::get('/categories/{ch}', 'CategoriesController@getCategoriesByCharacter');
Route::get('/categories', 'CategoriesController@index');

Route::get('/c/{category_slug}/{page?}', 'CategoriesController@categoriesListing');

//occasions    
Route::get('/occasions', 'OccasionsController@index');

// product detail page    
Route::get('posts/{post}/comments/{comment}', function ($postId, $commentId) {
//
});

Route::get('compare/remove/{category_slug}/{product_id?}', 'ProductsController@ClearComparedCategory');    
Route::get('compare/removeProduct/{category_slug}/{product_id}', 'ProductsController@removeProduct');    
Route::get('compare/{category_slug?}/{arg1?}/{arg2?}/{arg3?}/{arg4?}', 'ProductsController@compare')->name('compare');
Route::get('getComparedProduct/{category_slug?}/{product_id?}/{type?}', 'ProductsController@getComparedProduct');
Route::get('getMakeAnOffer/{productId}/{sellerId}', 'ProductsController@getMakeAnOffer');    
Route::post('saveAnOffer/{productId}/{sellerId}', 'ProductsController@saveAnOffer');
Route::post('retractOffer/{productId}/{sellerId}', 'ProductsController@retractOffer');
Route::get('getBids/{productId}/{sellerId}', 'ProductsController@getBids');

Route::get('product/{slug}', 'ProductsController@detail')->name('productSlugUrl');
Route::get('sellerstore/{manufacturer}/{page?}','ProductsController@sellerstore');
Route::get('occasion/{occasion}/{page?}','ProductsController@occasionProductListing');


//seller profile routes
Route::get('seller-profile/{id}', 'ProfileController@sellerProfile')->name('seller_profile');
// remember passoword sign in box 
Route::post('get/u/p/ck', 'Auth\AuthController@getUserPassword')->name('getUserPassword');
Route::get('shoppingcart', 'ShoppingcartController@index');
Route::get('product1', 'HomeController@getproducts');
Route::post('cart', 'ShoppingcartController@cart');
Route::get('cart', 'ShoppingcartController@cart')->name('cart');
Route::post('cartSku', 'ShoppingcartController@cartSku');
Route::get('cartSku', 'ShoppingcartController@cartSku')->name('cartSku');
Route::post('favorite', 'ShoppingcartController@favorite');
Route::get('favorite', 'ShoppingcartController@favorite')->name('favorite');
Route::post('watchlist', 'ShoppingcartController@watchlist');
Route::get('watchlist', 'ShoppingcartController@watchlist')->name('watchlist');
Route::get('removefromwishlist', 'ShoppingcartController@removefromwishlist')->name('removefromwishlist');
Route::post('removefromwishlist', 'ShoppingcartController@removefromwishlist')->name('removefromwishlist');   
Route::get('removefromwatchlist', 'ShoppingcartController@removefromwatchlist')->name('removefromwatchlist');
Route::post('removefromwatchlist', 'ShoppingcartController@removefromwatchlist')->name('removefromwatchlist');
Route::get('removefromcart', 'ShoppingcartController@removefromcart')->name('removefromcart');
Route::post('removefromcart', 'ShoppingcartController@removefromcart')->name('removefromcart');   

Route::get('updatecart', 'ShoppingcartController@updatecart')->name('updatecart');
Route::get('incrementcart', 'ShoppingcartController@incrementcart')->name('incrementcart');
Route::post('incrementcart', 'ShoppingcartController@incrementcart')->name('incrementcart');
Route::get('decrease', 'ShoppingcartController@decrease')->name('decrease');
Route::post('decrease', 'ShoppingcartController@decrease')->name('decrease');
Route::get('removecart', 'ShoppingcartController@removecart')->name('removecart');
Route::post('removecart', 'ShoppingcartController@removecart')->name('removecart');
Route::get('removefavorite', 'ShoppingcartController@removefavorite')->name('removefavorite');
Route::post('removefavorite', 'ShoppingcartController@removefavorite')->name('removefavorite');    
Route::get('updateqtycart', 'ShoppingcartController@updateqtycart')->name('updateqtycart');
Route::post('updateqtycart', 'ShoppingcartController@updateqtycart')->name('updateqtycart');
Route::get('remove_bascket', 'ShoppingcartController@remove_bascket')->name('remove_bascket');
Route::post('remove_bascket', 'ShoppingcartController@remove_bascket')->name('remove_bascket');
Route::get('remove_bascket_shopping', 'ShoppingcartController@remove_bascket_shopping')->name('remove_bascket_shopping');

Route::post('remove_bascket_shopping', 'ShoppingcartController@remove_bascket_shopping')->name('remove_bascket_shopping');
Route::get('updateqtyshoppingcart', 'ShoppingcartController@updateqtyshoppingcart')->name('updateqtyshoppingcart');
Route::post('updateqtyshoppingcart', 'ShoppingcartController@updateqtyshoppingcart')->name('updateqtyshoppingcart');  

//forum page
Route::get('forum', 'ForumController@index')->name('forum');
Route::post('topiclisting', 'ForumController@topicListing')->name('topicListing');
Route::get('shippingRate/{frompincode}/{topincode}', 'ShippingController@getShippingRate');
Route::get('shippingServices/{frompincode}/{topincode}', 'ShippingController@getShippingServices');
});

/**/

use Illuminate\Support\Facades\Input;

/*
* State drop dbase_get_record_with_names(dbase_identifier, record_number)
*/
Route::get('/information/create/ajax-state', function() {
    return getAllStates(Input::get('country_id'));
})->name('getAllStates');
/*
* City drop down
*/
Route::get('/information/create/ajax-city', function() {
    return getAllCities(Input::get('state_id'));
})->name('getAllCities');



Route::get('helpcenter/', 'FaqController@index')->name('helpcenter');
Route::get('helpcenter/terms_conditions', 'FaqController@termscond')->name('termscond');
Route::get('helpcenter/{id}', 'FaqController@helpcenter')->name('helpcenter_single');



Route::get('currency-change/{currency}/{rate}', function($currency , $rate){
    session(['currency' => $currency]);
    session(['currency_rate' => $rate]);
    return redirect('/');
});

