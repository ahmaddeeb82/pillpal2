<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\AdminCategoryController;
use App\Http\Controllers\Dashboard\AdminCompanyController;
use App\Http\Controllers\Dashboard\AdminMedicineController;
use App\Http\Controllers\Dashboard\AdminOrderController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorehouseController;
use App\Http\Controllers\UserController;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\Return_;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('ApiLocalize')->group(function () {
    /* ----------------- Authentication Routes ----------------- */
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', [AuthController::class,'logout']);
    /* ----------------- Order Routes ----------------- */
        Route::group(['prefix'=> 'orders'], function () {
            Route::post('add', [OrderController::class,'createOrder']);
            Route::get('list', [OrderController::class,'userOrders']);
            Route::get('detail', [OrderController::class,'orderDetails']);
            Route::delete('delete', [OrderController::class,'deleteOrder']);
        });
        /* ----------------- Profile Routes ----------------- */
        Route::group(['prefix'=> 'profile'], function () {
            Route::post('edit', [ProfileController::class,'editInfo']);
            Route::get('get', [ProfileController::class,'getInfo']);
            Route::post('set-image', [ProfileController::class,'setImage']);
            Route::delete('delete-image', [ProfileController::class,'deleteImage']);
            Route::post('change-password', [ProfileController::class,'changePassword']);
            });
            //----------------Company Routes--------------------------
        Route::group(['prefix'=>'companies'],function (){
            Route::get('detail',[CompanyController::class,'company']);
            Route::get('list',[CompanyController::class,'companies']);
            Route::get('home',[CompanyController::class,'companyForHome']);
        });
           //-----------------Category Routes---------------------------
        Route::group(['prefix'=>'categories'],function(){
            Route::get('detail',[CategoryController::class,'category']);
            Route::get('list',[CategoryController::class,'allCategories']);
            Route::get('home',[CategoryController::class,'categoriesForHome']);
        });
        //--------------------Medicine Routes---------------------------
        Route::group(['prefix'=>'medicines'],function(){
            Route::get('detail',[MedicineController::class,'medicineInfo']);
        });
        //--------------------Favorite Routes----------------------------
        Route::group(['prefix'=>'favorite'],function(){
            Route::post('add',[MedicineController::class,'addFavorite']);
            Route::get('get',[MedicineController::class,'userFavorites']);
            Route::delete('delete',[MedicineController::class,'deleteFavorite']);
        });
        //---------------------Search Route--------------------------------
        Route::get('search',[MedicineController::class,'searchByName']) ;   
        //---------------------Storehouse Route--------------------------------
        Route::get('storehouses', [StorehouseController::class,'storehouses']);
       
    });
});
//---------------------Admin Routes--------------------------------
Route::middleware('ApiLocalize')->group(function () {
Route::group(['prefix'=> 'admin'],function(){
    Route::post('login', [AdminController::class, 'login']);
    Route::middleware('auth:admin')->group(function () {
        Route::get('logout', [AdminController::class,'logout']);
        /* ----------------- Order Routes ----------------- */
        Route::group(['prefix' => 'orders'], function() {
            Route::get('list-in-preparation', [AdminOrderController::class, 'adminInPreparationOrders']);
            Route::get('list-sent', [AdminOrderController::class, 'adminSentOrders']);
            Route::get('list-delivered', [AdminOrderController::class, 'adminDeliveredOrders']);
            Route::get('detail', [AdminOrderController::class,'orderDetails']);
            Route::put('status', [AdminOrderController::class,'updateStatus']);
            Route::put('payment', [AdminOrderController::class,'updatePayment']);
            Route::get('in-preparation', [AdminOrderController::class,'inPreparationCounter']);
            Route::get('sent', [AdminOrderController::class,'sentCounter']);
            Route::get('delivered', [AdminOrderController::class,'deliveredCounter']);
        });
        //--------------------Companies Routes-------------------------------
        Route::group(['prefix' => 'companies'], function() {
            Route::post('add', [AdminCompanyController::class, 'addCompany']);
            Route::get('show', [AdminCompanyController::class, 'showCompanies']);
            Route::post('edit', [AdminCompanyController::class, 'editCompanyName']);
        });
        //---------------------Categories Routes---------------------------------------
        Route::group(['prefix' => 'categories'], function() {
            Route::post('add', [AdminCategoryController::class, 'addCategory']);
            Route::get('show', [AdminCategoryController::class, 'showCategories']);
            Route::post('edit', [AdminCategoryController::class, 'editCategory']);
        });
        //---------------------Medicines Routes---------------------------------------
        Route::group(['prefix' => 'medicines'], function() {
            Route::post('add', [AdminMedicineController::class, 'addmedicine']);
            // Route::get('show', [AdminCategoryController::class, 'showCategories']);
            // Route::post('edit', [AdminCategoryController::class, 'editCategory']);
        });
    });
});
});
//---------------------SuperAdmins Route--------------------------------
Route::group(['prefix'=> 'superadmin'],function(){
    Route::post('login', [AdminController::class, 'superLogin']);
    Route::middleware('auth:superadmin')->group(function () {
        Route::post('add',[AdminController::class,'addAdmin']);
        Route::get('logout', [AdminController::class,'logout']);
    });
});

