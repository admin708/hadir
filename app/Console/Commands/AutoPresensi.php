<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Jadwal;
use App\Presensi;
use Illuminate\Database\QueryException as Error;

class AutoPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perintah untuk menjalankan auto presensi jika user tidak melakukan presesni di hari aktif';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /*logic here*/
        // User::find(502)->update(['name' => 'Rahmat Suregar']);

        $today = today()->addHours(23)->addMinutes(59)->addSeconds(59);
        $data = User::cekPegawaiHadirToday()->get();
        foreach ($data as $key => $value) {
            $libur = Jadwal::getJadwalPegawai($value->jadwal_id, $value->id)->is_today_libur;
            if (!$libur) {
                \DB::beginTransaction();
                try {
                    $masuk = Presensi::firstOrCreate([
                        'user_id' => $value->id,
                        'tipe' => 1,
                        'tanggal' => today()->format('Y-m-d')
                    ],[
                        'user_id' => $value->id,
                        'waktu' => $today,
                        'tanggal' => today()->format('Y-m-d'),
                        'tipe' => 1,
                        'status_presensi_id' => $value->status_pegawai == 1 ? 2:5,
                        'catatan' => 'Tidak checklock',
                        'jadwal_id' => $value->jadwal_id,
                        'foto' => 'no.jpg',
                        'ip_location' => request()->ip(),
                        'auto_presensi' => true
                    ]);

                    $pulang = Presensi::firstOrCreate([
                        'user_id' => $value->id,
                        'tipe' => 3,
                        'tanggal' => today()->format('Y-m-d')
                    ],[
                        'user_id' => $value->id,
                        'waktu' => $today,
                        'tanggal' => today()->format('Y-m-d'),
                        'tipe' => 3,
                        'status_presensi_id' => $value->status_pegawai == 1 ? 2:5,
                        'catatan' => 'Tidak checklock',
                        'jadwal_id' => $value->jadwal_id,
                        'foto' => 'no.jpg',
                        'ip_location' => request()->ip(),
                        'auto_presensi' => true
                    ]);
                    \DB::commit();
                    $this->info('Berhasil menambah presensi '.$value->name);
                    \Log::info('Berhasil menambah presensi '.$value->name);
                } catch (Error $th) {
                    \DB::rollback();
                    $this->error('Terjadi kesalahan menambah data '.$value->name);
                    \Log::error('Terjadi kesalahan menambah data '.$value->name);
                }
            }else{
                $this->warn('Hari Libur untuk '.$value->name);
                \Log::warning('Hari Libur untuk '.$value->name);
            }
        }


    }
}
