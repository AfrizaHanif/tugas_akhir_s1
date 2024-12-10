<?php

use App\Http\Controllers\Admin\AnalysisController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CripsController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\InputController;
use App\Http\Controllers\Admin\SubTeamsController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\AuthController;
//use App\Http\Controllers\Developer\OfficerController as DeveloperOfficerController;
use App\Http\Controllers\Home\HomeController;
//use App\Http\Controllers\Home\OfficerController as HomeOfficerController;
use App\Http\Controllers\Home\ScoreController as HomeScoreController;
use App\Http\Controllers\Home\ReportController as HomeReportController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\JSONController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController as AllReportController;
use App\Http\Controllers\MessageController;
//use App\Http\Controllers\Officer\OfficerController as OfficerOfficerController;
use App\Http\Controllers\Officer\ReportController as OfficerReportController;
use App\Http\Controllers\Officer\ScoreController as OfficerScoreController;
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
Route::controller(OfficerController::class)->group(function() {
    Route::prefix('officers')->name('officers.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/search', 'search')->name('search');
        //Route::get('/auto', 'auto')->name('auto');
    });
});
Route::get('/eotm', [HomeScoreController::class, 'index']);
Route::get('/reports', [HomeReportController::class, 'index']);
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

//REPORTS
/*
Route::prefix('reports')->name('reports.')->group(function () {
    Route::controller(AllReportController::class)->group(function() {
        Route::get('/officers', 'officers')->name('officers');
        Route::prefix('input')->name('input.')->group(function () {
            Route::get('/{period}', 'inpall')->name('all');
            Route::get('/{period}/{id}', 'inpsingle')->name('single');
        });
        Route::get('/analysis/{month}/{year}', 'analysis')->name('analysis');
        Route::get('/result/{subteam}/{month}/{year}', 'team_result')->name('teamresult');
        Route::get('/result/{month}/{year}', 'result')->name('result');
        Route::get('/certificate/{month}/{year}', 'certificate')->name('certificate');
    });
});
*/

//BACK END
//ADMIN'S DASHBOARD
Route::middleware(['auth', 'checkAdmin'])->group(function () {
    //Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin');
    Route::prefix('admin')->name('admin.')->group(function () {
        //MASTERS
        Route::prefix('masters')->name('masters.')->group(function () {
            Route::resource('/officers', OfficerController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
            Route::prefix('officers')->name('officers.')->group(function () {
                Route::controller(OfficerController::class)->group(function() {
                    Route::get('/search', 'search')->name('search');
                    Route::post('/import', 'import')->name('import');
                    Route::post('/export', 'export')->name('export');
                });
            });
            Route::middleware('checkPart:Admin')->group(function () {
                Route::resource('/positions', PositionController::class, ['only' => ['store', 'update', 'destroy']]);
                Route::resource('/parts', PartController::class, ['only' => ['store', 'update', 'destroy']]);
                Route::resource('/users', UserController::class);
                Route::resource('/periods', PeriodController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
                Route::prefix('periods')->name('periods.')->group(function () {
                    Route::controller(PeriodController::class)->group(function() {
                        Route::post('/refresh', 'refresh')->name('refresh');
                        Route::post('/start/{period}', 'start')->name('start');
                        Route::post('/skip/{period}', 'skip')->name('skip');
                        Route::post('/finish/{period}', 'finish')->name('finish');
                    });
                });
                Route::resource('/categories', CategoryController::class);
                Route::resource('/criterias', CriteriaController::class);
                Route::resource('/crips', CripsController::class);
                Route::resource('/teams', TeamsController::class);
                Route::resource('/subteams', SubTeamsController::class);
            });
        });
        //INPUTS
        Route::prefix('inputs')->name('inputs.')->group(function () {
            Route::middleware('checkPart:Admin')->group(function () {
                Route::resource('/data', InputController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
                Route::prefix('data')->name('data.')->group(function () {
                    Route::controller(InputController::class)->group(function() {
                        Route::post('/import/{period}', 'import')->name('import');
                        /*
                        Route::prefix('import')->name('import.')->group(function () {
                            Route::get('/presensi/{period}', 'import_presensi')->name('presensi');
                            Route::get('/ckp/{period}', 'import_ckp')->name('ckp');
                            Route::get('/berakhlak/{period}', 'import_berakhlak')->name('berakhlak');
                        });
                        */
                        Route::prefix('export')->name('export.')->group(function () {
                            Route::post('/', 'export_latest')->name('latest');
                            Route::post('/old/{period}', 'export_old')->name('old');
                            Route::post('/all', 'export_all')->name('all');
                        });
                        Route::post('/destroyall/{period}', 'destroyall')->name('destroyall');
                        Route::post('/convert/{period}', 'convert')->name('convert');
                        Route::post('/refresh/{period}', 'refresh')->name('refresh');
                        Route::post('/reset/{period}', 'reset')->name('reset');
                    });
                });
            });
            Route::middleware('checkPart:KBPS')->group(function () {
                Route::prefix('validate')->name('validate.')->group(function () {
                    Route::controller(ScoreController::class)->group(function() {
                        Route::get('/', 'index')->name('index');
                        Route::post('/get/{period}', 'get')->name('get');
                        Route::post('/yes/{id}', 'yes')->name('yes');
                        Route::post('/yesall/{id}', 'yesall')->name('yesall');
                        Route::post('/yesall/remain/{id}', 'yesall_remain')->name('yesall.remain');
                        Route::post('/no/{id}', 'no')->name('no');
                        Route::post('/noall/{id}', 'noall')->name('noall');
                        Route::post('/noall/remain/{id}', 'noall_remain')->name('noall.remain');
                        Route::post('/finish/{period}', 'finish')->name('finish');
                    });
                });
            });
        });
        //ANALYSIS
        Route::prefix('analysis')->name('analysis.')->group(function () {
            Route::controller(AnalysisController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/latest', 'saw')->name('saw');
                Route::get('/{period}', 'history_saw')->name('history');
                /*
                Route::prefix('wp')->name('wp.')->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/latest', 'wp')->name('wp');
                    Route::get('/{period}', 'history_wp')->name('history');
                });
                */
            });
        });
        //Route::get('/results', [ResultController::class, 'index'])->name('results');
        //REPORTS
        //Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::controller(ReportController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/officers', 'officers')->name('officers');
                Route::prefix('input')->name('input.')->group(function () {
                    Route::get('/{period}', 'inpall')->name('all');
                    Route::get('/{period}/{id}', 'inpsingle')->name('single');
                });
                Route::get('/analysis/{month}/{year}', 'analysis')->name('analysis');
                Route::get('/result/{subteam}/{month}/{year}', 'team_result')->name('teamresult');
                Route::get('/result/{month}/{year}', 'result')->name('result');
                Route::get('/certificate/{month}/{year}', 'certificate')->name('certificate');
            });
        });
        //FEEDBACK
        Route::resource('/messages', MessageController::class, ['only' => ['index', 'destroy']]);
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::controller(MessageController::class)->group(function() {
                Route::post('/in', 'store_in')->name('in');
            });
        });
        //PENGATURAN
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::controller(SettingController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/update', 'update')->name('update');
            });
        });
        //LOGS
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::controller(LogController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/export', 'export')->name('export');
            });
        });
        //Route::get('/logs', [LogController::class, 'index'])->name('logs');
    });
});

//OFFICER'S DASHBOARD
Route::middleware(['auth', 'checkOfficer'])->group(function () {
    Route::get('/officer', [DashboardController::class, 'officer'])->name('officer');
    Route::prefix('officer')->name('officer.')->group(function () {
        Route::prefix('officers')->name('officers.')->group(function () {
            Route::controller(OfficerController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/search', 'search')->name('search');
                Route::post('/export', 'export')->name('export');
            });
        });
        Route::get('/eotm', [OfficerScoreController::class, 'index'])->name('eotm.index');
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::controller(OfficerReportController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/score/{month}/{year}', 'score')->name('score');
            });
        });
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::controller(SettingController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/update', 'update')->name('update');
            });
        });
        Route::resource('/messages', MessageController::class, ['only' => ['index', 'destroy']]);
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::controller(MessageController::class)->group(function() {
                Route::post('/in', 'store_in')->name('in');
            });
        });
        //LOGS
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::controller(LogController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/export', 'export')->name('export');
            });
        });
        //Route::get('/logs', [LogController::class, 'index'])->name('logs');
    });
});

//DEVELOPER'S DASHBOARD
Route::middleware(['auth', 'checkDev'])->group(function () {
    Route::get('/developer', [DashboardController::class, 'developer'])->name('developer');
    Route::prefix('developer')->name('developer.')->group(function () {
        Route::prefix('masters')->name('masters.')->group(function () {
            Route::resource('/officers', OfficerController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
            Route::prefix('officers')->name('officers.')->group(function () {
                Route::controller(OfficerController::class)->group(function() {
                    Route::get('/', 'index')->name('index');
                    Route::get('/search', 'search')->name('search');
                    Route::post('/import', 'import')->name('import');
                    Route::post('/export', 'export')->name('export');
                });
            });
            Route::resource('/positions', PositionController::class, ['only' => ['store', 'update', 'destroy']]);
            Route::resource('/users', UserController::class);
            Route::resource('/teams', TeamsController::class);
            Route::resource('/subteams', SubTeamsController::class);
        });
        Route::resource('/messages', MessageController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::controller(MessageController::class)->group(function() {
                Route::post('/out/{id}', 'store_out')->name('out');
            });
        });
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::controller(SettingController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/update', 'update')->name('update');
            });
        });
        //LOGS
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::controller(LogController::class)->group(function() {
                Route::get('/', 'index')->name('index');
                Route::post('/export', 'export')->name('export');
            });
        });
        //Route::get('/logs', [LogController::class, 'index'])->name('logs');
    });
});

