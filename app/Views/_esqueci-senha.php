<?= $this->extend('layouts/_main'); ?>

<?= $this->section('content'); ?>
<?php $hcSiteKey = config('Hcaptcha')->siteKey ?? ''; ?>

<style>
	.vl-auth-input::placeholder {
		color: #a9adb3 !important;
		opacity: 1 !important;
	}

	.vl-auth-input:-ms-input-placeholder {
		color: #a9adb3 !important;
	}

	.vl-auth-input::-ms-input-placeholder {
		color: #a9adb3 !important;
	}
</style>

<div class="container-fluid py-3 vl-site-noticias">
	<div class="container">
		<section class="pt-4 pb-4 margin-top-ultra">
			<div class="row">
				<div class="col-12">
					<nav class="custom-breadcrumb mb-4" aria-label="Migalhas de navegacao">
						<ol class="breadcrumb d-flex align-items-center">
							<li class="breadcrumb-item">
								<a href="<?= site_url('site'); ?>">
									<i class="bi bi-house-fill pe-1" aria-hidden="true"></i>Home
								</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page">
								<i class="bi bi-key-fill pe-1" aria-hidden="true"></i>Esqueci a senha
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</section>

		<section class="mb-4">
			<div class="row justify-content-center">
				<div class="col-12 col-md-8 col-lg-6">
					<form class="card border-secondary bg-dark text-light p-4" id="esqueci" method="post">
						<h1 class="h4 text-white text-center mb-4">Recuperação de conta</h1>

						<?php if ($formulario == 'email'): ?>
							<div class="mb-3">
								<input type="email" id="email" name="email" class="form-control bg-dark text-light border-secondary vl-auth-input"
									placeholder="E-mail cadastrado na plataforma" required autofocus />
							</div>
						<?php elseif ($formulario == 'senha'): ?>
							<div class="mb-3">
								<input type="password" id="senha" name="senha" class="form-control bg-dark text-light border-secondary vl-auth-input"
									placeholder="Nova Senha" required autofocus />
							</div>

							<div class="mb-3">
								<input type="password" id="senhaconfirmacao" name="senhaconfirmacao"
									class="form-control bg-dark text-light border-secondary vl-auth-input"
									placeholder="Digite novamente a Senha" required />
							</div>
						<?php endif; ?>

						<?php if (getenv('CI_ENVIRONMENT') !== 'development' && $hcSiteKey !== ''): ?>
							<div class="d-flex justify-content-center mt-2">
								<div class="h-captcha" data-sitekey="<?= esc($hcSiteKey, 'attr'); ?>"></div>
							</div>
						<?php endif; ?>

						<div class="d-grid mt-4">
							<button class="btn vl-noticias-btn-filtro btn-submeter" type="button">Enviar</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
</div>

<script type="text/javascript">
	$('#esqueci').on('submit', function (e) {
		e.preventDefault();
	});

	$('.btn-submeter').on('click', function () {
		$.ajax({
			type: 'POST',
			async: false,
			url: window.location.href,
			data: $('#esqueci').serialize(),
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					<?php if ($formulario == 'senha'): ?>
						$('#esqueci').hide();
						setTimeout(function () {
							document.location.href = '<?= site_url('site') . '?openLogin=1'; ?>';
						}, 5000);
					<?php endif; ?>
				} else {
					popMessage('ATENCAO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});
</script>

<?= $this->endSection(); ?>
