<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Actions\SendVerificationEmail;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(SendVerificationEmail::class, function () {
			return new SendVerificationEmail();
		});
	}
}
