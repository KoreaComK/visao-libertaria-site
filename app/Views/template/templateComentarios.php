<?php

use CodeIgniter\I18n\Time;
?>
<?php helper('data') ?>
<?php if (!empty($comentarios)) : ?>
	<?php foreach ($comentarios as $chave => $comentario) : ?>
		<div class="card-body <?= (count($comentarios) == $chave + 1) ? ('') : ('border-bottom'); ?> ml-1 mr-1 mb-1" id="<?= $comentario['id']; ?>"><small><small>
			<img src="<?= ($comentario['avatar']!=NULL)?($comentario['avatar']):(site_url('public/assets/avatar-default.png')); ?>" class="rounded float-left mr-3" style="height:auto; width:4.5rem;">	
				<span class="card-title badge badge-secondary"><?= $comentario['apelido']; ?></span> <span class="badge badge-primary"><?= Time::createFromFormat('Y-m-d H:i:s', $comentario['atualizado'])->toLocalizedString('dd MMMM yyyy H:mm:ss'); ?></span>
					<p class="card-text comentario-<?= $comentario['id']; ?>"><?= $comentario['comentario']; ?></p>
					<?php if ($colaborador == $comentario['colaboradores_id']) : ?>
						<p class="card-text">
							<button class="btn btn-sm m-0 p-0 btn-link comentario-excluir" type="button" data-information="<?= $comentario['id']; ?>">Excluir Comentário</button>
							<button class="btn btn-sm m-0 p-0 btn-link comentario-alterar float-right" type="button" data-information="<?= $comentario['id']; ?>">Editar Comentário</button>
						</p>
						<p class="card-text"></p>
					<?php endif; ?>
				</small></small>
		</div>
	<?php endforeach; ?>
<?php else : ?>
	<div class="card-body ml-1 mr-1 mb-1 text-center"><small><small>
				<p class="card-text">Não há comentários neste artigo.</p>
			</small></small>
	</div>
<?php endif; ?>

<script>
	$(document).ready(function() {
		$(".comentario-excluir").on("click", function(e) {
			var id_comentario = e.target.getAttribute('data-information');
			excluirComentario(id_comentario);
		});
	});
	$(document).ready(function() {
		$(".comentario-alterar").on("click", function(e) {
			var id_comentario = e.target.getAttribute('data-information');
			$('#id_comentario').val(id_comentario);
			$('#comentario').val($('.comentario-'+id_comentario).html());
			$('#comentario').focus();
		});
	});
</script>