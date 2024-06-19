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
							<h5 class="mb-2 mb-sm-0">Meus artigos <span class="badge bg-primary bg-opacity-10 text-primary">03</span></h5>
							<a href="#" class="btn btn-sm btn-primary mb-0">Add New</a>
						</div>
					</div>
					<!-- Card header END -->

					<!-- Card body START -->
					<div class="card-body">

						<!-- Search and select START -->
						<div class="row g-3 align-items-center justify-content-between mb-3" data-np-autofill-form-type="other" data-np-checked="1" data-np-watching="1">
							<!-- Search -->
							<div class="col-md-8">
								<form class="rounded position-relative" data-np-autofill-form-type="other" data-np-checked="1" data-np-watching="1">
									<input class="form-control pe-5 bg-transparent" type="search" placeholder="Search" aria-label="Search" data-np-intersection-state="visible">
									<button class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y" type="submit"><i class="fas fa-search fs-6 "></i></button>
								</form>
							</div>

							<!-- Select option -->
							<div class="col-md-3">
								<!-- Short by filter -->
								<form>
									<select class="form-select z-index-9 bg-transparent" aria-label=".form-select-sm" data-np-intersection-state="visible">
										<option value="">Sort by</option>
										<option>Free</option>
										<option>Newest</option>
										<option>Oldest</option>
									</select>
								</form>
							</div>
						</div>
						<!-- Search and select END -->

						<!-- Blog list table START -->
						<div class="table-responsive border-0">
							<table class="table align-middle p-4 mb-0 table-hover table-shrink">
								<!-- Table head -->
								<thead class="table-dark">
									<tr>
										<th scope="col" class="border-0 rounded-start">Blog Name</th>
										<th scope="col" class="border-0">Author Name</th>
										<th scope="col" class="border-0">Published Date</th>
										<th scope="col" class="border-0">Categories</th>
										<th scope="col" class="border-0">Status</th>
										<th scope="col" class="border-0 rounded-end">Action</th>
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
										<!-- Table data -->
										<td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="bi bi-trash"></i></a>
                        <a href="dashboard-post-edit.html" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                      </div>
										</td>
									</tr>

									<!-- Table item -->
									<tr>
										<!-- Table data -->
										<td>
											<h6 class="course-title mt-2 mt-md-0 mb-0"><a href="#">Dirty little secrets about the business industry</a></h6>
										</td>
										<!-- Table data -->
										<td>
											<h6 class="mb-0"><a href="#">Dennis Barrett</a></h6>
										</td>
										<!-- Table data -->
										<td>Jan 19, 2022</td>
										<!-- Table data -->
										<td>
											<a href="#" class="badge text-bg-info mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Marketing</a>
										</td>
										<!-- Table data -->
										<td>
											<span class="badge bg-warning bg-opacity-15 text-warning mb-2">Draft</span>
										</td>
										<!-- Table data -->
										<td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="bi bi-trash"></i></a>
                        <a href="dashboard-post-edit.html" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                      </div>
										</td>
									</tr>

									<!-- Table item -->
									<tr>
										<!-- Table data -->
										<td>
											<h6 class="course-title mt-2 mt-md-0 mb-0"><a href="#">7 common mistakes everyone makes while traveling</a></h6>
										</td>
										<!-- Table data -->
										<td>
											<h6 class="mb-0"><a href="#">Billy Vasquez</a></h6>
										</td>
										<!-- Table data -->
										<td>Nov 11, 2022</td>
										<!-- Table data -->
										<td>
											<a href="#" class="badge text-bg-danger mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Photography</a>
										</td>
										<!-- Table data -->
										<td>
											<span class="badge bg-success bg-opacity-10 text-success mb-2">Live</span>
										</td>
										<!-- Table data -->
										<td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="bi bi-trash"></i></a>
                        <a href="dashboard-post-edit.html" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                      </div>
										</td>
									</tr>

									<!-- Table item -->
									<tr>
										<!-- Table data -->
										<td>
											<h6 class="course-title mt-2 mt-md-0 mb-0"><a href="#">5 investment doubts you should clarify</a></h6>
										</td>
										<!-- Table data -->
										<td>
											<h6 class="mb-0"><a href="#">Lori Stevens</a></h6>
										</td>
										<!-- Table data -->
										<td>Jan 22, 2022</td>
										<!-- Table data -->
										<td>
											<a href="#" class="badge text-bg-success mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Gadgets</a>
										</td>
										<!-- Table data -->
										<td>
											<span class="badge bg-success bg-opacity-10 text-success mb-2">Live</span>
										</td>
										<!-- Table data -->
										<td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="bi bi-trash"></i></a>
                        <a href="dashboard-post-edit.html" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                      </div>
										</td>
									</tr>

									<!-- Table item -->
									<tr>
										<!-- Table data -->
										<td>
											<h6 class="course-title mt-2 mt-md-0 mb-0"><a href="#">Bad habits that people in the industry need to quit</a></h6>
										</td>
										<!-- Table data -->
										<td>
											<h6 class="mb-0"><a href="#">Larry Lawson</a></h6>
										</td>
										<!-- Table data -->
										<td>Dec 06, 2022</td>
										<!-- Table data -->
										<td>
											<a href="#" class="badge bg-primary mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Sports</a>
										</td>
										<!-- Table data -->
										<td>
											<span class="badge bg-danger bg-opacity-10 text-danger mb-2">Removed</span>
										</td>
										<!-- Table data -->
										<td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="bi bi-trash"></i></a>
                        <a href="dashboard-post-edit.html" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                      </div>
										</td>
									</tr>

									<!-- Table item -->
									<tr>
										<!-- Table data -->
										<td>
											<h6 class="course-title mt-2 mt-md-0 mb-0"><a href="#">Around the web: 20 fabulous infographics about business</a></h6>
										</td>
										<!-- Table data -->
										<td>
											<h6 class="mb-0"><a href="#">Bryan Knight</a></h6>
										</td>
										<!-- Table data -->
										<td>Feb 14, 2022</td>
										<!-- Table data -->
										<td>
											<a href="#" class="badge text-bg-danger mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Travel</a>
										</td>
										<!-- Table data -->
										<td>
											<span class="badge bg-success bg-opacity-10 text-success mb-2">Live</span>
										</td>
										<!-- Table data -->
										<td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="bi bi-trash"></i></a>
                        <a href="dashboard-post-edit.html" class="btn btn-light btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                      </div>
										</td>
									</tr>

								</tbody>
								<!-- Table body END -->
							</table>
						</div>
						<!-- Blog list table END -->

						<!-- Pagination START -->
						<div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
							<!-- Content -->
							<p class="mb-sm-0 text-center text-sm-start">Showing 1 to 8 of 20 entries</p>
							<!-- Pagination -->
							<nav class="mb-sm-0 d-flex justify-content-center" aria-label="navigation">
								<ul class="pagination pagination-sm pagination-bordered mb-0">
									<li class="page-item disabled">
										<a class="page-link" href="#" tabindex="-1" aria-disabled="true">Prev</a>
									</li>
									<li class="page-item"><a class="page-link" href="#">1</a></li>
									<li class="page-item active"><a class="page-link" href="#">2</a></li>
									<li class="page-item disabled"><a class="page-link" href="#">..</a></li>
									<li class="page-item"><a class="page-link" href="#">15</a></li>
									<li class="page-item">
										<a class="page-link" href="#">Next</a>
									</li>
								</ul>
							</nav>
						</div>
						<!-- Pagination END -->
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