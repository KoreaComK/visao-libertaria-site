<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">Cadastre-se para se tornar um colaborador do canal</h3>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="bg-light mb-3" style="padding: 30px;">
				<h6 class="font-weight-bold">Atenção</h6>
				<p>Forneça seus dados abaixo. Preocupamo-nos com a sua privacidade, portanto,
					use um nome público como preferir ser chamado. Esse nome aparecerá nos materiais que você indicar e
					também nos vídeos.
					Seu e-mail estará visível apenas para funcionários internos do site, mas precisa ser um e-mail real
					e o confirmaremos na próxima etapa.</p>
			</div>
		</div>
		<div class="col-md-7">
			<div class="contact-form bg-light mb-3" style="padding: 30px;">
				<form name="cadastrarColaborador" id="cadastrarColaboradorForm" class="needs-validation">
					<div class="row">
						<div class="col-xl-6">
							<div class="control-group mb-2">
								<input type="text" class="form-control m-2" id="apelido"
									placeholder="Nome Público (Apelido)" name="apelido" required
									data-validation-required-message="Por favor digite o seu apelido no site">
							</div>
						</div>
						<div class="col-xl-6">
							<div class="control-group mb-2">
								<input type="email" class="form-control m-2" id="email" placeholder="E-mail"
									required name="email"
									data-validation-required-message="Por favor digite o seu e-mail">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-6">
							<div class="control-group mb-2">
								<input type="password" class="form-control m-2" id="senha" name="senha"
									placeholder="Senha" required
									data-validation-required-message="Please enter a subject">
							</div>
						</div>
						<div class="col-xl-6">
							<div class="control-group mb-2">
								<input type="password" class="form-control m-2" name="senhaconfirmacao"
									id="senhaconfirmacao" placeholder="Digite a senha novamente" required
									data-validation-required-message="Please enter a subject">
								<p class="help-block text-danger"></p>
							</div>
							<div>
							</div>
						</div>
					</div>
					<div class="d-grid justify-content-center">
						<div class="h-captcha" data-sitekey="f70c594b-cc97-4440-980b-6b506509df17"></div>
					</div>
					<div class="d-grid gap-2 col-6 mx-auto">
						<button class="btn btn-primary btn-submeter"
							type="button">Cadastrar</button>
					</div>
			</div>
			</form>
		</div>
	</div>
</div>
</div>

<script>
	$('.btn-submeter').on('click', function () {
		$.ajax({
			type: 'POST',
			async: false,
			url: '<?= base_url() . 'site/cadastrar'; ?>',
			data: $('#cadastrarColaboradorForm').serialize(),
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
