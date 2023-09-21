<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">Acesse sua conta de colaborador</h3>
	</div>
	<div class="justify-content-center row">
		<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
		<form class="form-signin col-4 mb-5 mt-5" id="login" method="post" onsubmit="return validateLogin();">
			<div class="form-label-group mb-3">
				<input type="email" id="email" name="email" class="form-control" value="<?=$email_form;?>" placeholder="E-mail" required autofocus />
			</div>

			<div class="form-label-group mb-3">
				<input type="password" id="senha" name="senha" class="form-control" value="<?=$senha_form;?>" placeholder="Senha" required>
			</div>

			<div class="checkbox mb-3">
				<label>
					<input type="checkbox" id="lembrar" name="lembrar" value="lembrar" <?=($email_form!='')?('checked'):('');?>> Lembre-se de mim
				</label>
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Acessar</button>
		</form>
		<div class="col-12 mb-5">
				<a href="<?=site_url('site/esqueci'); ?>">Esqueci minha senha</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	function validateLogin() {
		$('.mensagem').html('');
		$.ajax({
			type: 'POST',
			async: false,
			url: '<?= base_url().'site/login'; ?>',
			data: $('#login').serialize(),
			dataType: 'json',
			success: function (retorno) {
				if(retorno.status == true){
					$('.mensagem').addClass('bg-success');
					$('.mensagem').removeClass('bg-danger');
				}else{
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
				}
				$('.mensagem').addClass(retorno.classe);
				$('.mensagem').html(retorno.mensagem);
				$('.mensagem').show();
				if(retorno.status == true)
				{
					window.location.href = "<?= base_url('colaboradores/perfil'); ?>";
				}
			}
		});
		return false;
	}
</script>
<?= $this->endSection(); ?>