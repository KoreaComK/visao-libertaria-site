<?php use CodeIgniter\I18n\Time; ?>

<?php if ($pautasList['pautas'] !== NULL && !empty($pautasList['pautas'])): ?>
	<?php foreach ($pautasList['pautas'] as $pauta): ?>

		<div class="card shadow-0 col-sm-6 col-lg-4">
			<a href="<?= site_url('colaboradores/pautas/detalhamento/' . $pauta['id']); ?>">
				<div class="bg-image hover-zoom rounded-3">
					<img class="w-100 object-fit-cover" style="max-height: 20rem;" src="<?= $pauta['imagem']; ?>">
				</div>
			</a>
			<div class="card-body p-2">
				<h5 class="card-title fw-bold"><a class="btn-link h5 "
						href="<?= site_url('colaboradores/pautas/detalhamento/' . $pauta['id']); ?>">
						<?= $pauta['titulo']; ?></a>
				</h5>
				<div>
					<small>
						<ul class="nav nav-divider mb-3">
							<li class="nav-item">Sugerido em 
								<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMM yyyy'); ?>
							</li>
						</ul>
					</small>
					<p class="">
						<?= $pauta['texto']; ?>
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
	<?php if ($pautasList['pager']): ?>
		<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
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
					$('.listagem-colaborador').html(data);
				}
			});
		});
	});
</script>