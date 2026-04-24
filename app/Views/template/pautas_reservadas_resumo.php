<?php

declare(strict_types=1);

use CodeIgniter\I18n\Time;

/** @var list<array<string, mixed>> $pautasReservadas */
$pautasReservadas = $pautasReservadas ?? [];
$totalReservadas = count($pautasReservadas);

?>
<p class="small text-muted border-bottom pb-2 mb-3">
	<strong><?= $totalReservadas; ?></strong>
	<?= $totalReservadas === 1 ? 'pauta reservada para o fechamento' : 'pautas reservadas para o fechamento'; ?>
</p>
<?php if ($pautasReservadas === []): ?>
	<p class="text-muted small text-center py-2 mb-0">Nenhuma no momento. Use “Reservar” na lista ao lado.</p>
<?php else: ?>
	<?php
	$grupos = [];
	foreach ($pautasReservadas as $pauta) {
		$chaveTag = (string) ($pauta['tag_fechamento'] ?? '');
		if (! array_key_exists($chaveTag, $grupos)) {
			$grupos[$chaveTag] = [];
		}
		$grupos[$chaveTag][] = $pauta;
	}
	?>
	<?php foreach ($grupos as $tag => $lista): ?>
		<div class="mb-3">
			<div class="d-flex align-items-center gap-2 mb-2">
				<span class="badge text-bg-primary"><?= esc($tag !== '' ? $tag : '(sem tag)'); ?></span>
				<small class="text-muted"><?= count($lista); ?> <?= count($lista) === 1 ? 'pauta' : 'pautas'; ?></small>
			</div>
			<ul class="list-group list-group-flush small rounded border">
				<?php foreach ($lista as $pauta): ?>
					<li class="list-group-item px-2 py-2 min-w-0 overflow-hidden">
						<div class="d-flex align-items-start gap-2 min-w-0">
							<?php if (($pauta['pauta_antiga'] ?? '') === 'S'): ?>
								<span class="text-danger flex-shrink-0" title="Pauta antiga"><i class="bi bi-exclamation-circle-fill"></i></span>
							<?php endif; ?>
							<div class="min-w-0 flex-grow-1" style="max-width: 100%;">
								<div class="fw-medium text-break" style="overflow-wrap: anywhere;">
									<?= esc(Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMM yyyy')); ?>
									— <?= esc($pauta['titulo'] ?? ''); ?>
								</div>
								<div class="text-muted text-break mt-1" style="overflow-wrap: anywhere;">
									Sugerido: <?= esc($pauta['apelido'] ?? ''); ?>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
