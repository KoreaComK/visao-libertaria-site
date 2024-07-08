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
			<h5>Pesquisa de colaborador</h5>
				<!-- Search and select START -->
				<div class="row align-items-center justify-content-between" data-np-autofill-form-type="other">
					<!-- Search -->
					<form class="container" method="get" name="pesquisa-permissoes" id="pesquisa-permissoes">
						<div class="row">
							<div class="col-md-3 mb-2">
								<div class="control-group">
									<input type="text" class="form-control form-control-sm" name="apelido" id="apelido"
										placeholder="Apelido" />
								</div>
							</div>
							<div class="col-md-3 mb-2">
								<div class="control-group">
									<input type="text" class="form-control form-control-sm" name="email" id="email"
										placeholder="E-mail" />
								</div>
							</div>

							<div class="col-md-2 mb-2">
								<div class="control-group">
									<select class="form-select form-select-sm" name="status" id="atribuicao">
										<option value="" selected>Escolha a atribuição</option>
										<?php foreach ($atribuicoes as $atribuicao): ?>
											<label class="atribuicoes btn btn-secondary btn-sm vl-bg-c">
												<option name="atribuicoes" value="<?= $atribuicao['id']; ?>">
													<?= $atribuicao['nome']; ?>
												</option>
											<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-md-2 mb-2">
								<div class="control-group">
									<select class="form-select form-select-sm" name="status" id="status">
										<option selected value="A">Ativo</option>
										<option value="I">Inativo</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="control-group">
									<button class="btn btn-primary btn-sm btn-block btn-submeter"
										type="button">Enviar</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="permissoes-list"></div>
				<!-- Search and select END -->
			</div>
		</div>
	</div>
</div>
</div>

<script>

	$('.btn-submeter').on('click', function () {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/permissoesList'); ?>",
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
				$('.permissoes-list').html(data);
			}
		});
	});

	$(document).ready(function () {
		$(".btn-submeter").click();
	});
</script>

<?= $this->endSection(); ?>