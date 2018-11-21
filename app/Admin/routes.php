<?php

Admin::registerAdminRoutes();

Route::group([
    'namespace' => 'App\Admin\Controllers',
    'prefix' => 'admin',
    'middleware' => ['web', 'admin'],
    'as' => 'admin::'
], function () {
    Route::get('/', 'HomeController@index')->name('main');
    Route::post('/upload/image', 'UploadController@image')->name('upload.image');
    Route::post('/upload/cover', 'UploadController@cover')->name('upload.cover');
    Route::delete('/upload/cover', 'UploadController@deleteCover')->name('upload.delete_cover');

    Route::group([
        'namespace' => 'Base'
    ], function () {
        Route::resource('/about', 'AboutController')->only(['index', 'update']);
    });

    ///
    Route::group([
        'middleware' => ['admin.check_permission']
    ], function () {
        /// 商品管理
        Route::group([
            'namespace' => 'Items'
        ], function () {
            Route::get('items/warning', 'ItemController@warning')->name('items.warning');
            Route::put('items/warning', 'ItemController@warning')->name('items.warning.update');
            Route::resource('items', 'ItemController')->except('show');
            Route::resource('categories', 'CategoryController')->except('show');
            Route::resource('topics', 'TopicController')->except('show');
            Route::get('items/check','ItemController@checkSn')->name('items.check');
        });

        /// 订单管理
        Route::group([
            'namespace' => 'Orders'
        ], function () {
            Route::get('/orders', 'OrderController@index')->name('orders.index');
            Route::get('/paying', 'OrderController@paying')->name('orders.paying');
            Route::get('/delivering', 'OrderController@delivering')->name('orders.delivering');
            Route::get('/receiving', 'OrderController@receiving')->name('orders.receiving');
            Route::get('/commenting', 'OrderController@commenting')->name('orders.commenting');
            Route::get('/finish', 'OrderController@finish')->name('orders.finish');
            Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
            Route::put('/orders/{order}/modify_price', 'OrderController@modifyPrice')->name('orders.modify_price');
            Route::put('/orders/{order}/deliver', 'OrderController@deliver')->name('orders.deliver');
            Route::delete('/orders/{order}', 'OrderController@destroy')->name('orders.destroy');

            ///
            Route::get('/refunds', 'RefundController@index')->name('refunds.index');
            Route::put('/refunds/{order_refund}/agree', 'RefundController@agree')->name('refunds.agree');
            Route::put('/refunds/{order_refund}/refuse', 'RefundController@refuse')->name('refunds.refuse');

            ///
            Route::resource('comments', 'CommentController')->except(['create', 'edit']);
        });

        /// 首页管理
        Route::group([
            'namespace' => 'Home'
        ], function () {
            Route::resource('banners', 'BannerController')->except('show');
            Route::resource('navigations', 'NavigationController')->except('show');
            Route::resource('recommends', 'RecommendController')->only(['index', 'store', 'destroy']);
        });

        /// 用户管理
        Route::group([
            'namespace' => 'Users'
        ], function () {
            Route::resource('users', 'UserController')->only(['index', 'show']);
        });
    });
});