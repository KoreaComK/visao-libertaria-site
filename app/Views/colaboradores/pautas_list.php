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
	<div class="my-3 p-3 bg-white rounded box-shadow">

		<?php
		foreach ($pautasList['pautas'] as $pauta): ?>

			<div class="media text-muted pt-3 border-bottom">
				<image class="mr-2 rounded img-thumbnail" for="btn-check-outlined" src="<?= $pauta['imagem']; ?>" />
				<p class="media-body pb-3 mb-0 small lh-125  border-gray">
					<strong class="d-block">
						<?php if ($pauta['pauta_antiga']=='S'): ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" alt="Pauta Antiga" fill="currentColor" class="bi bi-patch-exclamation-fill text-danger" viewBox="0 0 16 16">
								<path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
							</svg>
						<?php endif; ?>
						<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?> - 
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