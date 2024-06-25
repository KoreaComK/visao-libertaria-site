<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row g-4">
			<div class="col-12">
				<div class="d-flex flex-wrap gap-3">
					<?php if (in_array('3', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="revisar" value="2">
							<label class="btn btn-outline-light w-100" for="revisar">
								<div class="icon-xl fs-1 rounded-3 text-primary text-center">
									<i class="fas fa-pen-to-square"></i>
								</div>
								<span class="mb-0 text-primary">Revisar
									<?= ($resumo['revisar'] < 10) ? ('0' . $resumo['revisar']) : ($resumo['revisar']); ?>
								</span>
							</label>
						</div>
					<?php endif; ?>
					<?php if (in_array('4', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="narrar" value="3">
							<label class="btn btn-outline-light w-100" for="narrar">
								<div class="icon-xl fs-1 rounded-3 text-info text-center">
									<i class="fas fa-microphone"></i>
								</div>
								<span class="mb-0 text-info">Narrar
									<?= ($resumo['narrar'] < 10) ? ('0' . $resumo['narrar']) : ($resumo['narrar']); ?>
								</span>
							</label>
						</div>
					<?php endif; ?>
					<?php if (in_array('5', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="produzir" value="4">
							<label class="btn btn-outline-light w-100" for="produzir">
								<div class="icon-xl fs-1 rounded-3 text-secondary text-center">
									<i class="fas fa-laptop-code"></i>
								</div>
								<span class="mb-0 text-secondary">Produzir
									<?= ($resumo['produzir'] < 10) ? ('0' . $resumo['produzir']) : ($resumo['produzir']); ?>
								</span>
							</label>
						</div>
					<?php endif; ?>
					<?php if (in_array('6', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="publicar" value="5">
							<label class="btn btn-outline-light w-100" for="publicar">
								<div class="icon-xl fs-1 rounded-3 text-danger text-center">
									<i class="fab fa-youtube"></i>
								</div>
								<span class="mb-0 text-danger">Publicar
									<?= ($resumo['publicar'] < 10) ? ('0' . $resumo['publicar']) : ($resumo['publicar']); ?>
								</span>
							</label>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>

		<!-- Post list table START -->
		<div class="card border bg-transparent rounded-3 mt-4">

			<div class="card-header bg-transparent border-bottom p-3">
				<div class="d-sm-flex justify-content-between align-items-center">
					<h5 class="mb-2 mb-sm-0">Artigos que precisam de colaboração</h5>
				</div>
			</div>
			<!-- Card body START -->
			<div class="card-body p-3">

				<!-- Search and select START -->
				<div class="row g-3 align-items-center justify-content-between mb-3" data-np-autofill-form-type="other"
					data-np-checked="1" data-np-watching="1">
					<!-- Search -->
					<div class="col-md-12">
						<form class="rounded position-relative" data-np-autofill-form-type="other" data-np-checked="1"
							data-np-watching="1">
							<input class="form-control pe-5 bg-transparent" type="search" id="text-pesquisa"
								name="text-pesquisa" placeholder="Pesquisar" aria-label="Pesquisar">
							<button
								class="btn bg-transparent border-0 px-4 py-2 position-absolute top-50 end-0 translate-middle-y btn-pesquisar"
								type="submit"><i class="fas fa-magnifying-glass"></i></button>
						</form>
					</div>
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
</div>
</div>

<script>
	function refreshListPublicado(url, fase_producao) {
		if (url == false) {
			url = '<?php echo base_url('colaboradores/artigos/artigosColaborarList'); ?>';
		}
		if (fase_producao != false) {
			$.ajax({
				url: url,
				type: 'get',
				dataType: 'html',
				data: {
					fase_producao_id: fase_producao,
					texto: $('#text-pesquisa').val()
				},
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (data) {
					$('.tabela-publicado').html(data);
				}
			});
		}
	}

	$('.btn-pesquisar').on('click', function (e) {
		refreshListPublicado(false,$("input[name='fase_producao']:checked").val());
	});

	$('.radio').on('click', function (e) {
		refreshListPublicado(false, e.currentTarget.value);
	})

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$(document).ready(function () {
		refreshListPublicado(false, false);
	});
</script>

<?= $this->endSection(); ?>