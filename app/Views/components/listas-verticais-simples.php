<?php
use CodeIgniter\I18n\Time;

/*
Variáveis:

listas = {
    lista {
        Variáveis da Lista Escolhida
    }
}
urlComponente = link para o Componente
classeListaCSS = Onde ficarão os cards
*/
?>

<?php if ($listas['lista'] !== NULL && !empty($listas['lista'])): ?>
	<?php foreach ($listas['lista'] as $lista): ?>
        <?= view_cell($urlComponente, $lista); ?>
	<?php endforeach; ?>
<?php else: ?>
	<div class="text-center">
		<h6 class="text-center">Nenhum resultado foi encontrado.</h6>
	</div>
<?php endif; ?>
<div class="mt-3 d-flex justify-content-center">
	<?php if ($listas['pager']): ?>
		<?= $listas['pager']->simpleLinks('lista', 'default_template') ?>
	<?php endif; ?>
</div>
<script>
	$(function () {
		$('.btn-tooltip').tooltip();
	});

	$(document).ready(function () {
		$('.page-link ').on('click', function (e) {
			e.preventDefault();
			$.ajax({
				url: e.target.href,
				type: 'get',
				dataType: 'html',
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (data) {
					$('.<?=$classeListaCSS;?>').html(data);
				}
			});
		});
	});
</script>
</div>