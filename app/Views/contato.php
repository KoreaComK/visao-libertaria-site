<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">Entre em contato conosco!</h3>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="bg-light mb-3" style="padding: 30px;">
				<h6 class="font-weight-bold">ATENÇÃO</h6>
				<p>Este não é um local para sugerir pautas. Caso deseje informar uma pauta para os vídeos faça o seu cadastro no site.<br/><br/>
				Contatos feitos com o intuito de sugerir pautas não serão considerados.<br/><br/>
				Caso persista, os e-mails serão colocados em uma blacklist.
			</p>
			</div>
		</div>
		<div class="col-md-7">
			<div class="contact-form bg-light mb-3" style="padding: 30px;">
				<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
				<form class="form-signin col-12" id="contato" method="post">
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
					<div class="d-flex justify-content-center">
						<div class="h-captcha" data-sitekey="f70c594b-cc97-4440-980b-6b506509df17"></div>
					</div>
					<button class="btn btn-lg btn-primary btn-block btn-submeter" type="submit">Acessar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('.btn-submeter').on('click', function (e) {
		e.preventDefault();
		$('.mensagem').html('');
		$.ajax({
			type: 'POST',
			async: false,
			url: '<?= base_url() . 'site/contato'; ?>',
			data: $('#contato').serialize(),
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').modal('show'); },
			complete: function () { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				$('.mensagem').removeClass('bg-danger');
				$('.mensagem').removeClass('bg-success');
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