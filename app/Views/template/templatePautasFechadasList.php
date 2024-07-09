<?php

use CodeIgniter\I18n\Time;

?>
<?php if ($pautasList['pautas'] !== NULL && !empty($pautasList['pautas'])): ?>
	<table class="table align-middle p-4 mb-0 mt-2 table-hover table-shrink">
		<!-- Table head -->
		<thead class="table-dark">
			<tr>
				<th scope="col" class="border-0 rounded-start">Título</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($pautasList['pautas'] as $pauta): ?>
				<tr>
					<th scope="row">
						<?= $pauta['titulo']; ?>
					</th>
					<td>
						<a href="<?= site_url('colaboradores/pautas/fechadas/' . $pauta['id']); ?>"
							class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
							aria-label="Ir para o artigo" data-bs-original-title="Ir para a pauta"><i
								class="fas fa-arrow-up-right-from-square"></i></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<!-- Table body END -->
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-4">
		Não foi retornado nenhuma pauta fechada.
	</div>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($pautasList['pager']): ?>
		<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
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
						$('.pautas-fechadas-list').html(data);
					}
				});
			});

		});
	</script>