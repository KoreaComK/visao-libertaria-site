<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="row py-4">
		<div class="col-12">
			<h1 class="mb-0 h2">Acesse sua conta de colaborador</h1>
		</div>
	</div>
	<div class="d-flex justify-content-center row">
		<form class="card form-signin col-12 col-md-4 p-4" id="login" method="post" onsubmit="return validateLogin();">
			<div class="form-label-group mb-3">
				<input type="email" id="email" name="email" class="form-control" value="<?= $email_form; ?>"
					placeholder="E-mail" required autofocus />
			</div>

			<div class="form-label-group mb-3">
				<input type="password" id="senha" name="senha" class="form-control" value="<?= $senha_form; ?>"
					placeholder="Senha" required>
			</div>

			<?php if ($lembrar == ''): ?>

				<div class="d-flex justify-content-center">
					<div class="h-captcha" data-sitekey="f70c594b-cc97-4440-980b-6b506509df17"></div>
				</div>

			<?php endif; ?>

			<div class="form-check mb-3">
				<label>
					<input type="checkbox" id="lembrar" name="lembrar" class="form-check-input" value="lembrar"
						<?= ($email_form != '') ? ('checked') : (''); ?>>
					<label class="form-check-label" for="lembrar">
						Lembre-se de mim
					</label>

				</label>
			</div>
			<div class="d-grid gap-2">
				<button class="btn btn-lg btn-primary btn-block" type="submit">Acessar</button>
			</div>
			<div class="col-12 mt-3 mb-3">
				<a href="<?= site_url('site/esqueci'); ?>">Esqueci minha senha</a>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	function validateLogin() {
		$.ajax({
			type: 'POST',
			async: false,
			url: '<?= base_url() . 'site/login'; ?>',
			data: $('#login').serialize(),
			dataType: 'json',
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					setTimeout(function () {
						window.location.href = "<?= ($url === false) ? (base_url('colaboradores/perfil')) : ($url); ?>";
					}, 1000);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
		return false;
	}
</script>
<?= $this->endSection(); ?>