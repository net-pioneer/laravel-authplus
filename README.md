# laravel-authplus
Multi authentication for laravel v1.0.0 Based On LiveWire

note : <br>
First Rival it's my first project which i decided to publish it for public usage.
so if something wrong happened in my codes, plz fix it by yourself and share it to us LOL.

<h5>Features :</h5>
- Captcha
- Two Factor Authentication (based on your Configuration Like SMS/Email/Google authenticator)
- Multi Authentication
- Auto Generation DB tables
- and etc.
<h5>Installation : </h5><br>
<code>composer require net-pioneer/laravel-authplus</code><br>
Or
copy the hole files into your Project-name/Packages/netpioneer/authplus <br>
and then add codes bellow to your main composer
<code>"netpioneer\\authplus\\": "packages/netpioneer/authplus/src/"</code>
<br>
<pre>"autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/",
            "netpioneer\\authplus\\": "packages/netpioneer/authplus/src/"
        }
    },</pre>

Usages :<br>
<h5>1:</h5>
<pre>php artisan vendor:publish --provider=netpioneer\authplus\Providers\AuthplusServiceProvider</pre>
<h5>2:</h5>
<pre>php artisan migrate</pre>
NOTE : Before to run migration you should config your auth.php in config folder.like guards,verifications and etc.
<h5>3:</h5>
<pre>php artisan make:livewire Login</pre>
NOTE : you should know LiveWire Basics . you i write it short
<h5>4:</h5>
View :
```html
<div>
    <h1>livewire page is here</h1>
    <hr>
    <form wire:submit.prevent="submit">
        @csrf
        @if(!$twofactor)
        <input type="text" name="username" wire:model.defer="username">
        <br>
        <input type="password" name="password" wire:model.defer="password">
        <br>
        <img src="/ap/ap_captcha?t={{time()}}" /><br>
        <input type="text" name="captcha" wire:model.defer="captcha">
        @else
        <input type="text" name="twofactor_input" wire:model.defer="twofactor_input">
        @endif
        <input type="submit">
    </form>
    <div style="color:red;">{{$err}}</div>
</div>
```
inside LiveWire Class:
```php
public function submit(){
        try {
            $this->res = app(AuthPlusAuthenticateUser::class)->twofactor($this->twofactor_input)->captcha($this->captcha)->Authenticate('admin', $this->username, $this->password, true);
            $this->err = $this->res;
            return redirect()->away(AuthPlusLogics::getHomePage());
        }catch (LoginFailedExecption $exception){
            if($exception->getStatus() == LoginStatusEnum::TwoFactorRequired){
                $this->twofactor = true;
                $this->err = 'two factor';
            }else {
                $this->err = "login failed > " . $exception->getStatus() . " - data : " . (is_array($exception->getData()) ? implode(",",$exception->getData()) : $exception->getData());
            }
        }

    }
```
You Customize your Auth stuff in Service Provider because everything are on default :
```php
AuthPlus::AuthenticateMethod(AuthPlusAuthenticateUserDefualt::class);
AuthPlus::RedirectAuthenticated(AuthPlusRedirectAuthenticatedCustom::class);
AuthPlus::TwoFactorAuthenticator(AuthPlusTwoFactorSmsAuthenticator::class);
```
Router :
if open route were enabled on auth config file there is no need too create route for each path Lol except the Index file sould be managed on you !

<h5>Donate: </h5>
Good news ! If you enjoyed this package you could donate me by donating USDT to my wallet ! A Coffee or etc.
<br>
<code>USDT wallet Address (TRC20) : TBFJ3YirXc7vwwuRNeqhcBcQziB3h9bPbs</code> 
