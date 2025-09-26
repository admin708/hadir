<?php

namespace App\Imports;

use App\User;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PegawaiImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        if (!isset($row['nip'])) {
            return null;
        }

        if (!isset($row['nip']) && !isset($row['username'])) {
            return abort(403);
        }

        // return new User([
        //     'name' => $row['nama_lengkap'],
        //     'username' => $row['nip'],
        //     'role_id' => 'pegawai',
        //     'unitkerja_id' => $row['unit_kerja'],
        //     'jadwal_id' => $row['jadwal_pegawai'],
        //     'email' => isset($row['email']) ? $row['email']:null,
        //     'email_verified_at' => now(),
        //     'password' => \bcrypt(isset($row['password']) ? $row['password']:$row['nip']),
        //     'remember_token' => \Str::random(10)
        // ]);

        return User::updateOrCreate([
            'username' => $row['nip']
        ],[
            'name' => $row['nama_lengkap'],
            'username' => $row['nip'],
            'role_id' => 'pegawai',
            'unitkerja_id' => $row['unit_kerja'],
            'jadwal_id' => $row['jadwal_pegawai'],
            'email' => isset($row['email']) ? $row['email']:null,
            'email_verified_at' => now(),
            'password' => \bcrypt(isset($row['password']) ? $row['password']:$row['nip']),
            'remember_token' => \Str::random(10)
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
