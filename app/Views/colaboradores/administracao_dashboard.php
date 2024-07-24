<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row g-4 py-4">
			<div class="col-12">
				<h1 class="mb-0 h2">Administrativo</h1>
			</div>

			<div class="row g-4">
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-info bg-opacity-10 p-4 h-100">
						<h6>Artigos escritos</h6>
						<h2 class="fs-1 text-info">
							<?= (($artigos['escritos'] < 10) ? ('0') : ('')) . (number_format($artigos['escritos'], 0, ',', '.')); ?>
						</h2>
						<span>últimos 30 dias</span>
					</div>
				</div>

				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-danger bg-opacity-10 p-4 h-100">
						<h6>Artigos Descartados</h6>
						<h2 class="fs-1 text-danger">
							<?= (($artigos['descartados'] < 10) ? ('0') : ('')) . (number_format($artigos['descartados'], 0, ',', '.')); ?>
						</h2>
						<span>últimos 30 dias</span>
					</div>
				</div>

				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-secondary bg-opacity-10 p-4 h-100">
						<h6>Artigos Produzidos</h6>
						<h2 class="fs-1 text-secondary">
							<?= (($artigos['produzidos'] < 10) ? ('0') : ('')) . (number_format($artigos['produzidos'], 0, ',', '.')); ?>
						</h2>
						<span>últimos 30 dias</span>
					</div>
				</div>

				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-secondary bg-opacity-10 p-4 h-100">
						<h6>Artigos a publicar</h6>
						<h2 class="fs-1 text-secondary">
							<?= (($artigos['publicar'] < 10) ? ('0') : ('')) . (number_format($artigos['publicar'], 0, ',', '.')); ?>
						</h2>
						<span>últimos 30 dias</span>
					</div>
				</div>

			</div>
		</div>
	</div>

	<script type="text/javascript">
	</script>


	<?= $this->endSection(); ?>