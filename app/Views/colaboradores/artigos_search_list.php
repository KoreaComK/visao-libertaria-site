<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<h5>Pesquisa de artigos</h5>
	<form class="w-100" method="get" id="pesquisa" name="pesquisa">
		<div class="form-row">
			<div class="col-md-6 pb-2">
				<div class="control-group">
					<input type="text" class="form-control form-control-sm" id="titulo" name="titulo"
						placeholder="Pesquise pelo título" />
				</div>
			</div>

			<div class="col-md-4 pb-2">
				<div class="control-group">
					<select class="custom-select custom-select-sm" id="fase_producao" name="fase_producao">
						<option value="">Escolha a fase da produção</option>
						<?php foreach ($fase_producao as $fp): ?>
							<option value="<?= $fp['id']; ?>">
								<?= $fp['nome']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="col-md-2 pb-2">
				<div class="control-group">
					<select class="custom-select custom-select-sm" id="descartado" name="descartado">
						<option value="N">Ativo</option>
						<option value="S">Descartado</option>
					</select>
				</div>
			</div>

		</div>
		<div class="form-row">
			<div class="col-md-6 pb-2">
				<div class="control-group">
					<input type="text" class="form-control form-control-sm" id="colaborador" name="colaborador"
						placeholder="Pesquise pelo nome do colaborador" value="<?= (isset($colaborador)&&$colaborador['apelido']!=NULL)?($colaborador['apelido']):(''); ?>"/>
				</div>
			</div>
			<div class="col-md-3 pb-2">
				<div class="control-group">
					<select class="custom-select custom-select-sm" id="atribuicao" name="atribuicao">
						<option value="" selected>Escolha a atribuição</option>
						<option value="sugerido">Sugerido</option>
						<option value="escrito">Escrito</option>
						<option value="revisado">Revisado</option>
						<option value="narrado">Narrado</option>
						<option value="produzido">Produzido</option>
						<option value="marcado">Marcado</option>
					</select>
				</div>
			</div>

			<div class="col-md-3 pb-2">
				<div class="control-group">
					<button class="btn btn-primary btn-sm btn-block btn-submeter" type="button">Enviar</button>
				</div>
			</div>
		</div>

	</form>

	<div class="mb-3 mt-3 artigos-list"></div>

</div>

<script>
	$('.btn-submeter').on('click', function (e) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/artigosList'); ?>",
			type: "GET",
			dataType: 'html',
			data: {
				titulo: $('#titulo').val(),
				fase_producao: $('#fase_producao').val(),
				descartado: $('#descartado').val(),
				colaborador: $('#colaborador').val(),
				atribuicao: $('#atribuicao').val(),
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.artigos-list').html(data);
			}
		});
	});

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$(document).ready(function () {
		$(".btn-submeter").click();
	});
</script>

<?= $this->endSection(); ?>