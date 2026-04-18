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
pagerQueryParams = opcional, array associativo ex. ['papel' => 'escrito'] para anexar às URLs da paginação (AJAX)
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
<div class="col-12 mt-4 d-flex justify-content-center">
	<?php if ($listas['pager']): ?>
		<?= $listas['pager']->simpleLinks('lista', 'default_template') ?>
	<?php endif; ?>
</div>
<script>
	$(function () {
		$('.btn-tooltip').tooltip();
	});

	<?php if (empty($omitPagerAjaxDelegado ?? false)): ?>
	(function (listaCss, pagerExtra) {
		var ns = '.vlListaPager_' + listaCss.replace(/[^a-zA-Z0-9_-]/g, '_');
		pagerExtra = pagerExtra && typeof pagerExtra === 'object' && !Array.isArray(pagerExtra) ? pagerExtra : {};

		function mergeQuery(url) {
			try {
				var u = new URL(url, window.location.href);
				Object.keys(pagerExtra).forEach(function (k) {
					u.searchParams.set(k, String(pagerExtra[k]));
				});
				return u.toString();
			} catch (e) {
				return url;
			}
		}

		$(document).off('click' + ns, '.' + listaCss + ' .page-link').on('click' + ns, '.' + listaCss + ' .page-link', function (e) {
			e.preventDefault();
			$.ajax({
				url: mergeQuery(this.href),
				type: 'get',
				dataType: 'html',
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (data) {
					$('.' + listaCss).html(data);
				}
			});
		});
	})('<?= esc($classeListaCSS, 'js'); ?>', <?= json_encode(isset($pagerQueryParams) && is_array($pagerQueryParams) ? $pagerQueryParams : new \stdClass(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>);
	<?php endif; ?>
</script>
