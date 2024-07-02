<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<section class="py-4">
	<div class="container">
		<div class="row pb-4">
			<div class="col-12">
				<!-- Title -->
				<h1 class="mb-0 h2">Configurações do site</h1>
			</div>
		</div>
		<div class="g-4 row">

			<div class="col-lg-6">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">CRON SITE</h5>
						<form class="col-12" novalidate="yes" method="post" id="cron_form">
							<div class="mb-3">
								<label for="cron_hash">Hash do Cron</label>
								<div class="input-group">
									<input type="text" class="form-control" id="cron_hash" placeholder="Hash do Cron"
										name="cron_hash" required
										value="<?= (isset($dados['cron_hash'])) ? ($dados['cron_hash']) : (''); ?>">
								</div>
								<small class="text-muted">Ao alterar o hash, é
									necessário alterar o Cron no servidor</small>
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button" class="btn btn-sm btn-primary me-2 mb-0 salvar-config-cron">Salvar
									nova Hash</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">CRON ANCAPSUBOT</h5>
						<form class="col-12" novalidate="yes" method="post" id="cron_ancapsubot_form">
							<div class="mb-3">
								<label for="pauta_bot_hash">Hash de acesso do Ancapsubot</label>
								<div class="input-group">
									<input type="text" class="form-control" id="pauta_bot_hash"
										placeholder="Hash do Ancapsubot" name="pauta_bot_hash" required min="1"
										value="<?= (isset($dados['pauta_bot_hash'])) ? ($dados['pauta_bot_hash']) : (''); ?>">
								</div>
								<small class="text-muted">Ao alterar o hash, é necessário alterar a hash de acesso do
									Ancapsubot</small>
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-cron-ancapsubot">Salvar
									nova Hash</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card border mb-4">

					<div class="card-body">
						<h5 class="mb-3">Pautas</h5>
						<form class="col-12" novalidate="yes" method="post" id="pautas_form">
							<div class="mb-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="cron_pautas_status_delete"
										name="cron_pautas_status_delete" value="1"
										<?= (isset($dados['cron_pautas_status_delete']) && $dados['cron_pautas_status_delete'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="cron_pautas_status_delete">
										<small class="text-muted">Ativar exclusão das pautas</small></label>
								</div>
							</div>

							<h5 class="card-header-title cron_pautas_status_delete">Período para exclusão</h5>

							<div class="mb-3 cron_pautas_status_delete">
								<label for="titulo"><small>Data limite para exclusão</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_pautas_data_delete_number" placeholder="Data para exclusão"
											name="cron_pautas_data_delete_number" required min="1"
											value="<?= (isset($dados['cron_pautas_data_delete'])) ? (explode(' ', $dados['cron_pautas_data_delete'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4 mb-2">
										<select class="form-select form-select-sm" id="cron_pautas_data_delete_time"
											name="cron_pautas_data_delete_time">
											<option selected value="days" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
											<option value="weeks" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
											<option value="months" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'months') ? ('selected') : (''); ?>>mes(es)</option>
											<option value="years" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
										</select>
									</div>
								</div>
							</div>

							<hr class="cron_pautas_status_delete">
							<h5 class="card-header-title">Limites de envio</h5>

							<div class="mb-3">
								<label for="limite_pautas_diario"><small>Limites DIÁRIOS de pautas</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="limite_pautas_diario"
										placeholder="Data para exclusão" name="limite_pautas_diario" required min="1"
										value="<?= (isset($dados['limite_pautas_diario'])) ? ($dados['limite_pautas_diario']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="limite_pautas_semanal"><small>Limites SEMANAIS de pautas</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="limite_pautas_semanal"
										placeholder="Data para exclusão" name="limite_pautas_semanal" required min="1"
										value="<?= (isset($dados['limite_pautas_semanal'])) ? ($dados['limite_pautas_semanal']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="pauta_dias_antigo"><small>Idade máxima da pauta (em
										dias)</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="pauta_dias_antigo"
										placeholder="Idade Máxima da Pauta" name="pauta_dias_antigo" required min="0"
										value="<?= (isset($dados['pauta_dias_antigo'])) ? ($dados['pauta_dias_antigo']) : (''); ?>">
								</div>
							</div>

							<hr>
							<h5 class="card-header-title">Limites de escrita</h5>

							<div class="mb-3">
								<label for="pauta_tamanho_minimo"><small>Tamanho mínimo da pauta (em
										palavras)</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="pauta_tamanho_minimo"
										placeholder="Data para exclusão" name="pauta_tamanho_minimo" required min="1"
										value="<?= (isset($dados['pauta_tamanho_minimo'])) ? ($dados['pauta_tamanho_minimo']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="pauta_tamanho_maximo"><small>Tamanho máximo da pauta (em
										palavras)</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="pauta_tamanho_maximo"
										placeholder="Data para exclusão" name="pauta_tamanho_maximo" required min="1"
										value="<?= (isset($dados['pauta_tamanho_maximo'])) ? ($dados['pauta_tamanho_maximo']) : (''); ?>">
								</div>
							</div>


							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-pautas">Salvar</button>
							</div>
						</form>
					</div>
				</div>

				<div class="card border">
					<div class="card-body">
						<h5 class="mb-3">E-mails para contato</h5>
						<form class="col-12" novalidate="yes" method="post" id="gerais_form">
							<div class="mb-3">
								<label for="contato_email">Destinatário</label>
								<div class="input-group">
									<input type="text" class="form-control" id="contato_email"
										placeholder="Link do Youtube" name="contato_email" required min="1"
										value="<?= (isset($dados['contato_email'])) ? ($dados['contato_email']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="contato_email_copia">Cópia</label>
								<div class="input-group">
									<input type="text" class="form-control" id="contato_email_copia"
										placeholder="Link do Youtube" name="contato_email_copia" required min="1"
										value="<?= (isset($dados['contato_email_copia'])) ? ($dados['contato_email_copia']) : (''); ?>">
								</div>
								<small class="text-muted">Usar
									vírgulas para adicionar mais de um e-mail.</small>
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-gerais">Salvar</button>
							</div>
						</form>
					</div>
				</div>
			</div>


			<div class="col-lg-4">
				<div class="card border mb-4">

					<div class="card-body">
						<h5 class="mb-3">Artigos</h5>
						<form class="col-12" novalidate="yes" method="post" id="artigos_form">
							<div class="">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="cron_artigos_desmarcar_status"
										name="cron_artigos_desmarcar_status" value="1"
										<?= (isset($dados['cron_artigos_desmarcar_status']) && $dados['cron_artigos_desmarcar_status'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="cron_artigos_desmarcar_status">
										<small class="text-muted">Ativar desmarcação de artigos</small></label>
								</div>

								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="cron_artigos_descartar_status"
										name="cron_artigos_descartar_status" value="1"
										<?= (isset($dados['cron_artigos_descartar_status']) && $dados['cron_artigos_descartar_status'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="cron_artigos_descartar_status">
										<small class="text-muted">Ativar descarte de artigos</small></label>
								</div>
							</div>

							<hr class="cron_artigos_desmarcar_status">

							<h5 class="card-header-title cron_artigos_desmarcar_status">Tempo limite para desmarcar</h5>

							<h6 class="card-header-title cron_artigos_desmarcar_status">Artigos teóricos</h6>

							<div class="cron_artigos_desmarcar_status">
								<label
									for="cron_artigos_teoria_desmarcar_data_revisao_number"><small>Revisão</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_teoria_desmarcar_data_revisao_number"
											placeholder="Tempo para desmarcação"
											name="cron_artigos_teoria_desmarcar_data_revisao_number" required min="1"
											value="<?= (isset($dados['cron_artigos_teoria_desmarcar_data_revisao'])) ? (explode(' ', $dados['cron_artigos_teoria_desmarcar_data_revisao'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4">
										<select class="form-select form-select-sm"
											id="cron_artigos_teoria_desmarcar_data_revisao_time"
											name="cron_artigos_teoria_desmarcar_data_revisao_time">
											<option value="hours"
												<?= (isset($dados['cron_artigos_teoria_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_revisao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
											<option value="days"
												<?= (isset($dados['cron_artigos_teoria_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_revisao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="cron_artigos_desmarcar_status">
								<label
									for="cron_artigos_teoria_desmarcar_data_narracao_number"><small>Narração</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_teoria_desmarcar_data_narracao_number"
											placeholder="Tempo para desmarcação"
											name="cron_artigos_teoria_desmarcar_data_narracao_number" required min="1"
											value="<?= (isset($dados['cron_artigos_teoria_desmarcar_data_narracao'])) ? (explode(' ', $dados['cron_artigos_teoria_desmarcar_data_narracao'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4">
										<select class="form-select form-select-sm"
											id="cron_artigos_teoria_desmarcar_data_narracao_time"
											name="cron_artigos_teoria_desmarcar_data_narracao_time">
											<option value="hours"
												<?= (isset($dados['cron_artigos_teoria_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_narracao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
											<option value="days"
												<?= (isset($dados['cron_artigos_teoria_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_narracao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="cron_artigos_desmarcar_status">
								<label for="cron_artigos_teoria_desmarcar_data_producao"><small>Produção</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_teoria_desmarcar_data_producao_number"
											placeholder="Tempo para desmarcação"
											name="cron_artigos_teoria_desmarcar_data_producao_number" required min="1"
											value="<?= (isset($dados['cron_artigos_teoria_desmarcar_data_producao'])) ? (explode(' ', $dados['cron_artigos_teoria_desmarcar_data_producao'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4">
										<select class="form-select form-select-sm"
											id="cron_artigos_teoria_desmarcar_data_producao_time"
											name="cron_artigos_teoria_desmarcar_data_producao_time">
											<option value="hours"
												<?= (isset($dados['cron_artigos_teoria_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_producao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
											<option value="days"
												<?= (isset($dados['cron_artigos_teoria_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_producao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
										</select>
									</div>
								</div>
							</div>

							<h6 class="card-header-title cron_artigos_desmarcar_status">Artigos de notícia</h6>

							<div class="cron_artigos_desmarcar_status">
								<label for="cron_artigos_noticia_desmarcar_data_revisao"><small>Revisão</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_noticia_desmarcar_data_revisao_number"
											placeholder="Tempo para desmarcação"
											name="cron_artigos_noticia_desmarcar_data_revisao_number" required min="1"
											value="<?= (isset($dados['cron_artigos_noticia_desmarcar_data_revisao'])) ? (explode(' ', $dados['cron_artigos_noticia_desmarcar_data_revisao'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4">
										<select class="form-select form-select-sm"
											id="cron_artigos_noticia_desmarcar_data_revisao_time"
											name="cron_artigos_noticia_desmarcar_data_revisao_time">
											<option value="hours"
												<?= (isset($dados['cron_artigos_noticia_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_revisao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
											<option value="days"
												<?= (isset($dados['cron_artigos_noticia_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_revisao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="cron_artigos_desmarcar_status">
								<label
									for="cron_artigos_noticia_desmarcar_data_narracao_number"><small>Narração</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_noticia_desmarcar_data_narracao_number"
											placeholder="Tempo para desmarcação"
											name="cron_artigos_noticia_desmarcar_data_narracao_number" required min="1"
											value="<?= (isset($dados['cron_artigos_noticia_desmarcar_data_narracao'])) ? (explode(' ', $dados['cron_artigos_noticia_desmarcar_data_narracao'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4">
										<select class="form-select form-select-sm"
											id="cron_artigos_noticia_desmarcar_data_narracao_time"
											name="cron_artigos_noticia_desmarcar_data_narracao_time">
											<option value="hours"
												<?= (isset($dados['cron_artigos_noticia_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_narracao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
											<option value="days"
												<?= (isset($dados['cron_artigos_noticia_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_narracao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="cron_artigos_desmarcar_status">
								<label
									for="cron_artigos_noticia_desmarcar_data_producao_number"><small>Produção</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_noticia_desmarcar_data_producao_number"
											placeholder="Tempo para desmarcação"
											name="cron_artigos_noticia_desmarcar_data_producao_number" required min="1"
											value="<?= (isset($dados['cron_artigos_noticia_desmarcar_data_producao'])) ? (explode(' ', $dados['cron_artigos_noticia_desmarcar_data_producao'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4">
										<select class="form-select form-select-sm"
											id="cron_artigos_noticia_desmarcar_data_producao_time"
											name="cron_artigos_noticia_desmarcar_data_producao_time">
											<option value="hours"
												<?= (isset($dados['cron_artigos_noticia_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_producao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
											<option value="days"
												<?= (isset($dados['cron_artigos_noticia_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_producao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
										</select>
									</div>
								</div>
							</div>

							<hr class="cron_artigos_descartar_status">

							<h5 class="card-header-title cron_artigos_descartar_status">Tempo limite para descarte</h5>

							<div class="cron_artigos_descartar_status">
								<label for="titulo"><small>Artigos</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_artigos_descartar_data_number" placeholder="Data para exclusão"
											name="cron_artigos_descartar_data_number" required min="1"
											value="<?= (isset($dados['cron_artigos_descartar_data'])) ? (explode(' ', $dados['cron_artigos_descartar_data'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4 mb-4">
										<select class="form-select form-select-sm" id="cron_artigos_descartar_data_time"
											name="cron_artigos_descartar_data_time">
											<option selected value="days"
												<?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
											<option value="weeks" <?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
											<option value="months" <?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'months') ? ('selected') : (''); ?>>mes(es)</option>
											<option value="years" <?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
										</select>
									</div>
								</div>
							</div>

							<hr>
							<h5 class="card-header-title">Limites de escrita</h5>

							<div class="mb-3">
								<label for="pauta_tamanho_minimo"><small>Tamanho mínimo do artigo (em
										palavras)</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="artigo_tamanho_minimo"
										placeholder="Quantidade de artigos nos últimos vídeos"
										name="artigo_tamanho_minimo" required min="1"
										value="<?= (isset($dados['artigo_tamanho_minimo'])) ? ($dados['artigo_tamanho_minimo']) : (''); ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="pauta_tamanho_maximo"><small>Tamanho máximo do artigo (em
										palavras)</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="artigo_tamanho_maximo"
										placeholder="Quantidade de artigos nos últimos vídeos"
										name="artigo_tamanho_maximo" required min="1"
										value="<?= (isset($dados['artigo_tamanho_maximo'])) ? ($dados['artigo_tamanho_maximo']) : (''); ?>">
								</div>
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-artigos">Salvar</button>
							</div>
						</form>
					</div>
				</div>

				<div class="card border">
					<div class="card-body">
						<h5 class="mb-3">Listagens do site</h5>
						<form class="col-12" novalidate="yes" method="post" id="listagem_form">
							<div class="mb-3">
								<label for="site_quantidade_listagem"><small>Itens por página</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm"
										id="site_quantidade_listagem"
										placeholder="Quantidade de itens nas listagens do site"
										name="site_quantidade_listagem" required min="1"
										value="<?= (isset($dados['site_quantidade_listagem'])) ? ($dados['site_quantidade_listagem']) : (''); ?>">
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-listagem">Salvar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card border mb-4">

					<div class="card-body">
						<h5 class="mb-3">Notificações</h5>
						<form class="col-12" novalidate="yes" method="post" id="notificacoes_form">
							<div class="mb-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="cron_notificacoes_status_delete"
										name="cron_notificacoes_status_delete" value="1"
										<?= (isset($dados['cron_notificacoes_status_delete']) && $dados['cron_notificacoes_status_delete'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="cron_notificacoes_status_delete">
										<small class="text-muted">Ativar exclusão das notificações</small></label>
								</div>
							</div>

							<h5 class="card-header-title cron_notificacoes_status_delete">Período para exclusão</h5>

							<h6 class="card-header-title cron_notificacoes_status_delete">Excluir notificação</h6>

							<div class="mb-3 cron_notificacoes_status_delete">
								<label for="titulo"><small>Lida</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_notificacoes_data_visualizado_number"
											placeholder="Data para exclusão"
											name="cron_notificacoes_data_visualizado_number" required min="1"
											value="<?= (isset($dados['cron_notificacoes_data_visualizado'])) ? (explode(' ', $dados['cron_notificacoes_data_visualizado'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4 mb-2">
										<select class="form-select form-select-sm"
											id="cron_notificacoes_data_visualizado_time"
											name="cron_notificacoes_data_visualizado_time">
											<option selected value="days"
												<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
											<option value="weeks"
												<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
											<option value="months"
												<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'months') ? ('selected') : (''); ?>>mes(es)</option>
											<option value="years"
												<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="mb-3 cron_notificacoes_status_delete">
								<label for="titulo"><small>Não lida</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_notificacoes_data_cadastrado_number"
											placeholder="Data para exclusão"
											name="cron_notificacoes_data_cadastrado_number" required min="1"
											value="<?= (isset($dados['cron_notificacoes_data_cadastrado'])) ? (explode(' ', $dados['cron_notificacoes_data_cadastrado'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4 mb-4">
										<select class="form-select form-select-sm"
											id="cron_notificacoes_data_cadastrado_time"
											name="cron_notificacoes_data_cadastrado_time">
											<option selected value="days"
												<?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
											<option value="weeks" <?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
											<option value="months"
												<?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'months') ? ('selected') : (''); ?>>mes(es)</option>
											<option value="years" <?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
										</select>
									</div>
								</div>
							</div>

							<hr>
							<h5 class="card-header-title">Notificar sobre carteira não informada</h5>

							<div class="mb-3">
								<label for="titulo"><small>Enviar e-mail quantos dias antes do último dia do
										mês</small></label>
								<div class="row">
									<div class="col-md-8 mb-2">
										<input type="number" class="form-control form-control-sm"
											id="cron_email_carteira_data_number" placeholder="Data para exclusão"
											name="cron_email_carteira_data_number" required min="1"
											value="<?= (isset($dados['cron_email_carteira_data'])) ? (explode(' ', $dados['cron_email_carteira_data'])[0]) : (''); ?>">
									</div>
									<div class="col-md-4 mb-4">
										<select class="form-select form-select-sm" id="cron_email_carteira_data_time"
											name="cron_email_carteira_data_time">
											<option selected value="days" <?= (isset($dados['cron_email_carteira_data']) && explode(' ', $dados['cron_email_carteira_data'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
											<option value="weeks" <?= (isset($dados['cron_email_carteira_data']) && explode(' ', $dados['cron_email_carteira_data'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
										</select>
									</div>
								</div>
							</div>

							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-notificacoes">Salvar</button>
							</div>
						</form>
					</div>
				</div>

				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Configurações da home</h5>
						<form class="col-12" novalidate="yes" method="post" id="home_form">

							<div class="mb-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="home_banner_mostrar"
										name="home_banner_mostrar" value="1" <?= (isset($dados['home_banner_mostrar']) && $dados['home_banner_mostrar'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="home_banner_mostrar">
										<small class="text-muted">Ativar banners</small></label>
								</div>
							</div>

							<div class="mb-3 home_banner_mostrar">
								<label for="limite_pautas_diario"><small>Artigos por banner</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="home_banner"
										placeholder="Quantidade de artigos por banner" name="home_banner" required
										min="1"
										value="<?= (isset($dados['home_banner'])) ? ($dados['home_banner']) : (''); ?>">
								</div>
							</div>
							<hr>

							<div class="mb-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="home_newsletter_mostrar"
										name="home_newsletter_mostrar" value="1"
										<?= (isset($dados['home_newsletter_mostrar']) && $dados['home_newsletter_mostrar'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="home_newsletter_mostrar">
										<small class="text-muted">Ativar newsletter</small></label>
								</div>
							</div>

							<hr>

							<div class="mb-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="home_talvez_goste_mostrar"
										name="home_talvez_goste_mostrar" value="1"
										<?= (isset($dados['home_talvez_goste_mostrar']) && $dados['home_talvez_goste_mostrar'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="home_talvez_goste_mostrar">
										<small class="text-muted">Ativar talvez goste</small></label>
								</div>
							</div>

							<div class="mb-3 home_talvez_goste_mostrar">
								<label for="limite_pautas_diario"><small>Artigos no Talvez Goste</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="home_talvez_goste"
										placeholder="Quantidade de artigos no Talvez Goste" name="home_talvez_goste"
										required min="1"
										value="<?= (isset($dados['home_talvez_goste'])) ? ($dados['home_talvez_goste']) : (''); ?>">
								</div>
							</div>

							<hr>

							<div class="mb-3">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="home_ultimos_videos_mostrar"
										name="home_ultimos_videos_mostrar" value="1"
										<?= (isset($dados['home_ultimos_videos_mostrar']) && $dados['home_ultimos_videos_mostrar'] == '1') ? ('checked') : (''); ?>>
									<label class="form-check-label" for="home_ultimos_videos_mostrar">
										<small class="text-muted">Ativar últimos vídeos</small></label>
								</div>
							</div>

							<div class="mb-3 home_ultimos_videos_mostrar">
								<label for="home_ultimos_videos home_ultimos_videos_mostrar"><small>Quantidade de artigos nos últimos
										vídeos</small></label>
								<div class="input-group">
									<input type="number" class="form-control form-control-sm" id="home_ultimos_videos"
										placeholder="Quantidade de artigos nos últimos vídeos"
										name="home_ultimos_videos" required min="2" step="2"
										value="<?= (isset($dados['home_ultimos_videos'])) ? ($dados['home_ultimos_videos']) : (''); ?>">
								</div>
							</div>


							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-config-home">Salvar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(".salvar-config-cron").on("click", function () {
		form = new FormData(cron_form);
		submit(form);
	});

	$(".salvar-config-cron-ancapsubot").on("click", function () {
		form = new FormData(cron_ancapsubot_form);
		submit(form);
	});

	$(".salvar-config-pautas").on("click", function () {
		form = new FormData(pautas_form);
		if (!$('#cron_pautas_status_delete').is(':checked')) {
			form.append('cron_pautas_status_delete', '0');
		}
		submit(form);
	});

	$(".salvar-config-artigos").on("click", function () {
		form = new FormData(artigos_form);
		if (!$('#cron_artigos_desmarcar_status').is(':checked')) {
			form.append('cron_artigos_desmarcar_status', '0');
		}
		if (!$('#cron_artigos_descartar_status').is(':checked')) {
			form.append('cron_artigos_descartar_status', '0');
		}
		submit(form);
	});

	$(".salvar-config-notificacoes").on("click", function () {
		form = new FormData(notificacoes_form);
		if (!$('#cron_notificacoes_status_delete').is(':checked')) {
			form.append('cron_notificacoes_status_delete', '0');
		}
		submit(form);
	});

	$(".salvar-config-gerais").on("click", function () {
		form = new FormData(gerais_form);
		submit(form);
	});

	$(".salvar-config-listagem").on("click", function () {
		form = new FormData(listagem_form);
		submit(form);
	});

	$(".salvar-config-home").on("click", function () {
		form = new FormData(home_form);
		if (!$('#home_banner_mostrar').is(':checked')) {
			form.append('home_banner_mostrar', '0');
		}
		if (!$('#home_newsletter_mostrar').is(':checked')) {
			form.append('home_newsletter_mostrar', '0');
		}
		if (!$('#home_talvez_goste_mostrar').is(':checked')) {
			form.append('home_talvez_goste_mostrar', '0');
		}
		if (!$('#home_ultimos_videos_mostrar').is(':checked')) {
			form.append('home_ultimos_videos_mostrar', '0');
		}
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
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

	$('#cron_pautas_status_delete').change(function() {
		if(!this.checked) { $('.cron_pautas_status_delete').hide(); } else { $('.cron_pautas_status_delete').show(); }
	})

	$('#cron_artigos_desmarcar_status').change(function() {
		if(!this.checked) { $('.cron_artigos_desmarcar_status').hide(); } else { $('.cron_artigos_desmarcar_status').show(); }
	})

	$('#cron_artigos_descartar_status').change(function() {
		if(!this.checked) { $('.cron_artigos_descartar_status').hide(); } else { $('.cron_artigos_descartar_status').show(); }
	})

	$('#cron_notificacoes_status_delete').change(function() {
		if(!this.checked) { $('.cron_notificacoes_status_delete').hide(); } else { $('.cron_notificacoes_status_delete').show(); }
	})
	
	$('#home_banner_mostrar').change(function() {
		if(!this.checked) { $('.home_banner_mostrar').hide(); } else { $('.home_banner_mostrar').show(); }
	})
	
	$('#home_talvez_goste_mostrar').change(function() {
		if(!this.checked) { $('.home_talvez_goste_mostrar').hide(); } else { $('.home_talvez_goste_mostrar').show(); }
	})
	
	$('#home_ultimos_videos_mostrar').change(function() {
		if(!this.checked) { $('.home_ultimos_videos_mostrar').hide(); } else { $('.home_ultimos_videos_mostrar').show(); }
	})

	$(document).ready(function () {
		if(!$('#cron_pautas_status_delete').is(':checked')) {
			$('.cron_pautas_status_delete').hide();
		}
		if(!$('#cron_artigos_desmarcar_status').is(':checked')) {
			$('.cron_artigos_desmarcar_status').hide();
		}
		if(!$('#cron_artigos_descartar_status').is(':checked')) {
			$('.cron_artigos_descartar_status').hide();
		}
		if(!$('#cron_notificacoes_status_delete').is(':checked')) {
			$('.cron_notificacoes_status_delete').hide();
		}
		if(!$('#home_banner_mostrar').is(':checked')) {
			$('.home_banner_mostrar').hide();
		}
		if(!$('#home_talvez_goste_mostrar').is(':checked')) {
			$('.home_talvez_goste_mostrar').hide();
		}
		if(!$('#home_ultimos_videos_mostrar').is(':checked')) {
			$('.home_ultimos_videos_mostrar').hide();
		}

		
	});
</script>


<?= $this->endSection(); ?>