<?php

$this->view('/', 'index')->name('home');

//$this->get('/register', function () {
//    return redirect('/');
//});

$this->get('auth/fb', 'SocialController@gotoFacebook')->name('gotoFacebook');
$this->get('auth/fb/callback', 'SocialController@returnFromFacebook')->name('returnFromFacebook');

Auth::routes();
$this->get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

$this->get('contact', 'ContactController@index')->name('contact');
$this->post('contact/send', 'ContactController@send')->name('contact.send');

// resource methods: index, create, store, show, edit, update, destroy

$this->group(['middleware' => 'auth'], function () {
    $this->get('orders/{date}/{user}/{account}', 'OrdersController@getDateUser');
    $this->resource('orders', 'OrdersController')->only('index', 'show', 'store');

    $this->get('lunchreport', 'ReportController@index')->name('lunchreport');
    $this->get('dolunchreport', 'ReportController@doReport')->name('dolunchreport');

    $this->post('myaccount/pay', 'MyAccountController@pay')->name('myaccount.pay');
    $this->get('myaccount/completepayment', 'MyAccountController@completePayment')->name('myaccount.completepayment');
    $this->get('myaccount/cancel', 'MyAccountController@cancel')->name('myaccount.cancelpayment');
    $this->get('myaccount/notify', 'MyAccountController@orders')->name('myaccount.notify');
    $this->get('myaccount/getMyAccountPaymentsDatatable', 'MyAccountController@getPaymentsDatatable')->name('myaccount.getMyAccountPaymentsDatatable');
    $this->post('myaccount/getMyAccountOrdersDatatable', 'MyAccountController@getOrdersDatatable')->name('myaccount.getMyAccountOrdersDatatable');
    $this->get('myaccount', 'MyAccountController@index')->name('myaccount');
});

$this->group(['middleware' => ['auth', 'viewbackend'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    $this->resource('gradelevels', 'Admin\GradeLevelsController');
    $this->resource('providers', 'Admin\ProvidersController');
    $this->resource('menuitems', 'Admin\MenuItemsController');
    $this->resource('nolunchexceptions', 'Admin\NoLunchExceptionsController');
    $this->resource('reports', 'Admin\ReportsController')->only('index', 'show');

    $this->post('payments/updateAll', 'Admin\PaymentsController@updateAll')->name('payments.updateAll');
    $this->resource('payments', 'Admin\PaymentsController');

    $this->post('accounts/getDatatable', 'Admin\AccountsController@getDatatable')->name('accounts.getDatatable');
    $this->resource('accounts', 'Admin\AccountsController');

    $this->post('users/getDatatable', 'Admin\UsersController@getDatatable')->name('users.getDatatable');
    $this->resource('users', 'Admin\UsersController');

    $this->get('lunchdates/getProviderMenuItemsForEdit', 'Admin\LunchDatesController@getProviderMenuItemsHTMLForEdit')->name('lunchdates.getMenuItemsForEdit');
    $this->get('lunchdates/getProviderMenuItemsForCreate', 'Admin\LunchDatesController@getProviderMenuItemsHTMLForCreate')->name('lunchdates.getMenuItemsForCreate');
    $this->resource('lunchdates', 'Admin\LunchDatesController', ['except' => ['create', 'destroy']]);

//    $this->get('ordermaint/transfer', 'Admin\OrderMaintController@getTransferNames');
//    $this->post('ordermaint/transfer', 'Admin\OrderMaintController@postTransfer');
    $this->post('ordermaint/lunchdatelocktoggle', 'Admin\OrderMaintController@postLunchDateLockToggle')->name('ordermaint.toggle');
    $this->resource('ordermaint', 'Admin\OrderMaintController')->only('index', 'show');

//    $this->get('utilities', 'Admin\UtilityController@index');
//    $this->get('utilities/updateallcreditsdebits', 'Admin\UtilityController@updateAllCreditsDebits');
});