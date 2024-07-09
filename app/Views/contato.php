<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container">
	<div class="row py-4">
		<div class="col-12">
			<h1 class="mb-0 h2">Entre em contato conosco!</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="card mb-3 p-4">
				<h6 class="card-title fw-bold">ATENÇÃO</h6>
				<p>Este não é um local para sugerir pautas. Caso deseje informar uma pauta para os vídeos faça o seu
					cadastro no site.<br /><br />
					Contatos feitos com o intuito de sugerir pautas não serão considerados.<br /><br />
					Caso persista, os e-mails serão colocados em uma blacklist.
				</p>
			</div>
		</div>
		<div class="col-md-7">
			<div class="card contact-form mb-3 p-4">
				<form class="form-signin card-body" id="contato" method="post">
					<div class="form-label-group mb-3">
						<input type="email" id="email" name="email" class="form-control" value="<?= $email; ?>"
							placeholder="Seu e-mail" required autofocus />
					</div>
					<div class="form-label-group mb-3">
						<input type="text" id="assunto" name="assunto" class="form-control"
							placeholder="Assunto do contato" required>
					</div>
					<div class="mb-3">
						<textarea id="mensagem" name="mensagem" class="form-control" rows="5"
							placeholder="Digite sua mensagem aqui."></textarea>
					</div>
					<div class="d-grid justify-content-center">
						<div class="h-captcha" data-sitekey="f70c594b-cc97-4440-980b-6b506509df17"></div>
					</div>
					<div class="d-grid gap-2">
						<button class="btn btn-lg btn-primary btn-block btn-submeter" type="submit">Acessar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<script>
	$('.btn-submeter').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			async: false,
			url: '<?= base_url() . 'site/contato'; ?>',
			data: $('#contato').serialize(),
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});
</script>

<?= $this->endSection(); ?>