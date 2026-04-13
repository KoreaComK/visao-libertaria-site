<?php use CodeIgniter\I18n\Time; ?>
<style>
	.tabela-publicado .table thead.listagem-site-thead th {
		background-color: var(--bs-secondary-bg) !important;
		color: var(--bs-body-color);
		font-weight: 600;
		font-size: 0.7rem;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		border-bottom: 1px solid var(--bs-border-color) !important;
		box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
		vertical-align: middle;
	}
	[data-bs-theme="dark"] .tabela-publicado .table thead.listagem-site-thead th,
	[data-mdb-theme="dark"] .tabela-publicado .table thead.listagem-site-thead th {
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.08);
	}
</style>
<table class="table table-sm align-middle mb-0 table-hover table-shrink">
	<thead class="listagem-site-thead">
		<tr>
			<th scope="col">Título</th>
			<th scope="col">Escritor</th>
			<th scope="col">Tipo</th>
			<th scope="col">Estado</th>
			<th scope="col" class="text-nowrap">Prazos</th>
			<th scope="col" class="text-end text-nowrap">Ações</th>
		</tr>
	</thead>
	<tbody class="border-top-0">
		<?php if ($artigosList['artigos'] !== NULL && !empty($artigosList['artigos'])): ?>
			<?php foreach ($artigosList['artigos'] as $artigo): ?>
				<tr
					class="<?= ($colaborador === $artigo['marcado_colaboradores_id']) ? ('table-primary bg-opacity-10') : (($artigo['marcado_colaboradores_id'] != NULL) ? ('table-danger bg-opacity-10') : ('')); ?>">
					<td>
						<a href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']) ?>"
							class="fw-semibold small text-decoration-none btn-tooltip <?= ($artigo['anti_ia_contador'] >= $antiIaLimiteMax) ? ('text-danger') : (($artigo['anti_ia_contador'] >= $antiIaLimiteMin && $artigo['anti_ia_contador'] < $antiIaLimiteMax) ? ('text-warning') : ('')); ?>"
							data-toggle="tooltip" data-placement="top" title="<?= ($artigo['anti_ia_contador'] >= $antiIaLimiteMax) ? ('Texto com alto risco de ser escrito por I.A.') : (($artigo['anti_ia_contador'] >= $antiIaLimiteMin && $artigo['anti_ia_contador'] < $antiIaLimiteMax) ? ('Texto possivelmente escrito por I.A.') : ('')); ?>"><?= $artigo['titulo']; ?></a>
					</td>
					<td class="small">
						<a class="text-decoration-none"
							href="<?= site_url('site/escritor/' . urlencode($artigo['escrito'])); ?>"><?= $artigo['escrito']; ?></a>
					</td>
					<td>
						<span
							class="badge text-bg-<?= ($artigo['tipo_artigo'] == 'T') ? ('primary') : ('danger'); ?>"><?= ($artigo['tipo_artigo'] == 'T') ? ('Teórico') : ('Notícia'); ?></span>
					</td>
					<td>
						<span
							class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?>"><?= ($artigo['marcado'] != NULL) ? ($artigo['marcado']) : ('Disponível'); ?></span>
					</td>
					<td class="small text-muted">
						<div class="text-nowrap">Últ. Atual.<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['atualizado'])->toLocalizedString('dd MMM yyyy'); ?></div>
						<div class="text-nowrap">Data Limite <?= Time::createFromFormat('Y-m-d H:i:s', $artigo['limite'])->toLocalizedString('dd MMM yyyy'); ?></div>
					</td>
					<td class="text-end">
						<div class="d-inline-flex flex-wrap gap-2 justify-content-end">
							<?php if ($colaborador === $artigo['marcado_colaboradores_id']): ?>
								<a data-bs-toggle="modal" data-bs-target="#mi-modal"
									data-vl-artigo="<?= $artigo['id'] ?>"
									class="btn btn-light btn-floating mb-0 btn-tooltip btn-desmarcar"
									data-toggle="tooltip" data-placement="top" title="Desmarcar"><i
										class="fas fa-xmark"></i></a>
								<?php if ($fase_producao_id == '2'): //revisão ?>
									<a href="<?= site_url('colaboradores/artigos/cadastrar/') . $artigo['id']; ?>"
										class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
										data-placement="top" title="Revisar"><i class="fas fa-pen-to-square"></i></a>
								<?php elseif ($fase_producao_id == '3'): //narração ?>
									<a href="<?= site_url('colaboradores/artigos/detalhamento/') . $artigo['id']; ?>"
										class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
										data-placement="top" title="Narrar"><i class="fas fa-microphone"></i></a>
								<?php elseif ($fase_producao_id == '4'): //producao ?>
									<a href="<?= site_url('colaboradores/artigos/detalhamento/') . $artigo['id']; ?>"
										class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
										data-placement="top" title="Produzir"><i class="fas fa-video"></i></a>
								<?php endif; ?>
							<?php else: ?>

								<?php if (($colaborador != $artigo['escrito_colaboradores_id']) || ($colaborador == $artigo['escrito_colaboradores_id'] && $fase_producao_id != '2')): ?>
									<?php if ($fase_producao_id != '5'): ?>
										<a data-bs-toggle="modal" data-bs-target="#mi-modal"
											data-vl-artigo="<?= $artigo['id'] ?>"
											class="btn btn-light btn-floating mb-0 btn-tooltip btn-marcar" data-toggle="tooltip"
											data-placement="top" title="Marcar artigo"><i class="fas fa-bookmark"></i></a>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ($fase_producao_id == '5'): //publicação ?>
									<a href="<?= site_url('colaboradores/artigos/detalhamento/') . $artigo['id']; ?>"
										class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
										data-placement="top" title="Publicar"><i class="fab fa-youtube"></i></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6" class="p-0">
					<div class="text-center py-5 px-3">
						<i class="far fa-folder-open fa-2x text-muted mb-2 d-block" aria-hidden="true"></i>
						<p class="fw-semibold text-body mb-1">Nenhum artigo encontrado</p>
						<p class="small text-muted mb-0">Ajuste a pesquisa ou o tipo de artigo.</p>
					</div>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<div class="mt-2 mb-0 d-flex justify-content-center py-2 border-top bg-body-secondary bg-opacity-25">
	<?php if ($artigosList['pager']): ?>
		<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
	<?php endif; ?>
</div>
<script>
	$(function () {
		$('.btn-tooltip').tooltip();
	});

	$('.btn-tooltip').on('click', function (e) {
		showPrevia($(e.currentTarget).attr('data-vl-artigo'));
	});

	$(document).ready(function () {
		$('.page-link').on('click', function (e) {
			e.preventDefault();
			refreshListPublicado(e.target.href, $("input[name='fase_producao']:checked").val());
		});
	});

	$(".btn-desmarcar").on("click", function (e) {
		$('.conteudo-modal').html('Deseja desmarcar este artigo?');
		artigoId = $(e.currentTarget).attr('data-vl-artigo');
		$("#mi-modal").modal('show');
		$("#modal-btn-si").addClass('modal-btn-confirma-desmarcar');
		document.getElementById('mi-modal').addEventListener('hide.bs.modal', function (event) {
			$("#modal-btn-si").removeClass('modal-btn-confirma-desmarcar');
		});
	});

	$(".btn-marcar").on("click", function (e) {
		$('.conteudo-modal').html('Deseja marcar este artigo?');
		artigoId = $(e.currentTarget).attr('data-vl-artigo');
		$("#mi-modal").modal('show');
		$("#modal-btn-si").addClass('modal-btn-confirma-marcar');
		document.getElementById('mi-modal').addEventListener('hide.bs.modal', function (event) {
			$("#modal-btn-si").removeClass('modal-btn-confirma-marcar');
		});
	});

	$('.fase-producao-nome').html('<?= $atualizar['nome']; ?>');
</script>
