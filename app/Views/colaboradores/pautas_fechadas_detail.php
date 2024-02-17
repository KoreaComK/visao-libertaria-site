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
						<?php if ($pauta['pauta_antiga']=='S'): ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" alt="Pauta Antiga" fill="currentColor" class="bi bi-patch-exclamation-fill text-danger" viewBox="0 0 16 16">
								<path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
							</svg>
						<?php endif; ?>
						<a href="<?= site_url('colaboradores/pautas/detalhe/'.$pauta['id']); ?>" target="_blank"><?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?> - <?= $pauta['titulo']; ?> - <?=($pauta['qtde_comentarios']>0)?($pauta['qtde_comentarios']):('Nenhum'); ?><?=($pauta['qtde_comentarios']>1)?(' comentários'):(' comentário'); ?></a> <a href="<?= $pauta['link']; ?>" target="_blank">Ir para a notícia</a><br/>
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