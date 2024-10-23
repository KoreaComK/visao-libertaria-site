<?php

namespace App\Libraries;

class Cards
{
	public function cardsHorizontais($conteudo)
	{
		return view('components/cards-horizontais',array('dados'=>$conteudo));
	}

	public function cardsVerticaisSimples($conteudo) {
		return view('components/cards-verticais-simples',array('dados'=>$conteudo));
	}

	public function cardsVerticaisSimplesPautas($conteudo) {
		return view('components/cards-verticais-simples-pautas',array('dados'=>$conteudo));
	}
}