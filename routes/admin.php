<?php

/** Admin Register Form Route **/
Route::get('admin-register', 'Admin\Account\AdminRegisterController@showAdminRegisterForm')->name('admin.register');
/** Admin Register Route **/
Route::post('admin-register', 'Admin\Account\AdminRegisterController@createAdmin');
/** Admin Login Form Route **/
Route::get('admin-login', 'Admin\Account\AdminLoginController@showAdminLoginForm')->name('admin.login');
/** Admin Login Route **/
Route::post('admin-login', 'Admin\Account\AdminLoginController@adminLogin');
/** Admin Logout **/
Route::get('admin-logout', 'Admin\Account\AdminLoginController@logout')->name('admin.logout');

Route::group(['middleware'=>'auth:admin','prefix'=>'admin','namespace'=>'Admin'],function(){
    Route::get('dashboard', 'AdminDashboardController@showDashboard')->name('admin.dashboard');

    Route::group(['namespace'=>'Location'],function(){

        /** Add State **/
        Route::get('new-state-form', 'StateController@showNewStateForm')->name('admin.new_state_form');
        Route::post('add-state', 'StateController@addState')->name('admin.add_state');
        /** State List **/
        Route::get('state-list', 'StateController@stateList')->name('admin.state_list');
        /** Edit State **/
        Route::get('edit-state-form/{state_id}', 'StateController@showEditStateForm')->name('admin.edit_state_form');
        Route::post('update-state/{state_id}', 'StateController@updateState')->name('admin.update_state');
        /** Status Changing **/
        Route::get('state-change-status/{state_id}/{status}', 'StateController@changeStatus')->name('admin.state_change_status');

        /** Add District **/
        Route::get('new-district-form', 'DistrictController@showNewDistrictForm')->name('admin.new_district_form');
        Route::post('add-district', 'DistrictController@addDistrict')->name('admin.add_district');
        /** District List **/
        Route::get('district-list', 'DistrictController@districtList')->name('admin.district_list');
        /** Datatable Ajax **/
        Route::post('district-list-data', 'DistrictController@districtListData')->name('admin.district_list_data');
        /** Edit State **/
        Route::get('edit-district-form/{district_id}', 'DistrictController@showEditDistrictForm')->name('admin.edit_district_form');
        Route::post('update-district/{district_id}', 'DistrictController@updateDistrict')->name('admin.update_district');
        /** Status Changing **/
        Route::get('district-change-status/{district_id}/{status}', 'DistrictController@changeStatus')->name('admin.district_change_status');

        Route::get('new-sub-district-form', 'SubDistrictController@showNewSubDistrictForm')->name('admin.new_sub_district_form');
        Route::post('add-sub-district', 'SubDistrictController@addSubDistrict')->name('admin.add_sub_district');
        Route::get('sub-district-list', 'SubDistrictController@subDistrictList')->name('admin.sub_district_list');
        /** Datatable Ajax **/
        Route::post('sub-district-list-data', 'SubDistrictController@subDistrictListData')->name('admin.sub_district_list_data');
        /** Edit State **/
        Route::get('edit-sub-district-form/{sub_district_id}', 'SubDistrictController@showEditSubDistrictForm')->name('admin.edit_sub_district_form');
        Route::post('update-sub-district/{sub_district_id}', 'SubDistrictController@updateSubDistrict')->name('admin.update_sub_district');
        /** Status Changing **/
        Route::get('sub-district-change-status/{sub_district_id}/{status}', 'SubDistrictController@changeStatus')->name('admin.sub_district_change_status');

        Route::get('new-area-form', 'AreaController@showNewAreaForm')->name('admin.new_area_form');
        Route::post('add-area', 'AreaController@addArea')->name('admin.add_area');
        Route::get('area-list', 'AreaController@areaList')->name('admin.area_list');
        /** Datatable Ajax **/
        Route::post('area-list-data', 'AreaController@areaListData')->name('admin.area_list_data');
        /** Edit State **/
        Route::get('edit-area-form/{area_id}', 'AreaController@showEditAreaForm')->name('admin.edit_area_form');
        Route::post('update-area/{areat_id}', 'AreaController@updateArea')->name('admin.update_area');
         /** Status Changing **/
        Route::get('area-change-status/{area_id}/{status}', 'AreaController@changeStatus')->name('admin.area_change_status');
    });

    /** Common Function Route **/
    Route::group(['namespace'=>'CommonFunction'],function(){

        /** Ajax Route **/
        Route::post('retrive-district', 'CommonFunctionController@retriveDistrict');
        Route::post('retrive-sub-district', 'CommonFunctionController@retriveSubDistrict');
        Route::post('retrive-area', 'CommonFunctionController@retriveArea');

        Route::post('retrive-sub-category', 'CommonFunctionController@retriveSubCategory');
        Route::post('retrive-niece-category', 'CommonFunctionController@retriveNieceCategory');
        Route::post('retrive-size', 'CommonFunctionController@retriveSize');
    });

    /** Top-Category Route **/
    Route::group(['namespace'=>'TopCategory'],function(){
        Route::get('new-top-category', 'TopCategoryController@showTopCategoryForm')->name('admin.new_top_category');
        Route::put('add-top-category', 'TopCategoryController@addTopCategory')->name('admin.add_top_category');

        Route::get('top-category-list', 'TopCategoryController@topCategoryList')->name('admin.top_category_list');
        
        Route::get('top-category-change-status/{top_category_id}/{status}', 'TopCategoryController@changeStatus')->name('admin.top_category_change_status');

    	Route::get('edit-top-category/{top_category_id}', 'TopCategoryController@showEditTopCategoryForm')->name('admin.edit_top_category');
    	Route::put('update-top-category/{top_category_id}', 'TopCategoryController@updateTopCategory')->name('admin.update_top_category');
    });

    /** Sub-Category Route **/
    Route::group(['namespace'=>'SubCategory'],function(){
    	Route::get('new-sub-category', 'SubCategoryController@showSubCategoryForm')->name('admin.new_sub_category');
        Route::put('add-sub-category', 'SubCategoryController@addSubCategory')->name('admin.add_sub_category');
        
        Route::get('sub-category-list', 'SubCategoryController@subCategoryList')->name('admin.sub_category_list');
        
        Route::get('sub-category-change-status/{sub_category_id}/{status}', 'SubCategoryController@changeStatus')->name('admin.sub_category_change_status');

    	Route::get('edit-sub-category/{sub_category_id}', 'SubCategoryController@showEditSubCategoryForm')->name('admin.edit_sub_category');
    	Route::put('update-sub-category/{sub_category_id}', 'SubCategoryController@updateSubCategory')->name('admin.update_sub_category');
    });

    /** Niece-Category Route **/
    Route::group(['namespace'=>'NieceCategory'],function(){
        Route::get('new-niece-category', 'NieceCategoryController@showNieceCategoryForm')->name('admin.new_niece_category');
        Route::put('add-niece-category', 'NieceCategoryController@addNieceCategory')->name('admin.add_niece_category');

        Route::get('niece-category-list', 'NieceCategoryController@nieceCategoryList')->name('admin.niece_category_list');

        Route::get('niece-category-change-status/{niece_category_id}/{status}', 'NieceCategoryController@changeStatus')->name('admin.niece_category_change_status');

        Route::get('edit-niece-category/{niece_category_id}', 'NieceCategoryController@showEditNieceCategoryForm')->name('admin.edit_niece_category');
        Route::put('update-niece-category/{niece_category_id}', 'NieceCategoryController@updateNieceCategory')->name('admin.update_niece_category');
    });

    /** Size Route **/
    Route::group(['namespace'=>'Size'],function(){

        /** Size Route **/
        Route::get('new-size', 'SizeController@showSizeForm')->name('admin.new_size');
        Route::post('add-size', 'SizeController@addSize')->name('admin.add_size');

        Route::get('size-list', 'SizeController@sizeList')->name('admin.size_list');

        Route::get('edit-size/{size_id}', 'SizeController@showEditSizeForm')->name('admin.edit_size');
        Route::post('update-size/{size_id}', 'SizeController@updateSize')->name('admin.update_size');

        /** Size Mapping **/
        Route::get('new-mappping-size', 'SizeController@showMappingSizeForm')->name('admin.new_mappping_size');
        Route::post('add-mappping-size', 'SizeController@addMappingSize')->name('admin.add_mappping_size');
        Route::get('edit-mappping-size/{size_mapping_id}', 'SizeController@showEditMappingSizeForm')->name('admin.edit_mappping_size');
        Route::post('update-mappping-size/{size_mapping_id}', 'SizeController@updateMappingSize')->name('admin.update_mappping_size');
    });

    /** Brand Route **/
    Route::group(['namespace'=>'Brand'],function(){

        Route::get('new-brand', 'BrandController@showBrandForm')->name('admin.new_brand');
        Route::put('add-brand', 'BrandController@addBrand')->name('admin.add_brand');

        Route::get('brand_list', 'BrandController@brandList')->name('admin.brand_list');

        Route::get('brand-change-status/{brand_id}/{status}', 'BrandController@changeStatus')->name('admin.brand_change_status');

        Route::get('edit-brand/{brand_id}', 'BrandController@showEditBrandForm')->name('admin.edit_brand');
        Route::put('update-brand/{brand_id}', 'BrandController@updateBrand')->name('admin.update_brand');
    });

    /** Product Route **/
    Route::group(['namespace'=>'Product'],function(){

        /** New Product **/
        Route::get('new-product', 'ProductController@showProductForm')->name('admin.new_product');
        Route::put('add-product', 'ProductController@addProduct')->name('admin.add_product');
        
        /** Stock Price **/
        Route::get('product-price-entry/{product_id}', 'ProductController@productPriceEntry')->name('admin.product_price_entry');
        Route::post('add-price-entry/{product_id}', 'ProductController@addPriceEntry')->name('admin.add_price_entry');

        /** Products List **/
        Route::get('prouduct-list', 'ProductController@productList')->name('admin.product_list');
        Route::post('prouduct-list-data', 'ProductController@productListData')->name('admin.product_list_data');

        /** Active and In-Active Products List **/
        Route::get('active-prouduct-list', 'ProductController@activeProductList')->name('admin.active_product_list');
        Route::get('in-active-prouduct-list', 'ProductController@inactiveProductList')->name('admin.in_active_product_list');
        Route::post('active-in-active-prouduct-list-data', 'ProductController@activeInactiveProductListData')->name('admin.active_in_active_product_list_data');

        /** Product Additional Image **/
        Route::get('additional-prouduct-image-list/{product_id}', 'ProductController@showProductImageList')->name('admin.additional_product_image_list');
        Route::put('update-prouduct-additional-image/{additional_image_id}', 'ProductController@updateProductAdditionalImage')->name('admin.update_product_additional_image');

        /** Product View **/
        Route::get('view-product/{product_id}', 'ProductController@viewProduct')->name('admin.view_product');

        /** Edit Product **/
        Route::get('edit-product/{product_id}', 'ProductController@showEditProduct')->name('admin.edit_product');
        Route::put('update-product/{product_id}', 'ProductController@updateProduct')->name('admin.update_product');

        /** Product Update Price **/
        Route::get('edit-product-price/{product_id}', 'ProductController@showEditProductPrice')->name('admin.edit_product_price');
        Route::post('update-product-price/{product_id}', 'ProductController@updateProductPrice')->name('admin.update_product_price'); 
        Route::get('change-product-price-status/{product_price_id}/{status}', 'ProductController@changeProductPriceStatus')->name('admin.change_product_price_status');  

        /** Product Status **/
        Route::get('change-product-status/{product_id}/{status}', 'ProductController@changeProductStatus')->name('admin.change_product_status');  

        /** Make Discount Product **/
        Route::get('make-discount-product/{product_id}/{status}', 'ProductController@makeDiscountProduct')->name('admin.make_discount_status');  

        /** Ajax Route **/
        Route::post('retrive-size', 'ProductController@retriveSize');
        Route::post('retrive-sub-category', 'ProductController@retriveSubCategory');
        Route::post('retrive-niece-category', 'ProductController@retriveNieceCategory');
    });

    /**  **/
    Route::group(['namespace'=>'Mobile'],function(){

        Route::group(['namespace'=>'Slider'],function(){
            /** Adding New Slider **/
            Route::get('new-slider', 'SliderController@showSliderForm')->name('admin.new_slider');
            Route::put('add-slider', 'SliderController@addSlider')->name('admin.add_slider');
            /** Slider List **/
            Route::get('slider-list', 'SliderController@sliderList')->name('admin.slider_list');
            /** Slider Status **/
            Route::get('change-slider-status/{slider_id}/{status}', 'SliderController@changeSliderStatus')->name('admin.change_slider_status');
            /** Edit Slider **/
            Route::get('edit-slider/{slider_id}', 'SliderController@showEditSliderForm')->name('admin.edit_slider');
            Route::put('update-slider/{slider_id}', 'SliderController@updateSlider')->name('admin.update_slider');
        });

        Route::group(['namespace'=>'Offer'],function(){
            /** Adding New Offer **/
            Route::get('new-offer', 'OfferController@showOfferForm')->name('admin.new_offer');
            Route::put('add-offer', 'OfferController@addOffer')->name('admin.add_offer');
            /** Offer List **/
            Route::get('offer-list', 'OfferController@offerList')->name('admin.offer_list');
            /** Offer Status **/
            Route::get('change-offer-status/{offer_id}/{status}', 'OfferController@changeOfferStatus')->name('admin.change_offer_status');  
            /** Edit Offer **/
            Route::get('edit-offer/{offer_id}', 'OfferController@showEditOfferForm')->name('admin.edit_offer');
            Route::put('update-offer/{offer_id}', 'OfferController@updateOffer')->name('admin.update_offer');
        });
    });
});
?>