<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TourismPlaceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizQuestionController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserQuizController;




use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

Route::get('/test-cloudinary', function () {

    // Setup manual
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => 'ddrepuzxq',
            'api_key'    => '736112155155523',
            'api_secret' => 'Di11B6PPvsjHotH1KCfMpHOpbzM',
        ],
        'url' => [
            'secure' => true
        ],
    ]);

    try {
        $uploaded = $cloudinary->uploadApi()->upload(
            base_path('public/test.jpg'),
            [
                'folder' => 'test_upload',
                'overwrite' => true,
            ]
        );

        return "Upload berhasil! URL: <a href='{$uploaded['secure_url']}' target='_blank'>{$uploaded['secure_url']}</a>";
    } catch (\Exception $e) {
        return "Upload gagal: " . $e->getMessage();
    }
});




// ===== AUTH =====
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== LANDING =====
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/tentang-kami', [LandingPageController::class, 'about'])->name('tentang.kami');
Route::get('/kontak-kami', [LandingPageController::class, 'contact'])->name('kontak.kami');
Route::get('/wisata', [LandingPageController::class, 'wisata'])->name('wisata');
Route::get('/wisata/{slug}', [LandingPageController::class, 'show'])->name('tourism-places.show');

Route::post('/kontak/kirim', [ContactController::class, 'send'])->name('kontak.kirim');


Route::prefix('quiz')->middleware('auth')->group(function () {
    Route::get('/', [UserQuizController::class, 'index'])->name('quiz.index');
    Route::get('/{slug}', [UserQuizController::class, 'show'])->name('quiz.show');
    Route::post('/{slug}/submit', [UserQuizController::class, 'submit'])->name('quiz.submit');
});

Route::middleware(['auth', 'role:user'])->prefix('dashboard')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tambah-tempat-wisata-baru', [UserDashboardController::class, 'createTourismPlaces'])->name('dashboard.createTourismPlaces');
    Route::post('/tambah-tempat-wisata-baru', [UserDashboardController::class, 'storeTourismPlaces'])->name('dashboard.storeTourismPlaces');
    Route::get('/{slug}', [UserDashboardController::class, 'showTourismPlaces'])->name('dashboard.showTourismPlaces');
    Route::get('/{slug}/edit', [UserDashboardController::class, 'editTourismPlaces'])->name('dashboard.editTourismPlaces');

    // Tambah galeri (klik "+" slot kosong)
    Route::get('/gallery/{tourism_place}/add', [UserDashboardController::class, 'addGallery'])
        ->name('dashboard.addGallery');

    // Simpan galeri baru
    Route::post('/gallery/{tourism_place}/store', [UserDashboardController::class, 'storeGallery'])
        ->name('dashboard.storeGallery');

    // Edit foto galeri
    Route::get('/gallery/{id}/edit', [UserDashboardController::class, 'editGallery'])
        ->name('dashboard.editGallery');

    // Update foto galeri
    Route::put('/gallery/{id}', [UserDashboardController::class, 'updateGallery'])
        ->name('dashboard.updateGallery');

    // Hapus foto galeri
    Route::delete('/gallery/{id}', [UserDashboardController::class, 'deleteGallery'])
        ->name('dashboard.deleteGallery');

    // EDIT HERO (nama + kategori + cover)
    Route::put('/tourism/{id}/hero', [UserDashboardController::class, 'updateHero'])
        ->name('dashboard.updateHero');

    // EDIT DESKRIPSI
    Route::put('/tourism/{id}/description', [UserDashboardController::class, 'updateDescription'])
        ->name('dashboard.updateDescription');

    // EDIT FASILITAS
    Route::put('/tourism/{id}/facilities', [UserDashboardController::class, 'updateFacilities'])
        ->name('dashboard.updateFacilities');

    Route::put('/dashboard/wisata/{id}/detail', [UserDashboardController::class, 'updateDetail'])
        ->name('dashboard.updateDetail');

    Route::put('/dashboard/wisata/{id}/info', [UserDashboardController::class, 'updateInfo'])
        ->name('dashboard.updateInfo');

    Route::put('/dashboard/wisata/{id}/location', [UserDashboardController::class, 'updateLocation'])->name('dashboard.updateLocation');
});



Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kategori
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Wisata
        Route::get('/tourism-places', [TourismPlaceController::class, 'index'])->name('tourism-places.index');
        Route::get('/tourism-places/pending', [TourismPlaceController::class, 'pending'])->name('tourism-places.pending');
        Route::get('/tourism-places/create', [TourismPlaceController::class, 'create'])->name('tourism-places.create');
        Route::post('/tourism-places', [TourismPlaceController::class, 'store'])->name('tourism-places.store');
        Route::put('/tourism-places/{id}/activate', [TourismPlaceController::class, 'activate'])->name('tourism-places.activate');
        Route::get('/tourism-places/{slug}', [TourismPlaceController::class, 'show'])->name('tourism-places.show');
        Route::get('/tourism-places/{slug}/edit', [TourismPlaceController::class, 'edit'])->name('tourism-places.edit');


        // Galeri
        Route::post('/tourism-places/gallery/{tourism_place}/store', [TourismPlaceController::class, 'storeGallery'])->name('storeGallery');
        Route::get('/tourism-places/gallery/{id}/edit', [TourismPlaceController::class, 'editGallery'])->name('editGallery');
        Route::put('/tourism-places/gallery/{id}', [TourismPlaceController::class, 'updateGallery'])->name('updateGallery');
        Route::delete('/tourism-places/gallery/{id}', [TourismPlaceController::class, 'deleteGallery'])->name('deleteGallery');

        Route::put('/tourism-places/{id}/hero', [TourismPlaceController::class, 'updateHero'])->name('updateHero');
        Route::put('/tourism-places/{id}/description', [TourismPlaceController::class, 'updateDescription'])->name('updateDescription');
        Route::put('/tourism-places/{id}/facilities', [TourismPlaceController::class, 'updateFacilities'])->name('updateFacilities');

        Route::put('/tourism-places/{id}/info', [TourismPlaceController::class, 'updateInfo'])->name('updateInfo');
        Route::put('/tourism-places/{id}/location', [TourismPlaceController::class, 'updateLocation'])->name('updateLocation');









        Route::put('/tourism-places/{id}', [TourismPlaceController::class, 'update'])->name('tourism-places.update');

        // Verifikasi & Status
        Route::put('/tourism-places/{id}/verify', [TourismPlaceController::class, 'verify'])->name('tourism-places.verify');
        Route::put('/tourism-places/{id}/deactivate', [TourismPlaceController::class, 'deactivate'])->name('tourism-places.deactivate');        // Hapus
        Route::delete('/tourism-places/{id}', [TourismPlaceController::class, 'destroy'])->name('tourism-places.destroy');

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings/{id}', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


        // quiz 
        Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
        // Halaman show quiz
        Route::get('/quiz/{id}', [QuizController::class, 'showPage'])->name('quiz.show');
        // API CRUD lainnya
        Route::post('/quiz', [QuizController::class, 'store'])->name('quiz.store');
        Route::put('/quiz/{id}', [QuizController::class, 'update'])->name('quiz.update');
        Route::delete('/quiz/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');


        // Leaderboard
        Route::get('/quiz/{id}/leaderboard', [QuizController::class, 'leaderboard'])->name('quiz.leaderboard');

        // Quiz Questions
        Route::post('/quiz/{quizId}/question', [QuizQuestionController::class, 'store']);
        Route::put('/quiz/{quizId}/question/{id}', [QuizQuestionController::class, 'update']);
        Route::delete('/quiz/{quizId}/question/{id}', [QuizQuestionController::class, 'destroy']);

        // Menampilkan jawaban peserta
        Route::get('/quiz/result/{resultId}/answers', [QuizController::class, 'showAnswers'])->name('quiz.result.answers');


        Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');
        Route::post('facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::put('facilities/{facility}', [FacilityController::class, 'update'])->name('facilities.update');
        Route::delete('facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    });
