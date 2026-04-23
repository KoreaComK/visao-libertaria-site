<?php $totalRegistros = (!empty($pagamentosList['pager'])) ? (int) $pagamentosList['pager']->getTotal('pagamentos') : 0; ?>
<div id="pagamentos-total-registros" class="d-none" data-total-registros="<?= esc((string) $totalRegistros, 'attr'); ?>"></div>
<?php if ($pagamentosList['pagamentos'] !== NULL && !empty($pagamentosList['pagamentos'])): ?>
	<table class="table table-sm align-middle mb-0 table-hover table-shrink">
		<!-- Table head -->
		<thead class="listagem-site-thead">
			<tr>
				<th scope="col" class="border-0 rounded-start">Título</th>
				<th scope="col" class="border-0">Qtde. pago</th>
				<th scope="col" class="border-0">Nº de artigos</th>
				<th scope="col" class="border-0">Hash</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($pagamentosList['pagamentos'] as $pagamento): ?>
				<tr>
					<th scope="row">
						<?= esc($pagamento['titulo']); ?>
					</th>
					<td class="text-nowrap">
						<?= number_format((float) ($pagamento['quantidade_bitcoin'] ?? 0), 8, ',', '.'); ?>
					</td>
					<td class="text-nowrap">
						<span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
							<?= (int) ($pagamento['total_artigos'] ?? 0); ?>
						</span>
					</td>
					<td class="small text-break" style="max-width: 18rem;">
						<?= esc((string) ($pagamento['hash_transacao'] ?? '')); ?>
					</td>
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
		Não foi retornado nenhum pagamento.
	</div>
<?php endif; ?>


<div class="d-block mt-2 mb-0 d-flex justify-content-center py-2 border-top bg-body-secondary bg-opacity-25">
	<?php if ($pagamentosList['pager']): ?>
		<?= $pagamentosList['pager']->simpleLinks('pagamentos', 'default_template') ?>
	<?php endif; ?>

	<script>
		$(function () {
			$('.btn-tooltip').tooltip();
		});

		$(document).ready(function () {
			$('.page-link').on('click', function (e) {
				e.preventDefault();
				$.ajax({
					url: $(e.currentTarget).attr('href'),
					type: 'get',
					dataType: 'html',
					data: ($('#pesquisa-pagamentos').length ? $('#pesquisa-pagamentos').serialize() : {}),
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (data) {
						$('.pagamentos-list').html(data);
						if (typeof atualizarQuantidadeRegistrosPagamentos === 'function') {
							atualizarQuantidadeRegistrosPagamentos();
						}
					}
				});
			});
		});
	</script>