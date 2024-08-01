<?php

use CodeIgniter\I18n\Time;

?>
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
						<?= ($aviso['inicio'] != NULL && $aviso['inicio'] != '') ? (Time::createFromFormat('Y-m-d H:i:s', $aviso['inicio'])->toLocalizedString('dd MMMM yyyy')) : (''); ?>
					</td>
					<td>
						<?= ($aviso['fim'] != NULL && $aviso['fim'] != '') ? (Time::createFromFormat('Y-m-d H:i:s', $aviso['fim'])->toLocalizedString('dd MMMM yyyy')) : (''); ?>
					</td>
					<td>
						<div class="d-flex">
							<a class="btn btn-light btn-floating mb-0 btn-tooltip btn-descartar"
								data-aviso-id="<?= $aviso['id']; ?>" data-toggle="tooltip" data-placement="top"
								title="Excluir aviso"><i class="fas fa-trash-can"></i></a>
							<a href="<?= site_url('colaboradores/admin/avisos/' . $aviso['id']); ?>"
								class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
								title="Editar aviso"><i class="fas fa-pencil"></i></a>
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
	<?php if ($avisosList['pager']): ?>
		<?= $avisosList['pager']->simpleLinks('avisos', 'default_template') ?>
	<?php endif; ?>

	<script>
		$(function () {
			$('.btn-tooltip').tooltip();
		});

		$(".btn-descartar").on("click", function (e) {
			$('.conteudo-modal').html('Deseja realmente descartar este aviso?');
			artigoId = $(e.currentTarget).attr('data-aviso-id');
			$("#mi-modal").modal('show');
		});

		$("#modal-btn-no").on("click", function () {
			$("#mi-modal").modal('hide');
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
						$('.avisos-list').html(data);
					}
				});
			});

			$("#modal-btn-si").on("click", function () {
				$("#mi-modal").modal('hide');
				$.ajax({
					url: "<?php echo base_url('colaboradores/admin/avisosExcluir/'); ?>" + artigoId,
					type: 'get',
					dataType: 'json',
					data: {
					},
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$.ajax({
								url: "<?php echo base_url('colaboradores/admin/avisosList'); ?>",
								type: 'get',
								dataType: 'html',
								data: {
									apelido: $('#apelido').val(),
									email: $('#email').val(),
									atribuicao: $('#atribuicao').val(),
									status: $('#status').val(),
								},
								beforeSend: function () { $('#modal-loading').show(); },
								complete: function () { $('#modal-loading').hide() },
								success: function (data) {
									$('.avisos-list').html(data);
								}
							});
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
						artigoId = null;
					}
				});
				return false;
			});
		});
	</script>