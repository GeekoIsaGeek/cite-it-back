<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidationServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		Validator::extend('only_lowercase', function ($attribute, $value, $parameters, $validator): bool {
			$regex = '/^[a-z0-9]+$/';
			if (preg_match($regex, $value)) {
				return true;
			}
			return false;
		});
	}
}
