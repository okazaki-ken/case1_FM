<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        //ログイン情報の一致と確認。メアドの登録なしとPW不一致
        Fortify::authenticateUsing(function ($request){
            $user = \App\Models\User::where('email',$request->email)->first();

            if(! $user){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email'=>['ログイン情報が登録されていません'],
                ]);
            }

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) 
            {
                return $user;
            }

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email'=>['メールアドレスまたはパスワードが正しくありません'],
            ]);

        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

    }
}
