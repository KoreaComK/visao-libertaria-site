<?php

use CodeIgniter\I18n\Time;

helper('text');

$totalRegistros = (int) ($contatosList['total'] ?? 0);
?>
<span id="vl-contatos-total-inline" class="d-none" data-total="<?= esc((string) $totalRegistros, 'attr'); ?>"></span>
<?php if ($contatosList['contatos'] !== null && !empty($contatosList['contatos'])): ?>
	<table class="table table-sm align-middle mb-0 table-hover table-shrink">
		<thead class="listagem-site-thead">
			<tr>
				<th scope="col" class="text-center vl-contatos-col-check" style="width: 2.75rem;">
					<input type="checkbox" class="form-check-input contatos-check-all" title="Selecionar todos desta página"
						aria-label="Selecionar todos os contactos desta página" />
				</th>
				<th scope="col">Assunto</th>
				<th scope="col">E-mail</th>
				<th scope="col">Descrição</th>
				<th scope="col">Cadastrado</th>
				<th scope="col" style="width: 7rem;"></th>
			</tr>
		</thead>
		<tbody class="border-top-0">
			<?php foreach ($contatosList['contatos'] as $contato): ?>
				<?php
				$descricaoHtml = (string) ($contato['descricao'] ?? '');
				$descricaoHtml = html_entity_decode($descricaoHtml, ENT_QUOTES | ENT_HTML5, 'UTF-8');
				$descricaoHtml = preg_replace('/<\s*br\s*\/?>/i', "\n", $descricaoHtml);
				$descricaoBruta = strip_tags($descricaoHtml);
				$descricaoCurta = character_limiter($descricaoBruta, 200, '…');
				?>
				<tr>
					<td class="text-center align-middle vl-contatos-col-check">
						<input type="checkbox" class="form-check-input contato-linha-check" value="<?= esc($contato['id'], 'attr'); ?>"
							aria-label="Selecionar contacto" />
					</td>
					<td>
						<span class="fw-semibold small text-body"><?= esc($contato['assunto'] ?? ''); ?></span>
					</td>
					<td class="small vl-admin-contato-email">
						<span class="text-break d-block text-body"><?= esc($contato['email'] ?? ''); ?></span>
						<?php if (($contato['apelido'] ?? null) !== null): ?>
							<span class="badge bg-success bg-opacity-10 text-success mt-1">Usuário cadastrado</span>
						<?php else: ?>
							<span class="badge bg-secondary bg-opacity-10 text-secondary mt-1">Usuário não cadastrado</span>
						<?php endif; ?>
					</td>
					<td class="small vl-admin-contato-desc text-break" style="max-width: 28rem;">
						<?= nl2br(esc($descricaoCurta), false); ?>
					</td>
					<td class="small text-nowrap">
						<?php
						try {
							echo esc(Time::createFromFormat('Y-m-d H:i:s', $contato['criado'])->toLocalizedString('dd MMM yyyy HH:mm'));
						} catch (\Throwable $e) {
							echo esc($contato['criado'] ?? '');
						}
						?>
					</td>
					<td class="text-center">
						<div class="d-flex justify-content-center gap-1">
							<button type="button"
								class="btn btn-light btn-sm btn-floating mb-0 btn-contato-responder"
								data-bs-toggle="tooltip" data-bs-placement="top" title="Responder"
								data-contato-id="<?= esc($contato['id'], 'attr'); ?>"><i class="fas fa-pencil" aria-hidden="true"></i></button>
							<button type="button"
								class="btn btn-light btn-sm btn-floating mb-0 btn-excluir"
								data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir"
								data-contatos-id="<?= esc($contato['id'], 'attr'); ?>"><i class="fas fa-trash-can"
									aria-hidden="true"></i></button>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-4 text-muted">
		Não foi retornado nenhum contato.
	</div>
<?php endif; ?>

<?php if (!empty($contatosList['pager'])): ?>
	<div class="mt-2 mb-0 d-flex justify-content-center py-2 border-top bg-body-secondary bg-opacity-25">
		<?= $contatosList['pager']->simpleLinks('contatos', 'default_template'); ?>
	</div>
<?php endif; ?>
