<?php
use \Illuminate\Support\Facades\Route;
$config = config('auth');
if($config != null) {
    Route::group(['prefix' => $config['prefix'], 'middleware' => ['web']], function () use ($config) {
        foreach ($config['privilages'] as $c) {
            Route::group(['prefix'=>$c['url']],function () use($c){
                Route::middleware('guest')->group(function () use ($c) {
                    if ($c['signup']['show']) {
                        Route::get('register', function () {
                            return view('AuthPlus::index');
                        });
                        Route::get('login',$c['login']['view'])->name('login_'.$c['guard']);
                    }
                });
                Route::group(['middleware' => 'auth:' . $c['guard']], function () use ($c) {
                    Route::get('/test', function () use ($c){
                        return auth()->user();
                    });
                    Route::get('/logout', function () use ($c){
                        auth()->logout();
                        return "done";
                    });
                    $guardC = ucwords($c['guard']);
                    //Route::get('/', "\App\Http\Controllers\\" . $c['url'] . "\\" . $guardC . "MainController@index")->name($c['guard'] . '_root');
                    //Route::get('maincontent', "\App\Http\Controllers\\" . $c['url'] . "\\" . $guardC . "MainController@mainpage");
                    if ($c['open_route']) {
                        Route::any('/{class}/{action}/{param1?}/{param2?}', function ($class, $action, $param1 = 0, $param2 = 0) use ($c, $guardC) {
                            $clsstr = "\App\Http\Controllers\\" . $c['url'] . "\\" . $guardC . ucwords(strtolower($class)) . 'Controller';
                            //$a = action('Admin\Admin'.ucwords(strtolower($class)).'Controller@'.$action,['param1'=>$param1,'param2'=>$param2]);
                            $cls = new $clsstr;
                            $action = strtolower($action);
                            if (method_exists($cls, $action)) {
                                if ($_POST) {
                                    return $cls->$action(Request());
                                    //return app()->make($clsstr.'@process',[Request()]);
                                } else {
                                    return call_user_func_array(array($cls, $action), array($param1, $param2));
                                }
                            } else
                                abort(404);
                        });
                    }
                });
            });

        }
    });
}
