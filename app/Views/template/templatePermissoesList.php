<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($colaboradoresList['colaboradores'] !== NULL && !empty($colaboradoresList['colaboradores'])): ?>
	<table class="table align-middle p-4 mb-0 mt-2 table-hover table-shrink">
		<!-- Table head -->
		<thead class="table-dark">
			<tr>

				<th scope="col" class="border-0 rounded-start">Apelido</th>
				<th scope="col" class="border-0">E-mail</th>
				<th scope="col" class="border-0">Cadastrado</th>
				<th scope="col" class="border-0">Excluído</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($colaboradoresList['colaboradores'] as $colaborador): ?>
				<tr>
					<th scope="row">
						<?php if ($colaborador['avatar'] !== NULL && $colaborador['avatar'] !== ''): ?>
							<img id="avatar_menu" src="<?= $colaborador['avatar']; ?>" width="30" height="30"
								class="rounded-circle">
						<?php endif; ?>
						<?= $colaborador['apelido']; ?>
					</th>
					<td>
						<?= $colaborador['email']; ?>
					</td>
					<td>
						<?= Time::createFromFormat('Y-m-d H:i:s', $colaborador['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
					</td>
					<td>
						<?= ($colaborador['excluido'] == NULL) ? ('NÃO') : ('SIM'); ?>
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
					}
				});
			});
		});
	</script>