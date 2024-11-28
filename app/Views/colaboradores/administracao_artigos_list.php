<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">

		<!-- Post list table START -->
		<div class="card border bg-transparent rounded-3 mt-4">

			<div class="card-header bg-transparent border-bottom p-3">
				<div class="d-sm-flex justify-content-between align-items-center">
					<h5 class="mb-2 mb-sm-0">Listagem de artigos do site</h5>
				</div>
			</div>
			<!-- Card body START -->
			<div class="card-body p-3">

				<!-- Search and select START -->
				<div class="row g-3 align-items-center justify-content-between mb-3" data-np-autofill-form-type="other"
					data-np-checked="1" data-np-watching="1">
					<form class="rounded position-relative row mt-3" data-np-autofill-form-type="other"
						data-np-checked="1" data-np-watching="1">
						<!-- Search -->
						<div class="col-12 col-md-3 mt-3">
							<input class="form-control pe-5" type="search" id="text-pesquisa-publicado"
								name="text-pesquisa-publicado" placeholder="Pesquisar Título Artigo" aria-label="Pesquisar">
						</div>
						<div class="col-12 col-md-3 mt-3">
							<select class="form-control form-select select-pesquisa" id="select-pesquisa"
								name="select-pesquisa">
								<option value="">Fase de Produção</option>
								<option value="2">Revisando</option>
								<option value="3">Narrando</option>
								<option value="4">Produzindo</option>
								<option value="5">Publicando</option>
							</select>
						</div>
						<div class="col-12 col-md-3 mt-3">
							<select class="form-control form-select select-tipo" id="select-tipo" name="select-tipo">
								<option value="">Tipo</option>
								<option value="T">Teórico</option>
								<option value="N">Notícia</option>
							</select>
						</div>
						<div class="col-12 col-md-3 mt-3">
							<select class="form-control form-select select-descartado" id="select-descartado" name="select-descartado">
								<option value="N">Ativo</option>
								<option value="S">Descartado</option>
							</select>
						</div>
						
						<div class="col-12 col-md-8 mt-3">
							<input class="form-control pe-5" type="search" id="text-colaborador"
								name="text-pesquisa-publicado" placeholder="Pesquisar Colaborador" aria-label="Pesquisar">
						</div>
						<div class="col-12 col-md-3 mt-3">
							<select class="form-control form-select select-pesquisa" id="select-colaborador"
								name="select-pesquisa">
								<option value="A">Sugestor</option>
								<option value="B" selected>Escritor</option>
								<option value="C">Revisor</option>
								<option value="D">Narrador</option>
								<option value="E">Produtor</option>
							</select>
						</div>
						<div class="col-12 col-md-1 mt-3"><button class="btn border-0 btn-pesquisar-publicado"
								type="submit"><i class="fas fa-magnifying-glass"></i></button></div>
					</form>
				</div>
				<!-- Search and select END -->

				<!-- Post list table START -->
				<div class="table-responsive border-0 tabela-publicado">

				</div>
				<!-- Post list table END -->

			</div>
		</div>
		<!-- Post list table END -->
	</div>
	<!-- Counter END -->

</div>

<div class="modal fade" id="modalListagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Artigos em produção</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body corpo-listar">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-left" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>
	function refreshListPublicado(url) {
		if (url == false) {
			url = '<?php echo base_url('colaboradores/artigos/artigosList'); ?>';
		}
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'html',
			data: {
				texto: $('#text-pesquisa-publicado').val(),
				fase_producao: $('#select-pesquisa').val(),
				colaborador: $('#text-colaborador').val(),
				fase_producao_colaborador: $('#select-colaborador').val(),
				descartado: $('#select-descartado').val(),
				admin: true
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.tabela-publicado').html(data);
			}
		});
	}

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$('.btn-pesquisar-publicado').on('click', function (e) {
		refreshListPublicado(false);
	});

	$(document).ready(function () {
		refreshListPublicado(false);
	});

	$('.listagem-artigos-produzindo').on('click', function (e) {
		faseproducaoid = e.currentTarget.getAttribute('data-fase-producao');
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/artigosProduzindo'); ?>/" + faseproducaoid,
			method: "GET",
			data: {},
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.corpo-listar').html(retorno);
			}
		});
	});
</script>

<?= $this->endSection(); ?>