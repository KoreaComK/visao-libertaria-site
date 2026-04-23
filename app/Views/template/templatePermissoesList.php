<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<div class="d-none" id="permissoes-total-registros" data-total-registros="<?= (int) ($colaboradoresList['total'] ?? 0); ?>"></div>
<?php if ($colaboradoresList['colaboradores'] !== NULL && !empty($colaboradoresList['colaboradores'])): ?>
	<table class="table table-sm align-middle mb-0 table-hover table-shrink">
		<!-- Table head -->
		<thead class="listagem-site-thead">
			<tr>

				<th scope="col" class="border-0 rounded-start">Apelido</th>
				<th scope="col" class="border-0">E-mail</th>
				<th scope="col" class="border-0">Atribuições</th>
				<th scope="col" class="border-0">Cadastrado</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($colaboradoresList['colaboradores'] as $colaborador): ?>
				<tr>
					<th scope="row">
						<?php if ($colaborador['avatar'] !== NULL && $colaborador['avatar'] !== ''): ?>
							<img id="avatar_menu" src="<?= $colaborador['avatar']; ?>" width="24" height="24"
								class="rounded-circle">
						<?php endif; ?>
						<?= $colaborador['apelido']; ?>
					</th>
					<td>
						<?= $colaborador['email']; ?>
					</td>
					<td>
						<?php $nomesAtribuicoes = array_filter(explode(',', (string) ($colaborador['nomes_atribuicoes'] ?? ''))); ?>
						<div class="d-flex flex-wrap gap-1">
							<?php if (!empty($nomesAtribuicoes)): ?>
								<?php foreach ($nomesAtribuicoes as $nomeAtribuicao): ?>
									<span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
										<?= trim($nomeAtribuicao); ?>
									</span>
								<?php endforeach; ?>
							<?php else: ?>
								<span class="text-muted small">Sem atribuições</span>
							<?php endif; ?>
						</div>
					</td>
					<td>
						<?= Time::createFromFormat('Y-m-d H:i:s', $colaborador['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
					</td>
					<td>
						<a href="<?= site_url('colaboradores/admin/permissoes/') . $colaborador['id']; ?>"
							class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
							title="Editar colaboradores"><i class="fas fa-pencil"></i></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<!-- Table body END -->
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-4">
		Não foi retornado nenhum colaborador.
	</div>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($colaboradoresList['pager']): ?>
		<?= $colaboradoresList['pager']->simpleLinks('colaboradores', 'default_template') ?>
	<?php endif; ?>

	<script>
		$(function () {
			$('.btn-tooltip').tooltip();
		});

		$(document).ready(function () {
			$('.page-link ').on('click', function (e) {
				e.preventDefault();
				$.ajax({
					url: e.target.href,
					type: 'get',
					dataType: 'html',
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (data) {
						$('.permissoes-list').html(data);
						if (typeof atualizarQuantidadeRegistros === 'function') {
							atualizarQuantidadeRegistros();
						}
					}
				});
			});
		});
	</script>