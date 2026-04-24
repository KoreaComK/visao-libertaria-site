<?php

/**
 * Modal "Comentários da Pauta" + init JS + listener de abertura.
 * Usar em páginas de pautas (fechar, fechadas/ID, etc.) com o mesmo endpoint de comentários.
 *
 * @var array<string, mixed>|null $comentariosConfig Opcional: sobrescreve chaves do config (merge com defaults).
 */
$comentariosConfig = array_merge([
	'endpointPrefix'   => base_url('colaboradores/pautas/comentarios/'),
	'entityIdSelector' => '#idPauta',
	'autoLoad'         => false,
], $comentariosConfig ?? []);

?>
<style>
	/* Resumo compacto à la Fechamento de Pautas, um pouco maior que o thumb 72px da listagem */
	#modalComentariosPauta .modal-comentarios-pauta-thumb {
		width: 112px;
		height: 84px;
		object-fit: cover;
		flex-shrink: 0;
	}
	#modalComentariosPauta .modal-comentarios-pauta-texto {
		max-height: 6.5rem;
		overflow-y: auto;
		font-size: 0.875rem;
	}
	#modalComentariosPauta .div-list-comentarios {
		max-height: min(42vh, 400px);
	}
	/* Outline na cor do tema (administradores: #f3c921) */
	#modalComentariosPauta #btn-comentarios {
		color: #181818;
		background-color: transparent;
		border: 2px solid #f3c921;
	}
	#modalComentariosPauta #btn-comentarios:hover,
	#modalComentariosPauta #btn-comentarios:focus-visible {
		background-color: #f3c921;
		border-color: #f3c921;
		color: #181818;
	}
	#modalComentariosPauta #btn-comentarios:active {
		background-color: #d3a901;
		border-color: #d3a901;
		color: #181818;
	}
</style>
<div class="modal fade" id="modalComentariosPauta" tabindex="-1" aria-labelledby="modalComentariosPautaLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title fs-5" id="modalComentariosPautaLabel">Comentários da Pauta</h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<div class="card border-0 bg-light mb-3">
					<div class="card-body py-2 px-3">
						<div class="d-flex gap-3 align-items-start">
							<img src="" class="rounded border modalImagem modal-comentarios-pauta-thumb" alt="">
							<div class="min-w-0 flex-grow-1">
								<div class="modalTitulo fw-semibold small mb-1 lh-sm"></div>
								<div class="modalTexto text-body-secondary modal-comentarios-pauta-texto"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="div-comentarios">
					<div class="text-center mb-2">
						<button class="btn btn-sm px-3" id="btn-comentarios" type="button">Atualizar comentários</button>
					</div>
					<div class="mb-3">
						<input type="hidden" id="idPauta" name="idPauta" />
						<input type="hidden" id="id_comentario" name="id_comentario" />
						<label for="comentario" class="form-label small mb-1">Novo comentário</label>
						<textarea id="comentario" name="comentario" class="form-control form-control-sm" rows="4"
							placeholder="Digite seu comentário aqui"></textarea>
					</div>
					<div class="text-center mb-2">
						<button class="btn btn-primary btn-sm px-4" id="enviar-comentario" type="button">Enviar comentário</button>
					</div>
					<div class="border rounded-2 p-2 bg-white div-list-comentarios overflow-auto small"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-reset" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<?= view('template/colaboradores_comentarios_init', [
	'comentariosConfig' => $comentariosConfig,
]); ?>

<script>
	const modalComentarios = document.getElementById('modalComentariosPauta');
	if (modalComentarios) {
		modalComentarios.addEventListener('show.bs.modal', event => {
			// relatedTarget pode ser um filho (ex.: <i> dentro do <a>); os data-bs-* ficam no gatilho.
			const trigger = event.relatedTarget && event.relatedTarget.closest('[data-bs-pautas-id]');
			if (!trigger) {
				return;
			}

			$('.modalImagem').attr('src', trigger.getAttribute('data-bs-imagem'));
			$('.modalTexto').html(trigger.getAttribute('data-bs-texto'));
			$('.modalTitulo').html(trigger.getAttribute('data-bs-titulo'));
			$('#idPauta').val(trigger.getAttribute('data-bs-pautas-id'));
			getComentarios();
		});
	}
</script>
