<?php

if (! function_exists('avatar_padrao_url')) {
	function avatar_padrao_url(): string
	{
		return site_url('public/assets/avatar-default.png');
	}
}

if (! function_exists('avatar_personalizado')) {
	function avatar_personalizado(mixed $avatar): bool
	{
		if ($avatar === null) {
			return false;
		}

		return trim((string) $avatar) !== '';
	}
}

if (! function_exists('avatar_url')) {
	function avatar_url(mixed $avatar = null): string
	{
		if (! avatar_personalizado($avatar)) {
			return avatar_padrao_url();
		}

		return trim((string) $avatar);
	}
}
