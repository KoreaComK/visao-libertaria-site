<?php

use CodeIgniter\I18n\Time;

?>
<div class="card col-lg-3 mb-4 shadow-0 p-1">
	<img src="<?= $dados['imagem']; ?>" onerror="this.onerror = null; this.src='<?= site_url('public/assets/img/default-image.jpg') ?>'" alt="" class="card-img-top rounded-6 object-fit-cover">
	<div class="card-body p-2">
		<h5 class="card-title fw-bold">
			<?php if ($dados['pauta_antiga'] == 'S'): ?>
				<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
			<?php endif; ?>
			<?= $dados['titulo']; ?>
		</h5>
		<div>
			<small>
				<ul class="nav nav-divider">
					<li class="nav-item pointer">
						<div class="d-flex text-muted">
							<span class="">Sugerido por <a
									href="<?= site_url('site/colaborador/'); ?><?= urlencode($dados['apelido']); ?>"
									class="text-muted btn-link"><?= $dados['apelido']; ?></a></span>
						</div>
					</li>
					<li class="nav-item pointer text-muted">
						<?= Time::createFromFormat('Y-m-d H:i:s', $dados['criado'])->toLocalizedString('dd MMM yyyy'); ?>
					</li>
				</ul>
			</small>
			<p class="card-text"><?= $dados['texto']; ?></p>
			<a href="<?= $dados['link']; ?>" target="_blank" class="btn btn-outline-success btn-sm mb-1">Ler
				Notícia</a>
			<?php if (isset($_SESSION['colaboradores']['id'])): ?>
				<a href="" data-bs-titulo="<?= $dados['titulo']; ?>" data-bs-texto="<?= $dados['texto']; ?>"
					data-bs-pautas-id="<?= $dados['id']; ?>" data-bs-imagem="<?= $dados['imagem']; ?>"
					class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal"
					data-bs-target="#modalComentariosPauta">Comentários</a>
				<a href="<?= site_url('colaboradores/artigos/cadastrar?pauta=' . $dados['id']); ?>"
					class="btn btn-outline-primary btn-sm mb-1">Escrever artigo</a>
			<?php endif; ?>
			<?php if ($dados['colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
				<a href="<?= site_url('colaboradores/pautas/cadastrar/' . $dados['id']); ?>"
					data-bs-pautas-id="<?= $dados['id']; ?>" data-bs-toggle="modal" data-bs-target="#modalSugerirPauta"
					data-bs-titulo-modal="Alterar a pauta" class="btn btn-warning btn-sm mb-1">Editar</a>
			<?php endif; ?>
		</div>
	</div>
</div>
