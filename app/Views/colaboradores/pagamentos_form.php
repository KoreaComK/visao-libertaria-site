<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<div class="d-flex justify-content-center mb-5 text-left">
		<form class="needs-validation w-100" novalidate="yes" method="post" id="pagamentos_form">

			<div class="mb-3">
				<label for="titulo">Título do Pagamento</label>
				<div class="input-group">
					<input type="text" class="form-control" id="titulo"
						placeholder="Caso não seja informado nenhum título, será o mês/ano corrente" name="titulo"
						value="<?= (isset($pagamentos)) ? ($pagamentos['titulo']) : (''); ?>" <?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
				</div>
			</div>

			<div class="mb-3">
				<label for="quantidade_bitcoin">Qtde. Bitcoin</label>
				<input type="number" min="0" step="0.00000001" class="form-control" id="quantidade_bitcoin"
					name="quantidade_bitcoin" placeholder="0,12345678" required
					value="<?= (isset($pagamentos)) ? ($pagamentos['quantidade_bitcoin']) : (''); ?>"
					<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
			</div>

			<label for="">Multiplicadores (%)</label>
			<div class="form-row">
				<div class="col-md-3">
					<div class="control-group">
						<label for="multiplicador_escrito">Escrito</label>
						<input type="number" class="form-control" id="multiplicador_escrito" placeholder="Mult."
							required name="multiplicador_escrito" value="100"
							value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_escrito']) : (''); ?>"
							<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
					</div>
				</div>
				<div class="col-md-3">
					<div class="control-group">
						<label for="multiplicador_revisado">Revisado</label>
						<input type="number" class="form-control" id="multiplicador_revisado" placeholder="Mult."
							required name="multiplicador_revisado" value="100"
							value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_revisado']) : (''); ?>"
							<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
					</div>
				</div>
				<div class="col-md-3">
					<div class="control-group">
						<label for="multiplicador_narrado">Narrado</label>
						<input type="number" class="form-control" id="multiplicador_narrado" placeholder="Mult."
							required name="multiplicador_narrado" value="100"
							value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_narrado']) : (''); ?>"
							<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
					</div>
				</div>
				<div class="col-md-3">
					<div class="control-group">
						<label for="multiplicador_produzido">Produzido</label>
						<input type="number" class="form-control" id="multiplicador_produzido" placeholder="Mult."
							required name="multiplicador_produzido" value="150"
							value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_produzido']) : (''); ?>"
							<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
					</div>
				</div>
			</div>

			<?php if (isset($pagamentos)): ?>
				<div class="mt-3 d-flex justify-content-around">
					<button class="btn btn-primary mb-3 col-md-5 mr-1 buscar-detalhe" type="button">Mostrar Detalhes
						do Pagamento</button>
				</div>
			<?php else: ?>
				<div class="mt-3 d-flex justify-content-around">
					<button class="btn btn-primary mb-3 col-md-5 mr-1 gerar-preview" type="button">Buscar Artigos
						Pendentes</button>
				</div>
			<?php endif; ?>

			<div class="pagamento-preview"></div>

			<div class="mb-3 collapse">
				<label for="hash_transacao">Hash da transação do Pagamento</label>
				<div class="input-group">
					<input type="text" class="form-control" id="hash_transacao" required name="hash_transacao"
						value="<?= (isset($pagamentos)) ? ($pagamentos['hash_transacao']) : (''); ?>"
						<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
				</div>
			</div>

			<?php if (!isset($pagamentos)): ?>
				<div class="mt-3 d-flex justify-content-around ">
					<button class="btn btn-primary mb-3 col-md-5 mr-1 collapse submit-pagamento" type="button">Salvar
						Pagamento</button>
				</div>
			<?php endif; ?>
		</form>
	</div>

</div>

<script>
	<?php if (isset($pagamentos)): ?>

		$('.buscar-detalhe').on('click', function (e) {
			form = new FormData();
			form.append('pagamento_id',<?= $pagamentos['id']; ?>);
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/financeiro/detalhe'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "html",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function (retorno) {
					$('.pagamento-preview').html(retorno);
					$('.collapse').show();
				}
			});
		});

	<?php else: ?>

		$('.gerar-preview').on('click', function (e) {
			form = new FormData(pagamentos_form);
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/financeiro/preview'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "html",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function (retorno) {
					$('.pagamento-preview').html(retorno);
					$('.collapse').show();
				}
			});
		});

		$('.submit-pagamento').on('click', function (e) {
			form = new FormData(pagamentos_form);
			$.ajax({
				url: "<?= base_url('colaboradores/admin/financeiro/salvar'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "JSON",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function (retorno) {
					console.log(retorno);
					if (retorno.status) {
						window.location.href = "<?= base_url('colaboradores/admin/financeiro'); ?>";
					} else {
						$('.mensagem').show();
						$('.mensagem').html(retorno.mensagem);
						$('.mensagem').addClass('bg-danger');
						$('#titulo').focus();
					}
				}
			});
		});

	<?php endif; ?>
</script>

<?= $this->endSection(); ?>