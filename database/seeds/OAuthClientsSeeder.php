<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OAuthClientsSeeder extends Seeder
{
	public function run()
	{
		\Illuminate\Support\Facades\DB::table('oauth_clients')->insert(array(
			'client_id' => "testclient",
			'client_secret' => "testpass",
			'redirect_uri' => "http://fake/",
		));
	}
}