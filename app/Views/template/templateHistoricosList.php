<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($colaboradoresHistoricosList['colaboradoresHistoricos'] !== NULL && !empty($colaboradoresHistoricosList['colaboradoresHistoricos'])): ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">Ação</th>
				<th scope="col">Objeto</th>
				<th scope="col">Link do Objeto</th>
				<th scope="col">Data</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($colaboradoresHistoricosList['colaboradoresHistoricos'] as $colaboradorHistorico): ?>
				<tr>
					<th scope="row">
						<?= $colaboradorHistorico['acao']; ?>
					</th>
					<td>
						<?= $colaboradorHistorico['objeto']; ?>
					</td>
					<td>
						<?= $colaboradorHistorico['objeto_id'] ?>
					</td>
					<td>
					<?= Time::createFromFormat('Y-m-d H:i:s', $colaboradorHistorico['criado'])->toLocalizedString('dd MMMM yyyy H:mm:ss'); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($colaboradoresHistoricosList['pager']): ?>
		<?= $colaboradoresHistoricosList['pager']->simpleLinks('historico', 'default_template') ?>
	<?php endif; ?>

	<script>
	$(document).ready(function () {
		$('.page-link ').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: e.target.href,
			type: 'get',
			dataType: 'html',
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
			success: function (data) {
				$('.historicos-list').html(data);
			}
		});
	});
	});
</script>