<?php

use CodeIgniter\I18n\Time;

?>
<?php foreach ($artigosList['artigos'] as $artigo): ?>

	<div class="card shadow-0 col-sm-6 col-lg-3">
		<div class="bg-image hover-zoom rounded-3">
			<img class="w-100 object-fit-cover" src="<?= $artigo['imagem']; ?>">
		</div>
		<div class="card-body p-2">
			<h5 class="card-title fw-bold"><a class="btn-link text-black h5"
					href="<?= base_url() . 'site/artigo/' . $artigo['url_friendly']; ?>">
					<?= $artigo['titulo']; ?></a>
			</h5>
			<div>
				<small>
					<ul class="nav nav-divider">
						<li class="nav-item pointer">
							<div class="d-flex text-muted">
								<span class="">Por <a href="#"
										class="text-muted btn-link"><?= $artigo['apelido']; ?></a></span>
							</div>
						</li>
						<li class="nav-item pointer text-muted">
							<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('dd/MM/yyyy'); ?>
						</li>
					</ul>
				</small>
				<p class="">
					<?= substr($artigo['texto_revisado'], 0, strpos($artigo['texto_revisado'], "\n")); ?>
				</p>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<div class="d-none">
	<?php if ($artigosList['pager']): ?>
		<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
	<?php endif; ?>
</div>