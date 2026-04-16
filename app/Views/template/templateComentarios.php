<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if (!empty($comentarios)): ?>
	<?php foreach ($comentarios as $chave => $comentario): ?>
		<div class="card-body small py-2 px-3 <?= (count($comentarios) == $chave + 1) ? ('') : ('border-bottom border-opacity-25'); ?>"
			id="<?= esc($comentario['id'], 'attr'); ?>">
			<div class="row g-2 align-items-start">
				<div class="col-auto" style="width: 3.25rem;">
					<img src="<?= ($comentario['avatar'] != null && $comentario['avatar'] !== '') ? esc($comentario['avatar'], 'url') : site_url('public/assets/avatar-default.png'); ?>"
						class="rounded img-fluid" alt="" style="max-width: 100%; height: auto;">
				</div>
				<div class="col text-break">
					<div class="d-flex flex-wrap align-items-center gap-1 mb-1">
						<span class="badge bg-secondary"><?= esc($comentario['apelido']); ?></span>
						<span class="badge bg-primary text-wrap"><?= Time::createFromFormat('Y-m-d H:i:s', $comentario['atualizado'])->toLocalizedString('dd MMMM yyyy H:mm:ss'); ?></span>
					</div>
					<p class="card-text small mb-0 comentario-<?= esc($comentario['id']); ?>"><?= esc($comentario['comentario']); ?></p>
				</div>

			</div>
			<?php if ($colaborador == $comentario['colaboradores_id']): ?>
				<div class="row mt-2">
					<div class="col-6 text-start">
						<button class="btn btn-sm m-0 p-0 btn-link comentario-excluir" type="button"
							data-information="<?= esc($comentario['id'], 'attr'); ?>">Excluir comentário</button>
					</div>
					<div class="col-6 text-end">
						<button class="btn btn-sm m-0 p-0 btn-link comentario-alterar" type="button"
							data-information="<?= esc($comentario['id'], 'attr'); ?>">Editar comentário</button>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="card-body small py-3 px-3 text-center text-muted">
		<p class="card-text small mb-0">Não há comentários neste artigo</p>
	</div>
<?php endif; ?>

<script>
	$(document).ready(function () {
		$(".comentario-excluir").on("click", function (e) {
			var id_comentario = e.currentTarget.getAttribute('data-information');
			excluirComentario(id_comentario);
		});
	});
	$(document).ready(function () {
		$(".comentario-alterar").on("click", function (e) {
			var id_comentario = e.currentTarget.getAttribute('data-information');
			$('#id_comentario').val(id_comentario);
			$('#comentario').val($('.comentario-' + id_comentario).text());
			$('#comentario').focus();
		});
	});
</script>