<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<section class="pt-2">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bg-success bg-opacity-10 d-md-flex p-3 p-sm-4 my-3 text-center text-md-start rounded-3">
						<div class=" me-0 me-md-4">
							<div class="avatar avatar-xxl">
								<img style="max-width: 6rem;" class="avatar-img rounded-circle"
									src="<?= ($colaborador['avatar'] != NULL && $colaborador['avatar'] != '') ? ($colaborador['avatar']) : (site_url('public/assets/avatar-default.png')) ?>"
									alt="avatar">
							</div>
							<div class="text-center mt-n3 position-relative">
								<span class="badge bg-danger fs-6"><?= $contador_pautas; ?>
									pauta<?= ($contador_pautas > 1) ? ('s') : (''); ?></span>
							</div>
						</div>
						<div>
							<h2 class="m-0"><?= $colaborador['apelido']; ?></h2>
							<ul class="list-inline">
								<li class="list-inline-item"><i class="fas fa-user"></i>
									<?php foreach ($atribuicoes as $atribuicao): ?>
										<a
											class="badge text-bg-<?= $atribuicao['cor']; ?> mb-2 text-reset border"><?= $atribuicao['nome']; ?></a>
									<?php endforeach; ?>
								</li>
							</ul>
							<p class="my-2">Cadastrou-se no site há <?= $tempo; ?>.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="position-relative pt-0">
		<div class="container">
			<div class="row">
				<div class="col-12 mb-3">
					<h2>Pautas reservadas pelo Peter </h2>
				</div>
				<div class="col-12">
					<div class="row gy-4 <?=$classeListaCSS;?>"></div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function () {
		$.ajax({
			url: "<?php echo base_url('site/colaboradorList/'); ?><?= urlencode($colaborador['apelido']); ?>",
			type: 'get',
			dataType: 'html',
			data: {
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.<?=$classeListaCSS;?>').html(data);
			}
		});
	});
</script>

<?= $this->endSection(); ?>