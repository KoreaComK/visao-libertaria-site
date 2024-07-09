<?php

use CodeIgniter\I18n\Time;

?>
<?php if ($estaticasList['estaticas'] !== NULL && !empty($estaticasList['estaticas'])): ?>
	<table class="table align-middle p-4 mb-0 mt-2 table-hover table-shrink">
		<!-- Table head -->
		<thead class="table-dark">
			<tr>

				<th scope="col" class="border-0 rounded-start">Título</th>
				<th scope="col" class="border-0">Ativo</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($estaticasList['estaticas'] as $estatica): ?>
				<tr>
					<th scope="row">
						<?= $estatica['titulo']; ?>
					</th>
					<td>
						<?= ($estatica['ativo'] == 'A')?('Ativo'):('Inativo'); ?>
					</td>
					<td><a href="<?= site_url('colaboradores/admin/estaticas/' . $estatica['id']); ?>">Editar</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<!-- Table body END -->
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-4">
		Não foi retornado nenhuma página estática.
	</div>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($estaticasList['pager']): ?>
		<?= $estaticasList['pager']->simpleLinks('estaticas', 'default_template') ?>
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
						$('.estaticas-list').html(data);
					}
				});
			});
		});
	</script>