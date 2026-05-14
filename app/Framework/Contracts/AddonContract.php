<?php

namespace App\Framework\Contracts;

interface AddonContract
{
	public function addonSlug(): string;

	public function addonPath(): string;
}
