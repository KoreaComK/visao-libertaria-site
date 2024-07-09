<?php

use CodeIgniter\I18n\Time;

?>
<?php if ($pagamentosList['pagamentos'] !== NULL && !empty($pagamentosList['pagamentos'])): ?>
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
			<?php foreach ($pagamentosList['pagamentos'] as $pagamento): ?>
				<tr>
					<th scope="row">
						<?= $pagamento['titulo']; ?>
					</th>
					<td>
						<div class="d-flex">
							<a href="<?= site_url('colaboradores/admin/financeiro/' . $pagamento['id']); ?>"
								class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
								aria-label="Ver pagamento" data-bs-original-title="Ver pagamento"><i
									class="fas fa-arrow-up-right-from-square"></i></a>
						</div>
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
	<?php if ($pagamentosList['pager']): ?>
		<?= $pagamentosList['pager']->simpleLinks('pagamentos', 'default_template') ?>
	<?php endif; ?>

	<script>
		$(function () {
			$('.btn-tooltip').tooltip();
		});
	</script>