<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>

	<div class="row g-4">

		<div class="col-md-12 col-lg-12">
			<div class="card border">

				<div class="card-body">
					<h5 class="mb-3">Contato</h5>
					<form class="col-12" novalidate="yes" method="post" id="contatos_form">
						<div class="mb-3">
							<label><b>Assunto:</b> <?= $dados['assunto']; ?></label>
						</div>
						<div class="mb-3">
							<label><b>E-mail:</b> <?= $dados['email']; ?>
								(<?= ($dados['apelido'] == NULL) ? ('Usuário não cadastrado') : ($dados['apelido']); ?>)</label>
						</div>
						<div class="mb-3">
							<label><b>Descrição:</b> <br /><?= $dados['descricao']; ?></label>
						</div>
						<div class="mb-3">
							<textarea id="resposta" name="resposta" class="form-control" rows="5"
								placeholder="Digite a resposta"><?= $dados['resposta']; ?></textarea>
							<small>Ao responder, um e-mail será enviado para o usuário com o conteúdo</small>
						</div>
						<div class="d-sm-flex justify-content-end">
							<button type="button"
								class="btn btn-sm btn-primary me-2 mb-0 salvar-contato-form">Responder</button>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>

</div>

<script>
	$(document).ready(function () {
		$(function () {
			$('.btn-tooltip').tooltip();
		});
	});

	$(".salvar-contato-form").on("click", function () {
		if($('#resposta').val() == '') {
			popMessage('ATENÇÃO', "Resposta não informada.", TOAST_STATUS.DANGER);
		}
		form = new FormData(contatos_form);
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/contatoResposta/'.$dados['id']); ?>",
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
					setTimeout(function () {
						document.location.href = '<?= site_url('colaboradores/admin/contatos'); ?>';
					}, 2000);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});

</script>

<?= $this->endSection(); ?>