<?php 
use CodeIgniter\I18n\Time;
?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<div class="my-3 p-3 bg-white rounded box-shadow">
		<?php $tag = NULL; ?>
		<?php foreach ($pautasList as $tag => $pautas): ?>
			<div class="card mb-3">
				<div class="card-body">
					<span class="btn btn-primary mb-3"><?= $tag; ?></span>
					<p class="card-text"><small class="text-muted"><bold>Quem sugeriu as pautas:</small></p>
					<p class="card-title">
					<?php foreach ($pautas['colaboradores'] as $pauta): ?>
						<?= $pauta; ?><br/>
					<?php endforeach; ?>
					</p>
					<p class="card-text"><small class="text-muted">Notícias selecionadas para a pauta</small></p>
					<p class="card-text">
					<?php foreach ($pautas['pautas'] as $pauta): ?>
						<a href="<?= $pauta['link']; ?>" target="_blank"><?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?> - <?= $pauta['titulo']; ?></a><br/>
					<?php endforeach; ?>
					</p>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="d-block mt-3">
			<a href="<?= site_url('colaboradores/pautas/fechadas'); ?>">Voltar</a></small>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>