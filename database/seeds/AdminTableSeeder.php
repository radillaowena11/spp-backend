<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
        'nama_petugas'  => 'Radilla Nathaniel',
        'username'      => 'nathaniel',
        'password'      => bcrypt ('123456'),
        'level'         => 'admin',
        ]);
        
    }
}
