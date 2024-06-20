<?php
use Mhasnainjafri\APIToolkit\logger\FileLogger;

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


Route::group(config('apitoolkit.route',[]), function($router)
{
    Route::get('/', function(){
        $logger=new FileLogger() ;
        $apilogs = $logger->getLogs();

        return view('apitoolkit::index', compact('apilogs'));


                    })->name("apilogs.index");
    Route::delete('/delete', function(){
        $logger=new FileLogger() ;

        $logger->deleteLogs();

        return redirect()->back();

    })->name("apilogs.deletelogs");
});



