<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {
	/** Home Api **/
    Route::get('home/{district_id}', 'HomeController@home')->name('api.home');
    /** Sub-Category Api **/
    Route::get('sub-categories/{category_id}', 'SubCategoryController@subCategories')->name('api.sub_categories');
    /** Niece-Category Api **/
    Route::get('niece-categories/{sub_category_id}', 'ProductController@nieceCategories')->name('api.niece_categories');
    /** Product List Api **/
    Route::get('product-list/{niece_category_id}/{district_id}', 'ProductController@productList');
    /** Product Detail Api **/
    Route::get('product-detail/{product_id}', 'ProductController@productDetail')->name('api.product_detail');
    /** Login Api **/
    Route::post('user-login', 'LoginController@userLogin')->name('api.user_login');
    /** Registration Api **/
    Route::post('user-registration', 'RegistrationController@userRegistration')->name('api.user_registration');
    /** State Fetching Api **/
    Route::get('state-fetching', 'LocationController@stateFetching')->name('api.state_fetching');
    /** District Fetching Api **/
    Route::get('district-fetching/{state_id}', 'LocationController@districtFetching')->name('api.district_fetching');
    /** Search Product Api **/
    Route::get('product-search/{district_id}/{query}', 'ProductController@productSearch');

    /** Member Middleware **/
    Route::middleware(['userauth'])->group(function () {
        /** Add to Cart Api **/
        Route::post('add-to-cart', 'CartController@addToCart')->name('api.add_to_cart');
        /** View Cart Api **/
        Route::post('view-cart', 'CartController@viewCart')->name('api.view_cart');
        /** View Cart Api **/
        Route::post('update-cart', 'CartController@updateCart')->name('api.update_cart');
        /** Remove Cart Api **/
        Route::post('remove-cart', 'CartController@removeCart')->name('api.remove_cart');

        /** Add to Wish List Api **/
        Route::post('add-to-wish-list', 'WishListController@addToWishList')->name('api.add_to_wish_list');
        /** View Wish List Api **/
        Route::post('view-wish-list', 'WishListController@viewWishList')->name('api.wish_list');
        /** Remove Wish List Api **/
        Route::post('remove-wish-list', 'WishListController@removeWishList')->name('api.remove_wish_list');

        /** Fetching User Profile Api **/
        Route::post('fetching-user-profile', 'UserController@fetchingUserProfile')->name('api.fetching_user_profile');
        /** Update User Profile Api **/
        Route::post('update-user-profile', 'UserController@updateUserProfile')->name('api.update_user_profile');

        /** Checkout Api **/
        Route::post('checkout', 'CheckoutController@checkout')->name('api.checkout');
        /** Stock Checking Api **/
        Route::post('stock-checking', 'CheckoutController@stockChecking')->name('api.stock_checking');

        /** Fetching Address Api **/
        Route::post('address-fetching', 'AddressController@addressFetching')->name('api.address_fetching');
        /** Update Address Api **/
        Route::post('update-address', 'AddressController@updateAddress')->name('api.update_address');

        /** Fetching Sub-District Api **/
        Route::post('fetch-sub-district', 'LocationController@fetchSubDistrict')->name('api.fetch_sub_district');
        /** Fetching Area Api **/
        Route::post('fetch-area', 'LocationController@fetchArea')->name('api.fetch_area');

        /** Cash Order Api **/
        Route::post('cash-order', 'OrderController@cashOrder')->name('api.cash_order');
        /** Online Order Api**/
        Route::post('online-order', 'OrderController@onlineOrder')->name('api.online_order');
        /** Online Payment Success Api**/
        Route::post('online-payment-success', 'OrderController@onlinePaymentSuccess')->name('api.online_payment_success');
        /** Online Payment Failed Api**/
        Route::post('online-payment-failed', 'OrderController@onlinePaymentFailed')->name('api.online_payment_failed');

        /** Order List Api **/
        Route::post('order-list', 'OrderController@orderList')->name('api.order_list');
        /** Order Detail Api **/
        Route::post('order-detail', 'OrderController@orderDetail')->name('api.order_detail');
    });
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

?>
