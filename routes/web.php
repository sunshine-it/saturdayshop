<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'PagesController@root')->name('root');
Route::redirect('/', '/products')->name('root');
// 商品列表
Route::get('products', 'ProductsController@index')->name('products.index');
// 商品详情(使用正则解决路由冲突)
Route::get('products/{product}', 'ProductsController@show')->name('products.show')->where(['product' => '[0-9]+']);

Auth::routes(['verify' => true]);

// auth 中间件代表需要登录，verified 中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');
    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::get('orders', 'OrdersController@index')->name('orders.index');
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
    Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
    // 客户确认收货路由
    Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');
    // 用户评价
    Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
    Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
    // 申请退款路由
    Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');
    // 用户的优惠卷列表
    Route::get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');
    // 众筹下单
    Route::post('crowdfunding_orders', 'OrdersController@crowdfunding')->name('crowdfunding_orders.store');
    // 分期付款
    Route::post('payment/{order}/installment', 'PaymentController@payByInstallment')->name('payment.installment');
    // 分期付款列表
    Route::get('installments', 'InstallmentsController@index')->name('installments.index');
    // 分期的详情
    Route::get('installments/{installment}', 'InstallmentsController@show')->name('installments.show');
    // 分期还款
    Route::get('installments/{installment}/alipay', 'InstallmentsController@payByAlipay')->name('installments.alipay');
    // 支付回调前端
    Route::get('installments/alipay/return', 'InstallmentsController@alipayReturn')->name('installments.alipay.return');
    // 微信分期支付
    Route::get('installments/{installment}/wechat', 'InstallmentsController@payByWechat')->name('installments.wechat');
    // 秒杀
    Route::post('seckill_orders', 'OrdersController@seckill')->name('seckill_orders.store');
});
// 后端回调不能放在 auth 中间件中
// 支付宝回调通知
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
// 微信支付回调通知
Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');
// 微信退款回调通知
Route::post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');
// 支付宝分期后端回调通知
Route::post('installments/alipay/notify', 'InstallmentsController@alipayNotify')->name('installments.alipay.notify');
// 微信分期支付后端回调通知
Route::post('installments/wechat/notify', 'InstallmentsController@wechatNotify')->name('installments.wechat.notify');
// 微信分期退款后端回调通知
Route::post('installments/wechat/refund_notify', 'InstallmentsController@wechatRefundNotify')->name('installments.wechat.refund_notify');



// 支付测试
// Route::get('alipay', function() {
//     return app('alipay')->web([
//         'out_trade_no' => time(),
//         'total_amount' => '1', // 一元 RMB
//         'subject' => 'test subject - 测试',
//     ]);
// });
