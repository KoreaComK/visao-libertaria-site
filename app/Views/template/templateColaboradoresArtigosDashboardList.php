<?php use CodeIgniter\I18n\Time; ?>
<table class="table align-middle p-4 mb-0 table-hover table-shrink">
	<!-- Table head -->
	<thead class="table-dark">
		<tr>
			</th>
			<th scope="col" class="border-0 rounded-start"></th>
			<th scope="col" class="border-0">Título</th>
			<th scope="col" class="border-0">Escritor</th>
			<th scope="col" class="border-0">Publicado em</th>
			<th scope="col" class="border-0">Tipo</th>
			<th scope="col" class="border-0">Status</th>
			<th scope="col" class="border-0 rounded-end"></th>
		</tr>
	</thead>

	<!-- Table body START -->
	<tbody class="border-top-0">
		<?php if ($artigosList['artigos'] !== NULL && !empty($artigosList['artigos'])): ?>
			<?php foreach ($artigosList['artigos'] as $artigo): ?>
				<tr>
					<!-- Table data -->
					<td>
						<img class="rounded-3"  src="<?= $artigo['imagem']; ?>" style="width: 4rem; height auto;" />
					</td>
					<!-- Table data -->
					<td>
						<h6 class="mb-0"><a href="#"><?= $artigo['titulo']; ?></a></h6>
					</td>
					<td>
						<h6 class="mb-0"><a href="<?=site_url('site/escritor/'.urlencode($artigo['escrito'])); ?>"><?= $artigo['escrito']; ?></a></h6>
					</td>
					<!-- Table data -->
					<td><?= ($artigo['data_publicado']!=NULL)?(Time::createFromFormat('Y-m-d H:i:s', $artigo['data_publicado'])->toLocalizedString('dd MMMM yyyy')):(''); ?>
					</td>
					<!-- Table data -->
					<td>
					<a href="#" class="badge text-bg-<?= ($artigo['tipo_artigo']=='T')?('primary'):('danger');?> mb-2"><?= ($artigo['tipo_artigo']=='T')?('Teórico'):('Notícia');?></a>
					</td>
					<!-- Table data -->
					<td>
						<span
							class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?> mb-2"><?= $artigo['nome']; ?></span>
					</td>
					<!-- Table data -->
					<td>
						<div class="d-flex gap-2">
							<a href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>"
								class="btn btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip" data-placement="top"
								title="Ir para o artigo"><i class="fas fa-arrow-up-right-from-square"></i></i></a>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<!-- Table data -->
				<td colspan="6">
					<h6 class="text-center">Nenhum resultado foi encontrado</h6>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
	<!-- Table body END -->
</table>
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
		$('.page-link').on('click', function (e) {
			e.preventDefault();
			refreshListPublicado(e.target.href);
		});
	});
</script>