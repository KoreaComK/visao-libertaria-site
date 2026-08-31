<?php

declare(strict_types=1);

$totalLista = (int) ($categoriasList['total'] ?? 0);
?>
<div class="d-none" id="categorias-total-registros" data-total-registros="<?= $totalLista; ?>"></div>
<?php if ($categoriasList['categorias'] !== null && ! empty($categoriasList['categorias'])): ?>
	<table class="table table-sm align-middle mb-0 table-hover table-shrink">
		<thead class="listagem-site-thead">
			<tr>
				<th scope="col" class="border-0 rounded-start">Nome</th>
				<th scope="col" class="border-0">Situação</th>
				<th scope="col" class="border-0">Criada em</th>
				<th scope="col" class="border-0 text-center">Pautas</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($categoriasList['categorias'] as $categoria): ?>
				<?php
				$qtd = (int) ($categoria['qtd_pautas'] ?? 0);
				$inativada = ! empty($categoria['excluido']);
				?>
				<tr class="<?= $inativada ? 'text-muted' : ''; ?>">
					<th scope="row"><?= esc($categoria['nome'] ?? ''); ?></th>
					<td>
						<?php if ($inativada): ?>
							<span class="badge text-bg-secondary">Inativada</span>
						<?php else: ?>
							<span class="badge text-bg-success">Ativa</span>
						<?php endif; ?>
					</td>
					<td>
						<?= esc(app_time($categoria['criado'])->toLocalizedString('dd MMMM yyyy HH:mm')); ?>
					</td>
					<td class="text-center">
						<span class="badge text-bg-secondary"><?= $qtd; ?></span>
					</td>
					<td>
						<div class="d-flex gap-1 justify-content-end">
							<a href="<?= site_url('colaboradores/admin/categorias/' . $categoria['id']); ?>"
								class="btn btn-light btn-floating mb-0" title="Editar categoria">
								<i class="fas fa-pencil"></i>
							</a>
							<?php if ($qtd > 0): ?>
								<button type="button" class="btn btn-light btn-floating mb-0 btn-desvincular-pautas"
									data-categoria-id="<?= esc($categoria['id']); ?>"
									data-categoria-nome="<?= esc($categoria['nome'] ?? '', 'attr'); ?>"
									data-categoria-qtd="<?= $qtd; ?>"
									title="Remover pautas vinculadas">
									<i class="fas fa-link-slash"></i>
								</button>
							<?php endif; ?>
							<?php if ($inativada): ?>
								<button type="button" class="btn btn-light btn-floating mb-0 btn-reativar-categoria"
									data-categoria-id="<?= esc($categoria['id']); ?>"
									data-categoria-nome="<?= esc($categoria['nome'] ?? '', 'attr'); ?>"
									title="Reativar categoria">
									<i class="fas fa-rotate-left"></i>
								</button>
							<?php else: ?>
								<button type="button" class="btn btn-light btn-floating mb-0 btn-inativar-categoria"
									data-categoria-id="<?= esc($categoria['id']); ?>"
									data-categoria-nome="<?= esc($categoria['nome'] ?? '', 'attr'); ?>"
									title="Inativar categoria">
									<i class="fas fa-ban"></i>
								</button>
							<?php endif; ?>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-2 py-3 text-muted small">
		Nenhuma categoria encontrada com os filtros atuais.
	</div>
<?php endif; ?>

<div class="d-block mt-3">
	<?php if (! empty($categoriasList['pager'])): ?>
		<?= $categoriasList['pager']->simpleLinks('categorias', 'default_template'); ?>
	<?php endif; ?>
</div>
