<?php

use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckRoomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FullCalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageBookingController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomDisplayController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['middleware' => 'PreventBackHistory'])->group(function () {
    Auth::routes();
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [RoomController::class, 'getAllRooms'])->name('all.room');
// !-----------------------------------------Modal---------------------------------------
Route::get('/getModalDetails', [AdminController::class, 'getModalDetails'])->name('get.modal.details');
Route::post('/updateModalDetails', [AdminController::class, 'updateModalDetails'])->middleware(['auth', 'isAdmin'])->name('update.modal.details');
Route::get('/notiModal', [AdminController::class, 'getNotice'])->name('notice.modal');

// !-----------------------------------------FullCalendar-------------------------------
Route::get('index', [FullCalendarController::class, 'index'])->name('index');
Route::get('/getBookingIndex', [FullCalendarController::class, 'getBookingIndex'])->name('get.booking.index');
Route::get('/getBookingIndexAdmin', [FullCalendarController::class, 'getBookingIndexAdmin'])->name('get.booking.index.admin');
Route::get('/getBookingIndexAdminV2', [FullCalendarController::class, 'getBookingIndexAdminV2'])->name('get.booking.index.admin.v2');
Route::get('/getBookingIndexDetails', [FullCalendarController::class, 'getBookingIndexDetails'])->name('get.booking.index.details');

Route::post('/verifyMeeting', [ManageBookingController::class, 'verifyMeeting'])->middleware('auth')->name('verify.meeting');

Route::get('/room/{room:slug}', [RoomDisplayController::class, 'show'])->name('room.show');
Route::get('/room/{room:slug}/upcoming', [RoomDisplayController::class, 'upcoming'])->name('room.upcoming');
Route::post('/room/{room:slug}/verify', [RoomDisplayController::class, 'verify'])->middleware('auth')->name('room.verify');

foreach (['karamiso', 'tonkotsu', 'sukiyaki', 'shabushabu', 'kinoko'] as $slug) {
    Route::redirect("/$slug", "/room/$slug", 301);
}
Route::redirect('/nabezo', '/', 301);

Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin', 'auth']], function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('update-profile-info', [AdminController::class, 'updateInfo'])->name('adminUpdateInfo');
    Route::post('change-profile-picture', [AdminController::class, 'updatePicture'])->name('adminPictureUpdate');
    Route::post('change-password', [AdminController::class, 'changePassword'])->name('adminChangePassword');

    // !-----------------------------------------Room---------------------------------------
    Route::get('/room', [RoomController::class, 'index'])->name('admin.room');
    Route::post('/add-room', [RoomController::class, 'addRoom'])->name('add.room');
    Route::get('/getRoomList', [RoomController::class, 'getRoomList'])->name('get.room.list');
    Route::post('/getRoomDetails', [RoomController::class, 'getRoomDetails'])->name('get.room.details');
    Route::post('/updateRoomDetails', [RoomController::class, 'updateRoomDetails'])->name('update.room.details');
    Route::post('/deleteRoom', [RoomController::class, 'deleteRoom'])->name('delete.room');

    // !-----------------------------------------Manage Booking----------------------------------*/
    Route::get('/booking', [ManageBookingController::class, 'index'])->name('admin.booking');
    Route::post('/add-booking', [BookingController::class, 'addUserBooking'])->name('add.booking');
    Route::get('/getBookingList', [ManageBookingController::class, 'getBookingList'])->name('get.booking.list');
    Route::post('/getBookingDetails', [ManageBookingController::class, 'getBookingDetails'])->name('get.booking.details');
    Route::post('/updateBookingDetails', [ManageBookingController::class, 'updateBookingDetails'])->name('update.booking.details');
    Route::post('/verifyBookingDetails', [ManageBookingController::class, 'verifyBookingDetails'])->name('verify.booking.details');
    Route::post('/cancleBookingDetails', [ManageBookingController::class, 'cancleBookingDetails'])->name('cancle.booking.details');
    Route::post('/deleteBooking', [ManageBookingController::class, 'deleteBooking'])->name('delete.booking');
    Route::post('/deleteSelectedBooking', [ManageBookingController::class, 'deleteSelectedBooking'])->name('delete.selected.booking');
    Route::get('/export', [ManageBookingController::class, 'exportExcel'])->name('export.selected.booking');

    // !-----------------------------------------Report Booking----------------------------------*/
    Route::get('/getReportList', [ReportController::class, 'getReportList'])->name('get.report.list');
    Route::post('/getReportDetails', [ReportController::class, 'getReportDetails'])->name('get.report.details');
    Route::post('/deleteReport', [ReportController::class, 'deleteReport'])->name('delete.report');
    Route::post('/deleteSelectedReports', [ReportController::class, 'deleteSelectedReports'])->name('delete.selected.reports');

    // !-----------------------------------------Accessories-----------------------------*/
    Route::get('/accessories', [AccessoriesController::class, 'index'])->name('admin.accessories');
    Route::post('/add-acc', [AccessoriesController::class, 'addAcc'])->name('add.accessories');
    Route::get('/getAccList', [AccessoriesController::class, 'getAccList'])->name('get.accessories.list');
    Route::post('/getAccDetails', [AccessoriesController::class, 'getAccDetails'])->name('get.accessories.details');
    Route::post('/updateAccDetails', [AccessoriesController::class, 'updateAccDetails'])->name('update.accessories.details');
    Route::post('/deleteAcc', [AccessoriesController::class, 'deleteAcc'])->name('delete.accessories');
    // !-----------------------------------------User-----------------------------*/
    Route::get('/manageuser', [DepartmentController::class, 'index'])->name('admin.manageuser');
    Route::post('/add-users', [ManageUserController::class, 'addUser'])->name('add.user');
    Route::get('/getUserList', [ManageUserController::class, 'getUserList'])->name('get.user.list');
    Route::post('/getUserDetails', [ManageUserController::class, 'getUserDetails'])->name('get.user.details');
    Route::post('/updateUserDetails', [ManageUserController::class, 'updateUserDetails'])->name('update.user.details');
    Route::post('/deleteUser', [ManageUserController::class, 'deleteUser'])->name('delete.user');
    // !-----------------------------------------Department-----------------------------*/
    Route::post('/add-department', [DepartmentController::class, 'addDepartment'])->name('add.department');
    Route::get('/getDepartment', [DepartmentController::class, 'getDepartmentList'])->name('get.department.list');
    Route::post('/getDepartmentDetails', [DepartmentController::class, 'getDepartmentDetails'])->name('get.department.details');
    Route::post('/updateDepartmentDetails', [DepartmentController::class, 'updateDepartmentDetails'])->name('update.department.details');
    Route::post('/deleteDepartment', [DepartmentController::class, 'deleteDepartment'])->name('delete.department');

});

Route::group(['prefix' => 'user', 'middleware' => ['isUser', 'auth', 'PreventBackHistory']], function () {
    Route::get('dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('settings', [UserController::class, 'settings'])->name('user.settings');

    // !-----------------------------------------Booking----------------------------------*/
    Route::get('/booking', [BookingController::class, 'index'])->name('user.booking');
    Route::post('/add-booking', [BookingController::class, 'addUserBooking'])->name('user.add.booking');
    Route::get('/getBookingList', [BookingController::class, 'getUserBookingList'])->name('user.get.booking.list');
    Route::post('/getUserBookingDetails', [BookingController::class, 'getUserBookingDetails'])->name('user.get.booking.details');
    Route::post('/updateUserBookingDetails', [BookingController::class, 'updateUserBookingDetails'])->name('user.update.booking.details');
    Route::post('/deleteUserBooking', [BookingController::class, 'deleteUserBooking'])->name('user.delete.booking');

    // !-----------------------------------------Check Room----------------------------------*/
    Route::get('/checkroom/{RoomID}', [CheckRoomController::class, 'view'])->name('user.checkroom');
});
