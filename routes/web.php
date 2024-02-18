<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnalysisController;
use App\Http\Controllers\Admin\Beta\PerformanceController as BetaPerformanceController;
use App\Http\Controllers\Admin\Beta\PresenceController as BetaPresenceController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\OfficerController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\PerformanceController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\SubCriteriaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\OfficerController as HomeOfficerController;
use App\Http\Controllers\Home\ResultController as HomeResultController;
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

//FRONT END
//HOMEPAGE
Route::controller(HomeController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::resource('/officers', HomeOfficerController::class);
    Route::resource('/results', HomeResultController::class);
});

//LOGIN AND LOGOUT
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function() {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'auth')->name('login.auth');
    });
});
Route::middleware('auth')->group(function () {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/logout', 'logout')->name('logout');
    });
});

//BACK END
//ALL
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::resource('/officers', OfficerController::class);
    });
    Route::prefix('analysis')->name('analysis.')->group(function () {
        Route::prefix('saw')->name('saw.')->group(function () {
            Route::get('/', [AnalysisController::class, 'index'])->name('index');
            Route::get('/{period}', [AnalysisController::class, 'saw'])->name('saw');
        });
        Route::prefix('wp')->name('wp.')->group(function () {
            Route::get('/', [AnalysisController::class, 'index'])->name('index');
            Route::get('/{period}', [AnalysisController::class, 'wp'])->name('wp');
        });
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::controller(ReportController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/officers', 'officers')->name('officers');
            Route::prefix('input')->name('input.')->group(function () {
                Route::get('/{period}', 'inpall')->name('all');
                Route::get('/{period}/{id}', 'inpsingle')->name('single');
            });
            //Route::get('/input/{period}', 'input')->name('input');
            Route::get('/analysis/{period}', 'analysis')->name('analysis');
            Route::get('/result/{period}', 'result')->name('result');
        });
    });
});
//ADMIN / KEPEGAWAIAN
Route::middleware(['auth', 'checkPart:Admin'])->group(function () {
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::resource('/departments', DepartmentController::class, ['only' => ['store', 'update', 'destroy']]);
        Route::resource('/parts', PartController::class, ['only' => ['store', 'update', 'destroy']]);
        Route::resource('/users', UserController::class);
        Route::resource('/periods', PeriodController::class);
        Route::resource('/criterias', CriteriaController::class);
        Route::resource('/subcriterias', SubCriteriaController::class);
    });
    Route::prefix('inputs')->name('inputs.')->group(function () {
        Route::resource('/presences', PresenceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::prefix('beta')->name('beta.')->group(function () {
            Route::resource('/presences', BetaPresenceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        });
    });
});
//KEPALA BAGIAN UMUM & KOORDINASI TIM TEKNIS
Route::middleware(['auth', 'checkPart:KBU,KTT'])->group(function () {
    Route::prefix('inputs')->name('inputs.')->group(function () {
        Route::resource('/performances', PerformanceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::prefix('beta')->name('beta.')->group(function () {
            Route::resource('/performances', BetaPerformanceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        });
    });
});
//KEPALA BPS JAWA TIMUR
Route::middleware(['auth', 'checkPart:KBPS'])->group(function () {
    Route::prefix('inputs/results')->name('results.')->group(function () {
        Route::controller(ResultController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::post('/get/{period}', 'get')->name('get');
            Route::post('/yes/{id}', 'yes')->name('yes');
            Route::post('/no/{id}', 'no')->name('no');
            Route::post('/finish/{period}', 'finish')->name('finish');
        });
    });
});
