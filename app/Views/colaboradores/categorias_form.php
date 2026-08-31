<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<section class="py-4">
	<div class="container">
		<div class="row pb-4">
			<div class="col-12">
				<h1 class="mb-0 h2"><?= esc($titulo); ?></h1>
			</div>
		</div>
		<div class="g-4 row">
			<div class="col-lg-12">
				<div class="card border">
					<div class="card-body">
						<form class="col-12" novalidate="yes" method="post" id="categorias_form">
							<div class="col-12">
								<div class="mb-3">
									<label class="form-label" for="nome">Nome</label>
									<input type="text" class="form-control" id="nome" name="nome"
										placeholder="Nome da categoria" maxlength="255"
										value="<?= (isset($categoria['nome'])) ? esc($categoria['nome'], 'attr') : ''; ?>">
								</div>
							</div>

							<div class="d-sm-flex justify-content-end gap-2">
								<a href="<?= site_url('colaboradores/admin/categorias'); ?>"
									class="btn btn-sm btn-light me-2 mb-0">Voltar</a>
								<button type="button" class="btn btn-sm btn-primary me-2 mb-0 salvar-categoria">
									Salvar categoria
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$('.salvar-categoria').on('click', function () {
		submit(new FormData(document.getElementById('categorias_form')));
	});

	$('#categorias_form').on('submit', function (e) {
		e.preventDefault();
		submit(new FormData(this));
	});

	function submit(form) {
		$.ajax({
			url: "<?= base_url('colaboradores/admin/categoriasGravar') . (($categoria === false || empty($categoria['id'])) ? ('') : ('/' . $categoria['id'])); ?>",
			method: 'POST',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					setTimeout(function () {
						window.location.href = "<?= site_url('colaboradores/admin/categorias'); ?>";
					}, 1500);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}
</script>

<?= $this->endSection(); ?>
