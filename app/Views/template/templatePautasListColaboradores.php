<div class="pautas-list row">
	<?php foreach ($pautasList['pautas'] as $pauta): ?>
		<?= view_cell('\App\Libraries\Cards::cardsVerticaisSimplesPautas', $pauta); ?>
	<?php endforeach; ?>
</div>
<div class="d-none">
	<?php if ($pautasList['pager']): ?>
		<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
	<?php endif; ?>
</div>