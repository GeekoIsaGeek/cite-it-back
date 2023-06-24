<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueJsonField implements ValidationRule
{
	private $model;

	private $column;

	private $key;

	public function __construct($model, $column, $key)
	{
		$this->model = $model;
		$this->column = $column;
		$this->key = $key;
	}

	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if ($this->model::whereJsonContains($this->column, [$this->key => $value])->count() > 0) {
			$fail(trans('validation.unique', ['attribute' => $attribute]));
		}
	}
}
