<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ManageBookingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\CheckRoomController;
use App\Http\Controllers\KaramisoController;
use App\Http\Controllers\NabezoController;
use App\Http\Controllers\ShabuController;
use App\Http\Controllers\SukiyakiController;
use App\Http\Controllers\TonkotsuController;
use App\Http\Controllers\KinokoController;

use Illuminate\Support\Facades\Auth;

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


Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['middleware'=>'PreventBackHistory'])->group(function () {
    Auth::routes();
});


Route::get('/home', [HomeController::class, 'index'])->name('home');
 //!-----------------------------------------Modal---------------------------------------
 Route::get('/getModalDetails',[AdminController::class,'getModalDetails'])->name('get.modal.details');
 Route::post('/updateModalDetails',[AdminController::class, 'updateModalDetails'])->name('update.modal.details');

  //!-----------------------------------------FullCalendar-------------------------------
  Route::get('index',[FullCalendarController::class,'index'])->name('index');
  Route::get('/getBookingIndex',[FullCalendarController::class,'getBookingIndex'])->name('get.booking.index');
  Route::get('/getBookingIndexDetails',[FullCalendarController::class,'getBookingIndexDetails'])->name('get.booking.index.details');

//!-----------------------------------------Karamiso Index Room---------------------------------------
Route::get('/karamiso',[KaramisoController::class,'index'])->name('get.karamiso');
Route::get('/getBookingKara',[KaramisoController::class,'getBookingKara'])->name('get.booking.kara');
Route::get('/getBookingKara2Test',[KaramisoController::class,'getBookingKara2TEST'])->name('get.booking.kara.test');
Route::post('/deleteBookingKaramiso',[KaramisoController::class,'deleteBookingKaramiso'])->name('delete.booking.karamiso');
Route::post('/verifyMeeting',[KaramisoController::class, 'verifyMeeting'])->name('verify.meeting');
Route::get('/notiModal',[KaramisoController::class,'getNotiModal'])->name('karamiso.noti.modal');

//!-----------------------------------------Nabezo Index Room---------------------------------------
Route::get('/nabezo',[NabezoController::class,'index'])->name('get.nabezo');
Route::get('/getBookingNabezo',[NabezoController::class,'getBookingNabe'])->name('get.booking.nabezo');
Route::get('/getBookingNabe2Test',[NabezoController::class,'getBookingNabe2TEST'])->name('get.booking.nabezo.test');
Route::post('/deleteBookingNabezo',[NabezoController::class,'deleteBookingNabezo'])->name('delete.booking.nabezo');
Route::post('/verifyMeeting',[NabezoController::class, 'verifyMeeting'])->name('verify.meeting');

//!-----------------------------------------Tonkotsu Index Room---------------------------------------
Route::get('/tonkotsu',[TonkotsuController::class,'index'])->name('get.tonkotsu');
Route::get('/getBookingTonkotsu',[TonkotsuController::class,'getBookingTonkotsu'])->name('get.booking.tonkotsu');
Route::get('/getBookingTonkotsu2Test',[TonkotsuController::class,'getBookingTonkotsu2TEST'])->name('get.booking.tonkotsu.test');
Route::post('/deleteBookingTonkotsu',[TonkotsuController::class,'deleteBookingTonkotsu'])->name('delete.booking.tonkotsu');
Route::post('/verifyMeeting',[TonkotsuController::class, 'verifyMeeting'])->name('verify.meeting');

//!-----------------------------------------Sukiyaki Index Room---------------------------------------
Route::get('/sukiyaki',[SukiyakiController::class,'index'])->name('get.sukiyaki');
Route::get('/getBookingSukiyaki',[SukiyakiController::class,'getBookingSukiyaki'])->name('get.booking.sukiyaki');
Route::get('/getBookingSukiyaki2Test',[SukiyakiController::class,'getBookingSukiyaki2TEST'])->name('get.booking.sukiyaki.test');
Route::post('/deleteBookingSukiyaki',[SukiyakiController::class,'deleteBookingSukiyaki'])->name('delete.booking.sukiyaki');
Route::post('/verifyMeeting',[SukiyakiController::class, 'verifyMeeting'])->name('verify.meeting');

//!-----------------------------------------Shabushabu Index Room---------------------------------------
Route::get('/shabushabu',[ShabuController::class,'index'])->name('get.shabu');
Route::get('/getBookingShabu',[ShabuController::class,'getBookingShabu'])->name('get.booking.shabu');
Route::get('/getBookingShabu2Test',[ShabuController::class,'getBookingShabu2TEST'])->name('get.booking.shabu.test');
Route::post('/deleteBookingShabu',[ShabuController::class,'deleteBookingShabu'])->name('delete.booking.shabu');
Route::post('/verifyMeeting',[ShabuController::class, 'verifyMeeting'])->name('verify.meeting');

//!-----------------------------------------Kinoko Index Room---------------------------------------
Route::get('/kinoko',[KinokoController::class,'index'])->name('get.kinoko');
Route::get('/getBookingKinoko',[KinokoController::class,'getBookingKinoko'])->name('get.booking.kinoko');
Route::get('/getBookingKinoko2Test',[KinokoController::class,'getBookingKinoko2TEST'])->name('get.booking.kinoko.test');
Route::post('/deleteBookingKinoko',[KinokoController::class,'deleteBookingKinoko'])->name('delete.booking.kinoko');
Route::post('/verifyMeeting',[KinokoController::class, 'verifyMeeting'])->name('verify.meeting');



Route::group(['prefix'=>'admin', 'middleware'=>['isAdmin','auth','PreventBackHistory']], function(){
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
        Route::get('/profile',[AdminController::class,'profile'])->name('admin.profile');
        Route::post('update-profile-info',[AdminController::class,'updateInfo'])->name('adminUpdateInfo');
        Route::post('change-profile-picture',[AdminController::class,'updatePicture'])->name('adminPictureUpdate');
        Route::post('change-password',[AdminController::class,'changePassword'])->name('adminChangePassword');

        //!-----------------------------------------Room---------------------------------------
        Route::get('/room',[RoomController::class,'index'])->name('admin.room');
        Route::post('/add-room',[RoomController::class,'addRoom'])->name('add.room');
        Route::get('/getRoomList', [RoomController::class,'getRoomList'])->name('get.room.list');
        Route::post('/getRoomDetails',[RoomController::class,'getRoomDetails'])->name('get.room.details');
        Route::post('/updateRoomDetails',[RoomController::class, 'updateRoomDetails'])->name('update.room.details');
        Route::post('/deleteRoom',[RoomController::class,'deleteRoom'])->name('delete.room');
        
         //!-----------------------------------------Manage Booking----------------------------------*/
        Route::get('/booking',[ManageBookingController::class,'index'])->name('admin.booking');
        Route::post('/add-booking',[ManageBookingController::class,'addBooking'])->name('add.booking');
        Route::get('/getBookingList',[ManageBookingController::class,'getBookingList'])->name('get.booking.list');
        Route::post('/getBookingDetails',[ManageBookingController::class,'getBookingDetails'])->name('get.booking.details');
        Route::post('/updateBookingDetails',[ManageBookingController::class, 'updateBookingDetails'])->name('update.booking.details');
        Route::post('/verifyBookingDetails',[ManageBookingController::class, 'verifyBookingDetails'])->name('verify.booking.details');
        Route::post('/cancleBookingDetails',[ManageBookingController::class, 'cancleBookingDetails'])->name('cancle.booking.details');
        Route::post('/deleteBooking',[ManageBookingController::class,'deleteBooking'])->name('delete.booking');

        //!-----------------------------------------Report Booking----------------------------------*/
        Route::get('/getReportList',[ReportController::class,'getReportList'])->name('get.report.list');
        Route::post('/getReportDetails',[ReportController::class,'getReportDetails'])->name('get.report.details');
        Route::post('/deleteReport',[ReportController::class,'deleteReport'])->name('delete.report');

         //!-----------------------------------------Accessories-----------------------------*/
        Route::get('/accessories',[AccessoriesController::class,'index'])->name('admin.accessories');
        Route::post('/add-acc',[AccessoriesController::class,'addAcc'])->name('add.accessories');
        Route::get('/getAccList', [AccessoriesController::class,'getAccList'])->name('get.accessories.list');
        Route::post('/getAccDetails',[AccessoriesController::class,'getAccDetails'])->name('get.accessories.details');
        Route::post('/updateAccDetails',[AccessoriesController::class, 'updateAccDetails'])->name('update.accessories.details');
        Route::post('/deleteAcc',[AccessoriesController::class,'deleteAcc'])->name('delete.accessories');
          //!-----------------------------------------User-----------------------------*/
        Route::get('/manageuser',[DepartmentController::class,'index'])->name('admin.manageuser');
        Route::post('/add-users',[ManageUserController::class,'addUser'])->name('add.user');
        Route::get('/getUserList', [ManageUserController::class,'getUserList'])->name('get.user.list');
        Route::post('/getUserDetails',[ManageUserController::class,'getUserDetails'])->name('get.user.details');
        Route::post('/updateUserDetails',[ManageUserController::class, 'updateUserDetails'])->name('update.user.details');
        Route::post('/deleteUser',[ManageUserController::class,'deleteUser'])->name('delete.user');
          //!-----------------------------------------Department-----------------------------*/
        Route::post('/add-department',[DepartmentController::class,'addDepartment'])->name('add.department');
        Route::get('/getDepartment', [DepartmentController::class,'getDepartmentList'])->name('get.department.list');
        Route::post('/getDepartmentDetails',[DepartmentController::class,'getDepartmentDetails'])->name('get.department.details');
        Route::post('/updateDepartmentDetails',[DepartmentController::class, 'updateDepartmentDetails'])->name('update.department.details');
        Route::post('/deleteDepartment',[DepartmentController::class,'deleteDepartment'])->name('delete.department');
       
});

    Route::group(['prefix'=>'user', 'middleware'=>['isUser','auth','PreventBackHistory']], function(){
    Route::get('dashboard',[UserController::class,'index'])->name('user.dashboard');
    Route::get('profile',[UserController::class,'profile'])->name('user.profile');
    Route::get('settings',[UserController::class,'settings'])->name('user.settings');

          //!-----------------------------------------Booking----------------------------------*/
    Route::get('/booking',[BookingController::class,'index'])->name('user.booking');
    Route::post('/add-booking',[BookingController::class,'addUserBooking'])->name('user.add.booking');
    Route::get('/getBookingList',[BookingController::class,'getUserBookingList'])->name('user.get.booking.list');
    Route::post('/getUserBookingDetails',[BookingController::class,'getUserBookingDetails'])->name('user.get.booking.details');
    Route::post('/updateUserBookingDetails',[BookingController::class, 'updateUserBookingDetails'])->name('user.update.booking.details');
    Route::post('/deleteUserBooking',[BookingController::class,'deleteUserBooking'])->name('user.delete.booking');
    
          //!-----------------------------------------Check Room----------------------------------*/
    Route::get('/checkroom/{RoomID}', [CheckRoomController::class,'view'])->name('user.checkroom');
});
