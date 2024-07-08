<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>


<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container w-auto">
	<div class="row py-4">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<!-- Chart START -->
			<div class="card border">
				<!-- Card body -->
				<div class="card-body">
					<!-- Form START -->
					<form class="w-100" novalidate="yes" method="post" id="aviso_form" enctype='multipart/form-data'>
						<!-- Main form -->
						<div class="row">
							<!-- Main toolbar -->
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Aviso</label>
									<div class="input-group">
										<input type="text" class="form-control" id="aviso" placeholder="Aviso da home"
											name="aviso" value="<?= ($aviso !== false) ? ($aviso['aviso']) : (''); ?>">
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label" for="link">Link do aviso</label>
									<div class="input-group">
										<div class="input-group-text"><i class="fas fa-link"></i></div>
										<input type="text" class="form-control" id="link" placeholder="Link do aviso"
											name="link" value="<?= ($aviso !== false) ? ($aviso['link']) : (''); ?>">
									</div>
								</div>
							</div>

							<div class="col-md-6 mb-3">
								<label for="inicio">Início</label>
								<input id="inicio" class="form-control datepicker" data-toggle="datepicker"
									value="<?= ($aviso !== false && $aviso['inicio'] !== NULL) ? (Time::createFromFormat('Y-m-d H:i:s', $aviso['inicio'])->toLocalizedString('dd/MM/yyyy')) : (''); ?>"
									type="text" id="inicio" name="inicio" />

							</div>
							<div class="col-md-6 mb-3">
								<label for="fim">Fim</label>
								<input id="fim" class="form-control datepicker" data-toggle="datepicker"
									value="<?= ($aviso !== false && $aviso['fim'] !== NULL) ? (Time::createFromFormat('Y-m-d H:i:s', $aviso['fim'])->toLocalizedString('dd/MM/yyyy')) : (''); ?>" type="text" id="fim"
									name="fim" />

							</div>

							<div class="d-flex justify-content-center">
								<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_aviso"
									type="button">Salvar aviso</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$('#enviar_aviso').on('click', function () {
		form = new FormData(aviso_form);
		$.ajax({
			url: "<?= site_url('colaboradores/admin/avisosGravar') . (($aviso === false || $aviso['id'] == NULL) ? ('') : ('/' . $aviso['id'])); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					// setTimeout(function () {
					// 	window.location.href = "<?= site_url('colaboradores/admin/avisos'); ?>";
					// }, 2000);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	})

	$(function () {
		$( ".datepicker" ).datepicker({
			dateFormat: "dd/mm/yy",
			changeMonth: true,
			changeYear: true
		});
		$('.datepicker').mask('00/00/0000');
	});

</script>


<?= $this->endSection(); ?>