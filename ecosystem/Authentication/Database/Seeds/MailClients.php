<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MailClients extends Seeder
{
	public function run()
	{
		$data = [
			'id' 			=> 1,
			'client' 		=> 'App Mailer',
			'client_slug' 	=> 'app_mailer',
			'client_icon' 	=> null,
			'is_active'		=> '1',
		];

		$model = model('Ecosystem\Authentication\Models\MailClient');
		$model->insert($data);
	}
}
