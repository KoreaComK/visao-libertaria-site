<?php

use CodeIgniter\I18n\Time;

$pager = $artigosList['pager'] ?? null;
$artigosPagina = $artigosList['artigos'] ?? [];
$totalRegistros = isset($artigosList['total']) ? (int) $artigosList['total'] : count($artigosPagina);
$temLinhas = $artigosPagina !== null && !empty($artigosPagina);
?>
<table class="table table-sm align-middle mb-0 table-hover table-shrink">
	<thead class="listagem-site-thead">
		<tr>
			<th scope="col">Título</th>
			<th scope="col">Escritor</th>
			<th scope="col">Publicado em</th>
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
							href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']) ?>"><?= esc($artigo['titulo']); ?></a>
					</td>
					<td class="small">
						<a class="text-decoration-none"
							href="<?= site_url('site/escritor/' . urlencode($artigo['escrito'])); ?>"><?= esc($artigo['escrito']); ?></a>
					</td>
					<td class="small text-nowrap">
						<?= ($artigo['data_publicado'] != null)
							? Time::createFromFormat('Y-m-d H:i:s', $artigo['data_publicado'])->toLocalizedString('dd MMM yyyy')
							: '—'; ?>
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
				<td colspan="5" class="p-0">
					<div class="text-center py-5 px-3">
						<i class="far fa-folder-open fa-2x text-muted mb-2 d-block" aria-hidden="true"></i>
						<p class="fw-semibold text-body mb-1">Nenhum artigo encontrado</p>
						<p class="small text-muted mb-0">Ajuste os filtros ou limpe a pesquisa para ver mais resultados.</p>
					</div>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<div class="mt-2 mb-0 d-flex justify-content-center py-2 border-top bg-body-secondary bg-opacity-25">
	<?php if ($pager): ?>
		<?= $pager->simpleLinks('artigos', 'default_template') ?>
	<?php endif; ?>
</div>
<script>
	(function () {
		var total = <?= (int) $totalRegistros; ?>;
		var label = total === 0
			? 'Nenhum artigo no total'
			: (total === 1 ? '1 artigo no total' : total + ' artigos no total');
		$('.listagem-site-contador').text(label);

		$('.tabela-publicado .page-link').off('click.listagemSite').on('click.listagemSite', function (e) {
			e.preventDefault();
			var href = $(this).attr('href');
			if (href) {
				refreshListPublicado(href);
			}
		});
	})();
</script>
