<?php
/** @var list<array> $notificacoes */
/** @var array $colaboradores */
/** @var int $idx_inicio */
$idx_inicio = $idx_inicio ?? 0;
?>
<?php foreach ($notificacoes as $i => $n): ?>
	<?php
	$idx = $idx_inicio + $i;
	$recadoCollapseId = 'recado-pauta-' . $idx;
	$ehPauta = ($n['objeto'] == 'pautas');
	?>
	<div class="card mb-2 recado-item <?= ($n['data_visualizado'] == null) ? 'recado-nao-lido' : ''; ?>">
		<ul class="list-group list-group-flush <?= ($n['data_visualizado'] == null) ? 'border border-primary' : ''; ?>">
			<li class="list-group-item p-2">
				<div class="d-flex align-items-center">
					<div class="me-2">
						<img src="<?= esc(avatar_url($n['avatar'] ?? null), 'attr'); ?>"
							alt="" class="rounded-circle" style="height:auto; width:3.5rem;">
					</div>
					<div class="ms-2 flex-grow-1">
						<p class="mb-0">
							<?= ($n['sujeito_colaboradores_id'] == $colaboradores['id']) ? 'Você ' : esc($n['apelido']); ?>
							<?= esc($n['acao']); ?>
							<?php if ($ehPauta): ?>
								<?= str_replace(
									'{link}',
									'<a href="#' . $recadoCollapseId . '" class="btn-link recado-toggle-pauta" data-pauta-id="' . esc($n['id_objeto'], 'attr') . '" data-target-panel="#' . $recadoCollapseId . '" aria-expanded="false" aria-controls="' . $recadoCollapseId . '">',
									str_replace('{/link}', '</a>', $n['notificacao'])
								); ?>
							<?php elseif ($n['objeto'] == 'artigos'): ?>
								<?= str_replace('{link}', '<a href="' . base_url('colaboradores/artigos/detalhamento/' . $n['id_objeto']) . '" class="btn-link">', str_replace('{/link}', '</a>', $n['notificacao'])); ?>
							<?php endif; ?>
							<?= esc($n['tempo'] ?? ''); ?>
						</p>
					</div>
				</div>
			</li>
		</ul>
		<?php if ($ehPauta): ?>
			<div class="collapse border-top recado-pauta-painel" id="<?= $recadoCollapseId; ?>" data-loaded="0">
				<div class="p-3 bg-light">
					<div class="recado-pauta-conteudo small text-muted">Carregando…</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endforeach; ?>
