<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">Exclusão de conta</h3>
	</div>
	<div class="justify-content-center row">
		<div class="mensagem p-3 mb-2 rounded text-white text-center <?=($mensagem!=NULL)?('bg-success'):('collapse'); ?> col-12"><?=$mensagem;?></div>
	</div>
</div>
<script type="text/javascript">
	$('.btn-submeter').on('click', function () {
		$('.mensagem').html('');
		$.ajax({
			type: 'POST',
			async: false,
			url: window.location.href,
			data: $('#esqueci').serialize(),
			dataType: 'json',
			success: function (retorno) {
				if (retorno.status == true) {
					$('.mensagem').addClass('bg-success');
					$('.mensagem').removeClass('bg-danger');
				} else {
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
				}
				$('.mensagem').html(retorno.mensagem);
				$('.mensagem').show();
			}
		});
	});
</script>
<?= $this->endSection(); ?>