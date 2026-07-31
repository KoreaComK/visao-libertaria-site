<?php
$imagem = $pauta['imagem'] ?? null;
?>
<div class="recado-pauta-resumo">
	<?php if (!empty($imagem)): ?>
		<img src="<?= esc($imagem); ?>" alt="" class="img-fluid rounded mb-2" style="max-height: 10rem; width: 100%; object-fit: cover;">
	<?php endif; ?>
	<p class="fw-semibold mb-1"><?= esc($pauta['titulo'] ?? ''); ?></p>
	<p class="small text-muted mb-3"><?= nl2br(esc($texto_resumo)); ?></p>

	<p class="small fw-semibold mb-2">Últimos comentários</p>
	<?php if (empty($comentarios)): ?>
		<p class="small text-muted mb-0">Nenhum comentário nesta pauta.</p>
	<?php else: ?>
		<ul class="list-unstyled mb-0">
			<?php foreach ($comentarios as $c): ?>
				<li class="mb-2 pb-2 border-bottom">
					<div class="d-flex gap-2">
						<img src="<?= esc(($c['avatar'] != null) ? $c['avatar'] : site_url('public/assets/avatar-default.png')); ?>"
							alt="" class="rounded-circle" width="28" height="28">
						<div>
							<p class="mb-0 small">
								<span class="fw-semibold"><?= esc($c['apelido'] ?? ''); ?></span>
								<span class="text-muted">· <?= esc(tempo_relativo($c['criado'] ?? '')); ?></span>
							</p>
							<p class="mb-0 small"><?= nl2br(esc($c['comentario'] ?? '')); ?></p>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
