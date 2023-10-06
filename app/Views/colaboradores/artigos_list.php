<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div
		class="mensagem p-3 mb-2 rounded text-white text-center <?= (!isset($_GET['status'])) ? ('collapse') : (''); ?> <?= (isset($_GET['status']) && $_GET['status'] == 'true') ? ('bg-success') : ('bg-danger'); ?> col-12">
		<?= (isset($_GET['status']) && $_GET['status'] == 'true') ? ('Ação salva com sucesso.') : ('Ocorreu um erro ao realizar sua ação.'); ?></div>
	<div class="my-3 p-3 bg-white rounded box-shadow">
		<?php foreach ($artigosList['artigos'] as $artigo): ?>
			<div class="media text-muted pt-3 border-bottom mb-3 pb-3">
				<image class="mr-2 rounded img-thumbnail" for="btn-check-outlined" style="height: auto; max-width:250px;"
					src="<?= $artigo['imagem']; ?>" />
				<p class="media-body pb-3 mb-0 small lh-125  border-gray">
					<strong class="d-block">
						<?= date_format(new DateTime($artigo['criado']), 'd') . ' ' . month_helper(date_format(new DateTime($artigo['criado']), 'F'), 3) . ' ' . date_format(new DateTime($artigo['criado']), 'Y'); ?>
						-
						<?= $artigo['titulo']; ?>
					</strong>
					<?= ($artigo['fase_producao_id'] == '1' || $artigo['fase_producao_id'] == '2') ? (substr($artigo['texto_original'], 0, 350)) : (substr($artigo['texto_revisado'], 0, 350)); ?>...
					<a
						href="<?= (($artigo['fase_producao_id'] == '6' || $artigo['fase_producao_id'] == '7')) ? (site_url('site/artigo/' . $artigo['url_friendly'])) : (site_url('colaboradores/artigos/detalhe/' . $artigo['id'])); ?>">Ler
						artigo completo.</a>
					<br />

					<?php if ($artigo['sugerido'] != NULL): ?>
						<small class="badge badge-primary m-1 p-1">Sugerido:
							<?= $artigo['sugerido']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['escrito'] != NULL): ?>
						<small class="badge badge-primary m-1 p-1">Escrito:
							<?= $artigo['escrito']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['revisado'] != NULL): ?>
						<small class="badge badge-primary m-1 p-1">Revisado:
							<?= $artigo['revisado']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['narrado'] != NULL): ?>
						<small class="badge badge-primary m-1 p-1">Narrado:
							<?= $artigo['narrado']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['produzido'] != NULL): ?>
						<small class="badge badge-primary m-1 p-1">Produzido:
							<?= $artigo['produzido']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['publicado'] != NULL): ?>
						<small class="badge badge-primary m-1 p-1">Publicado:
							<?= $artigo['publicado']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['descartado'] != NULL): ?>
						<small class="badge badge-danger m-1 p-1">Descartado:
							<?= $artigo['descartado']; ?>
						</small>
					<?php endif; ?>

					<?php if ($artigo['marcado_colaboradores_id'] == null || $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
						<?php if (($artigo['fase_producao_id'] == '1' || $artigo['fase_producao_id'] == '2') && $artigo['escrito_colaboradores_id'] == $usuario && $artigo['descartado'] == NULL): ?>
							<small class="d-block text-right mt-3"><a
									href="<?= site_url('colaboradores/artigos/cadastrar/' . $artigo['id']); ?>">Alterar
									artigo</a></small>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '2' && in_array('3', $permissoes) && $artigo['escrito_colaboradores_id'] != $usuario && $artigo['descartado'] == NULL): ?>
							<small class="d-block text-right mt-3"><a
									href="<?= site_url('colaboradores/artigos/previa/' . $artigo['id']); ?>">Ver detalhe do
									artigo</a></small>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '3' && in_array('4', $permissoes) && $artigo['descartado'] == NULL): ?>
							<small class="d-block text-right mt-3"><a
									href="<?= site_url('colaboradores/artigos/narrar/' . $artigo['id']); ?>">Narrar artigo</a></small>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '4' && in_array('5', $permissoes) && $artigo['descartado'] == NULL): ?>
							<small class="d-block text-right mt-3"><a
									href="<?= site_url('colaboradores/artigos/produzir/' . $artigo['id']); ?>">Produzir
									artigo</a></small>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '5' && in_array('6', $permissoes) && $artigo['descartado'] == NULL): ?>
							<small class="d-block text-right mt-3"><a
									href="<?= site_url('colaboradores/artigos/publicar/' . $artigo['id']); ?>">Publicar
									artigo</a></small>
						<?php endif; ?>
					<?php elseif ($artigo['marcado_colaboradores_id'] != $_SESSION['colaboradores']['id']): ?>
						<small class="d-block text-right mt-3">Artigo marcado por
							<?= $artigo['marcado']; ?>
						</small>
					<?php endif; ?>
					<?php if ($artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
						<small class="d-block text-right mt-3"><a href="#" class="desmarcar"
								data-information="<?= $artigo['id']; ?>">Desmarcar artigo</a></small>
					<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>

		<div class="d-block mt-3">
			<?php if ($artigosList['pager']): ?>
				<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
	$(".desmarcar").on("click", function (e) {
		form = new FormData();
		id = e.target.getAttribute('data-information');
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/desmarcar/'); ?>"+id,
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				if (retorno.status) {
					location.reload();
				} else {
					$('.mensagem').show();
					$('.mensagem').html(retorno.mensagem);
					$('.mensagem').addClass('bg-danger');
				}
			}
		});
	});
</script>

<?= $this->endSection(); ?>