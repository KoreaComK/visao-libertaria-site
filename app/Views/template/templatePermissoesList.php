<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($colaboradoresList['colaboradores'] !== NULL && !empty($colaboradoresList['colaboradores'])): ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">Apelido</th>
				<th scope="col">E-mail</th>
				<th scope="col">Cadastrado</th>
				<th scope="col">Excluido</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($colaboradoresList['colaboradores'] as $colaborador): ?>
				<tr>
					<th scope="row">
						<?= $colaborador['apelido']; ?>
					</th>
					<td>
						<?= $colaborador['email']; ?>
					</td>
					<td>
						<?= Time::createFromFormat('Y-m-d H:i:s', $colaborador['criado'])->toLocalizedString('dd MMMM yyyy H:mm:ss'); ?>
					</td>
					<td>
						<?= ($colaborador['excluido'] == NULL) ? ('NÃO') : ('SIM'); ?>
					</td>
					<td><a href="<?= site_url('colaboradores/admin/permissoes/' . $colaborador['id']); ?>">Editar</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($colaboradoresList['pager']): ?>
		<?= $colaboradoresList['pager']->simpleLinks('colaboradores', 'default_template') ?>
	<?php endif; ?>

	<script>
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