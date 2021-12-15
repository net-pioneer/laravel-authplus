<?php
use \Illuminate\Support\Facades\Route;
$config = config('auth');
if(isset($config['signture'])) {
    if(isset($config['guards']['sanctum']))
        unset($config['guards']['sanctum']);
    Route::group(['prefix' => $config['prefix'], 'middleware' => ['web']], function () use ($config) {
        Route::get('ap_captcha','\netpioneer\authplus\Classes\Captcha@GenerateCaptcha');
        foreach ($config['guards'] as $guard=>$c) {
            $url = @$c['url'];
            Route::group(['prefix'=>$url],function () use($c,$guard){
                Route::middleware('guest')->group(function () use ($c,$guard) {
                    if ($c['signup']['show']) {
                        Route::get('login',$c['login']['view'])->middleware('throttle:10,1')->name('login_'.$guard);
                    }
                });
                Route::group(['middleware' => 'auth:' . $guard], function () use ($guard,$c) {
                    Route::get('/test', function (){
                        return auth()->user();
                    });
                    Route::get('/logout', function () {
                        auth()->logout();
                        return "done";
                    });
                    $guardC = ucwords($guard);
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
