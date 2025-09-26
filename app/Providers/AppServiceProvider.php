<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Pengaturan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->overrideConfigValues();
        \Carbon\Carbon::setLocale(config('app.locale'));

        Gate::define('admin', function($user) {
            return $user->role_id == 'admin';
        });

        Gate::define('pimpinan_umum', function($user) {
            return $user->role_id == 'pimpinan_umum';
        });

        Gate::define('pimpinan', function($user) {
            return $user->role_id == 'pimpinan';
        });

        Gate::define('pegawai', function($user) {
            return $user->role_id == 'pegawai';
        });
    }

    protected function overrideConfigValues()
    {
        // $set =  Pengaturan::first();
        // $set = '';
        $config = \Config::set([
            'app.timezone' => 'Asia/Makassar',
            // 'app.name' => optional($set)->nama_instansi != null ? $set->nama_instansi:'Layanan Verifikasi Tugas Akhir',
            'app.locale' => 'id',
            // 'mail.from.address' => optional($set)->email != null ? $set->email:'rsuregar@unhas.ac.id',
            // 'mail.from.name' => optional($set)->nama_instansi != null ? $set->nama_instansi:'Layanan Verifikasi Tugas Akhir',
            // 'mail.mailers.smtp.username' => optional($set)->email != null ? $set->email:'rsuregar@unhas.ac.id',
            // 'mail.mailers.smtp.password' => optional($set)->password != null ? decrypt($set->password):'mautauyah?',
            // 'mail.mailers.smtp.port' => optional($set)->port != null ? $set->port:587,
            // 'mail.mailers.smtp.host' => optional($set)->host != null ? $set->host:'smtp.gmail.com',
            // 'mail.mailers.smtp.encryption' => optional($set)->encryption != null ? $set->encryption:'tls',
        ]);

        config($config);
    }
}
