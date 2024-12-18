<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<!-- Counter START -->
		<div class="row g-4">
			<!-- Counter item -->
			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<div class="icon-xl fs-1 bg-success bg-opacity-10 rounded-3 text-success text-center">
						<i class="far fa-file-lines"></i>
					</div>
					<div class="text-center">
						<a class="btn-link listagem-artigos-produzindo" data-fase-producao="1"
							href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalListagem">
							<h3 class="mb-0">
								<?= ($resumo['escrevendo'] < 10) ? ('0' . $resumo['escrevendo']) : ($resumo['escrevendo']); ?>
							</h3>
							<h6 class="mb-0">Escrevendo</h6>
						</a>
					</div>
				</div>
			</div>

			<!-- Counter item -->
			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<!-- Icon -->
					<div class="icon-xl fs-1 bg-primary bg-opacity-10 rounded-3 text-primary text-center">
						<i class="fas fa-pen-to-square"></i>
					</div>
					<!-- Content -->
					<div class="text-center">
						<a class="btn-link listagem-artigos-produzindo" data-fase-producao="2"
							href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalListagem">
							<h3 class="mb-0">
								<?= ($resumo['revisando'] < 10) ? ('0' . $resumo['revisando']) : ($resumo['revisando']); ?>
							</h3>
							<h6 class="mb-0">Revisando</h6>
						</a>
					</div>
				</div>
			</div>

			<!-- Counter item -->
			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<div class="">
						<!-- Icon -->
						<div class="icon-xl fs-1 bg-info bg-opacity-10 rounded-3 text-info text-center">
							<i class="fas fa-microphone"></i>
						</div>
						<!-- Content -->
						<div class="text-center">
							<a class="btn-link listagem-artigos-produzindo" data-fase-producao="3"
								href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalListagem">
								<h3 class="mb-0">
									<h3 class="mb-0">
										<?= ($resumo['narrando'] < 10) ? ('0' . $resumo['narrando']) : ($resumo['narrando']); ?>
									</h3>
								</h3>
								<h6 class="mb-0">Narrando</h6>
							</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Counter item -->
			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<!-- Icon -->
					<div class="icon-xl fs-1 bg-secondary bg-opacity-10 rounded-3 text-secondary text-center">
						<i class="fas fa-video"></i>
					</div>
					<!-- Content -->
					<div class="text-center">
						<a class="btn-link listagem-artigos-produzindo" data-fase-producao="4"
							href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalListagem">
							<h3 class="mb-0">
								<h3 class="mb-0">
									<?= ($resumo['produzindo'] < 10) ? ('0' . $resumo['produzindo']) : ($resumo['produzindo']); ?>
								</h3>
							</h3>
							<h6 class="mb-0">Produzindo</h6>
						</a>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<!-- Icon -->
					<div class="icon-xl fs-1 bg-danger bg-opacity-10 rounded-3 text-danger text-center">
						<i class="fab fa-youtube"></i>
					</div>
					<!-- Content -->
					<div class="text-center">
						<a class="btn-link listagem-artigos-produzindo" data-fase-producao="5"
							href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalListagem">
							<h3 class="mb-0">
								<h3 class="mb-0">
									<?= ($resumo['publicando'] < 10) ? ('0' . $resumo['publicando']) : ($resumo['publicando']); ?>
								</h3>
							</h3>
							<h6 class="mb-0">Publicando</h6>
						</a>
					</div>
				</div>
			</div>

			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<!-- Icon -->
					<div class="icon-xl fs-1 bg-warning bg-opacity-10 rounded-3 text-warning text-center">
						<i class="fab fa-bitcoin"></i>
					</div>
					<!-- Content -->
					<div class="text-center">
						<a class="btn-link listagem-artigos-produzindo" data-fase-producao="6"
							href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalListagem">
							<h3 class="mb-0">
								<h3 class="mb-0">
									<?= ($resumo['pagando'] < 10) ? ('0' . $resumo['pagando']) : ($resumo['pagando']); ?>
								</h3>
							</h3>
							<h6 class="mb-0">Pagando</h6>
						</a>
					</div>
				</div>
			</div>

		</div>

		<div class="card border mt-4 mb-4">
			<div class="card-header border-bottom p-3">
				<h5 class="card-header-title mb-0">Tempos limites para colaborar</h5>
			</div>
			<!-- Card body START -->
			<div class="card-body">
				<p>ATENÇÃO! Se os limites não forem respeitados, o artigo será desmarcado e o colaborador terá que
					esperar um tempo para voltar a pegar o mesmo artigo.</p>
				<p>Os limites estão <b><?= ($limite['ativo'] == '1') ? ('ativos') : ('inativos'); ?></b>. O tempo de
					bloqueio é de <b><?= $limite['bloqueio']; ?></b>.</p>
				<div class="row">
					<div class="col-md-12 col-lg-4">
						<p>
							Para os artigos <b class="text-uppercase">teóricos</b>, os limites são:
						<ul class="list-unstyled">
							<li>Revisão: <b><?= $limite['teoria']['revisao']; ?></b></li>
							<li>Narração: <b><?= $limite['teoria']['narracao']; ?></b></li>
							<li>Produção: <b><?= $limite['teoria']['producao']; ?></b></li>
						</ul>
						</p>
					</div>
					<div class="col-md-12 col-lg-4">
						<p>
							Para os artigos de <b class="text-uppercase">notícia</b>, os limites são:
						<ul class="list-unstyled">
							<li>Revisão: <b><?= $limite['noticia']['revisao']; ?></b></li>
							<li>Narração: <b><?= $limite['noticia']['narracao']; ?></b></li>
							<li>Produção: <b><?= $limite['noticia']['producao']; ?></b></li>
						</ul>
						</p>
					</div>
					<div class="col-md-12 col-lg-4">
						<p>O limite máximo para o artigo ser produzido está
							<b><?= ($limite['descartar']['ativo'] == '1') ? ('ativo') : ('inativo'); ?></b>.
						</p>
						<p>Caso o artigo não seja produzido em <b><?= $limite['descartar']['tempo']; ?></b>, o artigo
							será descartado.</p>
					</div>
				</div>


			</div>
			<!-- Card body END -->
		</div>

		<!-- Post list table START -->
		<div class="card border bg-transparent rounded-3 mt-4">

			<div class="card-header bg-transparent border-bottom p-3">
				<div class="d-sm-flex justify-content-between align-items-center">
					<h5 class="mb-2 mb-sm-0">Listagem de artigos do site</h5>
					<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>"
						class="btn btn-sm btn-primary mb-0">Novo Artigo</a>
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
						<div class="col-12 col-md-5 mt-3">
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
						<div class="col-12 col-md-1 mt-3"><button class="btn border-0 btn-pesquisar-publicado"
								type="submit"><i class="fas fa-magnifying-glass"></i></button></div>
						<div class="col-12 col-md-9 mt-3">
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
				fase_producao_colaborador: $('#select-colaborador').val()
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