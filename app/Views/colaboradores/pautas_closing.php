<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<style>
	/* Sticky precisa que a coluna direita tenha a mesma altura que a esquerda (row sem align-items-start). */
	.pautas-closing-resumo-card.sticky-top {
		z-index: 1020;
	}

	#pautas-encontradas-listagem {
		scroll-margin-top: 5.5rem;
	}
</style>

<div class="container py-3">
	<div class="row align-items-center g-3 mb-4">
		<div class="col-12 col-md">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
			<p class="text-muted small mb-0">Pesquise pautas abertas, revise comentários e finalize o fechamento quando necessário.</p>
		</div>
		<div class="col-12 col-sm-auto">
			<button type="button" class="btn btn-primary btn-sm w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modal-fechar">
				Fechar Pauta
			</button>
		</div>
	</div>

	<div class="card shadow-sm border-0 mb-4">
		<div class="card-header bg-white py-3">
			<h5 class="mb-0">Pesquisa de pautas</h5>
		</div>
		<div class="card-body">
			<form method="get" id="pesquisa-permissoes">
				<div class="row g-2 align-items-stretch">
					<div class="col-12 col-md-9">
						<input type="text" class="form-control form-control-sm h-100" id="pesquisa"
							placeholder="Pesquise pelas pautas" />
					</div>
					<div class="col-12 col-md-3 d-grid">
						<button class="btn btn-primary btn-sm btn-submeter" type="button">Buscar</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-12 col-lg-8">
			<div class="card shadow-sm border-0" id="pautas-encontradas-listagem">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0">Pautas encontradas</h5>
				</div>
				<div class="card-body">
					<div class="pautas-list">
						<div class="text-center text-muted small py-4">Carregando pautas...</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-4">
			<div class="card shadow-sm border-0 sticky-top pautas-closing-resumo-card" style="top: 1rem;">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0 h6">Reservadas para fechamento</h5>
					<small class="text-muted d-block mt-1">Estas pautas entram no fechamento ao confirmar “Fechar Pauta”.</small>
					<div class="d-grid mt-2">
						<button type="button" class="btn btn-primary btn-sm w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modal-fechar">
							Fechar Pauta
						</button>
					</div>
				</div>
				<div class="card-body" style="max-height: min(70vh, calc(100vh - 12rem)); overflow-y: auto;">
					<div id="pautas-reservadas-resumo">
						<?= view('template/pautas_reservadas_resumo', ['pautasReservadas' => $pautasReservadas ?? []]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-fechar" tabindex="-1" role="dialog" aria-labelledby="FecharPauta" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="FecharPauta">Fechar pauta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p class="mb-1">Informe o título do fechamento da pauta.</p>
				<small class="text-muted d-block mb-3">Se não for informado nenhum título, será usada a data de hoje.</small>
				<label for="titulo_fechamento_pauta" class="form-label mb-1">Título do fechamento</label>
				<input type="text" id="titulo_fechamento_pauta" class="form-control" placeholder="Ex.: Fechamento Abril/2026"
					aria-label="Título do Fechamento">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-fechar">Fechar Pauta</button>
			</div>
		</div>
	</div>
</div>

<script>

	window.scrollPautasListagemTopo = function () {
		var el = document.getElementById('pautas-encontradas-listagem');
		if (el) {
			el.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	};

	window.recarregarResumoPautasReservadas = function () {
		$.ajax({
			url: "<?= base_url('colaboradores/pautas/pautasReservadasResumo'); ?>",
			type: 'get',
			dataType: 'html',
			success: function (html) {
				$('#pautas-reservadas-resumo').html(html);
			}
		});
	};

	$('.btn-fechar').on('click', function (e) {
		var id_pauta = e.target.getAttribute('data-information');

		form = new FormData();
		form.append('titulo', $('#titulo_fechamento_pauta').val());
		form.append('metodo', 'fechar');

		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/fechar'); ?>",
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
					setTimeout(function () {
						var id = retorno.parametros && retorno.parametros.id_pautas_fechadas;
						var destino = id
							? '<?php echo base_url('colaboradores/pautas/fechadas/'); ?>' + id
							: '<?php echo base_url('colaboradores/pautas/fechadas'); ?>';
						document.location.href = destino;
					}, 2000);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
				$('#modal-fechar').modal('toggle');
			}
		});
	});

	$('.btn-submeter').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/pautasList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				pesquisa: $('#pesquisa').val()
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.pautas-list').html(data);
				if (typeof window.recarregarResumoPautasReservadas === 'function') {
					window.recarregarResumoPautasReservadas();
				}
			}
		});
	});

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$(document).ready(function () {
		$(".btn-submeter").click();
	});

</script>
<?= view('template/modal_comentarios_pauta'); ?>

<?= $this->endSection(); ?>