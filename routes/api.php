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

Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/portal/voucher', 'PortalController@receiveVoucher');
Route::get('/portal/auto-connect', 'PortalController@checkAutoConnectDevice');
Route::get('/portal/device-access', 'PortalController@checkDeviceAccess');

Route::middleware('auth')->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    Route::post('/voucher-groups/{voucher_group}/archive', 'VoucherGroupController@archive')
        ->name('voucher-groups.archive');

    Route::prefix('dashboard')->group(function () {
        Route::get('/user-count', 'AnalyticsController@userCount');
        Route::get('/voucher-count', 'AnalyticsController@voucherCount');
        Route::get('/data-usage', 'AnalyticsController@dataUsage');
        Route::get('/monthly-trends', 'AnalyticsController@monthlyTrends');
        Route::get('/annual-trends', 'AnalyticsController@annualTrends');
    });

    Route::apiResources([
        'users' => 'UserController',
        'vouchers' => 'VoucherController',
        'plans' => 'PlanController',
        'voucher-groups' => 'VoucherGroupController',
    ]);

    Route::get('notifications/unread', 'NotificationController@unreadCount');
    Route::post('notifications/{notification}/read', 'NotificationController@markAdRead');
    Route::apiResource('notifications', 'NotificationController')->only('index', 'show', 'destroy');

    Route::put('/users/{user}/toggle-status', 'UserController@toggleStatus');

});

Route::get('/{any}', function () {
    return response('Page not found!', 404);
})->where('any', '.*');
