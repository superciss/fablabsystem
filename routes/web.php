<?php

use Illuminate\Support\Facades\Route;

use App\Models\Product;

use App\Http\Controllers\DesignController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Admin\OrdersController;

use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PurchaseController;

//staff
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffOrderController;
use App\Http\Controllers\Staff\StaffProductController;
use App\Http\Controllers\Staff\StaffSupplierController;
use App\Http\Controllers\Staff\StaffCategoryController;
use App\Http\Controllers\Staff\StaffInventoryController;
use App\Http\Controllers\Staff\StaffSupplyController;
use App\Http\Controllers\Staff\StaffReportController;
use App\Http\Controllers\Staff\StaffNotificationController;

//customer
use App\Http\Controllers\customer\CustomerDashboardController;
use App\Http\Controllers\customer\CustomerNotifyController;
use App\Http\Controllers\customer\CustomerShopController;
use App\Http\Controllers\customer\CustomerOrderController;
use App\Http\Controllers\customer\CartController;
use App\Http\Controllers\customer\CustomizationController;

//checker
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\PreventBackHistory;

// Landing page
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    $products = Product::with('category')->latest()->take(6)->get();

    return view('landingpage', compact('products'));
    
})->name('landingpage');


Route::get('/test-sms', [App\Http\Controllers\TestController::class, 'send']);



// Save design (guest only)
Route::post('/save-design', [DesignController::class, 'save'])
    ->middleware('guest')
    ->name('design.save');

// Guest routes
Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('login/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify.show');
    Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verify.code');
    Route::post('/resend-code', [AuthController::class, 'resendCode'])->name('resend.code');

    Route::get('/verify-phone', [AuthController::class, 'showVerify'])->name('phone.verify.show');
    Route::post('/verify-phone', [AuthController::class, 'verifyCode'])->name('phone.verify');
    Route::post('/resend-phone-code', [AuthController::class, 'resendCode'])->name('phone.resend');


    // Forgot password flow
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password.submit');

    Route::get('/verify-reset-code', [AuthController::class, 'showVerifyResetCodeForm'])->name('verify.reset.code');
    Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode'])->name('verify.reset.code.submit');

    Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('reset.password.form');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password.submit');
    Route::post('/resend-reset-code', [AuthController::class, 'resendResetCode'])->name('resend.reset.code');

});

// Authenticated routes
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin routes

Route::prefix('admin')->middleware([CheckRole::class.':admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory');
   // Route::get('/sales', [SaleController::class, 'index'])->name('admin.sale');
    Route::get('/users', [UserController::class, 'index'])->name('admin.user');
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.setting');

    // Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    // Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    // Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    // Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{prod}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{prod}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/notifications/count', [ProductController::class, 'count'])->name('notifications.counts');
    Route::get('/notifications/list', [ProductController::class, 'list'])->name('notifications.list');
    Route::get('/admin/notification', [ProductController::class, 'index_notify'])->name('notifications.index_notify');

    Route::get('/admin/raw-material', [App\Http\Controllers\Admin\RawMaterialController::class, 'index'])->name('admin.materials.index');
    Route::post('/admin/raw-material', [App\Http\Controllers\Admin\RawMaterialController::class, 'store'])->name('material.store');
    Route::put('/admin/raw-material/{prod}', [App\Http\Controllers\Admin\RawMaterialController::class, 'update'])->name('material.update');
    Route::delete('/admin/raw-material/{prod}', [App\Http\Controllers\Admin\RawMaterialController::class, 'destroy'])->name('material.destroy');


    Route::get('/machines', [App\Http\Controllers\admin\MachineController::class,'index'])->name('admin.machines.index');
    Route::post('/machines/store', [App\Http\Controllers\admin\MachineController::class,'store'])->name('machines.store');
    Route::put('/machines/{id}', [App\Http\Controllers\admin\MachineController::class,'update'])->name('machines.update');
    Route::delete('/machines/{id}', [App\Http\Controllers\admin\MachineController::class,'destroy'])->name('machines.destroy');



    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    //bulk Actions
     Route::post('orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
    Route::post('orders/bulk-update', [OrderController::class, 'bulkUpdate'])->name('orders.bulkUpdate');
    Route::post('orders/bulk-approve', [OrderController::class, 'bulkApprove'])->name('orders.bulkApprove');
    Route::get('/history', [App\Http\Controllers\admin\OrderHistoryController::class, 'index'])->name('admin.history.index');

    ////// order 2

     Route::get('/adminorder', [OrdersController::class, 'index'])->name('admin.order.index');
    Route::post('/adminorder', [OrdersController::class, 'store'])->name('adminorder.store');
    Route::put('/adminorder/{order}', [OrdersController::class, 'update'])->name('adminorder.update');
    Route::delete('/adminorder/{order}', [OrdersController::class, 'destroy'])->name('adminorder.destroy');

     Route::post('orders/bulk-deletes', [OrdersController::class, 'bulkDelete'])->name('adminorder.bulkDelete');
     Route::post('orders/bulk-updates', [OrdersController::class, 'bulkUpdate'])->name('adminorder.bulkUpdate');
     Route::post('orders/bulk-approves', [OrdersController::class, 'bulkApprove'])->name('adminorder.bulkApprove');
     Route::post('/admin/orders/bulk-paid', [OrderController::class, 'bulkPaid'])->name('adminorder.bulkPaid');



    Route::get('/orderitems', [OrderItemController::class, 'index'])->name('admin.orderitem.index');
    Route::post('/orderitems', [OrderItemController::class, 'store'])->name('orderitems.store');
    Route::put('/orderitems/{order}', [OrderItemController::class, 'update'])->name('orderitems.update');
    Route::delete('/orderitems/{order}', [OrderItemController::class, 'destroy'])->name('orderitems.destroy');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('admin.supplier.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

     Route::get('/sales', [SaleController::class, 'index'])->name('admin.sale.index');
    
     Route::get('pos', [App\Http\Controllers\admin\PosController::class, 'index'])->name('admin.sale.pos');
    Route::post('pos/store', [App\Http\Controllers\admin\PosController::class, 'store'])->name('pos.store');
    Route::post('pos/onlinepay', [App\Http\Controllers\admin\PosController::class, 'onlinepay'])->name('pos.onlinepay');

    
   Route::get('/categories', [CategoryController::class, 'index'])->name('admin.category.index');
   Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
   Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
   Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('notifications', [NotificationController::class, 'index'])->name('admin.notification.index');
    Route::post('notifications/send/{orderId}', [NotificationController::class, 'sendNotification'])->name('notification.send');
    Route::post('/notifications/bulk-send', [NotificationController::class, 'bulkSend'])->name('notification.bulkSend');
    Route::post('/notifications/bulk-delete', [NotificationController::class, 'bulkDelete'])->name('notification.bulkDelete');
    Route::post('/orders/{order}/send-sms', [NotificationController::class, 'sendOrderSms'])->name('orders.sendSms');
   

      Route::get('/purchases', [PurchaseController::class,'index'])->name('admin.purchase.index');
    Route::post('/purchases/store', [PurchaseController::class,'store'])->name('purchases.store');
    Route::put('/purchases/{purchase}', [PurchaseController::class,'update'])->name('purchases.update');
    Route::delete('/purchases/{purchase}', [PurchaseController::class,'destroy'])->name('purchases.destroy');

     Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class,'index'])->name('admin.report.index');

       Route::get('/customized-products', [App\Http\Controllers\Admin\CustomizedProductController::class, 'index'])->name('admin.customized.index');
       Route::delete('/customized/{id}', [App\Http\Controllers\Admin\CustomizedProductController::class,'destroy'])->name('customize.destroy');
       Route::patch('/customize/{id}/toggle-approval', [App\Http\Controllers\Admin\CustomizedProductController::class, 'toggleApproval'])->name('customize.toggleApproval');
       Route::patch('/customized/update-order/{id}', [App\Http\Controllers\Admin\CustomizedProductController::class, 'updateOrder'])->name('customize.updateOrder');
      Route::post('/customize/{id}/send-sms', [App\Http\Controllers\Admin\CustomizedProductController::class, 'sendCustomizeSms'])
    ->name('customize.sendSms');


    //    Route::post('/calculate-price', [App\Http\Controllers\Admin\CustomizedProductController::class, 'calculatePrice'])->name('calculate.price');


       Route::get('/textures', [App\Http\Controllers\Admin\TextureController::class,'index'])->name('admin.texture.index');
       Route::post('/textures/store', [App\Http\Controllers\Admin\TextureController::class,'store'])->name('textures.store');
       Route::put('/textures/{id}', [App\Http\Controllers\Admin\TextureController::class,'update'])->name('textures.update');
       Route::delete('/textures/{id}', [App\Http\Controllers\Admin\TextureController::class,'destroy'])->name('textures.destroy');

       Route::get('/personal-designs', [App\Http\Controllers\admin\CustomDesignController::class, 'index'])->name('personal_designs.index');
       Route::post('/personal-designs/{id}/approve',[App\Http\Controllers\admin\CustomDesignController::class, 'approve'])->name('personal_designs.approve');
       Route::put('/personal-designs/{id}/update-price', [App\Http\Controllers\admin\CustomDesignController::class, 'updatePrice'])->name('personal_designs.update-price');
       Route::delete('/personal-designs/{id}',[App\Http\Controllers\admin\CustomDesignController::class, 'destroy'])->name('personal_designs.destroy');
       Route::post('/personal-designs/{id}/send-sms', [App\Http\Controllers\admin\CustomDesignController::class, 'sendPersonalSms'])->name('personal_designs.send-sms');

});

    // Staff routes
    Route::prefix('staff')->middleware([CheckRole::class.':staff'])->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

        Route::get('/stafforders', [StaffOrderController::class, 'index'])->name('staff.orders.index');
        Route::post('/stafforders', [StaffOrderController::class, 'store'])->name('stafforders.store');
        Route::put('/stafforders/{order}', [StaffOrderController::class, 'update'])->name('stafforders.update');
        Route::delete('/stafforders/{order}', [StaffOrderController::class, 'destroy'])->name('stafforders.destroy'); 
        Route::post('/stafforders/bulk-update', [StaffOrderController::class, 'bulkUpdate'])->name('stafforders.bulkUpdate');
        Route::post('/stafforders/bulk-delete', [StaffOrderController::class, 'bulkDelete'])->name('stafforders.bulkDelete');


        Route::get('staffproduct', [StaffProductController::class, 'index'])->name('staff.product.index');
        Route::post('staffproduct', [StaffProductController::class, 'store'])->name('staffproduct.store');
        Route::put('staffproduct/{product}', [StaffProductController::class, 'update'])->name('staffproduct.update');
        Route::delete('staffproduct/{product}', [StaffProductController::class, 'destroy'])->name('staffproduct.destroy');

        Route::get('/machine', [App\Http\Controllers\staff\StaffMachineController::class,'index'])->name('staff.machine.index');
        Route::post('/machine/store', [App\Http\Controllers\staff\StaffMachineController::class,'store'])->name('machine.store');
        Route::put('/machine/{id}', [App\Http\Controllers\staff\StaffMachineController::class,'update'])->name('machine.update');
        Route::delete('/machine/{id}', [App\Http\Controllers\staff\StaffMachineController::class,'destroy'])->name('machine.destroy');



        Route::get('/paysupply', [StaffSupplierController::class, 'index'])->name('staff.paysupply.index');
        Route::post('/paysupply', [StaffSupplierController::class, 'store'])->name('paysupply.store');
        Route::put('/paysupply/{purchase}', [StaffSupplierController::class, 'update'])->name('paysupply.update');
        Route::delete('/paysupply/{purchase}', [StaffSupplierController::class, 'destroy'])->name('paysupply.destroy');

        Route::get('/staffsupplier', [StaffSupplyController::class, 'index'])->name('staff.suppliers.index');
        Route::post('/staffsupplier', [StaffSupplyController::class, 'store'])->name('staffsupplier.store');
        Route::put('/staffsupplier/{supplier}', [StaffSupplyController::class, 'update'])->name('staffsupplier.update');
        Route::delete('/staffsupplier/{supplier}', [StaffSupplyController::class, 'destroy'])->name('staffsupplier.destroy');

        Route::get('/staffcategories', [StaffCategoryController::class, 'index'])->name('staff.categories.index');
        Route::post('/staffcategories', [StaffCategoryController::class, 'store'])->name('staffcategories.store');
        Route::put('/staffcategories/{category}', [StaffCategoryController::class, 'update'])->name('staffcategories.update');
        Route::delete('/staffcategories/{category}', [StaffCategoryController::class, 'destroy'])->name('staffcategories.destroy');

        Route::get('/staffinventory', [StaffInventoryController::class, 'index'])->name('staff.inventories.index');
        Route::post('/staffinventory', [StaffInventoryController::class, 'store'])->name('staffinventory.store');
        Route::put('/staffinventory/{inventory}', [StaffInventoryController::class, 'update'])->name('staffinventory.update');
        Route::delete('/staffinventory/{inventory}', [StaffInventoryController::class, 'destroy'])->name('staffinventory.destroy');

        Route::get('/staffreport', [StaffReportController::class, 'index'])->name('staff.reports.index');

        Route::get('pos', [App\Http\Controllers\staff\StaffSaleController::class, 'index'])->name('staff.sale.index');
        Route::post('pos/store', [App\Http\Controllers\staff\StaffSaleController::class, 'store'])->name('sale.store');
        Route::post('pos/onlinepay', [App\Http\Controllers\staff\StaffSaleController::class, 'onlinepay'])->name('sale.onlinepay');

          Route::get('/staffnotification', [StaffNotificationController::class, 'index'])->name('staff.notification.index');
          Route::post('/notifications/send/{order}', [StaffNotificationController::class, 'sendMessage'])->name('notify.send');
          Route::post('/notify/bulk-send', [StaffNotificationController::class, 'bulkSend'])->name('notify.bulkSend');
          Route::post('/notify/bulk-delete', [StaffNotificationController::class, 'bulkDelete'])->name('notify.bulkDelete');
        Route::post('/notifications/sms/{order}', [StaffNotificationController::class, 'sendSms'])->name('notify.sendSms');

        Route::get('/customized-products', [App\Http\Controllers\staff\StaffCustomizeController::class, 'index'])->name('staff.customize.index');
       Route::delete('/customized/{id}', [App\Http\Controllers\staff\StaffCustomizeController::class,'destroy'])->name('customized.destroy');

        Route::get('/personal-designs', [App\Http\Controllers\staff\StaffCustomDesignController::class, 'index'])->name('personal_design.index');
       Route::post('/personal-designs/{id}/approve',[App\Http\Controllers\staff\StaffCustomDesignController::class, 'approve'])->name('personal_design.approve');
       Route::put('/personal-designs/{id}/update-price', [App\Http\Controllers\staff\StaffCustomDesignController::class, 'updatePrice'])->name('personal_design.update-price');
       Route::delete('/personal-designs/{id}',[App\Http\Controllers\staff\StaffCustomDesignController::class, 'destroy'])->name('personal_design.destroy');


       Route::get('/texture', [App\Http\Controllers\staff\StaffTextureController::class,'index'])->name('staff.textures.index');
       Route::post('/texture/store', [App\Http\Controllers\staff\StaffTextureController::class,'store'])->name('texture.store');
       Route::put('/texture/{id}', [App\Http\Controllers\staff\StaffTextureController::class,'update'])->name('texture.update');
       Route::delete('/texture/{id}', [App\Http\Controllers\staff\StaffTextureController::class,'destroy'])->name('texture.destroy');

       
        });

    // Customer routes
    Route::prefix('customer')->middleware([CheckRole::class.':customer'])->group(function () {
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/shop/{id}', [CustomerDashboardController::class, 'indexview'])->name('customer.indexview');
    Route::post('/shop/{id}/rating', [CustomerDashboardController::class, 'storeRating'])->name('customer.rating');


    // Route::get('/dashboard', [CustomerDashboardController::class, 'customerDashboard'])->name('customer.dashboard');

     Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'profile'])
         ->name('customer.profile.viewprofile');

     Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])
         ->name('profile.update');
            
        // Cart
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('customercart.add');
        Route::get('/cart', [CartController::class, 'index'])->name('customer.cart.index');
        Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('customercart.remove');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('customercart.checkout');
        Route::get('/cart/count', [CartController::class, 'cartCount'])->name('cart.count');
        Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])
    ->name('customercart.update');
        Route::post('/cart/remove-multiple', [CartController::class, 'bulkRemove'])->name('customercart.removeMultiple');
        Route::post('/cart/checkout-selected', [CartController::class, 'bulkCheckout'])->name('customercart.checkoutSelected');


        // Buy Now
        Route::post('/buy/{product}', [CustomerOrderController::class, 'buyNow'])->name('customershop.buy');

        // Order list
        Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('customer.orderlist.index');
        Route::post('customer/order/cancel/{order}', [CustomerOrderController::class, 'cancel'])->name('customerorder.cancel');


    Route::get('/customer/notifications', [CustomerNotifyController::class, 'index'])->name('customer.notifications.index');
    Route::post('/notifications/read/{id}', [CustomerNotifyController::class, 'markAsRead']);
    Route::get('/notifications/unread-count', [CustomerNotifyController::class, 'unreadCount'])->name('notifications.count');

     Route::get('/customizations', [CustomizationController::class, 'index'])
        ->name('customer.custom.index');

    Route::get('/customize', [CustomizationController::class, 'customizeditem'])
        ->name('customer.customized.index');

   Route::get('/shop/{id}/customize', [CustomizationController::class, 'customize'])->name('customershop.customize');

  
    Route::get('/customizer', [CustomizationController::class, 'create'])
        ->name('customer.custom.create');

    Route::post('/customizations', [App\Http\Controllers\customer\CustomizationController::class, 'store'])->name('customizations.store');
    Route::post('/customized-products/{id}/back-image', [CustomizationController::class, 'store1'])->name('customizations.store1');
    Route::post('/payment/pay', [CustomizationController::class, 'pay'])->name('payment.pay');
    Route::get('/customer/customized/pdf-data/{id}', [CustomizationController::class, 'getPdfData'])->name('customized.pdf.data');

     Route::get('/customized-products', [App\Http\Controllers\customer\CustomProductController::class, 'index'])->name('customer.customproduct.index');
     Route::post('/customized-pay-order', [App\Http\Controllers\customer\CustomProductController::class, 'pay'])->name('payment.order');

    Route::get('/customizations/{id}', [CustomizationController::class, 'show'])
        ->name('customer.custom.show');

    Route::post('/calculate-price', [CustomizationController::class, 'calculatePrice'])->name('calculate.price');

        // routes/web.php
      Route::get('/textures/json', [App\Http\Controllers\Customer\CustomerTextureController::class, 'listJson'])
     ->name('textures.json');

    Route::get('/personal-designs', [App\Http\Controllers\Customer\PersonalDesignController::class, 'index'])->name('personal-designs.index');
    Route::post('/personal-designs', [App\Http\Controllers\Customer\PersonalDesignController::class, 'store'])->name('personal-designs.store');
    Route::put('/personal-designs/{id}', [App\Http\Controllers\Customer\PersonalDesignController::class, 'update'])->name('personal-designs.update');
    Route::delete('/personal-designs/{id}', [App\Http\Controllers\Customer\PersonalDesignController::class, 'destroy'])->name('personal-designs.destroy');
    
    

    });
});