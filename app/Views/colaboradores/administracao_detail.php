<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="cron-tab" data-toggle="tab" data-target="#cron" type="button" role="tab" aria-controls="cron" aria-selected="true">Cron</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="pautas-tab" data-toggle="tab" data-target="#pautas" type="button" role="tab" aria-controls="pautas" aria-selected="false">Pautas</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="gerais-tab" data-toggle="tab" data-target="#gerais" type="button" role="tab" aria-controls="gerais" aria-selected="false">Geral</button>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade d-flex justify-content-center mt-2 show active" id="cron" role="tabpanel" aria-labelledby="cron-tab">
						<form class="col-12 col-md-6" novalidate="yes" method="post" id="pautas_form">
							<div class="mb-3">
								<label for="username">Hash do Cron</label>
								<div class="input-group">
									<input type="text" class="form-control" id="cron_hash" placeholder="Hash do Cron" name="cron_hash" required value="<?= (isset($post)) ? ($post['cron_hash']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<h4>Pautas</h4>
							</div>

							<div class="mb-3">
								<label for="titulo">Habilitar exclusão das pautas</label>
								<select class="custom-select" id="status">
									<option selected value="A">Ativar</option>
									<option value="I">Inativar</option>
								</select>
							</div>

							<div class="mb-3">
								<label for="titulo">Data limite para exclusão</label>
								<div class="form-row">
									<div class="col-md-8 mb-8">
										<input type="number" class="form-control" id="cron_pautas_data_delete_number" placeholder="Data para exclusão" name="cron_pautas_data_delete_number" required value="<?= (isset($post)) ? ($post['cron_pautas_data_delete_number']) : (''); ?>">
									</div>
									<div class="col-md-4 mb-4">
										<select class="custom-select" id="cron_pautas_data_delete_time" name="cron_pautas_data_delete_time">
											<option selected value="days">dia(s)</option>
											<option value="weeks">semana(s)</option>
											<option value="months">mes(es)</option>
											<option value="years">ano(s)</option>
										</select>
									</div>
								</div>
							</div>

							<button class="btn btn-primary btn-block mb-3" type="submit">Salvar alterações do Cron</button>
						</form>
					</div>
					<div class="tab-pane fade" id="pautas" role="tabpanel" aria-labelledby="pautas-tab">...</div>
					<div class="tab-pane fade" id="gerais" role="tabpanel" aria-labelledby="gerais-tab">...</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>
