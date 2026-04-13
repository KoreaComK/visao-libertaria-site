<?php

use CodeIgniter\I18n\Time;

$pager = $artigosList['pager'] ?? null;
$artigosPagina = $artigosList['artigos'] ?? [];
$incluirDescartados = !empty($artigosList['incluir_descartados']);
$temLinhas = $artigosPagina !== null && !empty($artigosPagina);
?>
<table class="table table-sm align-middle mb-0 table-hover">
	<thead class="listagem-site-thead">
		<tr>
			<th scope="col">Título</th>
			<th scope="col"><?= $incluirDescartados ? 'Publicado / descarte' : 'Publicado em'; ?></th>
			<th scope="col">Tipo</th>
			<th scope="col">Status</th>
		</tr>
	</thead>
	<tbody class="border-top-0">
		<?php if ($temLinhas): ?>
			<?php foreach ($artigosPagina as $artigo): ?>
				<tr>
					<td>
						<a class="fw-semibold small text-decoration-none"
							href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']); ?>"><?= esc($artigo['titulo']); ?></a>
					</td>
					<td class="small">
						<?php if (!empty($artigo['descartado'])): ?>
							<span class="text-muted d-block text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.04em;">Descartado</span>
							<span class="text-nowrap"><?= Time::createFromFormat('Y-m-d H:i:s', $artigo['descartado'])->toLocalizedString('dd MMM yyyy'); ?></span>
						<?php elseif (!empty($artigo['publicado'])): ?>
							<span class="text-nowrap"><?= Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('dd MMM yyyy'); ?></span>
						<?php else: ?>
							<span class="text-muted">—</span>
						<?php endif; ?>
					</td>
					<td>
						<span class="badge text-bg-<?= ($artigo['tipo_artigo'] == 'T') ? ('primary') : ('danger'); ?>">
							<?= ($artigo['tipo_artigo'] == 'T') ? ('Teórico') : ('Notícia'); ?>
						</span>
					</td>
					<td>
						<span class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?>">
							<?= esc($artigo['nome']); ?>
						</span>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="4" class="p-0">
					<div class="text-center py-5 px-3">
						<i class="fas fa-folder-open fa-2x text-muted mb-3 d-block" aria-hidden="true"></i>
						<p class="fw-semibold text-body mb-1">Nenhum resultado nesta pesquisa</p>
						<p class="small text-muted mb-0">Ajuste o título, marque ou desmarque <strong>Incluir artigos descartados</strong> ou veja os artigos em produção no quadro acima.</p>
					</div>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<div class="mt-2 mb-0 d-flex justify-content-center py-2 border-top bg-body-secondary bg-opacity-25">
	<?php if ($pager): ?>
		<?= $pager->simpleLinks('artigos', 'default_template'); ?>
	<?php endif; ?>
</div>
<script>
	(function () {
		$('.tabela-meus-publicados .page-link').off('click.meusPublicados').on('click.meusPublicados', function (e) {
			e.preventDefault();
			var href = $(this).attr('href');
			if (href) {
				refreshMeusPublicados(href);
			}
		});
	})();
</script>
