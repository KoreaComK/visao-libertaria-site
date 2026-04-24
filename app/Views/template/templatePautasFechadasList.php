<?php

declare(strict_types=1);

use CodeIgniter\I18n\Time;

$totalLista = (int) ($pautasList['total'] ?? 0);
?>
<div class="d-none" id="pautas-fechadas-total-registros" data-total-registros="<?= $totalLista; ?>"></div>
<?php if ($pautasList['pautas'] !== NULL && ! empty($pautasList['pautas'])): ?>
	<table class="table table-sm align-middle mb-0 table-hover table-shrink">
		<thead class="listagem-site-thead">
			<tr>
				<th scope="col" class="border-0 rounded-start">Fechamento</th>
				<th scope="col" class="border-0">Data</th>
				<th scope="col" class="border-0 text-center">Pautas</th>
				<th scope="col" class="border-0">Temas</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($pautasList['pautas'] as $pauta): ?>
				<?php
				$temas = array_filter(array_map('trim', explode('|||', (string) ($pauta['temas_csv'] ?? ''))));
				$qtd = (int) ($pauta['qtd_pautas'] ?? 0);
				?>
				<tr>
					<th scope="row"><?= esc($pauta['titulo'] ?? ''); ?></th>
					<td>
						<?= esc(Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy HH:mm')); ?>
					</td>
					<td class="text-center">
						<span class="badge text-bg-secondary"><?= $qtd; ?></span>
					</td>
					<td>
						<div class="d-flex flex-wrap gap-1">
							<?php if ($temas !== []): ?>
								<?php foreach ($temas as $temaBadge): ?>
									<span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle"><?= esc($temaBadge); ?></span>
								<?php endforeach; ?>
							<?php else: ?>
								<span class="text-muted small">—</span>
							<?php endif; ?>
						</div>
					</td>
					<td>
						<a href="<?= site_url('colaboradores/pautas/fechadas/' . $pauta['id']); ?>"
							class="btn btn-light btn-floating mb-0 btn-tooltip-pautas-fechadas" data-bs-toggle="tooltip"
							data-bs-placement="top" title="Abrir detalhe"><i class="fas fa-arrow-up-right-from-square"></i></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-2 py-3 text-muted small">
		Nenhum fechamento encontrado com os filtros atuais.
	</div>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if (! empty($pautasList['pager'])): ?>
		<?= $pautasList['pager']->simpleLinks('pautas', 'default_template'); ?>
	<?php endif; ?>
</div>
