<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div
		class="mensagem p-3 mb-2 rounded text-white text-center <?= (!isset($_GET['status'])) ? ('collapse') : (''); ?> <?= (isset($_GET['status']) && $_GET['status'] == 'true') ? ('bg-success') : ('bg-danger'); ?> col-12">
		<?= (isset($_GET['status']) && $_GET['status'] == 'true') ? ('Ação salva com sucesso.') : ('Ocorreu um erro ao realizar sua ação.'); ?></div>
	<div class="my-3 p-3 bg-white rounded box-shadow">
		<h5>Pesquisa de colaborador</h5>
		<form class="needs-validation w-100" novalidate="yes" method="get" id="pesquisa-permissoes">
			<div class="form-row">
				<div class="col-md-3">
					<div class="control-group mb-2">
						<input type="text" class="form-control form-control-sm" id="apelido" placeholder="Apelido" />
					</div>
				</div>
				<div class="col-md-3">
					<div class="control-group">
						<input type="text" class="form-control form-control-sm" id="email" placeholder="E-mail" />
					</div>
				</div>

				<div class="col-md-2">
					<div class="control-group">
						<select class="custom-select custom-select-sm" id="atribuicao">
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

				<div class="col-md-2">
					<div class="control-group">
						<select class="custom-select custom-select-sm" id="status">
							<option selected value="A">Ativo</option>
							<option value="I">Inativo</option>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="control-group">
						<button class="btn btn-primary btn-sm btn-block btn-submeter" type="button">Enviar</button>
					</div>
				</div>
			</div>

		</form>

		<div class="permissoes-list"></div>

	</div>
</div>
</div>

<script>
	$('.btn-submeter').on('click', function (e) {
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