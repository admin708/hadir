<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupdb:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $today = today()->format('Y-m-d');
        // if(!is_dir(storage_path('backups'))) mkdir(storage_path('backups'));

        // $this->process = new Process(sprintf(
        //     'mysqldump --compact --skip-comments -u%s -p%p %s > %s',
        //     config('database.connections.mysql.username'),
        //     config('database.connections.mysql.password'),
        //     config('database.connections.mysql.database'),
        //     storage_path("backups/{$today}.sql")
        // ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // try{
        //     $this->process->mustRun();
        //     \Log::info('oke');
        // }catch(ProcessFailedException $th){
        //     \Log::error('Gagal');
        // }
    }
}
