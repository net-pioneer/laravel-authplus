<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAPTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //this->withinTransaction = true;
        $config = config('auth');
        if(isset($config['guards']['sanctum']))
            unset($config['guards']['sanctum']);
            foreach ($config['guards'] as $guard=>$c){
                Schema::create($guard, function (Blueprint $table) use($c) {
                    $table->id();
                    $table->string('name');
                    $table->string('username');
                    $table->string('email')->unique();
                    if(is_array($c['signup']['verify'])){
                        foreach ($c['signup']['verify'] as $v){
                            $table->tinyInteger('is_verified_'.$v)->default(0);
                        }
                    }
                    $table->string('password');
                    $table->string('profile_avi', 2048)->default('N/A')->nullable();
                    $table->rememberToken();
                    $table->timestamps();
                });

                $model_template = file_get_contents(__DIR__.'/../../Models/model.stub');
                $model_template = str_replace('[CLASS]',ucwords($guard),$model_template);
                $model_template = str_replace('[CLASS_LOW]',strtolower($guard),$model_template);
                file_put_contents(app_path('Models/'.ucwords($guard).'.php'),$model_template);
            }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = config('auth');
        if(isset($config['guards']['sanctum']))
            unset($config['guards']['sanctum']);
        foreach ($config['guards'] as $guard=>$c){
            Schema::dropIfExists($guard);
        }
    }
}
