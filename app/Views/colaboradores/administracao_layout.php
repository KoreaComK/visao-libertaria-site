<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<section class="py-4">
	<div class="container">
		<div class="row pb-4">
			<div class="col-12">
				<!-- Title -->
				<h1 class="mb-0 h2">Configurações do layout</h1>
			</div>
		</div>
		<div class="g-4 row">

			<div class="col-lg-6">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Layout e imagens do site</h5>
						<form class="col-12" novalidate="yes" method="post" id="layout_form">
							<!-- <div class="mb-3">
								<label for="banner">Imagem do Banner</label>
								<input type="file" class="form-control" id="banner" name="banner" required
									aria-describedby="image" accept="image/png">
							</div> -->

							<div class="mb-3">
								<label for="banner">Imagem do Favicon</label>
								<input type="file" class="form-control" id="favicon" name="favicon" required
									aria-describedby="image" accept="image/ico">
							</div>

							<div class="mb-3">
								<label for="banner">Imagem de Rodapé</label>
								<input type="file" class="form-control" id="rodape" name="rodape" required
									aria-describedby="image" accept="image/png">
							</div>

							<div class="mb-3">
								<label for="estilos">Folha de estilos</label>
								<?= (file_exists('public/assets/estilos.css')) ? ('<a href="' . site_url("public/assets/estilos.css") . '" target="_blank" class="text-muted">Clique aqui para ver a folha de estilos atual.</a>') : (''); ?>
								<input type="file" class="form-control" id="estilos" name="estilos" required
									aria-describedby="css" accept="text/css">
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-layout">Salvar
									novo layout</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Nome e Descrição dos Sites</h5>
						<form class="col-12" novalidate="yes" method="post" id="gerais_form">

							<div class="mb-3">
								<label for="site_nome">Nome</label>
								<textarea id="site_nome" name="site_nome" class="form-control" rows="5"
									placeholder="Nome do Site"><?= (isset($dados['site_nome'])) ? ($dados['site_nome']) : (''); ?></textarea>
								<small class="text-muted">Usar <a href="https://codebeautify.org/string-to-json-online" target="_blank">este site</a>
									para deixar o JSON configurado corretamente.</small>
							</div>

							<div class="mb-3">
								<label for="site_descricao">Descrição</label> 
								<textarea id="site_descricao" name="site_descricao" class="form-control" rows="5"
									placeholder="Descrição do site"><?= (isset($dados['site_descricao'])) ? ($dados['site_descricao']) : (''); ?></textarea>
									<small class="text-muted">Usar <a href="https://codebeautify.org/string-to-json-online" target="_blank">este site</a>
									para deixar o JSON configurado corretamente.</small>
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-gerais">Salvar nomes e descrições</button>
							</div>
						</form>
					</div>
				</div>
			</div>





		</div>
	</div>
</section>

<script type="text/javascript">

	$(".salvar-config-layout").on("click", function () {
		form = new FormData(layout_form);
		submit(form);
	});

	$(".salvar-config-gerais").on("click", function () {
		form = new FormData(gerais_form);
		submit(form);
	});

	function submit(form) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/administracao'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}
</script>


<?= $this->endSection(); ?>