<?php use CodeIgniter\I18n\Time; ?>
<table class="table align-middle p-4 mb-0 table-hover table-shrink">
	<!-- Table head -->
	<thead class="table-dark">
		<tr style="vertical-align: middle !important;">
			<th scope="col" class="border-0 rounded-start"></th>
			<th scope="col" class="border-0">Título</th>
			<th scope="col" class="border-0">Escritor</th>
			<th scope="col" class="border-0">Última atualização</th>
			<th scope="col" class="border-0">Data limite</th>
			<th scope="col" class="border-0">Tipo</th>
			<th scope="col" class="border-0">Marcado</th>
			<th scope="col" class="border-0 rounded-end"></th>
		</tr>
	</thead>

	<!-- Table body START -->
	<tbody class="border-top-0">
		<?php if ($artigosList['artigos'] !== NULL && !empty($artigosList['artigos'])): ?>
			<?php foreach ($artigosList['artigos'] as $artigo): ?>
				<tr
					class="<?= ($colaborador === $artigo['marcado_colaboradores_id']) ? ('table-primary bg-opacity-10') : (($artigo['marcado_colaboradores_id'] != NULL) ? ('table-danger bg-opacity-10') : ('')); ?>">
					<!-- Table data -->
					<td>
						<img class="rounded-3" src="<?= $artigo['imagem']; ?>" style="width: 4rem; height auto;" />
					</td>
					<!-- Table data -->
					<td>
						<h6 class="mb-0"><a href="<?=site_url('colaboradores/artigos/detalhamento/'.$artigo['id'])?>"><?= $artigo['titulo']; ?></a></h6>
					</td>
					<td>
						<h6 class="mb-0"><a href="<?=site_url('site/escritor/'.urlencode($artigo['escrito'])); ?>"><?= $artigo['escrito']; ?></a></h6>
					</td>
					<!-- Table data -->
					<td><?= Time::createFromFormat('Y-m-d H:i:s', $artigo['atualizado'])->toLocalizedString('dd MMMM yyyy'); ?>
					</td>
					<td><?= Time::createFromFormat('Y-m-d H:i:s', $artigo['limite'])->toLocalizedString('dd MMMM yyyy'); ?>
					</td>
					<!-- Table data -->
					<td>
						<a href="#"
							class="badge text-bg-<?= ($artigo['tipo_artigo'] == 'T') ? ('primary') : ('danger'); ?> mb-2"><?= ($artigo['tipo_artigo'] == 'T') ? ('Teórico') : ('Notícia'); ?></a>
					</td>
					<!-- Table data -->
					<td>
						<span
							class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?> mb-2"><?= ($artigo['marcado'] != NULL) ? ($artigo['marcado']) : ('Disponível'); ?></span>
					</td>
					<!-- Table data -->
					<td class="">
						<div class="d-flex">

							<div class="d-flex gap-2">
								<a data-bs-toggle="modal" data-bs-target="#modalPrevia" data-vl-artigo="<?= $artigo['id'] ?>"
									class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
									data-placement="top" title="Prévia do artigo"><i class="fas fa-glasses"></i></a>
							</div>
							<?php if ($colaborador === $artigo['marcado_colaboradores_id']): ?>
								<div class="d-flex gap-2 ms-2">
									<a data-bs-toggle="modal" data-bs-target="#mi-modal" data-vl-artigo="<?= $artigo['id'] ?>"
										class="btn btn-light btn-floating mb-0 btn-tooltip btn-desmarcar" data-toggle="tooltip"
										data-placement="top" title="Desmarcar"><i class="fas fa-xmark"></i></a>
								</div>
								<?php if ($fase_producao_id == '2'): //revisão ?>
									<div class="d-flex gap-2 ms-2">
										<a href="<?= site_url('colaboradores/artigos/cadastrar/') . $artigo['id']; ?>"
											class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
											data-placement="top" title="Revisar"><i class="fas fa-pen-to-square"></i></a>
									</div>
								<?php elseif ($fase_producao_id == '3'): //narração ?>
									<div class="d-flex gap-2 ms-2">
										<a href="<?= site_url('colaboradores/artigos/detalhamento/') . $artigo['id']; ?>"
											class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
											data-placement="top" title="Narrar"><i class="fas fa-microphone"></i></a>
									</div>
								<?php elseif ($fase_producao_id == '4'): //producao ?>
									<div class="d-flex gap-2 ms-2">
										<a href="<?= site_url('colaboradores/artigos/detalhamento/') . $artigo['id']; ?>"
											class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
											data-placement="top" title="Produzir"><i class="fas fa-video"></i></a>
									</div>
								<?php endif; ?>
							<?php else: ?>

								<?php if (($colaborador != $artigo['escrito_colaboradores_id']) || ($colaborador == $artigo['escrito_colaboradores_id'] && $fase_producao_id != '2')): ?>
									<?php if ($fase_producao_id != '5'): ?>
										<div class="d-flex gap-2 ms-2">
											<a data-bs-toggle="modal" data-bs-target="#mi-modal" data-vl-artigo="<?= $artigo['id'] ?>"
												class="btn btn-light btn-floating mb-0 btn-tooltip btn-marcar" data-toggle="tooltip"
												data-placement="top" title="Marcar artigo"><i class="fas fa-bookmark"></i></a>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ($fase_producao_id == '5'): //publicação ?>
									<div class="d-flex gap-2 ms-2">
										<a href="<?= site_url('colaboradores/artigos/detalhamento/') . $artigo['id']; ?>"
											class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
											data-placement="top" title="Publicar"><i class="fab fa-youtube"></i></a>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<!-- Table data -->
				<td colspan="8">
					<h6 class="text-center">Nenhum resultado foi encontrado</h6>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
	<!-- Table body END -->
</table>
<div class="mt-3 d-flex justify-content-center">
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