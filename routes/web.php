<?php

use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerSaleController;
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
use App\Http\Controllers\WholesaleProductController;
use App\Http\Controllers\ZarinpalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/',  [MainController::class, 'index'])->name('main.index');
Route::get('/contact',  [PageController::class, 'contact'])->name('page.contact');
Route::get('/sendsms',  [MainController::class, 'sendTestSms']);

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['fa', 'en', 'ar'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('lang.switch');

Route::prefix('product')->group(function () {
    Route::get('/{id}/{slug}', [ProductController::class, 'view'])->name('product.view');
});

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::get('/items', [CartController::class, 'show'])->name('cart.show');
    Route::post('/increase/{product}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/decrease/{product}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::middleware('auth')->group(function () {
        Route::get('/address', [CartController::class, 'address'])->name('cart.address');
        Route::post('/pay', [CartController::class, 'pay'])->name('cart.pay');
    });
});

Route::get('/pay/{order}', [ZarinpalController::class, 'pay'])->name('zarinpal.pay');
Route::get('/callback/zarinpal', [ZarinpalController::class, 'callback'])->name('zarinpal.callback');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.create');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    Route::prefix('hydrojoy')->name('hydrojoy.')->group(function () {
        Route::get('/login', [\App\Http\Controllers\hydrojoy\AuthController::class, 'login'])->name('login');
        Route::post('/login', [\App\Http\Controllers\hydrojoy\AuthController::class, 'authenticate'])->name('authenticate');
    });
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index'); // صفحه اصلی پروفایل
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');

    Route::prefix('password')->name('password.')->group(function () {
        Route::get('/', [ProfileController::class, 'editPassword'])->name('edit');
        Route::post('/', [ProfileController::class, 'updatePassword'])->name('update');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [ProfileController::class, 'orders'])->name('index');
        Route::get('/{id}', [ProfileController::class, 'orderDetails'])->name('details');
    });

    Route::prefix('install-requests')->name('install_requests.')->group(function () {
        Route::get('/', [UserInstallRequestController::class, 'index'])->name('index');
        Route::get('/create', [UserInstallRequestController::class, 'create'])->name('create');
        Route::post('/', [UserInstallRequestController::class, 'store'])->name('store');
    });

    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('/sell', [StoreController::class, 'sell'])->name('sell');
        Route::post('/sell', [StoreController::class, 'create'])->name('create');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['role:admin,manager,nasab,wholesaler,marketer,seller,personel'])->group(function () {
        Route::get('/', [MainController::class, 'admin'])->name('index');
        Route::resource('users', UserController::class);
        Route::resource('users.product-user', InventoryController::class);
        Route::resource('categories', CategoryController::class);

        Route::resource('products', ProductController::class);
        Route::post('/products/{product}/ai/generate', [ProductController::class, 'generateAiContent'])->name('products.ai.generate');

        Route::resource('sliders', SliderController::class);
        Route::resource('pages', PageController::class);
        Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.uploadImage');

        Route::prefix('letters')->name('letters.')->group(function () {
            Route::get('/', [LetterController::class, 'index'])->name('index');
            Route::get('/create', [LetterController::class, 'create'])->name('create');
            Route::post('/', [LetterController::class, 'store'])->name('store');
            Route::get('/{letter}', [LetterController::class, 'show'])->name('show');
            Route::post('/{letter}/refer', [LetterController::class, 'refer'])->name('refer');
            Route::post('/{letter}/attachments',[LetterController::class, 'storeAttachment'])->name('attachments.store');
            Route::delete('/attachments/{attachment}',[LetterController::class, 'destroyAttachment'])->name('attachments.destroy');
            Route::get('/attachments/{attachment}/download', [LetterController::class, 'downloadAttachment'])->name('attachments.download');
        });

        Route::resource('install_requests', InstallRequestController::class);
        Route::resource('install_schedules', InstallScheduleController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('periodic_services', PeriodicServiceController::class)->only(['index', 'update']);

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        });
    });
});

Route::middleware(['auth'])->prefix('wholesaler')->name('wholesaler.')->group(function () {

    Route::middleware(['role:wholesaler'])->group(function () {
        Route::get('/products', [WholesaleProductController::class, 'index'])->name('products');
        Route::post('/products', [WholesaleProductController::class, 'store'])->name('products.store');
    });

    Route::middleware(['role:wholesaler,marketer'])->group(function () {
        Route::get('/stores/list', [StoreSaleController::class, 'index'])->name('stores.index');
        Route::get('stores/{store}/sale', [StoreSaleController::class, 'create'])->name('stores.sale');
        Route::post('stores/{store}/sale', [StoreSaleController::class, 'store'])->name('stores.sale.store');
    });
});

Route::middleware(['auth','role:admin,seller'])->prefix('store')->name('store.')->group(function () {
    Route::get('/products', [StoreProductController::class, 'index'])->name('products');
    Route::post('/products', [StoreProductController::class, 'store'])->name('products.store');

    Route::get('customers/{customer}/sale', [CustomerSaleController::class, 'create'])->name('customers.sale');
    Route::post('customers/{customer}/sale', [CustomerSaleController::class, 'store'])->name('customers.sale.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/assembly', [AssemblyController::class, 'index'])->name('assembly.index');
    Route::post('/assembly', [AssemblyController::class, 'store'])->name('assembly.store');
});

Route::get('/{slug}',  [PageController::class, 'show'])->name('page.show');

