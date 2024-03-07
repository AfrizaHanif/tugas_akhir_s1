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
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\Admin\SubCriteriaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoteCriteriaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\OfficerController as HomeOfficerController;
use App\Http\Controllers\Home\ResultController as HomeResultController;
use App\Http\Controllers\Home\ScoreController as HomeScoreController;
use App\Http\Controllers\JSONController;
use App\Http\Controllers\VoteController;
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
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::resource('/officers', HomeOfficerController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
Route::prefix('officers')->name('officers.')->group(function () {
    Route::get('/search', [HomeOfficerController::class, 'search'])->name('search');
    //Route::get('/auto', [HomeOfficerController::class, 'auto'])->name('auto');
});
Route::resource('/scores', HomeScoreController::class);
Route::resource('/results', HomeResultController::class);
Route::middleware(['auth', 'checkPart:Pegawai'])->group(function () {
    Route::prefix('votes')->name('votes.')->group(function () {
        Route::controller(VoteController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{period}', 'vote')->name('vote');
            Route::post('/{period}/{officer}/{criteria}', 'select')->name('select');
        });
    });
});
Route::controller(JSONController::class)->group(function() {
    Route::get('/autocomplete', 'autocomplete')->name('json.autocomplete');
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
Route::middleware(['auth', 'checkAdmin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::resource('/officers', OfficerController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::prefix('officers')->name('officers.')->group(function () {
            Route::get('/search', [OfficerController::class, 'search'])->name('search');
        });
        Route::middleware('checkPart:Admin')->group(function () {
            Route::resource('/departments', DepartmentController::class, ['only' => ['store', 'update', 'destroy']]);
            Route::resource('/parts', PartController::class, ['only' => ['store', 'update', 'destroy']]);
            Route::resource('/users', UserController::class);
            Route::resource('/periods', PeriodController::class, ['only' => ['index', 'store', 'destroy']]);
            Route::prefix('periods')->name('periods.')->group(function () {
                Route::controller(PeriodController::class)->group(function() {
                    Route::post('/skip/{period}', 'skip')->name('skip');
                    Route::post('/finish/{period}', 'finish')->name('finish');
                });
            });
            Route::resource('/criterias', CriteriaController::class);
            Route::resource('/subcriterias', SubCriteriaController::class);
            Route::resource('/vote-criterias', VoteCriteriaController::class);
        });
    });
    Route::prefix('inputs')->name('inputs.')->group(function () {
        Route::middleware('checkPart:Admin')->group(function () {
            Route::resource('/presences', PresenceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        });
        Route::middleware('checkPart:KBU')->group(function () {
            Route::prefix('kbu')->name('kbu.')->group(function () {
                Route::resource('/performances', PerformanceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
            });
        });
        Route::middleware('checkPart:KTT')->group(function () {
            Route::prefix('ktt')->name('ktt.')->group(function () {
                Route::resource('/performances', PerformanceController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
            });
        });
        Route::middleware('checkPart:KBPS')->group(function () {
            Route::prefix('scores')->name('scores.')->group(function () {
                Route::controller(ScoreController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::post('/get/{period}', 'get')->name('get');
                    Route::post('/yes/{id}', 'yes')->name('yes');
                    Route::post('/no/{id}', 'no')->name('no');
                    Route::post('/finish/{period}', 'finish')->name('finish');
                });
            });
        });
        Route::prefix('votes')->name('votes.')->group(function () {
            Route::controller(VoteController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/{period}', 'vote')->name('vote');
                Route::post('/{period}/{officer}/{criteria}', 'select')->name('select');
            });
        });
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
            Route::get('/analysis/{period}', 'analysis')->name('analysis');
            Route::get('/result/{period}', 'result')->name('result');
        });
    });
});

