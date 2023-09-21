<?php

namespace App\Libraries;

use App\Models\CategoriasModel;
use App\Models\LayoutSiteModel;
use App\Models\PautasModel;

class WidgetsSite {
	
	public function widgetCategorias() : array
	{
		$categoriasModel = new categoriasModel();
		$categorias = $categoriasModel->orderBy('nome')->findAll();
		if($categorias === false)
		{   
			return array();
		}
		return $categorias;
	}

	public function widgetArtigosByFaseProducaoCount() : array
	{
		$db = db_connect();
		$layoutSiteModel = new LayoutSiteModel($db);
		$pautasModel = new PautasModel();

		$pautas = $pautasModel->countAllResults();
		$fases = $layoutSiteModel->widgetEsteiraProducao();

		$resultado = array('Pautas'=>$pautas);
		foreach($fases as $fase)
		{
			$resultado[$fase->nome] = (int)$fase->quantidade;
		}
		if($resultado === false)
		{   
			return array();
		}
		return $resultado;
	}
	
}