<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="d-flex mt-3 justify-content-center">
		<a class="btn btn-primary" href="<?= site_url('colaboradores/admin/avisos/novo'); ?>"> Cadastrar aviso</a>
	</div>
	<div class="my-3 p-3 rounded box-shadow">

		<div class="card border bg-transparent rounded-3 mt-4">
			<div class="card-body p-3">
				<div class="avisos-list"></div>
			</div>
		</div>
	</div>
</div>
</div>

<script>
	$(document).ready(function () {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/avisosList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				apelido: $('#apelido').val(),
				email: $('#email').val(),
				atribuicao: $('#atribuicao').val(),
				status: $('#status').val(),
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.avisos-list').html(data);
			}
		});
	});
</script>

<?= $this->endSection(); ?>