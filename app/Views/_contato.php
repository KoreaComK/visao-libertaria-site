<?= $this->extend('layouts/_main'); ?>

<?= $this->section('content'); ?>
<?php
$hcSiteKey = config('Hcaptcha')->siteKey ?? '';
?>

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

	/*
	 * style.css global aplica float:left em textarea/input — isso faz o botão
	 * “Enviar mensagem” ficar ao lado do campo. Forçamos fluxo em coluna só aqui.
	 */
	#contato {
		display: flex;
		flex-direction: column;
	}

	#contato textarea.form-control,
	#contato input.form-control,
	#contato select.form-select {
		float: none !important;
		width: 100% !important;
	}

	#contato .vl-contato-msg-wrap {
		margin-bottom: 0.5rem;
	}

	#contato .vl-contato-msg-wrap .form-label {
		margin-bottom: 0.25rem;
	}

	#contato .vl-contato-msg-wrap textarea {
		margin-top: 0;
	}

	#contato .vl-contato-captcha-wrap {
		margin-top: 0.5rem;
		margin-bottom: 0;
	}

	#contato .vl-contato-acoes-envio {
		width: 100%;
		clear: both;
		margin-top: 0.5rem !important;
	}

	.vl-contato-login-link {
		font-size: inherit;
		vertical-align: baseline;
	}

	.vl-contato-login-link:hover,
	.vl-contato-login-link:focus {
		color: #fff !important;
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
								<i class="bi bi-envelope-fill pe-1" aria-hidden="true"></i>Contato
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</section>

		<section class="mb-4">
			<div class="row g-4 align-items-start">
				<div class="col-12 col-lg-5">
					<div class="card border-secondary bg-dark text-light">
						<div class="card-header border-secondary">
							<h2 class="h5 card-title mb-0 text-white">
								<i class="bi bi-exclamation-triangle-fill pe-2" aria-hidden="true"></i>Atenção
							</h2>
						</div>
						<div class="card-body">
							<p class="mb-0 text-white-50">
								Este não é um local para sugerir pautas. Caso deseje informar uma pauta para os vídeos,
								<a href="<?= site_url('site/cadastre-se'); ?>" class="link-light link-underline-opacity-75">cadastre-se na plataforma</a>
								ou
								<button type="button"
									class="btn btn-link vl-contato-login-link text-white-50 text-decoration-underline p-0 align-baseline border-0 shadow-none"
									data-bs-toggle="modal" data-bs-target="#header-login-modal">acesse a sua conta</button>.
							</p>
							<p class="mt-3 mb-0 text-white-50">
								Contatos feitos com o intuito de sugerir pautas não serão considerados.
							</p>
							<p class="mt-3 mb-0 text-white-50">
								Caso persista, os e-mails serão colocados em uma blacklist.
							</p>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-7">
					<div class="card border-secondary bg-dark text-light">
						<div class="card-header border-secondary">
							<h2 class="h5 card-title mb-0 text-white">
								<i class="bi bi-chat-dots-fill pe-2" aria-hidden="true"></i>Entre em contato conosco
							</h2>
						</div>
						<div class="card-body">
							<form id="contato" method="post" action="<?= base_url('site/contato'); ?>">
								<div class="mb-3">
									<label class="form-label text-white-50 small" for="email">E-mail</label>
									<input type="email" id="email" name="email"
										class="form-control bg-dark text-light border-secondary vl-auth-input"
										value="<?= esc($email ?? ''); ?>" placeholder="Seu e-mail" autocomplete="email" required
										autofocus />
								</div>
								<div class="mb-3">
									<label class="form-label text-white-50 small" for="select-assunto">Assunto</label>
									<select class="form-select bg-dark text-light border-secondary" id="select-assunto"
										name="select-assunto" required>
										<option value="" data-description="" selected disabled>Selecione um assunto</option>
										<?php foreach ($assuntos as $assunto): ?>
											<option value="<?= esc($assunto['id']); ?>"
												data-description="<?= esc($assunto['descricao'] ?? '', 'attr'); ?>"><?= esc($assunto['assunto']); ?></option>
										<?php endforeach; ?>
									</select>
									<small class="descricao text-white-50 d-block mt-1" role="status" aria-live="polite"></small>
								</div>
								<div class="mb-3">
									<label class="form-label text-white-50 small d-none" for="redesocial">Rede social</label>
									<input type="text" id="redesocial" name="redesocial"
										class="form-control bg-dark text-light border-secondary vl-auth-input d-none"
										placeholder="Rede social que sofreu bloqueio" />
								</div>
								<div class="mb-3">
									<label class="form-label text-white-50 small d-none" for="perfil">Perfil</label>
									<input type="text" id="perfil" name="perfil"
										class="form-control bg-dark text-light border-secondary vl-auth-input d-none"
										placeholder="Nome do perfil que foi bloqueado" />
								</div>
								<div class="vl-contato-msg-wrap">
									<label class="form-label text-white-50 small" for="mensagem">Mensagem</label>
									<textarea id="mensagem" name="mensagem"
										class="form-control bg-dark text-light border-secondary vl-auth-input" rows="5"
										placeholder="Digite sua mensagem aqui." maxlength="1000" required></textarea>
								</div>

								<?php if (getenv('CI_ENVIRONMENT') !== 'development' && $hcSiteKey !== ''): ?>
									<div class="d-flex justify-content-center vl-contato-captcha-wrap">
										<div class="h-captcha" data-sitekey="<?= esc($hcSiteKey, 'attr'); ?>"></div>
									</div>
								<?php endif; ?>

								<div class="d-grid mt-3 vl-contato-acoes-envio">
									<button class="btn vl-noticias-btn-filtro btn-submeter" type="submit">Enviar mensagem</button>
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
	(function () {
		var $form = $('#contato');
		var $btn = $form.find('.btn-submeter');
		var labelEnviar = 'Enviar mensagem';
		var labelEnviando = 'Enviando...';

		$form.on('submit', function (e) {
			e.preventDefault();
			if ($btn.prop('disabled')) {
				return;
			}
			var redirecionar = false;
			$btn.prop('disabled', true).text(labelEnviando);
			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				data: $form.serialize(),
				dataType: 'json',
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () {
					$('#modal-loading').hide();
					if (!redirecionar) {
						$btn.prop('disabled', false).text(labelEnviar);
					}
				},
				success: function (retorno) {
					if (retorno && retorno.status === true) {
						redirecionar = true;
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						setTimeout(function () {
							window.location.href = '<?= site_url('site'); ?>';
						}, 3000);
					} else {
						var msg = (retorno && retorno.mensagem) ? retorno.mensagem : 'Não foi possível enviar. Verifique os dados e tente de novo.';
						popMessage('ATENÇÃO', msg, TOAST_STATUS.DANGER);
					}
				},
				error: function (xhr) {
					var msg = 'Erro de comunicação com o servidor. Tente novamente em instantes.';
					if (xhr.responseJSON && xhr.responseJSON.mensagem) {
						msg = xhr.responseJSON.mensagem;
					}
					popMessage('ATENÇÃO', msg, TOAST_STATUS.DANGER);
				}
			});
		});
	})();

	$('#select-assunto').on('change', function () {
		var selectedOption = $(this).find('option:selected');
		var description = selectedOption[0].dataset.description || '';
		$('.descricao').text(description);

		if ($(this).val() === '2') {
			$('#perfil, #redesocial').removeClass('d-none');
			$('label[for="perfil"], label[for="redesocial"]').removeClass('d-none');
		} else {
			$('#perfil, #redesocial').addClass('d-none').val('');
			$('label[for="perfil"], label[for="redesocial"]').addClass('d-none');
		}
	});
</script>

<?= $this->endSection(); ?>
