<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="cron-tab" data-toggle="tab" data-target="#cron"
							type="button" role="tab" aria-controls="cron" aria-selected="true">Cron</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="pautas-tab" data-toggle="tab" data-target="#pautas" type="button"
							role="tab" aria-controls="pautas" aria-selected="false">Pautas</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="gerais-tab" data-toggle="tab" data-target="#gerais" type="button"
							role="tab" aria-controls="gerais" aria-selected="false">Geral</button>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade justify-content-center mt-2 show active" id="cron" role="tabpanel"
						aria-labelledby="cron-tab">
						<form class="col-12 mt-4" novalidate="yes" method="post" id="cron_form">
							<div class="mb-3">
								<label for="username">Hash do Cron <span class="text-muted">Ao alterar o hash, é
										necessário alterar o Cron no servidor</span></label>
								<div class="input-group">
									<input type="text" class="form-control" id="cron_hash" placeholder="Hash do Cron"
										name="cron_hash" required
										value="<?= (isset($dados['cron_hash'])) ? ($dados['cron_hash']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<h4>Pautas</h4>
							</div>

							<div class="mb-3">
								<label for="titulo">Habilitar exclusão das pautas</label>
								<select class="custom-select" id="cron_pautas_status_delete"
									name="cron_pautas_status_delete">
									<option value="1" <?= (isset($dados['cron_pautas_status_delete']) && $dados['cron_pautas_status_delete'] == '1') ? ('selected') : (''); ?>>Ativar
									</option>
									<option value="0" <?= (isset($dados['cron_pautas_status_delete']) && $dados['cron_pautas_status_delete'] == '0') ? ('selected') : (''); ?>>Inativar
									</option>
								</select>
							</div>

							<div class="mb-3">
								<label for="titulo">Data limite para exclusão</label>
								<div class="form-row">
									<div class="col-md-8 mb-8">
										<input type="number" class="form-control" id="cron_pautas_data_delete_number"
											placeholder="Data para exclusão" name="cron_pautas_data_delete_number"
											required min="1"
											value="<?= (isset($dados['cron_pautas_data_delete'])) ? (explode(' ', $dados['cron_pautas_data_delete'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4 mb-4">
										<select class="custom-select" id="cron_pautas_data_delete_time"
											name="cron_pautas_data_delete_time">
											<option selected value="days" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
											<option value="weeks" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
											<option value="months" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'months') ? ('selected') : (''); ?>>mes(es)</option>
											<option value="years" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
										</select>
									</div>
								</div>
							</div>

							<button class="btn btn-primary btn-block mb-3 salvar-config-cron" type="button">Salvar alterações
								do
								Cron</button>
						</form>
					</div>
					<div class="tab-pane fade" id="pautas" role="tabpanel" aria-labelledby="pautas-tab">
						<form class="col-12 mt-4" novalidate="yes" method="post" id="pautas_form">
							<div class="mb-3">
								<h4>Limites de Envio</h4>
							</div>

							<div class="mb-3">
								<label for="limite_pautas_diario">Limites DIÁRIOS de Pautas</label>
								<div class="input-group">
									<input type="number" class="form-control" id="limite_pautas_diario"
										placeholder="Data para exclusão" name="limite_pautas_diario" required min="1"
										value="<?= (isset($dados['limite_pautas_diario'])) ? ($dados['limite_pautas_diario']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="limite_pautas_semanal">Limites SEMANAIS de Pautas</label>
								<div class="input-group">
									<input type="number" class="form-control" id="limite_pautas_semanal"
										placeholder="Data para exclusão" name="limite_pautas_semanal" required min="1"
										value="<?= (isset($dados['limite_pautas_semanal'])) ? ($dados['limite_pautas_semanal']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<h4>Limites de Escrita</h4>
							</div>

							<div class="mb-3">
								<label for="pauta_tamanho_minimo">Tamanho Mínimo da Pauta</label>
								<div class="input-group">
									<input type="number" class="form-control" id="pauta_tamanho_minimo"
										placeholder="Data para exclusão" name="pauta_tamanho_minimo" required min="1"
										value="<?= (isset($dados['pauta_tamanho_minimo'])) ? ($dados['pauta_tamanho_minimo']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="pauta_tamanho_maximo">Tamanho Máximo da Pauta</label>
								<div class="input-group">
									<input type="number" class="form-control" id="pauta_tamanho_maximo"
										placeholder="Data para exclusão" name="pauta_tamanho_maximo" required min="1"
										value="<?= (isset($dados['pauta_tamanho_maximo'])) ? ($dados['pauta_tamanho_maximo']) : (''); ?>">
								</div>
							</div>

							<button class="btn btn-primary btn-block mb-3 salvar-config-pautas" type="button">Salvar alterações
								das Pautas</button>
						</form>
					</div>
					<div class="tab-pane fade" id="gerais" role="tabpanel" aria-labelledby="gerais-tab">...</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".salvar-config-cron").on("click", function () {
		form = new FormData(cron_form);
		submit(form);
	});

	$(".salvar-config-pautas").on("click", function () {
		form = new FormData(pautas_form);
		submit(form);
	});

	function submit(form) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/administracao'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').modal('show'); },
			complete: function () { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				$('.mensagem').show();
				$('.mensagem').html(retorno.mensagem);
				if (retorno.status) {
					$('.mensagem').removeClass('bg-danger');
					$('.mensagem').addClass('bg-success');
				} else {
					$('.mensagem').addClass('bg-danger');
					$('.mensagem').removeClass('bg-success');
				}
			}
		});
	}
</script>


<?= $this->endSection(); ?>