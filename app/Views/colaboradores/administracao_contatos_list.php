<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="my-3 p-3 rounded box-shadow">
		
		<div class="card border bg-transparent rounded-3 mt-4">
			<div class="card-body p-3">
			<h5>Pesquisar</h5>
				<!-- Search and select START -->
				<div class="row align-items-center justify-content-between" data-np-autofill-form-type="other">
					<!-- Search -->
					<form class="container" method="get" novalidate="yes" name="pesquisa_contatos" id="pesquisa_contatos">
						<div class="row">
							<div class="col-md-3 mb-2">
								<div class="control-group">
									<input type="text" class="form-control form-control-sm" name="email" id="email"
										placeholder="E-mail" />
								</div>
							</div>
							<div class="col-md-5 mb-2">
								<div class="control-group">
									<select class="form-select form-select-sm" name="assuntos" id="assuntos">
										<option value="" selected>Escolha o assunto</option>
										<?php foreach ($assuntos as $assunto): ?>
											<label class="assuntos btn btn-secondary btn-sm vl-bg-c">
												<option value="<?= $assunto['id']; ?>">
													<?= $assunto['assunto']; ?>
												</option>
											<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-md-2 mb-2">
								<div class="control-group">
									<select class="form-select form-select-sm" name="status" id="status">
										<option selected value="NR">Não respondido</option>
										<option value="R">Respondido</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="control-group">
									<button class="btn btn-primary btn-sm btn-submeter"
										type="button">Pesquisar</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="contatos-list"></div>
				<!-- Search and select END -->
			</div>
		</div>
	</div>
</div>
</div>

<script>
	$('.btn-submeter').on('click', function () {
		formData = $('#pesquisa_contatos').serialize();
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/contatosList'); ?>",
			type: 'get',
			dataType: 'html',
			data: formData,
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.contatos-list').html(data);
			}
		});
	});

	$(document).ready(function () {
		$(".btn-submeter").click();

		$("#modal-btn-si").on("click", function () {
			$("#mi-modal").modal('toggle');
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/contatosExcluir/'); ?>" + contatosId,
				type: 'get',
				dataType: 'json',
				data: {
				},
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$(".btn-submeter").trigger("click");
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

<?= $this->endSection(); ?>