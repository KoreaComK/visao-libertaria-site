<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($avisosList['avisos'] !== NULL && !empty($avisosList['avisos'])): ?>
	<table class="table align-middle p-4 mb-0 mt-2 table-hover table-shrink">
		<!-- Table head -->
		<thead class="table-dark">
			<tr>

				<th scope="col" class="border-0 rounded-start">Apelido</th>
				<th scope="col" class="border-0">Link</th>
				<th scope="col" class="border-0">Data Início</th>
				<th scope="col" class="border-0">Data Fim</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($avisosList['avisos'] as $aviso): ?>
				<tr>
					<th scope="row">
						<?= $aviso['aviso']; ?>
					</th>
					<td>
						<?= $aviso['link']; ?>
					</td>
					<td>
						<?= ($aviso['inicio'] != NULL && $aviso['inicio'] != '')?(Time::createFromFormat('Y-m-d H:i:s', $aviso['inicio'])->toLocalizedString('dd MMMM yyyy')):(''); ?>
					</td>
					<td>
						<?= ($aviso['fim'] != NULL && $aviso['fim'] != '')?(Time::createFromFormat('Y-m-d H:i:s', $aviso['fim'])->toLocalizedString('dd MMMM yyyy')):(''); ?>
					</td>
					<td><a href="<?= site_url('colaboradores/admin/avisos/' . $aviso['id']); ?>">Editar</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<!-- Table body END -->
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-4">
		Não foi retornado nenhum aviso.
	</div>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($avisosList['pager']): ?>
		<?= $avisosList['pager']->simpleLinks('avisos', 'default_template') ?>
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
						$('.avisos-list').html(data);
					}
				});
			});
		});
	</script>