<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="d-flex mt-3 justify-content-center">
		<a class="btn btn-primary" href="<?= site_url('colaboradores/admin/financeiro/pagar'); ?>"> Fazer
			Pagamento</a>
	</div>
	<div class="my-3 p-3 rounded box-shadow">

		<?php foreach ($pagamentosList['pagamentos'] as $pagamento): ?>

			<div class="media text-muted pt-3 border-bottom row " id="pauta_<?= $pagamento['id']; ?>">
				<div class="col-12">
					<p class="media-body pb-3 mb-0 small lh-125  border-gray">
						<strong class="d-block">
							<?= $pagamento['titulo']; ?>
						</strong>
						<small><a href="<?= site_url('colaboradores/admin/financeiro/' . $pagamento['id']); ?>"> Ver
								detalhes
								de Pagamento.</a></small>
					</p>
				</div>
			</div>

		<?php endforeach; ?>
		<div class="d-block mt-3">
			<?php if ($pagamentosList['pager']): ?>
				<?= $pagamentosList['pager']->simpleLinks('pagamentos', 'default_template') ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>