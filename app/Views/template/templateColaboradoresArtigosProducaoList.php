<?php use CodeIgniter\I18n\Time; ?>
<?php if ($artigos !== NULL && !empty($artigos)): ?>
	<?php foreach ($artigos as $artigo): ?>?>
		<tr>
			<!-- Table data -->
			<td>
				<img class="rounded-3" src="<?= $artigo['imagem']; ?>" style="width: 4rem; height auto;" />
			</td>
			<!-- Table data -->
			<td>
				<h6 class="mb-0"><a href="#"><?= $artigo['titulo']; ?></a></h6>
			</td>
			<!-- Table data -->
			<td><?= Time::createFromFormat('Y-m-d H:i:s', $artigo['atualizado'])->toLocalizedString('dd MMMM yyyy'); ?></td>
			<!-- Table data -->
			<td>
				<a href="#" class="badge text-bg-<?= ($artigo['tipo_artigo']=='T')?('primary'):('danger');?> mb-2"><?= ($artigo['tipo_artigo']=='T')?('Teórico'):('Notícia');?></a>
			</td>
			<!-- Table data -->
			<td>
				<span
					class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?> mb-2"><?= $artigo['nome']; ?></span>
			</td>
			<!-- Table data -->
			<td>
				<div class="d-flex gap-2">
					<?php if ($artigo['fase_producao_id'] == '1'): ?>
						<a class="btn btn-light btn-floating mb-0 btn-tooltip btn-descartar" data-artigo-id="<?= $artigo['id']; ?>"
							data-toggle="tooltip" data-placement="top" title="Descartar artigo"><i class="fas fa-trash-can"></i></a>
						<a href="<?=site_url('colaboradores/artigos/cadastrar/').$artigo['id'];?>" class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
							title="Continuar escrevendo"><i class="fas fa-pencil"></i></a>
					<?php endif; ?>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>
<?php else: ?>
	<tr>
		<!-- Table data -->
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