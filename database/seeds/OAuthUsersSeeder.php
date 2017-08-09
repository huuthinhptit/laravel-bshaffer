<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OAuthUsersSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('oauth_users')->insert(array(
            'username' => "bshaffer",
            'password' => sha1('brent123'),
            'first_name' => "Brent",
            'last_name' => "Shaffer",
        ));
    }
}