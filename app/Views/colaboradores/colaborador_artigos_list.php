<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-12 mb-4">
				<div class="row g-4">
					<div class="col-md-6 col-xl-3">
						<div class="card card-body bg-info bg-opacity-10 p-4 h-100">
							<h6>Artigos escritos
								<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip"
									data-toggle="tooltip" data-placement="top"
									title="Artigos escritos nos últimos 30 dias">
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
								<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip"
									data-toggle="tooltip" data-placement="top"
									title="Artigos publicados nos últimos 30 dias">
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
								<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip"
									data-toggle="tooltip" data-placement="top"
									title="Pautas cadastradas nos últimos 30 dias">
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
								<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip"
									data-toggle="tooltip" data-placement="top"
									title="Pautas utilizadas nos últimos 30 dias">
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
			<div class="col-12">
				<div class="row g-4 mb-4">
					<div class="col-sm-4 col-lg-2">
						<!-- Card START -->
						<div class="card card-body border h-100 text-center">
							<!-- Icon -->
							<div class="fs-1 text-success bg-success bg-opacity-10 rounded-3">
								<i class="far fa-file"></i>
							</div>
							<!-- Content -->
							<div class="ms-0">
								<h3 class="mb-0">
									<?= (($resumo['escritos'] < 10) ? ('0') : ('')) . $resumo['escritos']; ?>
								</h3>
								<h6 class="mb-0">Escritos</h6>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-4 col-lg-2">
						<!-- Card START -->
						<div class="card card-body border h-100 text-center">
							<!-- Icon -->
							<div class="fs-1 text-success bg-success bg-opacity-10 rounded-3">
								<i class="fab fa-youtube"></i>
							</div>
							<!-- Content -->
							<div class="ms-0">
								<h3 class="mb-0">
									<?= (($resumo['publicados'] < 10) ? ('0') : ('')) . $resumo['publicados']; ?>
								</h3>
								<h6 class="mb-0">Publicados</h6>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-sm-4 col-lg-2">
						<!-- Card START -->
						<div class="card card-body border h-100 text-center">
							<!-- Icon -->
							<div class="fs-1 text-success bg-success bg-opacity-10 rounded-3">
								<i class="fas fa-pen"></i>
							</div>
							<!-- Content -->
							<div class="ms-0">
								<h3 class="mb-0">
									<?= (($resumo['palavras_totais'] < 10) ? ('0') : ('')) . number_format($resumo['palavras_totais'], 0, ',', '.'); ?>
								</h3>
								<h6 class="mb-0">Palavras totais</h6>
							</div>
						</div>
						<!-- Card END -->
					</div>
					<div class="col-lg-6">
						<!-- Card START -->
						<div class="card card-body border h-100">
							<h3>Artigos publicados este mês</h3>
							<div>
								<div class="d-flex">
									<h6 class="mt-0">Quantos artigos publicados no mês são de sua autoria</h6>
									<span
										class="ms-auto"><?= ($resumo['autoral_mes'] < 10) ? ('0' . $resumo['autoral_mes']) : ($resumo['autoral_mes']); ?>
										de
										<?= ($resumo['total_mes'] < 10) ? ('0' . $resumo['total_mes']) : ($resumo['total_mes']); ?></span>
								</div>
								<div class="progress rounded-3">
									<div class="progress-bar progress-bar-striped progress-bar-animated"
										role="progressbar"
										style="width: <?= ($resumo['total_mes'] > 0) ? (number_format(($resumo['autoral_mes'] / $resumo['total_mes']) * 100, 0, ',', '.')) : ('0'); ?>%;"
										aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<!-- Card END -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<!-- Post list table START -->
				<div class="card border bg-transparent rounded-3 mb-4">

					<div class="card-header bg-transparent border-bottom p-3">
						<div class="d-sm-flex justify-content-between align-items-center">
							<h5 class="mb-2 mb-sm-0">Meus artigos em produção</h5>
							<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>"
								class="btn btn-sm btn-primary mb-0">Novo Artigo</a>
						</div>
					</div>
					<!-- Card body START -->
					<div class="card-body p-3">

						<!-- Search and select START -->
						<div class="row g-3 align-items-center justify-content-between mb-3"
							data-np-autofill-form-type="other" data-np-checked="1" data-np-watching="1">
							<!-- Search -->
							<div class="col-md-8">
								<form class="rounded position-relative" data-np-autofill-form-type="other"
									data-np-checked="1" data-np-watching="1">
									<input class="form-control pe-5 bg-transparent" type="search" id="text-pesquisa"
										name="text-pesquisa" placeholder="Pesquisar" aria-label="Pesquisar">
									<button
										class="btn bg-transparent border-0 px-4 py-2 position-absolute top-50 end-0 translate-middle-y btn-pesquisar-producao"
										type="submit"><i class="fas fa-magnifying-glass"></i></button>
								</form>
							</div>

							<!-- Select option -->
							<div class="col-md-3">
								<!-- Short by filter -->
								<form>
									<select class="form-select z-index-9 bg-transparent select-pesquisa"
										id="select-pesquisa" name="select-pesquisa">
										<option value="">Escolha a fase</option>
										<option value="1">Escrevendo</option>
										<option value="2">Revisando</option>
										<option value="3">Narrando</option>
										<option value="4">Produzindo</option>
										<option value="5">Publicando</option>
									</select>
								</form>
							</div>
						</div>
						<!-- Search and select END -->

						<!-- Post list table START -->
						<div class="table-responsive border-0">
							<table class="table align-middle p-4 mb-0 table-hover table-shrink">
								<!-- Table head -->
								<thead class="table-dark">
									<tr style="vertical-align: middle !important;">
										<th scope="col" class="border-0 rounded-start"></th>
										<th scope="col" class="border-0">Título</th>
										<th scope="col" class="border-0">Atualizado em</th>
										<th scope="col" class="border-0">Tipo do artigo</th>
										<th scope="col" class="border-0">Status</th>
										<th scope="col" class="border-0 rounded-end"></th>
									</tr>
								</thead>

								<!-- Table body START -->
								<tbody class="border-top-0 tabela-producao"></tbody>
								<!-- Table body END -->
							</table>
						</div>
						<!-- Post list table END -->

					</div>
				</div>
				<!-- Post list table END -->
			</div>
			<div class="col-12">
				<!-- Meus artigos publicados -->
				<div class="card border bg-transparent rounded-3">
					<?php
						$estrutura_tabela = array('dados' => array(
							'cabecalho' => array (
								'titulo' => 'Meus artigos publicados',
								'botao' => array(
									'show' => true,
									'label' => 'Ver página pública',
									'url' => site_url('site/escritor/'.urlencode($_SESSION['colaboradores']['nome'])),
									'target' => '_blank'
								),
								'pesquisa' => array(
									'tipo' => 'simples',
									'campo' => 'titulo',
									'ajax_default' => array('tipo'=>'finalizado')
								)
							)
						));
					?>
					<?= view_cell('\App\Libraries\Tabelas::adicionaEstruturaTabela', $estrutura_tabela); ?>
				</div>
			</div>
		</div>

	</div>
</div>



<script>

	$(document).ready(function () {
		$('.btn-pesquisar-producao').on('click', function (e) {
			refreshListProducao();
		});
		$('.select-pesquisa').on('change', function (e) {
			refreshListProducao();
		});
		$(".btn-pesquisar-producao").trigger("click");
	});

	function refreshListProducao() {
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/meusArtigosList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				titulo: $('#text-pesquisa').val(),
				fase_producao: $('#select-pesquisa').val(),
				tipo: 'emProducao'
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.tabela-producao').html(data);
			}
		});
	}

	$(document).ready(function () {

		$("#modal-btn-si").on("click", function () {
			$("#mi-modal").modal('hide');
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/descartar/'); ?>" + artigoId,
				type: 'get',
				dataType: 'json',
				data: {
				},
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$(".btn-pesquisar-producao").trigger("click");
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
					artigoId = null;
				}
			});
			return false;
		});
	});

	$("form").on("submit", function (e) {
		e.preventDefault();
	});
</script>

<?= $this->endSection(); ?>