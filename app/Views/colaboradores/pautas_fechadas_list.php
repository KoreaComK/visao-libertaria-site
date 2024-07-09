<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<!-- Title -->
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<div class="my-3 p-3 rounded box-shadow">
		
		<div class="card border bg-transparent rounded-3 mt-4">
			<div class="card-body p-3">
				<div class="pautas-fechadas-list"></div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/pautasFechadasList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.pautas-fechadas-list').html(data);
			}
		});
	});
</script>
<?= $this->endSection(); ?>