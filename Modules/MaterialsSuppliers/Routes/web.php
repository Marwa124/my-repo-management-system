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

// Route::prefix('materialssuppliers')->group(function() {
Route::group(['as' => 'materialssuppliers.admin.', 'prefix' => 'admin/materialssuppliers', 'namespace' => 'Admin', 'middleware' => ['auth']],function() {
     // Suppliers
     Route::delete('suppliers/destroy', 'SuppliersController@massDestroy')->name('suppliers.massDestroy');
     Route::resource('suppliers', 'SuppliersController');

        // Customer Groups
    Route::delete('customer-groups/destroy', 'CustomerGroupsController@massDestroy')->name('customer-groups.massDestroy');
    Route::post('customer-groups/media', 'CustomerGroupsController@storeMedia')->name('customer-groups.storeMedia');
    Route::post('customer-groups/ckmedia', 'CustomerGroupsController@storeCKEditorImages')->name('customer-groups.storeCKEditorImages');
    Route::resource('customer-groups', 'CustomerGroupsController');

});
