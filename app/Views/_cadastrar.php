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
								<i class="bi bi-person-plus-fill pe-1" aria-hidden="true"></i>Cadastre-se
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</section>

		<section class="mb-4">
			<div class="row g-4">
				<div class="col-12 col-lg-5">
					<div class="card border-secondary bg-dark text-light h-100">
						<div class="card-header border-secondary">
							<h2 class="h5 card-title mb-0 text-white">
								<i class="bi bi-info-circle-fill pe-2" aria-hidden="true"></i>Atenção
							</h2>
						</div>
						<div class="card-body">
							<p class="mb-0 text-white-50">
								Forneca seus dados abaixo. Preocupamo-nos com a sua privacidade, portanto, use um nome publico como
								preferir ser chamado. Esse nome aparecera nos materiais que voce indicar e tambem nos videos.
								Seu e-mail ficara visivel apenas para funcionarios internos do site, mas precisa ser um e-mail real
								e o confirmaremos na proxima etapa.
							</p>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-7">
					<div class="card border-secondary bg-dark text-light">
						<div class="card-header border-secondary">
							<h2 class="h5 card-title mb-0 text-white">
								<i class="bi bi-person-badge-fill pe-2" aria-hidden="true"></i>Cadastro de colaborador
							</h2>
						</div>
						<div class="card-body">
							<form name="cadastrarColaborador" id="cadastrarColaboradorForm">
								<div class="row g-3">
									<div class="col-12 col-xl-6">
										<input type="text" class="form-control bg-dark text-light border-secondary vl-auth-input" id="apelido"
											placeholder="Nome Publico (Apelido)" name="apelido" required
											data-validation-required-message="Por favor digite o seu apelido no site">
									</div>
									<div class="col-12 col-xl-6">
										<input type="email" class="form-control bg-dark text-light border-secondary vl-auth-input" id="email" placeholder="E-mail"
											required name="email" data-validation-required-message="Por favor digite o seu e-mail">
									</div>
								</div>

								<div class="row g-3 mt-1">
									<div class="col-12 col-xl-6">
										<input type="password" class="form-control bg-dark text-light border-secondary vl-auth-input" id="senha" name="senha"
											placeholder="Senha" required data-validation-required-message="Por favor digite sua senha">
									</div>
									<div class="col-12 col-xl-6">
										<input type="password" class="form-control bg-dark text-light border-secondary vl-auth-input" name="senhaconfirmacao"
											id="senhaconfirmacao" placeholder="Digite a senha novamente" required
											data-validation-required-message="Por favor confirme sua senha">
									</div>
								</div>

								<?php if (getenv('CI_ENVIRONMENT') !== 'development' && $hcSiteKey !== ''): ?>
									<div class="d-flex justify-content-center mt-4">
										<div class="h-captcha" data-sitekey="<?= esc($hcSiteKey, 'attr'); ?>"></div>
									</div>
								<?php endif; ?>

								<div class="d-grid mt-4">
									<button class="btn vl-noticias-btn-filtro btn-submeter" type="button">Cadastrar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
	$('.btn-submeter').on('click', function () {
		$.ajax({
			type: 'POST',
			async: false,
			url: '<?= base_url() . 'site/cadastre-se'; ?>',
			data: $('#cadastrarColaboradorForm').serialize(),
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENCAO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});
</script>

<?= $this->endSection(); ?>
