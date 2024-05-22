<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if (!empty($comentarios)): ?>
	<?php foreach ($comentarios as $chave => $comentario): ?>
		<div class="card-body <?= (count($comentarios) == $chave + 1) ? ('') : ('border-bottom'); ?> ml-1 mr-1 mb-1"
			id="<?= $comentario['id']; ?>">
			<div class="row">
				<div class='col-2'>
					<img src="<?= ($comentario['avatar'] != NULL) ? ($comentario['avatar']) : (site_url('public/assets/avatar-default.png')); ?>"
						class="rounded float-left mr-3" style="height:auto; max-width:inherit;">
				</div>
				<div class='col-10'>
					<span class="card-title badge bg-secondary"><?= $comentario['apelido']; ?></span> <span
						class="badge bg-primary"><?= Time::createFromFormat('Y-m-d H:i:s', $comentario['atualizado'])->toLocalizedString('dd MMMM yyyy H:mm:ss'); ?></span>
					<p class="card-text comentario-<?= $comentario['id']; ?>"><?= $comentario['comentario']; ?></p>
				</div>

			</div>
			<?php if ($colaborador == $comentario['colaboradores_id']): ?>
				<div class="row">
					<div class="col-6 text-start">
						<button class="btn btn-sm m-0 p-0 btn-link comentario-excluir" type="button"
							data-information="<?= $comentario['id']; ?>">Excluir Comentário</butto>
					</div>
					<div class="col-6 text-end">
						<button class="btn btn-sm m-0 p-0 btn-link comentario-alterar float-right" type="button"
							data-information="<?= $comentario['id']; ?>">Editar Comentário</button>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="card-body ml-1 mr-1 mb-1 text-center">
		<p class="card-text fs-6">Não há comentários neste artigo.</p>
	</div>
<?php endif; ?>

<script>
	$(document).ready(function () {
		$(".comentario-excluir").on("click", function (e) {
			var id_comentario = e.target.getAttribute('data-information');
			excluirComentario(id_comentario);
		});
	});
	$(document).ready(function () {
		$(".comentario-alterar").on("click", function (e) {
			var id_comentario = e.target.getAttribute('data-information');
			$('#id_comentario').val(id_comentario);
			$('#comentario').val($('.comentario-' + id_comentario).html());
			$('#comentario').focus();
		});
	});
</script>