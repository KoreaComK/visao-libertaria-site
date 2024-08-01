<?php use CodeIgniter\I18n\Time; ?>

<?php if ($artigosList['artigos'] !== NULL && !empty($artigosList['artigos'])): ?>
	<?php foreach ($artigosList['artigos'] as $artigo): ?>

		<div class="card shadow-0 col-sm-6 col-lg-4">
			<a href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>">
				<div class="bg-image hover-zoom rounded-3">
					<img class="w-100 object-fit-cover" src="<?= $artigo['imagem']; ?>">
				</div>
			</a>
			<div class="card-body p-2">
				<h5 class="card-title fw-bold"><a class="btn-link h5 "
						href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>">
						<?= $artigo['titulo']; ?></a>
				</h5>
				<div>
					<small>
						<ul class="nav nav-divider mb-3">
							<li class="nav-item">Publicado em
								<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
							</li>
						</ul>
					</small>
					<p class="">
						<?= $artigo['gancho']; ?>
					</p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="text-center">
		<h6 class="text-center">Nenhum resultado foi encontrado.</h6>
	</div>
<?php endif; ?>
<div class="mt-3 d-flex justify-content-center">
	<?php if ($artigosList['pager']): ?>
		<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
	<?php endif; ?>
</div>
<script>
	$(function () {
		$('.btn-tooltip').tooltip();
	});

	$(document).ready(function () {
		$('.page-link ').on('click', function (e) {
			e.preventDefault();
			$.ajax({
				url: e.target.href,
				type: 'get',
				dataType: 'html',
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (data) {
					$('.listagem-escritor').html(data);
				}
			});
		});
	});
</script>