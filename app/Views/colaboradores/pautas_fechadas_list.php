<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<!-- Title -->
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<div class="my-3 p-3 rounded box-shadow">

		<?php foreach ($pautasList['pautas'] as $pauta): ?>

			<div class="media text-muted pt-3 border-bottom row " id="pauta_<?= $pauta['id']; ?>">
				<div class="col-12">
					<p class="media-body pb-3 mb-0 small lh-125  border-gray">
						<strong class="d-block">
							<?= $pauta['titulo']; ?>
						</strong>
						<small><a href="<?= site_url('colaboradores/pautas/fechadas/' . $pauta['id']); ?>"> Ler todas as
								pautas</a></small>
					</p>
				</div>
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