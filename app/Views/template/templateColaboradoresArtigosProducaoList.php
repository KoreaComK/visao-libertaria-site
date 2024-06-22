<?php use CodeIgniter\I18n\Time; ?>
<?php if ($artigos !== NULL && !empty($artigos)): ?>
	<?php foreach ($artigos as $artigo): ?>?>
		<tr>
			<!-- Table data -->
			<td>
				<img src="<?= $artigo['imagem']; ?>" style="width: 4rem; height auto;" />
			</td>
			<!-- Table data -->
			<td>
				<h6 class="mb-0"><a href="#"><?= $artigo['titulo']; ?></a></h6>
			</td>
			<!-- Table data -->
			<td><?= /*Time::createFromFormat('Y-m-d H:i:s', $artigo['atualizado'])->toLocalizedString('dd MMMM yyyy');*/ 'lala'; ?></td>
			<!-- Table data -->
			<td>
				<a href="#" class="badge text-bg-warning mb-2"></a>
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
						<a class="btn btn-light btn-round mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
							title="Descartar artigo"><i class="fas fa-trash-can"></i></a>
						<a class="btn btn-light btn-round mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
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
</script>