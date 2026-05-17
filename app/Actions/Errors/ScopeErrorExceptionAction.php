<?php

namespace App\Actions\Errors;

use App\Framework\Logging\ScopedMonologHandler;

class ScopeErrorExceptionAction
{
	private readonly string $corePath;

	public function __construct()
	{
		$this->corePath = rtrim(wp_normalize_path(dirname(__DIR__, 3)), '/');
	}

	public function __invoke(bool $throw, \Throwable $e): bool
	{
		$file = wp_normalize_path($e->getFile());

		if (str_starts_with($file, $this->corePath)) {
			return true;
		}

		foreach (ScopedMonologHandler::registeredPaths() as $addonPath) {
			if (str_starts_with($file, $addonPath)) {
				return true;
			}
		}

		return false;
	}
}
