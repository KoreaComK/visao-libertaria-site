<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php $avatarPadrao = site_url('public/assets/avatar-default.png'); ?>
<?php if (!empty($comentarios)): ?>
	<?php foreach ($comentarios as $chave => $comentario): ?>
		<?php
		$tsRaw = trim((string) ($comentario['atualizado'] ?? ''));
		if ($tsRaw === '' || $tsRaw === '0000-00-00 00:00:00') {
			$tsRaw = trim((string) ($comentario['criado'] ?? ''));
		}
		$dataFmt = '';
		$dtAttr = '';
		if ($tsRaw !== '' && $tsRaw !== '0000-00-00 00:00:00') {
			$tObj = Time::createFromFormat('Y-m-d H:i:s', $tsRaw);
			if ($tObj === false) {
				try {
					$tObj = Time::parse($tsRaw);
				} catch (\Throwable $e) {
					$tObj = false;
				}
			}
			if ($tObj !== false) {
				$dataFmt = $tObj->toLocalizedString('dd MMMM yyyy H:mm:ss');
				$dtAttr = $tObj->format('Y-m-d H:i:s');
			}
		}
		?>
		<div class="card-body py-3 px-3 <?= (count($comentarios) == $chave + 1) ? '' : 'border-bottom border-secondary border-opacity-10'; ?>"
			id="<?= esc($comentario['id'], 'attr'); ?>">
			<div class="d-flex gap-3 align-items-start">
				<div class="flex-shrink-0" style="width: 2.75rem;">
					<img src="<?= ($comentario['avatar'] != null && $comentario['avatar'] !== '') ? esc($comentario['avatar'], 'attr') : esc($avatarPadrao, 'attr'); ?>"
						onerror="this.onerror=null;this.src='<?= esc($avatarPadrao, 'attr'); ?>';"
						class="rounded-circle img-fluid border border-light shadow-sm"
						alt="<?= esc('Avatar de ' . $comentario['apelido'], 'attr'); ?>"
						width="44" height="44" style="width: 2.75rem; height: 2.75rem; object-fit: cover;">
				</div>
				<div class="flex-grow-1 min-w-0 text-break">
					<div class="d-flex flex-wrap align-items-baseline gap-2 mb-1">
						<span class="fw-semibold small"><?= esc($comentario['apelido']); ?></span>
						<?php if ($dataFmt !== ''): ?>
							<time class="small text-muted" datetime="<?= esc($dtAttr, 'attr'); ?>"
								title="<?= esc($dataFmt, 'attr'); ?>"><?= esc($dataFmt); ?></time>
						<?php endif; ?>
					</div>
					<p class="card-text small mb-0 lh-base text-body comentario-<?= esc($comentario['id']); ?>"><?= esc($comentario['comentario']); ?></p>
					<?php if ($colaborador == $comentario['colaboradores_id']): ?>
						<div class="d-flex flex-wrap gap-3 mt-2 pt-1 border-top border-secondary border-opacity-10">
							<button class="btn btn-sm btn-link text-decoration-none p-0 comentario-alterar" type="button"
								data-information="<?= esc($comentario['id'], 'attr'); ?>">Editar</button>
							<button class="btn btn-sm btn-link text-danger text-decoration-none p-0 comentario-excluir" type="button"
								data-information="<?= esc($comentario['id'], 'attr'); ?>">Excluir</button>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="card-body py-4 px-3 text-center text-muted">
		<p class="small mb-0">Nenhum comentário ainda.</p>
	</div>
<?php endif; ?>

<script>
	$(function () {
		$(".comentario-excluir").on("click", function (e) {
			var id_comentario = e.currentTarget.getAttribute('data-information');
			excluirComentario(id_comentario);
		});
		$(".comentario-alterar").on("click", function (e) {
			var id_comentario = e.currentTarget.getAttribute('data-information');
			$('#id_comentario').val(id_comentario);
			$('#comentario').val($('.comentario-' + id_comentario).text());
			$('#comentario').focus();
		});
	});
</script>