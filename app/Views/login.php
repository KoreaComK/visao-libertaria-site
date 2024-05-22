<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">Acesse sua conta de colaborador</h3>
	</div>
	<div class="justify-content-center row">
		<form class="form-signin col-12 col-md-4 mb-5 mt-5" id="login" method="post" onsubmit="return validateLogin();">
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
		</form>
		<div class="col-12 mb-5">
			<a href="<?= site_url('site/esqueci'); ?>">Esqueci minha senha</a>
		</div>
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