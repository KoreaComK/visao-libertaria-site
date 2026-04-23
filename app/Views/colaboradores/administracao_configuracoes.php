<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<style>
	.config-page-skip:focus {
		position: fixed;
		left: 0.5rem;
		top: 0.5rem;
		z-index: 1080;
		padding: 0.5rem 1rem;
		background: var(--bs-warning);
		color: #fff;
		border-radius: 0.25rem;
		text-decoration: none;
		outline: 2px solid #fff;
		outline-offset: 2px;
	}

	.config-page-nav {
		position: sticky;
		top: 1rem;
		z-index: 1010;
		max-height: calc(100vh - 2rem);
		overflow-y: auto;
	}

	.config-page-nav .list-group-item {
		border-left: 3px solid transparent;
	}

	.config-page-nav .list-group-item.active {
		border-color: var(--bs-warning-border-subtle) !important;
		border-left-color: var(--bs-warning);
		background-color: var(--bs-warning-bg-subtle);
		color: var(--bs-emphasis-color) !important;
		font-weight: 600;
	}

	.config-page-nav .list-group-item.active:hover,
	.config-page-nav .list-group-item.active:focus-visible {
		background-color: var(--bs-warning-bg-subtle);
		color: var(--bs-emphasis-color) !important;
	}

	[data-mdb-theme=dark] .config-page-nav .list-group-item.active {
		border-color: var(--bs-warning-border-subtle) !important;
		background-color: var(--bs-tertiary-bg);
		color: var(--bs-body-color) !important;
	}

	[data-mdb-theme=dark] .config-page-nav .list-group-item.active:hover,
	[data-mdb-theme=dark] .config-page-nav .list-group-item.active:focus-visible {
		background-color: var(--bs-secondary-bg);
		color: var(--bs-body-color) !important;
	}

	.config-subsection-title {
		font-size: 0.8rem;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.02em;
		color: var(--bs-secondary-color);
		margin-bottom: 1rem;
		padding-bottom: 0.35rem;
		border-bottom: 1px solid var(--bs-border-color);
	}

	#accordionConfiguracoes .accordion-button:not(.collapsed) {
		color: var(--bs-emphasis-color);
		background-color: var(--bs-warning-bg-subtle);
		box-shadow: inset 0 calc(-1 * var(--bs-accordion-border-width)) 0 var(--bs-warning-border-subtle);
	}

	#accordionConfiguracoes .accordion-button:focus {
		border-color: var(--bs-warning-border-subtle);
		box-shadow: 0 0 0 .25rem rgba(var(--bs-warning-rgb), .25);
	}

	#accordionConfiguracoes .accordion-item {
		border-color: var(--bs-warning-border-subtle);
	}
</style>

<section class="py-4" aria-labelledby="config-page-title">
	<div class="container">
		<a href="#config-conteudo-principal"
			class="config-page-skip visually-hidden-focusable rounded">Pular para as configurações</a>

		<div class="row pb-4 align-items-end">
			<div class="col-12">
				<h1 id="config-page-title" class="mb-0 h2" tabindex="-1">Configurações do site</h1>
			</div>
		</div>

		<div class="row g-4">
			<div class="col-lg-3 d-none d-lg-block">
				<nav class="config-page-nav" aria-label="Seções de configuração">
					<div class="list-group list-group-flush border rounded shadow-sm">
						<a class="list-group-item list-group-item-action config-section-link py-2 px-3" href="#section-cron"
							data-bs-target="#collapseCron" role="button">Cron e integrações</a>
						<a class="list-group-item list-group-item-action config-section-link py-2 px-3" href="#section-pautas"
							data-bs-target="#collapsePautas" role="button">Pautas</a>
						<a class="list-group-item list-group-item-action config-section-link py-2 px-3" href="#section-artigos"
							data-bs-target="#collapseArtigos" role="button">Artigos</a>
						<a class="list-group-item list-group-item-action config-section-link py-2 px-3"
							href="#section-listagem" data-bs-target="#collapseListagem" role="button">Listagens e Anti IA</a>
						<a class="list-group-item list-group-item-action config-section-link py-2 px-3" href="#section-contato"
							data-bs-target="#collapseContato" role="button">Contato</a>
						<a class="list-group-item list-group-item-action config-section-link py-2 px-3"
							href="#section-notificacoes" data-bs-target="#collapseNotificacoes" role="button">Notificações</a>
					</div>
				</nav>
			</div>

			<div class="col-lg-9">
				<div id="config-conteudo-principal" tabindex="-1">
					<div class="accordion config-accordion shadow-sm" id="accordionConfiguracoes">

						<div class="accordion-item" id="section-cron">
							<h2 class="accordion-header">
								<button class="accordion-button" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseCron" aria-expanded="true" aria-controls="collapseCron"
									id="acc-btn-cron">
									Cron e integrações
								</button>
							</h2>
							<div id="collapseCron" class="accordion-collapse collapse show"
								data-bs-parent="#accordionConfiguracoes" role="region" aria-labelledby="acc-btn-cron">
								<div class="accordion-body">
									<div class="row g-3">
										<div class="col-md-6">
											<div class="card border h-100">
												<div class="card-body">
													<h3 class="h6 mb-3">Cron do site</h3>
													<form class="col-12" novalidate="yes" method="post" id="cron_form">
														<div class="mb-3">
															<label class="form-label" for="cron_hash">Hash do cron</label>
															<input type="text" class="form-control form-control-sm" id="cron_hash"
																name="cron_hash" required autocomplete="off"
																placeholder="Cole o hash usado nas URLs do cron"
																value="<?= (isset($dados['cron_hash'])) ? ($dados['cron_hash']) : (''); ?>">
															<small class="form-text text-muted">Ao alterar, atualize também a tarefa
																agendada no servidor.</small>
														</div>
														<div class="d-sm-flex justify-content-end">
															<button type="button"
																class="btn btn-sm btn-primary salvar-config-cron">Salvar hash</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="card border h-100">
												<div class="card-body">
													<h3 class="h6 mb-3">Cron Ancapsubot</h3>
													<form class="col-12" novalidate="yes" method="post" id="cron_ancapsubot_form">
														<div class="mb-3">
															<label class="form-label" for="pauta_bot_hash">Hash de acesso do
																bot</label>
															<input type="text" class="form-control form-control-sm" id="pauta_bot_hash"
																name="pauta_bot_hash" required autocomplete="off"
																placeholder="Hash configurado no Ancapsubot"
																value="<?= (isset($dados['pauta_bot_hash'])) ? ($dados['pauta_bot_hash']) : (''); ?>">
															<small class="form-text text-muted">Deve coincidir com a hash de acesso
																configurada no Ancapsubot.</small>
														</div>
														<div class="d-sm-flex justify-content-end">
															<button type="button"
																class="btn btn-sm btn-primary salvar-config-cron-ancapsubot">Salvar
																hash</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-item" id="section-pautas">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapsePautas" aria-expanded="false" aria-controls="collapsePautas"
									id="acc-btn-pautas">
									Pautas
								</button>
							</h2>
							<div id="collapsePautas" class="accordion-collapse collapse" data-bs-parent="#accordionConfiguracoes"
								role="region" aria-labelledby="acc-btn-pautas">
								<div class="accordion-body">
									<div class="card border">
										<div class="card-body">
											<form class="col-12" novalidate="yes" method="post" id="pautas_form">
												<div class="mb-3">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" id="cron_pautas_status_delete"
															name="cron_pautas_status_delete" value="1"
															<?= (isset($dados['cron_pautas_status_delete']) && $dados['cron_pautas_status_delete'] == '1') ? ('checked') : (''); ?>>
														<label class="form-check-label" for="cron_pautas_status_delete">Ativar exclusão
															automática de pautas antigas</label>
													</div>
													<small class="form-text text-muted d-block mt-1">Quando ativo, pautas acima da idade
														configurada podem ser removidas pelo cron.</small>
												</div>

												<p class="config-subsection-title cron_pautas_status_delete">Período para exclusão</p>

												<div class="mb-3 cron_pautas_status_delete">
													<label class="form-label" for="cron_pautas_data_delete_number">Idade máxima da
														pauta antes da exclusão</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_pautas_data_delete_number" name="cron_pautas_data_delete_number"
																required min="1" placeholder="Ex.: 30"
																value="<?= (isset($dados['cron_pautas_data_delete'])) ? (explode(' ', $dados['cron_pautas_data_delete'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm" id="cron_pautas_data_delete_time"
																name="cron_pautas_data_delete_time"
																aria-label="Unidade de tempo para exclusão de pautas">
																<option selected value="days" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
																<option value="weeks" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
																<option value="months" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'months') ? ('selected') : (''); ?>>mês(es)</option>
																<option value="years" <?= (isset($dados['cron_pautas_data_delete']) && explode(' ', $dados['cron_pautas_data_delete'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
															</select>
														</div>
													</div>
													<small class="form-text text-muted">Define quanto tempo a pauta permanece antes de
														ser elegível à exclusão.</small>
												</div>

												<hr class="cron_pautas_status_delete">
												<p class="config-subsection-title">Limites de envio</p>

												<div class="mb-3">
													<label class="form-label" for="limite_pautas_diario">Limite diário de
														pautas</label>
													<input type="number" class="form-control form-control-sm" id="limite_pautas_diario"
														name="limite_pautas_diario" required min="1" placeholder="Ex.: 5"
														value="<?= (isset($dados['limite_pautas_diario'])) ? ($dados['limite_pautas_diario']) : (''); ?>">
													<small class="form-text text-muted">Máximo de pautas que um colaborador pode enviar
														por dia.</small>
												</div>

												<div class="mb-3">
													<label class="form-label" for="limite_pautas_semanal">Limite semanal de
														pautas</label>
													<input type="number" class="form-control form-control-sm" id="limite_pautas_semanal"
														name="limite_pautas_semanal" required min="1" placeholder="Ex.: 20"
														value="<?= (isset($dados['limite_pautas_semanal'])) ? ($dados['limite_pautas_semanal']) : (''); ?>">
													<small class="form-text text-muted">Máximo de pautas por colaborador em uma
														semana.</small>
												</div>

												<div class="mb-3">
													<label class="form-label" for="pauta_dias_antigo">Idade máxima da pauta
														(dias)</label>
													<input type="number" class="form-control form-control-sm" id="pauta_dias_antigo"
														name="pauta_dias_antigo" required min="0" placeholder="Ex.: 14"
														value="<?= (isset($dados['pauta_dias_antigo'])) ? ($dados['pauta_dias_antigo']) : (''); ?>">
													<small class="form-text text-muted">Pautas mais antigas que esse valor (em dias)
														são rejeitadas ou ignoradas conforme a regra do sistema.</small>
												</div>

												<hr>
												<p class="config-subsection-title">Limites de escrita</p>

												<div class="mb-3">
													<label class="form-label" for="pauta_tamanho_minimo">Tamanho mínimo (palavras)</label>
													<input type="number" class="form-control form-control-sm" id="pauta_tamanho_minimo"
														name="pauta_tamanho_minimo" required min="1" placeholder="Ex.: 50"
														value="<?= (isset($dados['pauta_tamanho_minimo'])) ? ($dados['pauta_tamanho_minimo']) : (''); ?>">
												</div>

												<div class="mb-3">
													<label class="form-label" for="pauta_tamanho_maximo">Tamanho máximo (palavras)</label>
													<input type="number" class="form-control form-control-sm" id="pauta_tamanho_maximo"
														name="pauta_tamanho_maximo" required min="1" placeholder="Ex.: 800"
														value="<?= (isset($dados['pauta_tamanho_maximo'])) ? ($dados['pauta_tamanho_maximo']) : (''); ?>">
												</div>

												<div class="d-sm-flex justify-content-end">
													<button type="button" class="btn btn-sm btn-primary salvar-config-pautas">Salvar
														pautas</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-item" id="section-artigos">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseArtigos" aria-expanded="false" aria-controls="collapseArtigos"
									id="acc-btn-artigos">
									Artigos
								</button>
							</h2>
							<div id="collapseArtigos" class="accordion-collapse collapse"
								data-bs-parent="#accordionConfiguracoes" role="region" aria-labelledby="acc-btn-artigos">
								<div class="accordion-body">
									<div class="card border">
										<div class="card-body">
											<form class="col-12" novalidate="yes" method="post" id="artigos_form">
												<div class="mb-3">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" id="cron_artigos_desmarcar_status"
															name="cron_artigos_desmarcar_status" value="1"
															<?= (isset($dados['cron_artigos_desmarcar_status']) && $dados['cron_artigos_desmarcar_status'] == '1') ? ('checked') : (''); ?>>
														<label class="form-check-label" for="cron_artigos_desmarcar_status">Ativar
															desmarcação automática de artigos</label>
													</div>
												</div>
												<div class="mb-3">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" id="cron_artigos_descartar_status"
															name="cron_artigos_descartar_status" value="1"
															<?= (isset($dados['cron_artigos_descartar_status']) && $dados['cron_artigos_descartar_status'] == '1') ? ('checked') : (''); ?>>
														<label class="form-check-label" for="cron_artigos_descartar_status">Ativar
															descarte automático de artigos</label>
													</div>
												</div>

												<hr class="cron_artigos_desmarcar_status">
												<p class="config-subsection-title cron_artigos_desmarcar_status">Tempo limite para
													desmarcar</p>

												<p class="small text-muted cron_artigos_desmarcar_status mb-2">Artigos teóricos</p>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="cron_artigos_teoria_desmarcar_data_revisao_number">Fase
														revisão</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_teoria_desmarcar_data_revisao_number"
																name="cron_artigos_teoria_desmarcar_data_revisao_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_artigos_teoria_desmarcar_data_revisao'])) ? (explode(' ', $dados['cron_artigos_teoria_desmarcar_data_revisao'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_artigos_teoria_desmarcar_data_revisao_time"
																name="cron_artigos_teoria_desmarcar_data_revisao_time"
																aria-label="Unidade — teoria revisão">
																<option value="hours"
																	<?= (isset($dados['cron_artigos_teoria_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_revisao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days"
																	<?= (isset($dados['cron_artigos_teoria_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_revisao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
												</div>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="cron_artigos_teoria_desmarcar_data_narracao_number">Fase
														narração</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_teoria_desmarcar_data_narracao_number"
																name="cron_artigos_teoria_desmarcar_data_narracao_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_artigos_teoria_desmarcar_data_narracao'])) ? (explode(' ', $dados['cron_artigos_teoria_desmarcar_data_narracao'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_artigos_teoria_desmarcar_data_narracao_time"
																name="cron_artigos_teoria_desmarcar_data_narracao_time"
																aria-label="Unidade — teoria narração">
																<option value="hours"
																	<?= (isset($dados['cron_artigos_teoria_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_narracao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days"
																	<?= (isset($dados['cron_artigos_teoria_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_narracao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
												</div>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="cron_artigos_teoria_desmarcar_data_producao_number">Fase
														produção</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_teoria_desmarcar_data_producao_number"
																name="cron_artigos_teoria_desmarcar_data_producao_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_artigos_teoria_desmarcar_data_producao'])) ? (explode(' ', $dados['cron_artigos_teoria_desmarcar_data_producao'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_artigos_teoria_desmarcar_data_producao_time"
																name="cron_artigos_teoria_desmarcar_data_producao_time"
																aria-label="Unidade — teoria produção">
																<option value="hours"
																	<?= (isset($dados['cron_artigos_teoria_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_producao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days"
																	<?= (isset($dados['cron_artigos_teoria_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_teoria_desmarcar_data_producao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
												</div>

												<p class="small text-muted cron_artigos_desmarcar_status mb-2">Artigos de notícia</p>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="cron_artigos_noticia_desmarcar_data_revisao_number">Fase
														revisão</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_noticia_desmarcar_data_revisao_number"
																name="cron_artigos_noticia_desmarcar_data_revisao_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_artigos_noticia_desmarcar_data_revisao'])) ? (explode(' ', $dados['cron_artigos_noticia_desmarcar_data_revisao'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_artigos_noticia_desmarcar_data_revisao_time"
																name="cron_artigos_noticia_desmarcar_data_revisao_time"
																aria-label="Unidade — notícia revisão">
																<option value="hours"
																	<?= (isset($dados['cron_artigos_noticia_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_revisao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days"
																	<?= (isset($dados['cron_artigos_noticia_desmarcar_data_revisao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_revisao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
												</div>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="cron_artigos_noticia_desmarcar_data_narracao_number">Fase
														narração</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_noticia_desmarcar_data_narracao_number"
																name="cron_artigos_noticia_desmarcar_data_narracao_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_artigos_noticia_desmarcar_data_narracao'])) ? (explode(' ', $dados['cron_artigos_noticia_desmarcar_data_narracao'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_artigos_noticia_desmarcar_data_narracao_time"
																name="cron_artigos_noticia_desmarcar_data_narracao_time"
																aria-label="Unidade — notícia narração">
																<option value="hours"
																	<?= (isset($dados['cron_artigos_noticia_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_narracao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days"
																	<?= (isset($dados['cron_artigos_noticia_desmarcar_data_narracao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_narracao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
												</div>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="cron_artigos_noticia_desmarcar_data_producao_number">Fase
														produção</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_noticia_desmarcar_data_producao_number"
																name="cron_artigos_noticia_desmarcar_data_producao_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_artigos_noticia_desmarcar_data_producao'])) ? (explode(' ', $dados['cron_artigos_noticia_desmarcar_data_producao'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_artigos_noticia_desmarcar_data_producao_time"
																name="cron_artigos_noticia_desmarcar_data_producao_time"
																aria-label="Unidade — notícia produção">
																<option value="hours"
																	<?= (isset($dados['cron_artigos_noticia_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_producao'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days"
																	<?= (isset($dados['cron_artigos_noticia_desmarcar_data_producao']) && explode(' ', $dados['cron_artigos_noticia_desmarcar_data_producao'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
												</div>

												<p class="config-subsection-title cron_artigos_desmarcar_status">Tempo de bloqueio</p>

												<div class="mb-3 cron_artigos_desmarcar_status">
													<label class="form-label" for="artigo_tempo_bloqueio_number">Bloqueio após
														desmarcação</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="artigo_tempo_bloqueio_number" name="artigo_tempo_bloqueio_number" required
																min="1" placeholder="Quantidade"
																value="<?= (isset($dados['artigo_tempo_bloqueio'])) ? (explode(' ', $dados['artigo_tempo_bloqueio'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm" id="artigo_tempo_bloqueio_time"
																name="artigo_tempo_bloqueio_time" aria-label="Unidade — bloqueio">
																<option value="hours" <?= (isset($dados['artigo_tempo_bloqueio']) && explode(' ', $dados['artigo_tempo_bloqueio'])[1] == 'hours') ? ('selected') : (''); ?>>hora(s)</option>
																<option value="days" <?= (isset($dados['artigo_tempo_bloqueio']) && explode(' ', $dados['artigo_tempo_bloqueio'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
															</select>
														</div>
													</div>
													<small class="form-text text-muted">Período em que o colaborador fica impedido de
														marcar novamente após desmarcar.</small>
												</div>

												<hr class="cron_artigos_descartar_status">
												<p class="config-subsection-title cron_artigos_descartar_status">Tempo limite para
													descarte</p>

												<div class="mb-3 cron_artigos_descartar_status">
													<label class="form-label" for="cron_artigos_descartar_data_number">Prazo até o
														descarte automático</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_artigos_descartar_data_number" name="cron_artigos_descartar_data_number"
																required min="1" placeholder="Ex.: 90"
																value="<?= (isset($dados['cron_artigos_descartar_data'])) ? (explode(' ', $dados['cron_artigos_descartar_data'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm" id="cron_artigos_descartar_data_time"
																name="cron_artigos_descartar_data_time" aria-label="Unidade — descarte">
																<option selected value="days"
																	<?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
																<option value="weeks" <?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
																<option value="months" <?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'months') ? ('selected') : (''); ?>>mês(es)</option>
																<option value="years" <?= (isset($dados['cron_artigos_descartar_data']) && explode(' ', $dados['cron_artigos_descartar_data'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
															</select>
														</div>
													</div>
												</div>

												<hr>
												<p class="config-subsection-title">Limites de escrita (artigo)</p>

												<div class="mb-3">
													<label class="form-label" for="artigo_tamanho_minimo">Tamanho mínimo
														(palavras)</label>
													<input type="number" class="form-control form-control-sm" id="artigo_tamanho_minimo"
														name="artigo_tamanho_minimo" required min="1" placeholder="Ex.: 400"
														value="<?= (isset($dados['artigo_tamanho_minimo'])) ? ($dados['artigo_tamanho_minimo']) : (''); ?>">
												</div>

												<div class="mb-3">
													<label class="form-label" for="artigo_tamanho_maximo">Tamanho máximo
														(palavras)</label>
													<input type="number" class="form-control form-control-sm" id="artigo_tamanho_maximo"
														name="artigo_tamanho_maximo" required min="1" placeholder="Ex.: 5000"
														value="<?= (isset($dados['artigo_tamanho_maximo'])) ? ($dados['artigo_tamanho_maximo']) : (''); ?>">
												</div>

												<div class="d-sm-flex justify-content-end">
													<button type="button" class="btn btn-sm btn-primary salvar-config-artigos">Salvar
														artigos</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-item" id="section-listagem">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseListagem" aria-expanded="false" aria-controls="collapseListagem"
									id="acc-btn-listagem">
									Listagens e Anti IA
								</button>
							</h2>
							<div id="collapseListagem" class="accordion-collapse collapse"
								data-bs-parent="#accordionConfiguracoes" role="region" aria-labelledby="acc-btn-listagem">
								<div class="accordion-body">
									<div class="row g-3">
										<div class="col-lg-5">
											<div class="card border h-100">
												<div class="card-body">
													<h3 class="h6 mb-3">Listagens do site</h3>
													<form class="col-12" novalidate="yes" method="post" id="listagem_form">
														<div class="mb-3">
															<label class="form-label" for="site_quantidade_listagem">Itens por
																página</label>
															<input type="number" class="form-control form-control-sm"
																id="site_quantidade_listagem" name="site_quantidade_listagem" required min="1"
																placeholder="Ex.: 12"
																value="<?= (isset($dados['site_quantidade_listagem'])) ? ($dados['site_quantidade_listagem']) : (''); ?>">
															<small class="form-text text-muted">Quantidade padrão de itens nas listagens
																públicas (artigos, vídeos, etc.).</small>
														</div>
														<div class="d-sm-flex justify-content-end">
															<button type="button"
																class="btn btn-sm btn-primary salvar-config-listagem">Salvar
																listagens</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div class="col-lg-7">
											<div class="card border h-100">
												<div class="card-body">
													<h3 class="h6 mb-3">Anti IA</h3>
													<form class="col-12" novalidate="yes" method="post" id="anti_ia_form">
														<div class="mb-3">
															<label class="form-label" for="anti_ia_termos">Termos e critérios</label>
															<textarea class="form-control form-control-sm" id="anti_ia_termos"
																name="anti_ia_termos" rows="8"
																placeholder="Um termo ou critério por linha, conforme a regra do sistema"><?= (isset($dados['anti_ia_termos'])) ? ($dados['anti_ia_termos']) : (''); ?></textarea>
															<small class="form-text text-muted">Texto usado na verificação anti-IA;
																ajuste conforme a implementação do site.</small>
														</div>
														<div class="row g-3">
															<div class="col-sm-6">
																<label class="form-label" for="anti_ia_limite_minimo">Limite mínimo
																	(inteiro)</label>
																<input type="number" class="form-control form-control-sm"
																	id="anti_ia_limite_minimo" name="anti_ia_limite_minimo" required min="0"
																	step="1" placeholder="Ex.: 0"
																	value="<?= (isset($dados['anti_ia_limite_minimo'])) ? ($dados['anti_ia_limite_minimo']) : (''); ?>">
															</div>
															<div class="col-sm-6">
																<label class="form-label" for="anti_ia_limite_maximo">Limite máximo
																	(inteiro)</label>
																<input type="number" class="form-control form-control-sm"
																	id="anti_ia_limite_maximo" name="anti_ia_limite_maximo" required min="0"
																	step="1" placeholder="Ex.: 100"
																	value="<?= (isset($dados['anti_ia_limite_maximo'])) ? ($dados['anti_ia_limite_maximo']) : (''); ?>">
															</div>
														</div>
														<div class="d-sm-flex justify-content-end mt-3">
															<button type="button"
																class="btn btn-sm btn-primary salvar-config-anti-ia">Salvar Anti IA</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-item" id="section-contato">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseContato" aria-expanded="false" aria-controls="collapseContato"
									id="acc-btn-contato">
									Contato
								</button>
							</h2>
							<div id="collapseContato" class="accordion-collapse collapse" data-bs-parent="#accordionConfiguracoes"
								role="region" aria-labelledby="acc-btn-contato">
								<div class="accordion-body">
									<div class="card border">
										<div class="card-body">
											<h3 class="h6 mb-3">E-mails do formulário de contato</h3>
											<form class="col-12" novalidate="yes" method="post" id="gerais_form">
												<div class="mb-3">
													<label class="form-label" for="contato_email">Destinatário principal</label>
													<input type="email" class="form-control form-control-sm" id="contato_email"
														name="contato_email" required autocomplete="email" inputmode="email"
														placeholder="contato@exemplo.org"
														value="<?= (isset($dados['contato_email'])) ? ($dados['contato_email']) : (''); ?>">
													<small class="form-text text-muted">E-mail que recebe as mensagens enviadas pelo
														site.</small>
												</div>
												<div class="mb-3">
													<label class="form-label" for="contato_email_copia">Cópia (CC)</label>
													<input type="text" class="form-control form-control-sm" id="contato_email_copia"
														name="contato_email_copia" required autocomplete="off"
														placeholder="copia1@exemplo.org, copia2@exemplo.org"
														value="<?= (isset($dados['contato_email_copia'])) ? ($dados['contato_email_copia']) : (''); ?>">
													<small class="form-text text-muted">Separe vários endereços com vírgula.</small>
												</div>
												<div class="d-sm-flex justify-content-end">
													<button type="button" class="btn btn-sm btn-primary salvar-config-gerais">Salvar
														e-mails</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-item" id="section-notificacoes">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#collapseNotificacoes" aria-expanded="false"
									aria-controls="collapseNotificacoes" id="acc-btn-notificacoes">
									Notificações
								</button>
							</h2>
							<div id="collapseNotificacoes" class="accordion-collapse collapse"
								data-bs-parent="#accordionConfiguracoes" role="region" aria-labelledby="acc-btn-notificacoes">
								<div class="accordion-body">
									<div class="card border">
										<div class="card-body">
											<form class="col-12" novalidate="yes" method="post" id="notificacoes_form">
												<div class="mb-3">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" id="cron_notificacoes_status_delete"
															name="cron_notificacoes_status_delete" value="1"
															<?= (isset($dados['cron_notificacoes_status_delete']) && $dados['cron_notificacoes_status_delete'] == '1') ? ('checked') : (''); ?>>
														<label class="form-check-label" for="cron_notificacoes_status_delete">Ativar
															exclusão automática de notificações antigas</label>
													</div>
												</div>

												<p class="config-subsection-title cron_notificacoes_status_delete">Período para exclusão
												</p>
												<p class="small text-muted cron_notificacoes_status_delete mb-2">Notificações lidas</p>

												<div class="mb-3 cron_notificacoes_status_delete">
													<label class="form-label" for="cron_notificacoes_data_visualizado_number">Excluir após
														(leitura)</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_notificacoes_data_visualizado_number"
																name="cron_notificacoes_data_visualizado_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_notificacoes_data_visualizado'])) ? (explode(' ', $dados['cron_notificacoes_data_visualizado'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_notificacoes_data_visualizado_time"
																name="cron_notificacoes_data_visualizado_time"
																aria-label="Unidade — notificação lida">
																<option selected value="days"
																	<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
																<option value="weeks"
																	<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
																<option value="months"
																	<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'months') ? ('selected') : (''); ?>>mês(es)</option>
																<option value="years"
																	<?= (isset($dados['cron_notificacoes_data_visualizado']) && explode(' ', $dados['cron_notificacoes_data_visualizado'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
															</select>
														</div>
													</div>
												</div>

												<p class="small text-muted cron_notificacoes_status_delete mb-2">Notificações não lidas
												</p>

												<div class="mb-3 cron_notificacoes_status_delete">
													<label class="form-label" for="cron_notificacoes_data_cadastrado_number">Excluir após
														(cadastro)</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_notificacoes_data_cadastrado_number"
																name="cron_notificacoes_data_cadastrado_number" required min="1"
																placeholder="Quantidade"
																value="<?= (isset($dados['cron_notificacoes_data_cadastrado'])) ? (explode(' ', $dados['cron_notificacoes_data_cadastrado'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm"
																id="cron_notificacoes_data_cadastrado_time"
																name="cron_notificacoes_data_cadastrado_time"
																aria-label="Unidade — notificação não lida">
																<option selected value="days"
																	<?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
																<option value="weeks" <?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
																<option value="months"
																	<?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'months') ? ('selected') : (''); ?>>mês(es)</option>
																<option value="years" <?= (isset($dados['cron_notificacoes_data_cadastrado']) && explode(' ', $dados['cron_notificacoes_data_cadastrado'])[1] == 'years') ? ('selected') : (''); ?>>ano(s)</option>
															</select>
														</div>
													</div>
												</div>

												<hr>
												<p class="config-subsection-title">Carteira não informada</p>

												<div class="mb-3">
													<label class="form-label" for="cron_email_carteira_data_number">Antecedência do
														e-mail de lembrete</label>
													<div class="row g-2">
														<div class="col-md-8">
															<input type="number" class="form-control form-control-sm"
																id="cron_email_carteira_data_number" name="cron_email_carteira_data_number"
																required min="1" placeholder="Ex.: 3"
																value="<?= (isset($dados['cron_email_carteira_data'])) ? (explode(' ', $dados['cron_email_carteira_data'])[0]) : (''); ?>">
														</div>
														<div class="col-md-4">
															<select class="form-select form-select-sm" id="cron_email_carteira_data_time"
																name="cron_email_carteira_data_time" aria-label="Unidade — carteira">
																<option selected value="days" <?= (isset($dados['cron_email_carteira_data']) && explode(' ', $dados['cron_email_carteira_data'])[1] == 'days') ? ('selected') : (''); ?>>dia(s)</option>
																<option value="weeks" <?= (isset($dados['cron_email_carteira_data']) && explode(' ', $dados['cron_email_carteira_data'])[1] == 'weeks') ? ('selected') : (''); ?>>semana(s)</option>
															</select>
														</div>
													</div>
													<small class="form-text text-muted">Quanto tempo antes do fim do mês enviar o aviso
														para quem não informou carteira.</small>
												</div>

												<div class="d-sm-flex justify-content-end">
													<button type="button"
														class="btn btn-sm btn-primary salvar-config-notificacoes">Salvar
														notificações</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	(function () {
		var acc = document.getElementById('accordionConfiguracoes');
		function setActiveNavForSection(sectionId) {
			document.querySelectorAll('.config-section-link').forEach(function (l) {
				l.classList.toggle('active', l.getAttribute('href') === '#' + sectionId);
			});
		}
		if (acc) {
			acc.addEventListener('shown.bs.collapse', function (e) {
				var item = e.target.closest('.accordion-item');
				if (item && item.id) {
					setActiveNavForSection(item.id);
				}
			});
		}

		document.querySelectorAll('.config-section-link').forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				var targetSel = link.getAttribute('data-bs-target');
				if (!targetSel || typeof bootstrap === 'undefined') return;
				var collapseEl = document.querySelector(targetSel);
				if (!collapseEl) return;
				bootstrap.Collapse.getOrCreateInstance(collapseEl, { parent: acc, toggle: false }).show();
				var section = document.querySelector(link.getAttribute('href'));
				if (section) {
					section.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
				document.querySelectorAll('.config-section-link').forEach(function (l) { l.classList.remove('active'); });
				link.classList.add('active');
			});
		});

		var firstNav = document.querySelector('.config-section-link[href="#section-cron"]');
		if (firstNav) firstNav.classList.add('active');
	})();

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

	$(".salvar-config-anti-ia").on("click", function () {
		form = new FormData(anti_ia_form);
		submit(form);
	});

	function submit(form) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/configuracoes'); ?>",
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

	$('#cron_pautas_status_delete').change(function () {
		if (!this.checked) { $('.cron_pautas_status_delete').hide(); } else { $('.cron_pautas_status_delete').show(); }
	})

	$('#cron_artigos_desmarcar_status').change(function () {
		if (!this.checked) { $('.cron_artigos_desmarcar_status').hide(); } else { $('.cron_artigos_desmarcar_status').show(); }
	})

	$('#cron_artigos_descartar_status').change(function () {
		if (!this.checked) { $('.cron_artigos_descartar_status').hide(); } else { $('.cron_artigos_descartar_status').show(); }
	})

	$('#cron_notificacoes_status_delete').change(function () {
		if (!this.checked) { $('.cron_notificacoes_status_delete').hide(); } else { $('.cron_notificacoes_status_delete').show(); }
	})

	$(document).ready(function () {
		if (!$('#cron_pautas_status_delete').is(':checked')) {
			$('.cron_pautas_status_delete').hide();
		}
		if (!$('#cron_artigos_desmarcar_status').is(':checked')) {
			$('.cron_artigos_desmarcar_status').hide();
		}
		if (!$('#cron_artigos_descartar_status').is(':checked')) {
			$('.cron_artigos_descartar_status').hide();
		}
		if (!$('#cron_notificacoes_status_delete').is(':checked')) {
			$('.cron_notificacoes_status_delete').hide();
		}
	});
</script>


<?= $this->endSection(); ?>
