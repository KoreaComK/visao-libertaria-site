<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1"><?= esc($titulo); ?></h1>
				<p class="text-muted small mb-0">Configure os parâmetros de pagamento e gere o detalhamento dos artigos vinculados</p>
			</div>
		</div>

		<section class="card border rounded-3 shadow-sm">
			<div class="card-body p-3">
				<form class="needs-validation w-100" novalidate="yes" method="post" id="pagamentos_form">
					<div class="row g-2 g-md-3">
						<div class="col-12 col-lg-8">
							<label class="form-label small text-muted mb-1" for="titulo">Título do pagamento</label>
							<input type="text" class="form-control form-control-sm" id="titulo"
								placeholder="Caso não seja informado nenhum título, será o mês/ano corrente" name="titulo"
								value="<?= (isset($pagamentos)) ? ($pagamentos['titulo']) : (''); ?>" <?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
						</div>
						<div class="col-12 col-lg-4">
							<label class="form-label small text-muted mb-1" for="quantidade_bitcoin">Qtde. Bitcoin</label>
							<input type="number" min="0" step="0.00000001" class="form-control form-control-sm" id="quantidade_bitcoin"
								name="quantidade_bitcoin" placeholder="0,12345678" required
								value="<?= (isset($pagamentos)) ? ($pagamentos['quantidade_bitcoin']) : (''); ?>"
								<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
						</div>
					</div>

					<div class="rounded-3 border bg-body-secondary bg-opacity-50 p-3 mt-3">
						<h2 class="h6 mb-2 text-muted">Multiplicadores (%) dos artigos</h2>
						<div class="row g-2 g-md-3">
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_escrito">Escrito</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_escrito" placeholder="Mult."
									required name="multiplicador_escrito" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_escrito']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_revisado">Revisado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_revisado" placeholder="Mult."
									required name="multiplicador_revisado" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_revisado']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_narrado">Narrado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_narrado" placeholder="Mult."
									required name="multiplicador_narrado" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_narrado']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_produzido">Produzido</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_produzido" placeholder="Mult."
									required name="multiplicador_produzido" value="150"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_produzido']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
						</div>
					</div>

					<div class="rounded-3 border bg-body-secondary bg-opacity-50 p-3 mt-3">
						<h2 class="h6 mb-2 text-muted">Multiplicadores (%) das notícias</h2>
						<div class="row g-2 g-md-3">
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_escrito_noticia">Escrito</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_escrito_noticia" placeholder="Mult."
									required name="multiplicador_escrito_noticia" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_escrito_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_revisado_noticia">Revisado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_revisado_noticia"
									placeholder="Mult." required name="multiplicador_revisado_noticia" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_revisado_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_narrado_noticia">Narrado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_narrado_noticia" placeholder="Mult."
									required name="multiplicador_narrado_noticia" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_narrado_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_produzido_noticia">Produzido</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_produzido_noticia"
									placeholder="Mult." required name="multiplicador_produzido_noticia" value="150"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_produzido_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
						</div>
					</div>

					<?php if (isset($pagamentos)): ?>
						<div class="mt-3 d-flex justify-content-end">
							<button class="btn btn-primary btn-sm buscar-detalhe" type="button">
								<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i>Mostrar detalhes do pagamento
							</button>
						</div>
					<?php else: ?>
						<div class="mt-3 d-flex justify-content-end">
							<button class="btn btn-primary btn-sm gerar-preview" type="button">
								<i class="fas fa-list-check me-1" aria-hidden="true"></i>Buscar artigos pendentes
							</button>
						</div>
					<?php endif; ?>

					<div class="pagamento-preview mt-3"></div>

					<div class="mb-3 mt-3 collapse">
						<label class="form-label small text-muted mb-1" for="hash_transacao">Hash da transação do pagamento</label>
						<input type="text" class="form-control form-control-sm" id="hash_transacao" required name="hash_transacao"
							value="<?= (isset($pagamentos)) ? ($pagamentos['hash_transacao']) : (''); ?>"
							<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
					</div>

					<?php if (!isset($pagamentos)): ?>
						<div class="mt-3 d-flex justify-content-end">
							<button class="btn btn-primary btn-sm collapse submit-pagamento" type="button">
								<i class="fas fa-floppy-disk me-1" aria-hidden="true"></i>Salvar pagamento
							</button>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</section>
	</div>
</div>

<script>
	<?php if (isset($pagamentos)): ?>

		$('.buscar-detalhe').on('click', function (e) {
			form = new FormData();
			form.append('pagamento_id', <?= $pagamentos['id']; ?>);
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/financeiro/detalhe'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.pagamento-preview').html(retorno);
					$('.collapse').show();
				}
			});
		});

	<?php else: ?>

		$('.gerar-preview').on('click', function (e) {
			form = new FormData(document.getElementById('pagamentos_form'));
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/financeiro/preview'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.pagamento-preview').html(retorno);
					$('.collapse').show();
				}
			});
		});

		$('.submit-pagamento').on('click', function (e) {
			form = new FormData(document.getElementById('pagamentos_form'));
			$.ajax({
				url: "<?= base_url('colaboradores/admin/financeiro/salvar'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "JSON",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						setTimeout(function () {
							window.location.href = "<?= base_url('colaboradores/admin/financeiro'); ?>";
						}, 2000);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						$('#titulo').focus();
					}
				}
			});
		});

	<?php endif; ?>
</script>

<?= $this->endSection(); ?>