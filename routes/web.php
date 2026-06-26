<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MstBidangPegawaiController;
use App\Http\Controllers\MstChatAdminController;
use App\Http\Controllers\MstDataPegawaiController;
use App\Http\Controllers\MstDataPuskesmasController;
use App\Http\Controllers\MstGaleryController;
use App\Http\Controllers\MstGroupNavigationController;
use App\Http\Controllers\MstHakAksesNavController;
use App\Http\Controllers\MstHomeSettingController;
use App\Http\Controllers\MstJabatanPegawaiController;
use App\Http\Controllers\MstNavigationController;
use App\Http\Controllers\MstNewsController;
use App\Http\Controllers\MstOptionController;
use App\Http\Controllers\MstPublicInfoController;
use App\Http\Controllers\MstSliderController;
use App\Http\Controllers\MstStorageController;
use App\Http\Controllers\MstTahunController;
use App\Http\Controllers\Page\UserBeritaController;
use App\Http\Controllers\Page\UserHomeController;
use App\Http\Controllers\Page\UserProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2")->middleware('validate.redirect');
    Route::get('/forgotpassword', [AuthController::class, 'forgotpassword'])->name('password.index');
    Route::post('/forgotpassword', [AuthController::class, 'postforgotpassword'])->name('password.email')->middleware('validate.redirect');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetpassword'])->name('password.update')->middleware('validate.redirect');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'postregister'])->name('postregister')->middleware('validate.redirect');

    Route::get('/verify/{email}', [AuthController::class, 'verify'])->name('verify');

});


Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::put('/newpassword', [UserController::class, 'newpassword'])->name('newpassword')->middleware('role:admin,operator');


    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:admin,operator,user');

    //Manage User
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('role:admin,operator');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store')->middleware('role:admin,operator');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('role:admin,operator');
    Route::put('user/activate/{id}', [UserController::class, 'activate'])->name('user.activate')->middleware('role:admin,operator');
    Route::put('user/deactivate/{id}', [UserController::class, 'deactivate'])->name('user.deactivate')->middleware('role:admin,operator');
    Route::delete('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware('role:admin,operator');

    // Manage Pegawai
    Route::get('/penulis', [AuthorController::class, 'index'])->name('penulis.index')->middleware('role:admin, operator');
    Route::post('penulis/create', [AuthorController::class, 'store'])->name('penulis.store')->middleware('role:admin,operator');
    Route::put('penulis/update/{id}', [AuthorController::class, 'update'])->name('penulis.update')->middleware('role:admin,operator');
    Route::delete('penulis/delete/{id}', [AuthorController::class, 'delete'])->name('penulis.delete')->middleware('role:admin,operator');

    // Storage
    Route::get('/penyimpanan', [MstStorageController::class, 'index'])->name('storage.index')->middleware('role:admin, operator');
    Route::post('/penyimpanan', [MstStorageController::class, 'store'])->name('storage.store')->middleware('role:admin, operator');
    Route::delete('/penyimpanan/{id}', [MstStorageController::class, 'delete'])->name('storage.delete')->middleware('role:admin, operator');


    // Categories
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index')->middleware('role:admin,operator');
    Route::post('categories/create', [CategoriesController::class, 'store'])->name('categories.store')->middleware('role:admin,operator');
    Route::put('categories/update/{id}', [CategoriesController::class, 'update'])->name('categories.update')->middleware('role:admin,operator');
    Route::delete('categories/delete/{id}', [CategoriesController::class, 'delete'])->name('categories.delete')->middleware('role:admin,operator');


    // News
    Route::get('mstnews', [MstNewsController::class, 'index'])->name('mstnews.index')->middleware('role:admin,operator');
    // Route::get('mstnews/data', [MstNewsController::class, 'getData'])->name('mstnews.data')->middleware('role:admin,operator');
    Route::get('mstnews/detail/{id}', [MstNewsController::class, 'detail'])->name('mstnews.detail')->middleware('role:admin,operator');
    Route::get('mstnews/add', [MstNewsController::class, 'add'])->name('mstnews.add')->middleware('role:admin,operator');
    Route::post('mstnews/create', [MstNewsController::class, 'store'])->name('mstnews.store')->middleware('role:admin,operator');
    Route::get('mstnews/edit/{id}', [MstNewsController::class, 'edit'])->name('mstnews.edit')->middleware('role:admin,operator');
    Route::put('mstnews/update/{id}', [MstNewsController::class, 'update'])->name('mstnews.update')->middleware('role:admin,operator');
    Route::put('mstnews/activate/{id}', [MstNewsController::class, 'activate'])->name('mstnews.activate')->middleware('role:admin,operator');
    Route::put('mstnews/deactivate/{id}', [MstNewsController::class, 'deactivate'])->name('mstnews.deactivate')->middleware('role:admin,operator');
    Route::delete('mstnews/delete/{id}', [MstNewsController::class, 'delete'])->name('mstnews.delete')->middleware('role:admin,operator');

    // Comment
    Route::get('/comment', [MstNewsController::class, 'comment'])->name('comment')->middleware('role:admin,operator');
    Route::post('mstnews/store_comment/{id}', [MstNewsController::class, 'store_comment'])->name('mstnews.store_comment')->middleware('role:admin,operator');
    Route::put('mstnews/status_comment/{id}', [MstNewsController::class, 'status_comment'])->name('mstnews.status_comment')->middleware('role:admin,operator');
    Route::delete('mstnews/delete_comment/{id}', [MstNewsController::class, 'delete_comment'])->name('mstnews.delete_comment')->middleware('role:admin,operator');


    // Galery
    Route::get('/mstgalery', [MstGaleryController::class, 'index'])->name('mstgalery.index')->middleware('role:admin,operator');
    Route::post('/mstgalery', [MstGaleryController::class, 'store'])->name('mstgalery.store')->middleware('role:admin,operator');
    Route::put('/mstgalery/{id}', [MstGaleryController::class, 'update'])->name('mstgalery.update')->middleware('role:admin,operator');
    Route::delete('/mstgalery/{id}', [MstGaleryController::class, 'delete'])->name('mstgalery.delete')->middleware('role:admin,operator');



    // Configuration

    // Pemerintahan
    Route::get('/mststrukturorganisasi', [MstPublicInfoController::class, 'strukturorganisasi'])->name('mststrukturorganisasi.index')->middleware('role:admin,operator');
    Route::get('/msttugasfungsi', [MstPublicInfoController::class, 'tugasfungsi'])->name('msttugasfungsi.index')->middleware('role:admin,operator');

    // update public_info
    Route::put('mstupdate_info_public/{id}', [MstPublicInfoController::class, 'update_info_public'])->name('update_info_public.update')->middleware('role:admin,operator');


    // Home Setting
    Route::put('msthomeinfo_public/{id}', [MstHomeSettingController::class, 'update_info_public'])->name('msthomeinfo_public.update')->middleware('role:admin,operator');


    // Chat Admin
    Route::get('/mstchatadmin', [MstChatAdminController::class, 'index'])->name('mstchatadmin.index')->middleware('role:admin,operator');
    Route::post('/mstchatadmin/store', [MstChatAdminController::class, 'store'])->name('mstchatadmin.store')->middleware('role:admin,operator');
    Route::put('/mstchatadmin/update/{id}', [MstChatAdminController::class, 'update'])->name('mstchatadmin.update')->middleware('role:admin,operator');
    Route::delete('/mstchatadmin/delete/{id}', [MstChatAdminController::class, 'delete'])->name('mstchatadmin.delete')->middleware('role:admin,operator');
    // Sosial Media
    Route::get('/mstsosialmedia', [MstPublicInfoController::class, 'sosialMediaIndex'])->name('mstsosialmedia.index')->middleware('role:admin,operator');
    Route::put('/mstsosialmedia/{id}', [MstPublicInfoController::class, 'updatesosialMediaIndex'])->name('mstsosialmedia.update')->middleware('role:admin,operator');

    // MSTContact Us
    Route::get('/mstcontactus', [MstHomeSettingController::class, 'contactus'])->name('mstcontactus.index')->middleware('role:admin,operator');
    Route::delete('/mstcontactus/{id}', [MstHomeSettingController::class, 'contactusdelete'])->name('mstcontactus.delete')->middleware('role:admin,operator');

    // Kelola Group Navigasi
    Route::get('/mstkelolanavigasi', [MstGroupNavigationController::class, 'index'])->name('mstkelolanavigasi.index')->middleware('role:admin');
    Route::post('/mstkelolanavigasi/store', [MstGroupNavigationController::class, 'store'])->name('mstkelolanavigasi.store')->middleware('role:admin');
    Route::put('/mstkelolanavigasi/update/{id}', [MstGroupNavigationController::class, 'update'])->name('mstkelolanavigasi.update')->middleware('role:admin');
    Route::delete('/mstkelolanavigasi/delete/{id}', [MstGroupNavigationController::class, 'delete'])->name('mstkelolanavigasi.delete')->middleware('role:admin');




    // Kelola Navigasi
    Route::get('/mstnavigasi/{id}', [MstNavigationController::class, 'index'])->name('mstnavigasi.index')->middleware('role:admin');
    Route::post('/mstnavigasi/store/{id}', [MstNavigationController::class, 'store'])->name('mstnavigasi.store')->middleware('role:admin');
    Route::put('/mstnavigasi/update/{id}', [MstNavigationController::class, 'update'])->name('mstnavigasi.update')->middleware('role:admin');
    Route::delete('/mstnavigasi/delete/{id}', [MstNavigationController::class, 'delete'])->name('mstnavigasi.delete')->middleware('role:admin');


    // Hak Akses Navigasi
    Route::get('/msthaknavigasi', [MstHakAksesNavController::class, 'index'])->name('msthaknavigasi.index')->middleware('role:admin,operator');
    Route::post('/mstkelolanavigasi/{id}', [MstHakAksesNavController::class, 'store'])->name('msthaknavigasi.store')->middleware('role:admin,operator');
    Route::delete('/mstkelolanavigasi/{id}', [MstHakAksesNavController::class, 'delete'])->name('msthaknavigasi.delete')->middleware('role:admin,operator');


    // Galery
    Route::get('/msttahun', [MstTahunController::class, 'index'])->name('msttahun.index')->middleware('role:admin,operator');
    Route::post('/msttahun', [MstTahunController::class, 'store'])->name('msttahun.store')->middleware('role:admin,operator');
    Route::put('/msttahun/{id}', [MstTahunController::class, 'update'])->name('msttahun.update')->middleware('role:admin,operator');
    Route::delete('/msttahun/{id}', [MstTahunController::class, 'delete'])->name('msttahun.delete')->middleware('role:admin,operator');



    //Master Option
    Route::get('/mstoption', [MstOptionController::class, 'index'])->name('mstoption.index')->middleware('role:admin,operator');
    // Route::post('mstoption/create', [MstOptionController::class, 'store'])->name('mstoption.store')->middleware('role:admin,operator');
    Route::put('mstoption/update/{id}', [MstOptionController::class, 'update'])->name('mstoption.update')->middleware('role:admin,operator');
    // Route::delete('mstoption/delete/{id}', [MstOptionController::class, 'delete'])->name('mstoption.delete')->middleware('role:admin,operator');


    // Slider
    Route::get('/mstslider', [MstSliderController::class, 'index'])->name('mstslider.index')->middleware('role:admin,operator');
    Route::post('/mstslider/store', [MstSliderController::class, 'store'])->name('mstslider.store')->middleware('role:admin,operator');
    Route::put('/mstslider/update/{id}', [MstSliderController::class, 'update'])->name('mstslider.update')->middleware('role:admin,operator');
    Route::put('/mstslider/activate/{id}', [MstSliderController::class, 'activate'])->name('mstslider.activate')->middleware('role:admin,operator');
    Route::put('/mstslider/deactivate/{id}', [MstSliderController::class, 'deactivate'])->name('mstslider.deactivate')->middleware('role:admin,operator');
    Route::delete('/mstslider/delete/{id}', [MstSliderController::class, 'delete'])->name('mstslider.delete')->middleware('role:admin,operator');


    // Setting pegawai
    // Jabatan pegawai
    // Route::get('/mstjabatanpegawai', [MstJabatanPegawaiController::class, 'index'])->name('mstjabatanpegawai.index')->middleware('role:admin');
    Route::post('mstjabatanpegawai/create', [MstJabatanPegawaiController::class, 'store'])->name('mstjabatanpegawai.store')->middleware('role:admin,operator');
    Route::put('mstjabatanpegawai/update/{id}', [MstJabatanPegawaiController::class, 'update'])->name('mstjabatanpegawai.update')->middleware('role:admin,operator');
    Route::delete('mstjabatanpegawai/delete/{id}', [MstJabatanPegawaiController::class, 'delete'])->name('mstjabatanpegawai.delete')->middleware('role:admin,operator');

    // Bidang pegawai
    // Route::get('/mstbidangpegawai', [MstBidangPegawaiController::class, 'index'])->name('mstbidangpegawai.index')->middleware('role:admin');
    Route::post('mstbidangpegawai/create', [MstBidangPegawaiController::class, 'store'])->name('mstbidangpegawai.store')->middleware('role:admin,operator');
    Route::put('mstbidangpegawai/update/{id}', [MstBidangPegawaiController::class, 'update'])->name('mstbidangpegawai.update')->middleware('role:admin,operator');
    Route::delete('mstbidangpegawai/delete/{id}', [MstBidangPegawaiController::class, 'delete'])->name('mstbidangpegawai.delete')->middleware('role:admin,operator');

    // Data pegawai
    Route::get('/mstdatapegawai', [MstDataPegawaiController::class, 'index'])->name('mstdatapegawai.index')->middleware('role:admin,operator');
    Route::post('mstdatapegawai/create', [MstDatapegawaiController::class, 'store'])->name('mstdatapegawai.store')->middleware('role:admin,operator');
    Route::put('mstdatapegawai/update/{id}', [MstDatapegawaiController::class, 'update'])->name('mstdatapegawai.update')->middleware('role:admin,operator');
    Route::delete('mstdatapegawai/delete/{id}', [MstDatapegawaiController::class, 'delete'])->name('mstdatapegawai.delete')->middleware('role:admin,operator');

    // Data Puskesmas
    Route::get('/mstdatapuskesmas', [MstDataPuskesmasController::class, 'index'])->name('mstdatapuskesmas.index')->middleware('role:admin,operator');
    Route::post('mstdatapuskesmas/create', [MstDataPuskesmasController::class, 'store'])->name('mstdatapuskesmas.store')->middleware('role:admin,operator');
    Route::put('mstdatapuskesmas/update/{id}', [MstDataPuskesmasController::class, 'update'])->name('mstdatapuskesmas.update')->middleware('role:admin,operator');
    Route::delete('mstdatapuskesmas/delete/{id}', [MstDataPuskesmasController::class, 'delete'])->name('mstdatapuskesmas.delete')->middleware('role:admin,operator');

    // Attribut
    Route::get('/msticon', function () {
        return view('attribut.icon');
    })->name('msticon.index')->middleware('role:admin,operator');

});


Route::middleware(['track.visitors', 'track.online'])->group(function () {

    Route::get('/', [UserHomeController::class, 'index'])->name('home');


    // Contact
    Route::get('/contact', [UserHomeController::class, 'contact'])->name('user.contact');
    Route::post('/contact', [UserHomeController::class, 'store_contactus'])->name('user.contact');


    // Berita
    Route::get('/berita', [UserBeritaController::class, 'index'])->name('user.berita');
    Route::get('/berita/kategori/{categories}', [UserBeritaController::class, 'categories'])->name('user.kategori_berita');
    Route::get('/berita/detail/{slug}', [UserBeritaController::class, 'show'])->name('user.berita_show');

    Route::post('berita/store_comment/{id}', [UserBeritaController::class, 'store_comment'])->name('user.store_comment');


    // Profile
    Route::get('/pegawai', [UserHomeController::class, 'pegawai'])->name('profile.pegawai');


    Route::get('/datapuskesmas', [UserHomeController::class, 'datapuskesmas'])->name('user.datapuskesmas');



    // Search
    Route::get('/search', [UserBeritaController::class, 'search'])->name('user.search');

    Route::get('/profil/{slug}', [UserProfileController::class, 'index'])->name('user.profile');
    Route::get('/s/pelaporandinkes', [UserHomeController::class, 'redirect']);



});
