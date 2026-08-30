<div class="pautas-list row">
	<?php
	$paginaNoticias = 1;
	if (! empty($pautasList['pager']) && is_object($pautasList['pager'])) {
		$paginaNoticias = max(1, (int) $pautasList['pager']->getCurrentPage('noticias'));
	}
	foreach ($pautasList['pautas'] as $indiceCard => $pauta):
		$pauta['_list_index'] = ($paginaNoticias === 1) ? (int) $indiceCard : 99;
		?>
		<?= view_cell('\App\Libraries\Cards::cardsVerticaisSimplesPautas', $pauta); ?>
	<?php endforeach; ?>
</div>
<div class="d-none">
	<?php if ($pautasList['pager']): ?>
		<?= $pautasList['pager']->simpleLinks('noticias', 'default_template') ?>
	<?php endif; ?>
</div>
