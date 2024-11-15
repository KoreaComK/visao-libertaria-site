<?php use CodeIgniter\I18n\Time; ?>
<?php if ($artigos !== NULL && !empty($artigos)): ?>
	<table class="table align-middle p-4 mb-0 table-hover table-shrink">
		<thead class="table-dark">
			<tr style="vertical-align: middle !important;">
				<th scope="col" class="border-0 rounded-start"></th>
				<th scope="col" class="border-0">Título</th>
				<th scope="col" class="border-0">Atualizado em</th>
				<th scope="col" class="border-0">Tipo do artigo</th>
				<th scope="col" class="border-0">Status</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<tbody class="border-top-0">
			<?php foreach ($artigos as $artigo): ?>
				<tr>
					<td>
						<img class="rounded-3" src="<?= $artigo['imagem']; ?>" style="width: 4rem; height auto;" />
					</td>
					<td>
						<h6 class="mb-0"><a
								href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']) ?>"><?= $artigo['titulo']; ?></a>
						</h6>
					</td>
					<td><?= Time::createFromFormat('Y-m-d H:i:s', $artigo['atualizado'])->toLocalizedString('dd MMMM yyyy'); ?>
					</td>
					<td>
						<a href="#"
							class="badge text-bg-<?= ($artigo['tipo_artigo'] == 'T') ? ('primary') : ('danger'); ?> mb-2"><?= ($artigo['tipo_artigo'] == 'T') ? ('Teórico') : ('Notícia'); ?></a>
					</td>
					<td>
						<span
							class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?> mb-2"><?= $artigo['nome']; ?></span>
					</td>
					<td>
						<div class="d-flex gap-2">
							<?php if ($artigo['fase_producao_id'] == '1'): ?>
								<a class="btn btn-light btn-floating mb-0 btn-tooltip btn-descartar"
									data-artigo-id="<?= $artigo['id']; ?>" data-toggle="tooltip" data-placement="top"
									title="Descartar artigo"><i class="fas fa-trash-can"></i></a>
								<a href="<?= site_url('colaboradores/artigos/cadastrar/') . $artigo['id']; ?>"
									class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
									title="Continuar escrevendo"><i class="fas fa-pencil"></i></a>
							<?php endif; ?>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	<?php else: ?>
		<tr>
			<td colspan="6">
				<h6 class="text-center">Nenhum resultado foi encontrado</h6>
			</td>
		</tr>
	<?php endif; ?>
	<script>
		$(function () {
			$('.btn-tooltip').tooltip();
		});

		var artigoId = null;

		$(".btn-descartar").on("click", function (e) {
			$('.conteudo-modal').html('Deseja realmente descartar este artigo?');
			artigoId = $(e.currentTarget).attr('data-artigo-id');
			$("#mi-modal").modal('show');
		});

		$("#modal-btn-no").on("click", function () {
			$("#mi-modal").modal('hide');
		});
	</script>