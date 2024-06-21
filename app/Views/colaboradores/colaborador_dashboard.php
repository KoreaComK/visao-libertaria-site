<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<!-- Counter START -->
		<div class="row g-4">
			<!-- Counter item -->
			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<!-- Icon -->
					<div class="icon-xl fs-1 bg-success bg-opacity-10 rounded-3 text-success text-center">
						<i class="far fa-file-lines"></i>
					</div>
					<!-- Content -->
					<div class="text-center">
						<h3 class="mb-0">10</h3>
						<h6 class="mb-0">Escrevendo</h6>
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
						<h3 class="mb-0">01</h3>
						<h6 class="mb-0">Revisando</h6>
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
							<h3 class="mb-0">03</h3>
							<h6 class="mb-0">Narrando</h6>
						</div>
					</div>
				</div>
			</div>

			<!-- Counter item -->
			<div class="col-md-6 col-lg-2">
				<div class="card card-body border p-3">
					<!-- Icon -->
					<div class="icon-xl fs-1 bg-secondary bg-opacity-10 rounded-3 text-secondary text-center">
						<i class="fas fa-laptop-code"></i>
					</div>
					<!-- Content -->
					<div class="text-center">
						<h3 class="mb-0">04</h3>
						<h6 class="mb-0">Produzindo</h6>
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
						<h3 class="mb-0">05</h3>
						<h6 class="mb-0">Publicando</h6>
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
						<h3 class="mb-0">20</h3>
						<h6 class="mb-0">Pagando</h6>
					</div>
				</div>
			</div>

		</div>

		<div class="col-12 mt-4">
				<!-- Blog list table START -->
				<div class="card border bg-transparent rounded-3">
					<!-- Card header START -->
					<div class="card-header bg-transparent border-bottom p-3">
						<div class="d-sm-flex justify-content-between align-items-center">
							<h5 class="mb-2 mb-sm-0">Artigos do mês <span class="badge bg-primary bg-opacity-10 text-primary">03</span></h5>
							<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>" class="btn btn-sm btn-primary mb-0">Novo Artigo</a>
						</div>
					</div>
					<!-- Card header END -->

					<!-- Card body START -->
					<div class="card-body">

						<!-- Blog list table START -->
						<div class="table-responsive border-0">
							<table class="table align-middle p-4 mb-0 table-hover table-shrink">
								<!-- Table head -->
								<thead class="table-dark">
									<tr>
										<th scope="col" class="border-0 rounded-start">Título</th>
										<th scope="col" class="border-0">Autor</th>
										<th scope="col" class="border-0">Data criado</th>
										<th scope="col" class="border-0">Categoria</th>
										<th scope="col" class="border-0">Status</th>
									</tr>
								</thead>

								<!-- Table body START -->
								<tbody class="border-top-0">
									<!-- Table item -->
									<tr>
										<!-- Table data -->
										<td>
											<h6 class="course-title mt-2 mt-md-0 mb-0"><a href="#">12 worst types of business accounts you follow on Twitter</a></h6>
										</td>
										<!-- Table data -->
										<td>
											<h6 class="mb-0"><a href="#">Lori Stevens</a></h6>
										</td>
										<!-- Table data -->
										<td>Jan 22, 2022</td>
										<!-- Table data -->
										<td>
											<a href="#" class="badge text-bg-warning mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Technology</a>
										</td>
										<!-- Table data -->
										<td>
											<span class="badge bg-success bg-opacity-10 text-success mb-2">Live</span>
										</td>
										
									</tr>

								</tbody>
								<!-- Table body END -->
							</table>
						</div>
						<!-- Blog list table END -->
					</div>
				</div>
				<!-- Blog list table END -->
			</div>
		</div>
		<!-- Counter END -->
		
	</div>
</div>
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