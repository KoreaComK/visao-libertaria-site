<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div class="my-3 p-3 bg-white rounded box-shadow">

		<?php helper('month_helper');
		foreach ($pautasList['pautas'] as $pauta): ?>

			<div class="media text-muted pt-3 border-bottom">
				<image class="mr-2 rounded img-thumbnail" for="btn-check-outlined"
					style="max-height: 120px; max-width:250px;" src="<?= $pauta['imagem']; ?>" />
				<p class="media-body pb-3 mb-0 small lh-125  border-gray">
					<strong class="d-block">
						<?= $pauta['titulo']; ?>
					</strong>
					<?= $pauta['texto']; ?>
					<a href="<?= $pauta['link']; ?>" target="_blank">Ler notícia original.</a><br />
					<small class="badge badge-primary m-1 p-1">Sugerido:
						<?= $pauta['apelido']; ?>
					</small>
					<small class="d-block text-right mt-3">
						<a href="<?= site_url('colaboradores/pautas/detalhe/' . $pauta['id']); ?>" target="_blank">Ler Pauta</a>
					</small>
					<?php if ($pauta['colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
						<small class="d-block text-right mt-3">
							<a href="<?= site_url('colaboradores/pautas/cadastrar/' . $pauta['id']); ?>">Editar Pauta</a>
						</small>
					<?php endif; ?>
					<small class="d-block text-right mt-3">
						<a href="<?= site_url('colaboradores/artigos/cadastrar?pauta=' . $pauta['id']); ?>">Escrever
							artigo</a>
					</small>
				</p>
			</div>

		<?php endforeach; ?>
		<div class="d-block mt-3">
			<?php if ($pautasList['pager']): ?>
				<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>