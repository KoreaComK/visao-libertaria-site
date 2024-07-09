<?php

use CodeIgniter\I18n\Time;

?>


<?php foreach ($pautasList['pautas'] as $pauta): ?>

	<div class="card col-lg-3 mb-4 shadow-0 p-1">
		<img src="<?= $pauta['imagem']; ?>" alt="" class="card-img-top rounded-6 object-fit-cover">
		<div class="card-body p-2">
			<h5 class="card-title fw-bold">
				<?php if ($pauta['pauta_antiga'] == 'S'): ?>
					<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
				<?php endif; ?>
				<?= $pauta['titulo']; ?>
			</h5>
			<div>
				<small>
					<ul class="nav nav-divider">
						<li class="nav-item pointer">
							<div class="d-flex text-muted">
								<span class="">Sugerido por <a href="<?= site_url('site/colaborador/'); ?><?= urlencode($pauta['apelido']); ?>"
										class="text-muted btn-link"><?= $pauta['apelido']; ?></a></span>
							</div>
						</li>
						<li class="nav-item pointer text-muted">
							<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMM yyyy'); ?>
						</li>
					</ul>
				</small>
				<p class="card-text"><?= $pauta['texto']; ?></p>
				<a href="<?= $pauta['link']; ?>" target="_blank" class="btn btn-outline-success btn-sm mb-1">Ler
					Notícia</a>
				<a href="" data-bs-titulo="<?= $pauta['titulo']; ?>" data-bs-texto="<?= $pauta['texto']; ?>"
					data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-imagem="<?= $pauta['imagem']; ?>"
					class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal"
					data-bs-target="#modalComentariosPauta">Comentários</a>
				<a href="<?= site_url('colaboradores/artigos/cadastrar?pauta=' . $pauta['id']); ?>"
					class="btn btn-outline-primary btn-sm mb-1">Escrever artigo</a>
				<?php if ($pauta['colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
					<a href="<?= site_url('colaboradores/pautas/cadastrar/' . $pauta['id']); ?>"
						data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-toggle="modal" data-bs-target="#modalSugerirPauta"
						data-bs-titulo-modal="Alterar a pauta" class="btn btn-warning btn-sm mb-1">Editar</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>


<div class="d-none">
	<?php if ($pautasList['pager']): ?>
		<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
	<?php endif; ?>
</div>