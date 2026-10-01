<?php

if (! function_exists('carteira_cadastrada')) {
	function carteira_cadastrada(mixed $carteira): bool
	{
		if ($carteira === null) {
			return false;
		}

		$carteira = trim((string) $carteira);
		if ($carteira === '') {
			return false;
		}

		return (new \App\Libraries\ValidaRegras())->carteira_bitcoin($carteira);
	}
}

if (! function_exists('colaborador_tem_carteira')) {
	function colaborador_tem_carteira(?int $colaboradorId = null): bool
	{
		if ($colaboradorId === null) {
			$colaboradores = session('colaboradores');
			$id = is_array($colaboradores) ? ($colaboradores['id'] ?? null) : null;
			if ($id === null || $id === '') {
				return false;
			}
			$colaboradorId = (int) $id;
		}

		if ($colaboradorId < 1) {
			return false;
		}

		$colaborador = model(\App\Models\ColaboradoresModel::class)
			->select('carteira')
			->find($colaboradorId);

		if (! is_array($colaborador)) {
			return false;
		}

		return carteira_cadastrada($colaborador['carteira'] ?? null);
	}
}
