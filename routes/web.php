<?php

use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CustomerSaleController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\InstallRequestController;
use App\Http\Controllers\InstallScheduleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PeriodicServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\StoreSaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInstallRequestController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WholesaleProductController;
use App\Http\Controllers\WholesalerSaleController;
use App\Http\Controllers\ZarinpalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [MainController::class, 'index'])
    ->name('main.index');

Route::get('/contact', [PageController::class, 'contact'])
    ->name('page.contact');

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

Route::get('/sendsms', [MainController::class, 'sendTestSms']);


/*
|--------------------------------------------------------------------------
| Language
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function (string $locale) {

    if (in_array($locale, ['fa', 'en', 'ar'], true)) {
        Session::put('locale', $locale);
    }

    return back();

})->name('lang.switch');


/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::prefix('product')->group(function () {

    Route::get('/{id}/{slug}', [ProductController::class, 'view'])
        ->name('product.view');

});


/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/

Route::prefix('cart')
    ->name('cart.')
    ->group(function () {

        // Cart API / AJAX
        Route::get('/', [CartController::class, 'index'])
            ->name('index');

        Route::get('/items', [CartController::class, 'show'])
            ->name('show');

        Route::post('/add/{product}', [CartController::class, 'add'])
            ->name('add');

        Route::post('/increase/{product}', [CartController::class, 'increase'])
            ->name('increase');

        Route::post('/decrease/{product}', [CartController::class, 'decrease'])
            ->name('decrease');

        Route::post('/remove/{product}', [CartController::class, 'remove'])
            ->name('remove');

        Route::delete('/clear', [CartController::class, 'clear'])
            ->name('clear');

        Route::post('/check-referral', [CartController::class, 'checkReferral'])
            ->name('checkReferral');


        // Checkout
        Route::middleware('auth')->group(function () {

            Route::get('/address', [CartController::class, 'address'])
                ->name('address');

            Route::post('/pay', [CartController::class, 'pay'])
                ->name('pay');

        });

    });


/*
|--------------------------------------------------------------------------
| Payment
|--------------------------------------------------------------------------
*/

Route::get('/pay/{order}', [ZarinpalController::class, 'pay'])
    ->name('zarinpal.pay');

Route::get('/callback/zarinpal', [ZarinpalController::class, 'callback'])
    ->name('zarinpal.callback');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Register
    Route::get('/register', [AuthController::class, 'create'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.create');


    // Login
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'authenticate'])
        ->name('authenticate');


    // Hydrojoy Login
    Route::prefix('hydrojoy')
        ->name('hydrojoy.')
        ->group(function () {

            Route::get('/login', [
                \App\Http\Controllers\hydrojoy\AuthController::class,
                'login'
            ])->name('login');

            Route::post('/login', [
                \App\Http\Controllers\hydrojoy\AuthController::class,
                'authenticate'
            ])->name('authenticate');

        });

});


Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {

        Route::get('/', [ProfileController::class, 'index'])
            ->name('index');

        Route::get('/edit', [ProfileController::class, 'edit'])
            ->name('edit');

        Route::post('/update', [ProfileController::class, 'update'])
            ->name('update');


        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        Route::prefix('password')
            ->name('password.')
            ->group(function () {

                Route::get('/', [ProfileController::class, 'editPassword'])
                    ->name('edit');

                Route::post('/', [ProfileController::class, 'updatePassword'])
                    ->name('update');

            });


        /*
        |--------------------------------------------------------------------------
        | User Orders
        |--------------------------------------------------------------------------
        */

        Route::prefix('orders')
            ->name('orders.')
            ->group(function () {

                Route::get('/', [ProfileController::class, 'orders'])
                    ->name('index');

                Route::get('/{id}', [ProfileController::class, 'orderDetails'])
                    ->name('details');

            });


        /*
        |--------------------------------------------------------------------------
        | Installation Requests
        |--------------------------------------------------------------------------
        */

        Route::prefix('install-requests')
            ->name('install_requests.')
            ->group(function () {

                Route::get('/', [UserInstallRequestController::class, 'index'])
                    ->name('index');

                Route::get('/create', [UserInstallRequestController::class, 'create'])
                    ->name('create');

                Route::post('/', [UserInstallRequestController::class, 'store'])
                    ->name('store');

            });

        /*
        |--------------------------------------------------------------------------
        | Store Profile
        |--------------------------------------------------------------------------
        */

        Route::prefix('store')
            ->name('store.')
            ->group(function () {

                Route::get('/', [StoreController::class, 'index'])
                    ->name('index');

                Route::get('/sell', [StoreController::class, 'sell'])
                    ->name('sell');

                Route::post('/sell', [StoreController::class, 'create'])
                    ->name('create');

            });

    });


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::middleware([
            'role:admin,manager,installer,wholesaler,marketer,seller,personel'
        ])->group(function () {


            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get('/', [MainController::class, 'admin'])
                ->name('index');


            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */

            Route::resource('users', UserController::class);

            Route::resource(
                'users.product-user',
                InventoryController::class
            );

            Route::prefix('users/wallet')
                ->name('users.wallet.')
                ->group(function () {

                    Route::get('/{user}', [WalletController::class,'userWallet'])
                        ->name('index');

                });


            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            Route::resource('categories', CategoryController::class);


            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */

            Route::resource('products', ProductController::class);

            Route::patch(
                '/products/{product}/stock',
                [ProductController::class, 'updateStock']
            )->name('products.stock.update');

            Route::post(
                '/products/upload-image',
                [ProductController::class, 'uploadImage']
            )->name('products.uploadImage');


            /*
            |--------------------------------------------------------------------------
            | Commissions
            |--------------------------------------------------------------------------
            */

            Route::get('/commissions', [CommissionController::class, 'index'])
                ->name('commissions.index');


            /*
            |--------------------------------------------------------------------------
            | Sliders / Pages
            |--------------------------------------------------------------------------
            */

            Route::resource('sliders', SliderController::class);

            Route::resource('pages', PageController::class);


            /*
            |--------------------------------------------------------------------------
            | Letters
            |--------------------------------------------------------------------------
            */

            Route::prefix('letters')
                ->name('letters.')
                ->group(function () {

                    Route::get('/', [LetterController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [LetterController::class, 'create'])
                        ->name('create');

                    Route::post('/', [LetterController::class, 'store'])
                        ->name('store');

                    Route::get('/{letter}', [LetterController::class, 'show'])
                        ->name('show');

                    Route::post('/{letter}/refer', [LetterController::class, 'refer'])
                        ->name('refer');

                    Route::post(
                        '/{letter}/attachments',
                        [LetterController::class, 'storeAttachment']
                    )->name('attachments.store');

                    Route::delete(
                        '/attachments/{attachment}',
                        [LetterController::class, 'destroyAttachment']
                    )->name('attachments.destroy');

                    Route::get(
                        '/attachments/{attachment}/download',
                        [LetterController::class, 'downloadAttachment']
                    )->name('attachments.download');

                });


            /*
            |--------------------------------------------------------------------------
            | Installers
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'installers',
                InstallerController::class
            )->parameters([
                'installers' => 'user',
            ]);

            Route::patch(
                '/installers/{user}/approve',
                [InstallerController::class, 'approve']
            )->name('installers.approve');

            Route::patch(
                '/installers/{user}/reject',
                [InstallerController::class, 'reject']
            )->name('installers.reject');

            Route::get(
                '/installers/{user}/wholesalers',
                [InstallerController::class, 'wholesalers']
            )->name('installers.wholesalers');

            Route::put(
                '/installers/{user}/wholesalers',
                [InstallerController::class, 'syncWholesalers']
            )->name('installers.wholesalers.sync');

            /*
            |--------------------------------------------------------------------------
            | Installation
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/install_requests/create/{order}',
                [InstallRequestController::class, 'createFromOrder']
            )->name('install_requests.create_from_order');

            Route::post(
                '/install_requests/create/{order}',
                [InstallRequestController::class, 'storeFromOrder']
            )->name('install_requests.store_from_order');

            Route::get(
                '/service-requests',
                [InstallRequestController::class, 'serviceRequests']
            )->name('service_requests.index');

            Route::resource(
                'install_requests',
                InstallRequestController::class
            );

            Route::resource(
                'install_schedules',
                InstallScheduleController::class
            )->only([
                'index',
                'create',
                'store',
                'destroy',
            ]);

            Route::resource(
                'periodic_services',
                PeriodicServiceController::class
            )->only([
                'index',
                'update',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Orders
            |--------------------------------------------------------------------------
            */

            Route::prefix('orders')
                ->name('orders.')
                ->group(function () {

                    Route::get('/', [OrderController::class, 'index'])
                        ->name('index');

                    Route::get('/{order}', [OrderController::class, 'show'])
                        ->name('show');

                    Route::post(
                        '/{order}/status',
                        [OrderController::class, 'updateStatus']
                    )->name('updateStatus');

                });

        });

    });


/*
|--------------------------------------------------------------------------
| Wholesaler
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('wholesaler')
    ->name('wholesaler.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Wholesaler Only
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:wholesaler')->group(function () {

            // Purchase products
            Route::get('/products', [
                WholesaleProductController::class,
                'index'
            ])->name('products');

            Route::post('/products', [
                WholesaleProductController::class,
                'store'
            ])->name('products.store');


            // Purchase orders
            Route::get('/orders/purchases', [
                OrderController::class,
                'purchases'
            ])->name('orders.purchases');


            // Sales
            Route::get('/sales', [
                WholesalerSaleController::class,
                'index'
            ])->name('sales.index');

        });


        /*
        |--------------------------------------------------------------------------
        | Wholesaler / Marketer
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:wholesaler,marketer')->group(function () {

            Route::get('/stores/list', [
                StoreSaleController::class,
                'index'
            ])->name('stores.index');

            Route::get('/stores/{store}/sale', [
                StoreSaleController::class,
                'create'
            ])->name('stores.sale');

            Route::post('/stores/{store}/sale', [
                StoreSaleController::class,
                'store'
            ])->name('stores.sale.store');

        });

    });


/*
|--------------------------------------------------------------------------
| Store
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,seller',
])
    ->prefix('store')
    ->name('store.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Store Products / Purchases
        |--------------------------------------------------------------------------
        */

        Route::get('/products', [
            StoreProductController::class,
            'index'
        ])->name('products');

        Route::post('/products', [
            StoreProductController::class,
            'store'
        ])->name('products.store');

        Route::get('/orders/purchases', [
            OrderController::class,
            'purchases'
        ])->name('orders.purchases');


        /*
        |--------------------------------------------------------------------------
        | Store Sales
        |--------------------------------------------------------------------------
        */

        Route::get('/sales', [
            StoreSaleController::class,
            'sales'
        ])->name('sales.index');


        /*
        |--------------------------------------------------------------------------
        | Customer Sales
        |--------------------------------------------------------------------------
        */

        Route::get('/customers/{customer}/sale', [
            CustomerSaleController::class,
            'create'
        ])->name('customers.sale');

        Route::post('/customers/{customer}/sale', [
            CustomerSaleController::class,
            'store'
        ])->name('customers.sale.store');

    });


/*
|--------------------------------------------------------------------------
| Installer
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth',
    'role:installer',
])
    ->prefix('installer')
    ->name('installer.')
    ->group(function () {

        Route::get('/orders', [
            InstallScheduleController::class,
            'installerOrders'
        ])->name('orders.index');

        Route::get(
            '/install_schedules/{install_schedule}/report',
            [InstallScheduleController::class, 'report']
        )->name('install_schedules.report');

        Route::get(
            '/install_schedules/{install_schedule}/show/report',
            [InstallScheduleController::class, 'showReport']
        )->name('install-reports.show');

        Route::post(
            '/install_schedules/{install_schedule}/report',
            [InstallScheduleController::class, 'storeReport']
        )->name('install_schedules.report.store');

    });


/*
|--------------------------------------------------------------------------
| Assembly
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/assembly', [
        AssemblyController::class,
        'index'
    ])->name('assembly.index');

    Route::post('/assembly', [
        AssemblyController::class,
        'store'
    ])->name('assembly.store');


    /*
    |--------------------------------------------------------------------------
    | Wallet Profile
    |--------------------------------------------------------------------------
    */

    Route::prefix('wallet')
        ->name('wallet.')
        ->group(function () {

            Route::get('/', [WalletController::class,'index'])
                ->name('index');

        });

});


/*
|--------------------------------------------------------------------------
| Dynamic Pages
|--------------------------------------------------------------------------
|
| IMPORTANT:
| This route must remain at the end because it matches
| any single-level URL slug.
|--------------------------------------------------------------------------
*/

Route::get('/{slug}', [PageController::class, 'show'])
    ->name('page.show');