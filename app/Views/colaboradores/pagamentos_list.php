<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="d-flex mt-3 justify-content-center">
		<a class="btn btn-primary" href="<?= site_url('colaboradores/admin/financeiro/pagar'); ?>"> Fazer
			Pagamento</a>
	</div>
	<div class="my-3 p-3 rounded box-shadow">

		<div class="card border bg-transparent rounded-3 mt-4">
			<div class="card-body p-3">
				<div class="pagamentos-list"></div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/pagamentosList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.pagamentos-list').html(data);
			}
		});
	});
</script>
<?= $this->endSection(); ?>