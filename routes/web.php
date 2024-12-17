<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EdituserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductShoeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PersonaluserxController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportOrderController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\FinaceController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\EditshippingController;
use App\Http\Controllers\EditinController;
use App\Http\Controllers\EditfiController;
use App\Http\Controllers\ProductHomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\DocumentfanController;


// Route::get('/', function () {
//     return view('auth.login');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/userhome/products', [ProductShoeController::class, 'products'])->name('userhome.products');
    Route::get('/userhome/products/category/{categoryId}', [ProductShoeController::class, 'productsByCategory'])->name('userhome.productsByCategory');
    Route::post('/userhome/search', [ProductShoeController::class, 'search'])->name('userhome.search');
    Route::get('/autocomplete', [ProductShoeController::class, 'autocomplete'])->name('userhome.autocomplete');
    Route::get('/userhome/products/{id}', [ProductShoeController::class, 'show'])->name('userhome.show');
    Route::get('/userhome/updateStock/{id}', [ProductShoeController::class, 'updateStock'])->name('userhome.updateStock');


    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');


    Route::get('/myprofileuser', [PersonaluserxController::class, 'show'])->name('myprofileuser.show');
    Route::get('/myprofileuser/edit', [PersonaluserxController::class, 'edit'])->name('myprofileuser.edit');
    Route::put('/myprofileuser', [PersonaluserxController::class, 'update'])->name('myprofileuser.update');


    Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('order/success/{orderId}', [OrderController::class, 'success'])->name('order.success');
    Route::post('/update-address', [OrderController::class, 'updateAddress']);


    Route::get('/order/history', [OrderHistoryController::class, 'index'])->name('order.history');
    Route::get('/order/{orderId}', [OrderHistoryController::class, 'show'])->name('order.details');


});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/edituser/users', [EdituserController::class, 'userall'])->name('edituser.userall');
    Route::get('/edituser/users/{id}/edit', [EdituserController::class, 'edit'])->name('edituser.edit');
    Route::put('/edituser/users/{id}', [EdituserController::class, 'update'])->name('edituser.update');
    Route::post('/edituser/search', [EdituserController::class, 'search'])->name('edituser.search'); 
    Route::delete('/edituser/delete/{id}', [EdituserController::class, 'destroy'])->name('edituser.destroy');

    Route::get('/editshiping/shiping', [EditshippingController::class, 'userall'])->name('editshiping.userall');
    Route::get('/editshiping/shiping/{id}/edit', [EditshippingController::class, 'edit'])->name('editshiping.edit');
    Route::put('/editshiping/shiping/{id}', [EditshippingController::class, 'update'])->name('editshiping.update');
    Route::post('/editshiping/search', [EditshippingController::class, 'search'])->name('editshiping.search'); 
    Route::delete('/editshiping/delete/{id}', [EditshippingController::class, 'destroy'])->name('editshiping.destroy');

    Route::get('/editinventory/users', [EditinController::class, 'userall'])->name('editinventory.userall');
    Route::get('/editinventory/users/{id}/edit', [EditinController::class, 'edit'])->name('editinventory.edit');
    Route::put('/editinventory/users/{id}', [EditinController::class, 'update'])->name('editinventory.update');
    Route::post('/editinventory/search', [EditinController::class, 'search'])->name('editinventory.search'); 
    Route::delete('/editinventory/delete/{id}', [EditinController::class, 'destroy'])->name('editinventory.destroy');

    Route::get('/editfincereport/users', [EditfiController::class, 'userall'])->name('editfincereport.userall');
    Route::get('/editfincereport/users/{id}/edit', [EditfiController::class, 'edit'])->name('editfincereport.edit');
    Route::put('/editfincereport/users/{id}', [EditfiController::class, 'update'])->name('editfincereport.update');
    Route::post('/editfincereport/search', [EditfiController::class, 'search'])->name('editfincereport.search'); 
    Route::delete('/editfincereport/delete/{id}', [EditfiController::class, 'destroy'])->name('editfincereport.destroy');

    Route::get('/myprofile', [PersonalController::class, 'show'])->name('myprofile.show');
    Route::get('/myprofile/edit', [PersonalController::class, 'edit'])->name('myprofile.edit');
    Route::put('/myprofile', [PersonalController::class, 'update'])->name('myprofile.update');


    Route::get('/edituser', [SidebarController::class, 'edituser'])->name('edituser');
    Route::get('/allpd', [SidebarController::class, 'allpd'])->name('allpd');


    Route::get('/myproduct/home', [ProductsController::class, 'index'])->name('myproduct.home');
    Route::get('/product/create', [ProductsController::class, 'create'])->name('myproduct.create');
    Route::post('/myproduct/insert', [ProductsController::class, 'insert'])->name('myproduct.insert');
    Route::get('/myproduct/edit/{id}', [ProductsController::class, 'edit'])->name('myproduct.edit');
    Route::post('/myproduct/update/{id}', [ProductsController::class, 'update'])->name('myproduct.update');
    Route::post('/myproduct/delete/{id}', [ProductsController::class, 'delete'])->name('myproduct.delete');
    Route::get('/filter-by-category/{categoryId}', [ProductsController::class, 'filterByCategory'])->name('myproduct.filterByCategory');
    Route::post('/search', [ProductsController::class, 'search'])->name('myproduct.search');

    Route::get('/sales-report', [ReportController::class, 'generateSalesReport']);
    Route::get('/report/report-order', [ReportOrderController::class, 'index'])->name('report.report-order.index');
    Route::get('/report/order-details/{orderId}', [ReportOrderController::class, 'show'])->name('report.order-details');
    Route::post('/report/orders/update/{orderId}', [ReportOrderController::class, 'update'])->name('update.order');
    Route::get('/report/reportall', [ReportOrderController::class, 'reportall'])->name('reportall');
    Route::get('/report/unpaid-orders', [ReportOrderController::class, 'unpaidOrders'])->name('report.unpaid-orders');
    Route::get('/report/pendingPaymentOrders', [ReportOrderController::class, 'pendingPaymentOrders'])->name('report.pendingPaymentOrders');
    Route::get('/report/pendingPaymentOrdersdetail/{orderId}', [ReportOrderController::class, 'pendingPaymentOrdersdetail'])->name('report.pendingPaymentOrdersdetail');
    Route::get('/report/unpaid-ordersdetail/{orderId}', [ReportOrderController::class, 'unpaidOrdersdetail'])->name('report.unpaid-ordersdetail');
    Route::get('/orders/{order}', [ReportOrderController::class, 'show'])->name('report.report-order.show');
    Route::get('/reports/Best_selling', [ReportOrderController::class, 'brandSalesReport'])->name('report.Best_selling');
  
    
    Route::get('/report/doc', [DocumentController::class, 'doc'])->name('report.doc');
    Route::post('/download-selected', [DocumentController::class, 'downloadSelected'])->name('download.selected');
    Route::get('/summary-by-date', [DocumentController::class, 'getSummaryByDate'])->name('summary.by.date');
    Route::get('/filter-orders', [DocumentController::class, 'filterOrders'])->name('filter.orders');

});

Route::middleware(['auth', 'finance'])->group(function () {
    Route::get('/finace', [FinaceController::class, 'index'])->name('finance');
    Route::post('/finacereport/orders/update/{orderId}', [FinaceController::class, 'update'])->name('finacereport.update');
    Route::get('/finacereport/pendingPaymentOrdersdetail/{orderId}', [FinaceController::class, 'unpaidOrdersdetail'])->name('finacereport.unpaid-ordersdetail');
    Route::get('/finacereport/pendingPaymentOrders', [FinaceController::class, 'unpaidOrders'])->name('finacereport.unpaid-orders');
    Route::get('/finacereport/reportall', [FinaceController::class, 'reportall'])->name('finacereport.reportall');
    Route::get('/finacereport/finacereport.history', [FinaceController::class, 'history'])->name('finacereport.history');
    Route::get('/finacereport/orders/status/{orderId}', [FinaceController::class, 'getStatus'])->name('finacereport.getStatus');

    Route::get('/myprofilefinace', [FinaceController::class, 'showfinace'])->name('myprofilefinace.showfinace');
    Route::get('/myprofilefinace/edit', [FinaceController::class, 'editfinace'])->name('myprofilefinace.editfinace');
    Route::put('/myprofilefinace', [FinaceController::class, 'updatfinace'])->name('myprofilefinace.updatefinace');

    Route::get('/doc', [DocumentfanController::class, 'doc'])->name('doc');
    Route::post('/downloadselected', [DocumentfanController::class, 'downloadSelected'])->name('download');
    Route::get('/summarybydate', [DocumentfanController::class, 'getSummaryByDate'])->name('summary.');
    Route::get('/filterorders', [DocumentfanController::class, 'filterOrders'])->name('filter');



});

Route::middleware(['auth', 'shipping'])->group(function () {
    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping');
    Route::post('/Shippingreport/orders/update/{orderId}', [ShippingController::class, 'update'])->name('Shippingreport.update');
    Route::get('/Shippingreport/pendingPaymentOrdersdetail/{orderId}', [ShippingController::class, 'pendingPaymentOrdersdetail'])->name('Shippingreport.pendingPaymentOrdersdetail');
    Route::get('/Shippingreport/pendingPaymentOrders', [ShippingController::class, 'pendingPaymentOrders'])->name('Shippingreport.pendingPaymentOrders');
    Route::get('/Shippingreport/reportall', [ShippingController::class, 'reportall'])->name('Shippingreport.reportall');
    Route::get('/Shippingreport/Shippingreport.history', [ShippingController::class, 'history'])->name('Shippingreport.history');
    Route::get('/Shippingreport/orders/status/{orderId}', [ShippingController::class, 'getStatus'])->name('Shippingreport.getStatus');

    Route::get('/myprofileshiping', [ShippingController::class, 'showshiping'])->name('myprofileshiping.showshiping');
    Route::get('/myprofileshiping/edit', [ShippingController::class, 'editshiping'])->name('myprofileshiping.editshiping');
    Route::put('/myprofileshiping', [ShippingController::class, 'updatshiping'])->name('myprofileshiping.updateshiping');
});

Route::middleware(['auth', 'inventory'])->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::get('/inventory/home', [InventoryController::class, 'indexz'])->name('inventory.home');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory/insert', [InventoryController::class, 'insert'])->name('inventory.insert');
    Route::get('/inventory/edit/{id}', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::post('/inventory/delete/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');
    Route::get('/inventory/filter-by-categorys/{categoryId}', [InventoryController::class, 'filterByCategorys'])->name('inventory.filterByCategorys');
    Route::post('/inventory/search', [InventoryController::class, 'search'])->name('inventory.search');

    Route::get('/myprofileinventory', [InventoryController::class, 'showinventory'])->name('myprofileinventory.showinventory');
    Route::get('/myprofileinventory/edit', [InventoryController::class, 'editinventory'])->name('myprofileinventory.editinventory');
    Route::put('/myprofileinventory', [InventoryController::class, 'updatinventory'])->name('myprofileinventory.updateinventory');

    
});
Route::get('/', [ProductHomeController::class, 'products']);
Route::get('/auth/ProductHome/category/{categoryId}', [ProductHomeController::class, 'productsByCategory'])->name('auth.productsByCategory');
Route::post('/auth/ProductHome/search', [ProductHomeController::class, 'search'])->name('auth.search');
Route::get('/autocomplete/ProductHome', [ProductHomeController::class, 'autocomplete'])->name('auth.autocomplete');
Route::get('/auth/ProductHome/{id}', [ProductHomeController::class, 'show'])->name('auth.show');


Route::get('auth/google', [SocialAuthController::class, 'redirectToProvider']);
Route::get('auth/google/callback', [SocialAuthController::class, 'handleProviderCallback']);
Route::get('/about', [SidebarController::class, 'about'])->name('about');
Route::get('/game', [GameController::class, 'index'])->name('game');
Route::post('/move', [GameController::class, 'move']);
Route::get('/snake', function () {
    return view('snake');
});


require __DIR__.'/auth.php';

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
