<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-12">
			<div class="row g-4">
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-info bg-opacity-10 p-4 h-100">
						<h6>Artigos escritos
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Artigos escritos nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-1 text-info">
							<?= (($artigos['atual'] < 10) ? ('0') : ('')) . (number_format($artigos['atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2"><span
								class="text-<?= ($artigos['diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($artigos['diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($artigos['diferenca'] > 0) ? ('fa-up-long') : (($artigos['diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-success bg-opacity-10 p-4 h-100">
						<h6>Artigos publicados
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Artigos publicados nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-1 text-success">
							<?= (($artigos['publicados_atual'] < 10) ? ('0') : ('')) . (number_format($artigos['publicados_atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">
							<span
								class="text-<?= ($artigos['publicados_diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($artigos['publicados_diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($artigos['publicados_diferenca'] > 0) ? ('fa-up-long') : (($artigos['publicados_diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês
						</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-info bg-opacity-10 p-4 h-100">
						<h6>Pautas cadastradas
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Pautas cadastradas nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-1 text-info">
							<?= (($pautas['atual'] < 10) ? ('0') : ('')) . (number_format($pautas['atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2"><span
								class="text-<?= ($pautas['diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($pautas['diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($pautas['diferenca'] > 0) ? ('fa-up-long') : (($pautas['diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-success bg-opacity-10 p-4 h-100">
						<h6>Pautas utilizadas
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Pautas utilizadas nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-1 text-success">
							<?= (($pautas['utilizados_atual'] < 10) ? ('0') : ('')) . (number_format($pautas['utilizados_atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">
							<span
								class="text-<?= ($pautas['utilizados_diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($pautas['utilizados_diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($pautas['utilizados_diferenca'] > 0) ? ('fa-up-long') : (($pautas['utilizados_diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12 col-lg-4">
			<div class="card border h-100">
				<div class="card-header border-bottom d-flex justify-content-between align-items-center p-3">
					<h5 class="card-header-title mb-0">Sobre o colaborador</h5>
				</div>
				<div class="card-body">
					<div class="d-sm-flex justify-content-sm-between align-items-center mb-4">
						<div class="d-flex align-items-center">
							<?php if ($colaboradores['avatar'] !== NULL && $colaboradores['avatar'] != ''): ?>
								<div class="avatar">
									<img class="avatar-img rounded-circle" style="width: 3rem;"
										src="<?= $colaboradores['avatar']; ?>" alt="">
								</div>
							<?php endif; ?>
							<!-- Info -->
							<div class="ms-3">
								<h5 class="mb-0"><?= $colaboradores['apelido']; ?></h5>
								<p class="mb-0 small"><?= $colaboradores['email']; ?></p>
							</div>
						</div>
						<!-- <div class="d-flex mt-2 mt-sm-0">
							<h6 class="bg-danger py-2 px-3 text-white rounded">14K Follow</h6>
							<h6 class="bg-info py-2 px-3 text-white rounded ms-2">856 Posts</h6>
						</div> -->
					</div>

					<div class="row gy-3">
						<div class="col-md-12">
							<ul class="list-group list-group-borderless">
								<li class="list-group-item">
									<span>Carteira:</span>
									<span class="h6 mb-0"><?= $colaboradores['carteira']; ?></span>
								</li>
								<li class="list-group-item">
									<span>Colaborador desde:</span>
									<span
										class="h6 mb-0"><?= ($colaboradores['criado'] !== NULL) ? Time::createFromFormat('Y-m-d H:i:s', $colaboradores['criado'])->toLocalizedString('dd MMMM yyyy') : (''); ?></span>
								</li>
								<li class="list-group-item">
									<span
										class="h6 mb-0"><?= ($colaboradores['confirmado_data'] !== NULL) ? ('E-mail confirmado') : ('E-mail não confirmado'); ?></span>
								</li>
								<li class="list-group-item">
									<a class="link btn-link text-danger strike-bloquear <?=($colaboradores['strike_data'] !== NULL)?('d-none'):(''); ?>" href="javascript:void(0);">Bloquear colaborador</a>
									<a class="link btn-link text-danger strike-desbloquear <?=($colaboradores['strike_data'] !== NULL)?(''):('d-none'); ?>" href="javascript:void(0);">Desbloquear colaborador</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-lg-4">
			<!-- Popular blog START -->
			<div class="card border h-100">
				<!-- Card header -->
				<div class="card-header border-bottom p-3">
					<h5 class="card-header-title mb-0">Últimos artigos escritos deste mês</h5>
				</div>

				<!-- Card body START -->
				<div class="card-body p-3">

					<div class="row">
						<?php if (!empty($artigos['lista']) && $artigos['lista'] !== NULL): ?>
							<?php foreach ($artigos['lista'] as $chave => $artigo): ?>
								<?php if ($chave > 2): ?>
									<div class="col-12">
										<div class="d-flex align-items-center position-relative">
											<img class="col-2 rounded-3" src="<?= $artigo['imagem']; ?>" alt="Imagem">
											<div class="ms-3">
												<a href="<?= base_url("colaboradores/artigos/detalhamento/" . $artigo["id"]); ?>"
													class="h6 btn-link">
													<?= $artigo['titulo']; ?></a>
											</div>
										</div>
									</div>
									<hr class="my-3">
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="text-center">Artigos não encontrados.</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
			<!-- Popular blog END -->
		</div>

		<div class="col-md-6 col-lg-4">
			<!-- Popular blog START -->
			<div class="card border h-100">
				<!-- Card header -->
				<div class="card-header border-bottom p-3">
					<h5 class="card-header-title mb-0">Últimas pautas cadastradas deste mês</h5>
				</div>

				<!-- Card body START -->
				<div class="card-body p-3">

					<div class="row">
						<?php if (!empty($pautas['lista']) && $pautas['lista'] !== NULL): ?>
							<?php foreach ($pautas['lista'] as $chave => $pauta): ?>
								<?php if ($chave > 2): ?>
									<div class="col-12">
										<div class="d-flex align-items-center position-relative">
											<img class="col-2 rounded-3" src="<?= $pauta['imagem']; ?>" alt="Imagem">
											<div class="ms-3">
												<a href="<?= base_url("colaboradores/pautas	/detalhamento/" . $pauta["id"]); ?>"
													class="h6 btn-link">
													<?= $pauta['titulo']; ?></a>
											</div>
										</div>
									</div>
									<hr class="my-3">
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="text-center">Pautas não encontradas.</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
			<!-- Popular blog END -->
		</div>

		<div class="col-lg-12">
			<div class="card border mb-4">
				<div class="card-body">
					<h5 class="mb-3">Atribuições</h5>
					<form class="row" novalidate="yes" method="post" id="atribuicoes">
						<?php foreach ($atribuicoes as $atribuicao): ?>
							<div class="col-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="<?= $atribuicao['id']; ?>"
										name="atribuicoes[<?= $atribuicao['id']; ?>]" value="<?= $atribuicao['id']; ?>"
										<?= in_array($atribuicao['id'], $colaboradores_atribuicoes) ? ('checked') : (''); ?>>
									<label class="form-check-label" for="<?= $atribuicao['id']; ?>">
										<small class="text-muted"><?= $atribuicao['nome']; ?></small></label>
								</div>
							</div>
						<?php endforeach; ?>

						<div class="d-sm-flex justify-content-end">
							<button type="button" class="btn btn-sm btn-primary me-2 mb-0 salvar-atribuicoes">Salvar
								atribuições</button>
						</div>
					</form>
				</div>
			</div>


		</div>

		<div class="col-lg-12">
			<div class="card border h-100">
				<div class="card-header border-bottom d-flex justify-content-between align-items-center p-3">
					<h5 class="card-header-title mb-0">Emblemas do colaborador</h5>
				</div>
				<div class="card-body">
					<div class="row gy-3">
						<div class="col-12">
							<div class="text-center">Em desenvolvimento</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12">
			<!-- Chart START -->
			<div class="card border h-100">
				<!-- Card header -->
				<div class="card-header p-3 border-bottom">
					<h5 class="card-header-title mb-0">Artigos publicados nos últimos 12 meses</h5>
				</div>
				<!-- Card body -->
				<div class="card-body">
					<div id="chart"></div>
				</div>
			</div>
			<!-- Chart END -->
		</div>

		<div class="col-lg-12">
			<div class="card border h-100">
				<div class="card-body">
					<div class="row gy-3">
						<div class="col-12">
							<div class="text-center">
								<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3 btn-historico" id="btn-historico"
									type="button">Mostrar
									ações do usuário</button>
								<div class="historicos-list col-12 text-center"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
	$(document).ready(function () {
		$(function () {
			$('.btn-tooltip').tooltip();
		});
	});

	$('.salvar-atribuicoes').on('click', function (e) {
		form = new FormData(atribuicoes);
		form.append('colaborador_id', <?= $colaboradores['id'] ?>);

		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/permissoes'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});

	});
	
	

	function strike(form){
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/permissoes'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

	$('.strike-bloquear').on('click', function (e) {
		form = new FormData();
		form.append('strike_data', 'true');
		form.append('colaborador_id', <?= $colaboradores['id'] ?>);
		strike(form);
		$('.strike-desbloquear').removeClass('d-none');
		$('.strike-bloquear').addClass('d-none');
	});

	$('.strike-desbloquear').on('click', function (e) {
		form = new FormData();
		form.append('strike_data', 'false');
		form.append('colaborador_id', <?= $colaboradores['id'] ?>);
		strike(form);
		$('.strike-desbloquear').addClass('d-none');
		$('.strike-bloquear').removeClass('d-none');
	});

	$('.btn-historico').on('click', function (e) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/historico'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				apelido: '<?= $colaboradores['id']; ?>',
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.historicos-list').html(data);
			}
		});
	});
</script>

<?php if (isset($graficos['base']) && !empty($graficos['base'])): ?>
	<script>
		var options = {
			chart: {
				type: 'bar',
				height: '350',
			},
			plotOptions: {
				bar: {
					borderRadius: 10,
					dataLabels: {
						position: 'top',
					},
				}
			},
			dataLabels: {
				enabled: true,
				formatter: function (val) {
					return val;
				},
				offsetY: -20,
				style: {
					fontSize: '14px',
					colors: ["#304758"]
				}
			},
			series: [{
				name: 'Artigos escritos',
				data: [
					<?php foreach ($graficos['base'] as $chave => $i): ?>
																				<?= '"' . $i . '",'; ?>
												<?php endforeach; ?>
				]
			}],
			xaxis: {
				categories: [
					<?php foreach ($graficos['base'] as $chave => $i): ?>
																				<?= '"' . $chave . '",'; ?>
												<?php endforeach; ?>
				]
			}
		}

		var chart = new ApexCharts(document.querySelector("#chart"), options);

		chart.render();
	</script>
<?php endif; ?>

<?= $this->endSection(); ?>