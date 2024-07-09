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
						<?= ($estatica['ativo'] == 'A') ? ('Ativo') : ('Inativo'); ?>
					</td>
					<td>
						<a class="btn btn-light btn-floating mb-0 btn-tooltip btn-descartar"
							data-estatica-id="<?= $estatica['id']; ?>" data-toggle="tooltip" data-placement="top"
							title="Excluir página"><i class="fas fa-trash-can"></i></a>
						<a href="<?= site_url('colaboradores/admin/estaticas/' . $estatica['id']); ?>"
							class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
							title="Editar página"><i class="fas fa-pencil"></i></a>
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
		$(function () {
			$('.btn-tooltip').tooltip();
		});

		$(".btn-descartar").on("click", function (e) {
			$('.conteudo-modal').html('Deseja realmente descartar esta página?');
			artigoId = $(e.currentTarget).attr('data-estatica-id');
			$("#mi-modal").modal('show');
		});

		$("#modal-btn-no").on("click", function () {
			$("#mi-modal").modal('hide');
		});

		var artigoId = null;

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

			$("#modal-btn-si").on("click", function () {
				$("#mi-modal").modal('hide');
				$.ajax({
					url: "<?php echo base_url('colaboradores/admin/paginasExcluir/'); ?>" + artigoId,
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
								url: "<?php echo base_url('colaboradores/admin/estaticasList'); ?>",
								type: 'get',
								dataType: 'html',
								data: {

								},
								beforeSend: function () { $('#modal-loading').show(); },
								complete: function () { $('#modal-loading').hide() },
								success: function (data) {
									$('.estaticas-list').html(data);
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