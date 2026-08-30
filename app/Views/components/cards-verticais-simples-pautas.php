<?php
use CodeIgniter\I18n\Time;

$indiceLista = (int) ($dados['_list_index'] ?? 99);
$loadingImg = $indiceLista < 4 ? 'eager' : 'lazy';
$fetchPriority = $indiceLista === 0 ? 'high' : null;
?>
<div class="card col-lg-3 mb-4 shadow-0 p-1">
	<img src="<?= esc(url_imagem_pauta($dados['id'] ?? ''), 'attr'); ?>"
		alt="<?= esc((string) ($dados['titulo'] ?? ''), 'attr'); ?>"
		class="card-img-top rounded-6 object-fit-cover"
		width="480" height="208"
		decoding="async"
		loading="<?= esc($loadingImg, 'attr'); ?>"
		<?php if ($fetchPriority !== null): ?>fetchpriority="<?= esc($fetchPriority, 'attr'); ?>"<?php endif; ?>>
	<div class="card-body p-2">
		<h5 class="card-title fw-bold">
			<?php if ($dados['pauta_antiga'] == 'S'): ?>
				<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
			<?php endif; ?>
			<?= $dados['titulo']; ?>
		</h5>
		<div>
			<small>
				<ul class="nav align-items-center flex-wrap gap-2 mb-2">
					<li class="nav-item text-muted">
						<span>Sugerido por <a
								href="<?= site_url('site/colaborador/'); ?><?= urlencode($dados['apelido']); ?>"
								class="text-muted btn-link"><?= $dados['apelido']; ?></a></span>
					</li>
					<li class="nav-item text-muted">
						<?= app_time($dados['criado'])->toLocalizedString('dd MMM yyyy'); ?>
					</li>
				</ul>
			</small>
			<p class="card-text"><?= $dados['texto']; ?></p>
			<a href="<?= $dados['link']; ?>" target="_blank" class="btn btn-outline-success btn-sm mb-1">Ler
				Notícia</a>
			<?php if (isset($_SESSION['colaboradores']['id'])):
				$nComentarios = (int) ($dados['qtde_comentarios'] ?? 0);
				$comentariosAria = $nComentarios === 1
					? 'Comentários, 1 comentário'
					: 'Comentários, ' . $nComentarios . ' comentários';
				?>
				<a href="" data-bs-titulo="<?= $dados['titulo']; ?>" data-bs-texto="<?= $dados['texto']; ?>"
					data-bs-pautas-id="<?= $dados['id']; ?>" data-bs-imagem="<?= esc(url_imagem_pauta($dados['id'] ?? ''), 'attr'); ?>"
					class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal"
					data-bs-target="#modalComentariosPauta"
					aria-label="<?= esc($comentariosAria, 'attr'); ?>">Comentários (<?= $nComentarios; ?>)</a>
				<a href="<?= site_url('colaboradores/artigos/cadastrar?pauta=' . $dados['id']); ?>"
					class="btn btn-outline-primary btn-sm mb-1">Escrever artigo</a>
			<?php endif; ?>
			<?php if (isset($_SESSION['colaboradores']['id']) && (int) ($dados['colaboradores_id'] ?? 0) === (int) $_SESSION['colaboradores']['id']): ?>
				<a href="<?= site_url('colaboradores/pautas/cadastrar/' . $dados['id']); ?>"
					data-bs-pautas-id="<?= $dados['id']; ?>" data-bs-toggle="modal" data-bs-target="#modalSugerirPauta"
					data-bs-titulo-modal="Alterar a pauta" class="btn btn-warning btn-sm mb-1">Editar</a>
			<?php endif; ?>
		</div>
	</div>
</div>
