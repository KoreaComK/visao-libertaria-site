<?php use CodeIgniter\I18n\Time; ?>
<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
	#editor .ql-container {
		min-height: 320px;
		font-size: 0.875rem;
	}

	#editor .ql-editor {
		min-height: 320px;
		cursor: text;
		font-size: 0.875rem;
		line-height: 1.5;
	}

	.admin-artigos-form .accordion-item .card {
		border-radius: var(--bs-border-radius-xl);
	}

	@media (min-width: 992px) {
		.admin-artigo-painel-lateral {
			position: sticky;
			top: 1rem;
		}
	}

	.admin-artigo-painel-lateral .accordion-button {
		font-size: 0.875rem;
	}

	.admin-artigo-painel-lateral .accordion-body {
		font-size: 0.875rem;
	}

	.admin-artigo-painel-lateral .div-list-comentarios .card-body,
	.admin-artigo-painel-lateral .div-list-comentarios .card-text {
		font-size: 0.875rem;
	}

	/* Painel lateral: recolhido em mobile; visível a partir de lg (Bootstrap collapse + d-lg-block) */
	.modal-artigo-corpo-historico p {
		margin-bottom: 0.75rem;
	}

	.modal-artigo-corpo-historico p:last-child {
		margin-bottom: 0;
	}
</style>

<div class="container-fluid py-3 admin-artigos-form">
	<div class="container">
	<div class="row py-2">
		<div class="col-12">
			<h1 class="mb-0 h2" id="heading-admin-artigo-form">Atualização de artigos</h1>
			<p class="text-muted small mb-0 mt-1">Edição administrativa do conteúdo, mídia, publicação e metadados</p>
		</div>
	</div>
	<?php
	$adminPainelLateralAtivo = (! empty($historico))
		|| (! empty($historicoTexto))
		|| (isset($artigo['id']) && $artigo['id'] !== null);
	?>
	<div class="row g-3 align-items-start justify-content-center">
		<div class="<?= $adminPainelLateralAtivo ? 'col-12 col-lg-8' : 'col-12'; ?>">
			<div class="accordion accordion-flush border rounded-3 shadow-sm overflow-hidden" id="accordionFlushExample">
				<div class="accordion-item">
					<h2 class="accordion-header" id="flush-headingOne">
						<button class="accordion-button" type="button" data-bs-toggle="collapse"
							data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
							Conteúdo escrito
						</button>
					</h2>
					<div id="flush-collapseOne" class="accordion-collapse collapse show"
						aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
						<div class="accordion-body">
							<div class="card border rounded-3 shadow-sm">
								<!-- Card body -->
								<div class="card-body p-3">
									<!-- Form START -->
									<form class="w-100" novalidate method="post" id="artigo_form"
										enctype='multipart/form-data'>
										<input type="hidden" name="admin" value="true" />
										<!-- Main form -->
										<div class="row g-3">
											<div class="col-12">
												<div class="mb-0">
													<label class="form-label small text-muted mb-1" for="tipo_artigo">Tipo de artigo</label>
													<div class="input-group input-group-sm">
														<select class="form-select form-select-sm" name="tipo_artigo"
															id="tipo_artigo">
															<option value="" <?= (isset($artigo) && isset($artigo['tipo_artigo']) && $artigo['tipo_artigo'] == "") ? ('selected="true"') : (''); ?>>Escolha o
																tipo
																do
																artigo
															</option>
															<option value="T" <?= (isset($artigo) && isset($artigo['tipo_artigo']) && $artigo['tipo_artigo'] == "T") ? ('selected="true"') : (''); ?>>Teórico
															</option>
															<option value="N" <?= (isset($artigo) && isset($artigo['tipo_artigo']) && $artigo['tipo_artigo'] == "N") ? ('selected="true"') : (''); ?>>Notícia
															</option>
														</select>
													</div>
												</div>
											</div>

											<div class="col-12">
												<?php if (!empty($pauta)): ?>
													<div class="mb-0">
														<label class="form-label small text-muted mb-1 d-block" for="link">Notícia da pauta</label>
														<input type="text" class="form-control d-none" id="link"
															name="link"
															value="<?= (isset($pauta['link'])) ? (esc($pauta['link'])) : (''); ?>"
															tabindex="-1" autocomplete="off">
														<p class="mb-2">
															<a class="btn btn-sm btn-primary" href="<?= esc($pauta['link']); ?>"
																target="_blank" rel="noopener noreferrer">Acessar a notícia</a>
														</p>
														<div class="small text-body"><?= esc($pauta['titulo']); ?></div>
														<small class="d-block text-danger aviso-pauta"></small>
													</div>
												<?php else: ?>
													<div class="mb-0">
														<label class="form-label small text-muted mb-1" for="link">Link da Notícia</label>
														<div class="input-group input-group-sm">
															<div class="input-group-text"><i class="fas fa-link" aria-hidden="true"></i>
															</div>
															<input type="text" class="form-control form-control-sm" id="link"
																placeholder="Link da notícia para pauta" name="link"
																value="<?= esc($artigo['link'] ?? '', 'attr'); ?>">
														</div>
														<small class="d-block text-danger aviso-pauta"></small>
													</div>
												<?php endif; ?>
											</div>

											<!-- <div class="mb-3">
								<label for="imagem">Categorias</label>
									<div class="d-flex flex-wrap m-n1">
										<?php //foreach ($categorias as $categoria) : ?>
											<div class="btn-group-toggle p-1" data-toggle="buttons">
												<label class="btn btn-secondary vl-bg-c">
													<input id="categoria_<? //$categoria['id']; ?>" value="<? //$categoria['id']; ?>" name="categorias[<? //$categoria['id']; ?>]" type="checkbox" <? //in_array($categoria['id'], $categorias_artigo) ? ('checked') : (''); ?>> <? //$categoria['nome']; ?>
												</label>
											</div>
										<?php //endforeach; ?>
									</div>
								</div> -->

											<div class="col-12">
												<!-- Post name -->
												<div class="mb-0">
													<label class="form-label small text-muted mb-1" for="titulo">Título</label>
													<input type="text" class="form-control form-control-sm" id="titulo" name="titulo"
														placeholder="Título do artigo" maxlength="100"
														value="<?= esc($artigo['titulo'] ?? '', 'attr'); ?>">
													<div class="form-text text-muted small">O título deve ser chamativo. Deixe as palavras-chave em maiúsculo.</div>
												</div>
											</div>
											<!-- Short description -->
											<div class="col-12">
												<div class="mb-0">
													<label class="form-label small text-muted mb-1" for="gancho">Gancho</label>
													<textarea class="form-control form-control-sm" rows="3"
														placeholder="Texto curto antes da vinheta" id="gancho"
														maxlength="600"
														name="gancho"><?= esc($artigo['gancho'] ?? ''); ?></textarea>
													<div class="form-text text-muted small">Um parágrafo chamativo que cative o telespectador. Máximo 600 caracteres.</div>
												</div>
											</div>

											<!-- Main toolbar -->
											<div class="col-12">
												<!-- Subject -->
												<div class="mb-0">
													<label class="form-label small text-muted mb-1" for="texto" id="label-corpo-artigo-admin">Corpo do artigo</label>
													<div class="rounded-3 border" id="editor" role="textbox" aria-multiline="true" aria-labelledby="label-corpo-artigo-admin">
													</div>
													<textarea id="texto" name="texto"
														class="d-none"><?= esc($artigo['texto'] ?? ''); ?></textarea>
													<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 pt-1">
														<span id="artigo-tamanho-help" class="form-text text-muted small mb-0">Artigo deve ter entre
															<?= (int) ($config['artigo_tamanho_minimo'] ?? 0); ?> e
															<?= (int) ($config['artigo_tamanho_maximo'] ?? 0); ?>
															palavras.</span>
														<span class="form-text text-muted small mb-0 ms-auto flex-shrink-0" id="count_message" role="status"></span>
													</div>
												</div>
											</div>

											<!-- Short description -->
											<div class="col-12">
												<div class="mb-0">
													<label class="form-label small text-muted mb-1" for="referencias">Referências</label>
													<textarea class="form-control form-control-sm" rows="3"
														placeholder="Referências para embasar seu texto"
														id="referencias"
														name="referencias"><?= esc($artigo['referencias'] ?? ''); ?></textarea>
													<div class="form-text text-muted small">Todos os links utilizados para dar embasamento ao texto, exceto a pauta.</div>
												</div>
											</div>

											<div class="d-flex justify-content-center">
												<button class="btn btn-primary btn-sm w-100 mb-0" id="enviar_artigo"
													type="button">Salvar artigo</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-item">
					<h2 class="accordion-header" id="flush-headingTwo">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
							Arquivo de áudio
						</button>
					</h2>
					<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
						data-bs-parent="#accordionFlushExample">
						<div class="accordion-body">
							<div class="card border rounded-3 shadow-sm">
								<div class="card-body p-3">
									<form class="w-100" novalidate method="post" id="artigo_audio"
										enctype='multipart/form-data'>
										<input type="hidden" name="admin" value="true" />
										<div class="mb-3">
											<label class="form-label small text-muted mb-1" for="audio">Arquivo de áudio</label>
											<input type="file" class="form-control form-control-sm" id="audio" name="audio"
												required aria-describedby="audio-formato-help" accept=".mp3,audio/mpeg">
											<small id="audio-formato-help" class="text-muted">O arquivo precisa ser do formato .mp3</small>
										</div>
										<div
											class="d-block mb-0 text-start <?= ($artigo['arquivo_audio'] == null) ? ('d-none') : (''); ?> player">
											<audio controls class="w-100 rounded-3 bg-primary audioplayer">
												<source
													src="<?= ($artigo['arquivo_audio'] != null && $artigo['arquivo_audio'] !== '') ? esc($artigo['arquivo_audio'], 'url') : ''; ?>"
													type="audio/mpeg" class="source-player">
											</audio>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-item">
					<h2 class="accordion-header" id="flush-headingThree">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#flush-collapseThree" aria-expanded="false"
							aria-controls="flush-collapseThree">
							Links de produção
						</button>
					</h2>
					<div id="flush-collapseThree" class="accordion-collapse collapse"
						aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
						<div class="accordion-body">
							<div class="card border rounded-3 shadow-sm">
								<!-- Card body -->
								<div class="card-body p-3">
									<form class="w-100" novalidate method="post" id="artigo_producao">
										<input type="hidden" name="admin" value="true" />
										<div class="mb-3">
											<label class="form-label small text-muted mb-1" for="video_link">Link do Vídeo no YouTube</label>
											<div class="input-group input-group-sm">
												<span class="input-group-text"><i class="fas fa-link" aria-hidden="true"></i></span>
												<input type="text" class="form-control form-control-sm" id="video_link"
													name="video_link" placeholder="Link do Vídeo no YouTube"
													value="<?= esc($artigo['link_produzido'] ?? '', 'attr'); ?>" required>
											</div>
										</div>
										<div class="mb-3">
											<label class="form-label small text-muted mb-1" for="shorts_link">Link do Shorts no YouTube</label>
											<div class="input-group input-group-sm">
												<span class="input-group-text"><i class="fas fa-link" aria-hidden="true"></i></span>
												<input type="text" class="form-control form-control-sm" id="shorts_link"
													value="<?= esc($artigo['link_shorts'] ?? '', 'attr'); ?>" name="shorts_link"
													placeholder="Link do Shorts no YouTube" required>
											</div>
										</div>
										<div class="d-flex justify-content-center">
											<button class="btn btn-primary btn-sm w-100 mb-0" id="enviar_producao"
												type="button">Salvar produção</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-item">
					<h2 class="accordion-header" id="flush-headingFour">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#flush-collapseFour" aria-expanded="false"
							aria-controls="flush-collapseFour">
							Links de Publicação
						</button>
					</h2>
					<div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour"
						data-bs-parent="#accordionFlushExample">
						<div class="accordion-body">
							<div class="card border rounded-3 shadow-sm">
								<!-- Card body -->
								<div class="card-body p-3">
									<form class="needs-validation w-100" id="artigo_publicacao" novalidate
										method="post">
										<input type="hidden" name="admin" value="true" />
										<div class="mb-3">
											<label class="form-label small text-muted mb-1" for="tags_video_youtube">Tags do Vídeo no YouTube</label>
											<p id="tags-video-youtube-hint" class="form-text text-muted small mb-2">Coloque <span class="text-nowrap">#</span> na frente de cada tag e separe-as com espaço.</p>
											<input type="text" class="form-control form-control-sm" id="tags_video_youtube"
												name="tags_video_youtube" placeholder="Tags do Vídeo"
												value="<?= esc($artigo['tags_video_youtube'] ?? '', 'attr'); ?>"
												aria-describedby="tags-video-youtube-hint" required>
										</div>

										<div class="d-grid mb-3">
											<button class="btn btn-primary btn-sm" id="btn-tags" type="button">Gerar
												descrição do vídeo</button>
										</div>

										<div class="mb-3 div-descricao collapse">
											<label class="visually-hidden" for="text-descricao-video-youtube">Descrição gerada do vídeo</label>
											<textarea id="text-descricao-video-youtube" class="form-control form-control-sm text-descricao"
												rows="4"></textarea>
										</div>

										<div class="mb-3">
											<label class="form-label small text-muted mb-1" for="link_video_youtube">Link do Vídeo no YouTube (Visão Libertária)</label>
											<div class="input-group input-group-sm">
												<span class="input-group-text"><i class="fas fa-link" aria-hidden="true"></i></span>
												<input type="text" class="form-control form-control-sm" id="link_video_youtube"
													name="link_video_youtube"
													value="<?= esc($artigo['link_video_youtube'] ?? '', 'attr'); ?>"
													placeholder="Link do Vídeo no Canal do Visão Libertária" required>
											</div>
										</div>
										<div class="d-flex justify-content-center">
											<button class="btn btn-primary btn-sm w-100 mb-0" id="enviar_publicacao"
												type="button">Salvar publicação</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-item">
					<h2 class="accordion-header" id="flush-headingFive">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#flush-collapseFive" aria-expanded="false"
							aria-controls="flush-collapseFive">
							Demais informações
						</button>
					</h2>
					<div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive"
						data-bs-parent="#accordionFlushExample">
						<div class="accordion-body">
							<div class="card border rounded-3 shadow-sm">
								<!-- Card body -->
								<div class="card-body p-3">
									<form class="needs-validation w-100" id="artigo_informacoes_adicionais"
										novalidate method="post">
										<input type="hidden" name="admin" value="true" />
										<div class="row g-3">
											<div class="col-12 col-md-6">
												<label class="form-label small text-muted mb-1" for="revisado_colaboradores_id">Revisor</label>
												<select class="form-select form-select-sm" name="revisado_colaboradores_id"
													id="revisado_colaboradores_id">
													<option value="" <?= ($artigo['revisado_colaboradores_id'] == null) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($revisores as $colaborador) : ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['revisado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= esc($colaborador['apelido']); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12 col-md-6">
												<label class="form-label small text-muted mb-1" for="narrado_colaboradores_id">Narrador</label>
												<select class="form-select form-select-sm" name="narrado_colaboradores_id"
													id="narrado_colaboradores_id">
													<option value="" <?= ($artigo['narrado_colaboradores_id'] == null) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($narradores as $colaborador) : ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['narrado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= esc($colaborador['apelido']); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12 col-md-6">
												<label class="form-label small text-muted mb-1" for="produzido_colaboradores_id">Produtor</label>
												<select class="form-select form-select-sm" name="produzido_colaboradores_id"
													id="produzido_colaboradores_id">
													<option value="" <?= ($artigo['produzido_colaboradores_id'] == null) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($produtores as $colaborador) : ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['produzido_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= esc($colaborador['apelido']); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12 col-md-6">
												<label class="form-label small text-muted mb-1" for="publicado_colaboradores_id">Publicado</label>
												<select class="form-select form-select-sm" name="publicado_colaboradores_id"
													id="publicado_colaboradores_id">
													<option value="" <?= ($artigo['publicado_colaboradores_id'] == null) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($publicadores as $colaborador) : ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['publicado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= esc($colaborador['apelido']); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12 col-md-6">
												<label class="form-label small text-muted mb-1" for="marcado_colaboradores_id">Marcado</label>
												<select class="form-select form-select-sm" name="marcado_colaboradores_id"
													id="marcado_colaboradores_id">
													<option value="" <?= ($artigo['marcado_colaboradores_id'] == null) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($colaboradores as $colaborador) : ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['marcado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= esc($colaborador['apelido']); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12 col-md-6">
												<label class="form-label small text-muted mb-1" for="descartado">Situação</label>
												<select class="form-select form-select-sm" name="descartado" id="descartado">
													<option value="false" <?= ($artigo['descartado'] == null) ? ('selected="true"') : (''); ?>>Ativo</option>
													<option value="true" <?= ($artigo['descartado'] != null) ? ('selected="true"') : (''); ?>>Descartado</option>
												</select>
											</div>
											<div class="col-12">
												<label class="form-label small text-muted mb-1" for="fase_producao_id">Fase da produção</label>
												<select class="form-select form-select-sm" name="fase_producao_id"
													id="fase_producao_id">
													<?php foreach ($fase_producao as $fp) : ?>
														<option value="<?= $fp['id']; ?>"
															<?= ($artigo['fase_producao_id'] == $fp['id']) ? ('selected="true"') : (''); ?>><?= esc($fp['nome']); ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12">
												<button class="btn btn-primary btn-sm w-100 mb-0"
													id="enviar_informacoes_adicionais" type="button">Salvar informações adicionais</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($adminPainelLateralAtivo) : ?>
			<div class="col-12 d-lg-none">
				<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse"
					data-bs-target="#adminPainelLateralMobile" aria-expanded="false" aria-controls="adminPainelLateralMobile">
					Histórico e comentários
				</button>
			</div>
			<aside class="col-12 col-lg-4 admin-artigo-painel-lateral" aria-label="Histórico do artigo, versões do texto e comentários">
				<div id="adminPainelLateralMobile" class="collapse d-lg-block mt-2 mt-lg-0 pt-lg-0">
				<?php if ($historico !== null && ! empty($historico)) : ?>
					<div class="mb-3">
						<div class="card border rounded-3 shadow-sm">
							<div class="accordion" id="accordionHistorico">
								<div class="accordion-item border-0">
									<h2 class="accordion-header" id="headingHistoricoArtigoAdmin">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoList" aria-expanded="false"
											aria-controls="historicoList">
											Histórico do artigo
										</button>
									</h2>
									<div id="historicoList" class="accordion-collapse collapse"
										aria-labelledby="headingHistoricoArtigoAdmin" data-bs-parent="#accordionHistorico">
										<div class="accordion-body">
											<ul class="list-group fw-light lista-historico">
												<?php foreach ($historico as $h) : ?>
													<li class="list-group-item p-1 border-0">
														<small>
															<?= esc($h['apelido']); ?>
															<?= esc($h['acao']); ?>
															<span class="badge rounded-pill text-bg-secondary fw-light">
																<?= Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
															</span>
														</small>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($historicoTexto !== null && ! empty($historicoTexto)) : ?>
					<div class="mb-3">
						<div class="card border rounded-3 shadow-sm">
							<div class="accordion" id="accordionHistoricoTextoAdmin">
								<div class="accordion-item border-0">
									<h2 class="accordion-header" id="headingHistoricoTextoAdmin">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoArtigoList" aria-expanded="false"
											aria-controls="historicoArtigoList">
											Histórico do texto
										</button>
									</h2>
									<div id="historicoArtigoList" class="accordion-collapse collapse"
										aria-labelledby="headingHistoricoTextoAdmin" data-bs-parent="#accordionHistoricoTextoAdmin">
										<div class="accordion-body">
											<ul class="list-group fw-light lista-historico-artigo">
												<?php foreach ($historicoTexto as $h) : ?>
													<li class="list-group-item p-1 border-0">
														<small><a class="btn-link btn-texto-historico"
																href="javascript:void(0);" onclick="mostraHistoricoTexto(this);"
																data-bs-toggle="modal" data-bs-target="#modalVerTextoHistorico"
																data-historico-texto-id="<?= esc((string) $h['id'], 'attr'); ?>">
																Ver texto de
																<?= Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
															</a></small>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if (isset($artigo['id']) && $artigo['id'] !== null) : ?>
					<div class="mb-0">
						<div class="card border rounded-3 shadow-sm">
							<div class="accordion" id="accordionComentariosArtigoAdmin">
								<div class="accordion-item border-0">
									<h2 class="accordion-header" id="headingComentariosArtigoAdmin">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#collapseComentariosArtigoAdmin" aria-expanded="false" aria-controls="collapseComentariosArtigoAdmin">
											Comentários
										</button>
									</h2>
									<div id="collapseComentariosArtigoAdmin" class="accordion-collapse collapse"
										aria-labelledby="headingComentariosArtigoAdmin" data-bs-parent="#accordionComentariosArtigoAdmin">
										<div class="accordion-body">
											<button class="btn btn-primary btn-sm w-100 mb-2" id="btn-comentarios" type="button">
												Atualizar comentários
											</button>
											<div class="div-comentarios">
												<div class="mb-2">
													<input type="hidden" id="id_comentario" name="id_comentario" />
													<label class="visually-hidden" for="comentario">Novo comentário</label>
													<textarea id="comentario" name="comentario"
														class="form-control form-control-sm" rows="4"
														placeholder="Digite seu comentário…"></textarea>
												</div>
												<button class="btn btn-primary btn-sm w-100 mb-2" id="enviar-comentario" type="button">
													Enviar comentário
												</button>
												<div class="card border rounded-2 shadow-sm mb-0 div-list-comentarios"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				</div>
			</aside>
		<?php endif; ?>
	</div>
	</div>
</div>


<div class="modal fade" id="modalListagem" tabindex="-1" role="dialog" aria-labelledby="modalListagemTitulo"
	aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalListagemTitulo">Artigos em produção</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body corpo-listar">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-start" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalVerTextoHistoricoTitulo" aria-hidden="true"
	id="modalVerTextoHistorico">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalVerTextoHistoricoTitulo">Histórico do artigo</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<h1 class="display-2" id="modal-artigo-titulo"></h1>
					<p class="lead" id="modal-artigo-gancho"></p>
					<div class="position-relative mb-3">
						<div class="pt-3 pb-3">
							<div>
								<div id="modal-artigo-texto" class="modal-artigo-corpo-historico"></div>
								<h4 class="mb-3">Referências:</h4>
								<div id="modal-artigo-referencias" class="modal-artigo-corpo-historico"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between">
				<button type="button" class="me-auto btn btn-outline-danger btn-reverter">Reverter
					artigo</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
					id="modal-btn-close">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function verificaPautaEscrita() {
		var form = new FormData(document.getElementById('artigo_form'));
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/verificaPautaEscrita' . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id']))); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status === false) {
					$('.aviso-pauta').text(retorno.mensagem || '');
				} else {
					$('.aviso-pauta').text('');
				}
			}
		});
	}

	const Font = Quill.import('formats/font');
	Font.whitelist = ['roboto'];
	Quill.register(Font, true);
	const options = {
		modules: {
			toolbar: null,
		},
		placeholder: 'Escreva seu texto aqui.',
		theme: 'snow'
	};
	const quill = new Quill('#editor', options);
	window.quill = quill;
	quill.on('text-change', function () {
		$('#texto').val(quill.getText(0, quill.getLength()));
	});
	<?php if ($artigo['texto'] !== null): ?>
		quill.setContents([
			{ insert: <?= json_encode(preg_replace('/\s\s+/', "\n\n", htmlspecialchars_decode($artigo['texto']))); ?> },
		]);
	<?php endif; ?>
	$('#texto').val(quill.getText(0, quill.getLength()));
</script>
<?= view('template/colaboradores_contagem_palavras_init', [
	'contagemPalavrasConfig' => [
		'endpoint'            => site_url('colaboradores/artigos/contarPalavrasTexto'),
		'textareaSelector'    => '#texto',
		'outputSelector'      => '#count_message',
		'debounceMs'          => 200,
		'bindQuillWindowName' => 'quill',
	],
]); ?>
<script type="text/javascript">
	if (typeof window.VL_CONTAGEM_PALAVRAS_INIT === 'function') {
		window.VL_CONTAGEM_PALAVRAS_INIT();
	}

	$(document).ready(function () {
		verificaPautaEscrita();
	})

	$('#link').on('change', function (e) {
		verificaPautaEscrita();
	});

	$('#enviar_artigo').on('click', function () {
		var form = new FormData(document.getElementById('artigo_form'));
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/salvar') . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id'])); ?>",
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
					atualizaHistorico();
					atualizaArtigoHistorico();
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	})

	var elAudioInput = document.getElementById('audio');
	if (elAudioInput) {
	elAudioInput.addEventListener('change', function (evt) {
		var file = evt.target.files && evt.target.files[0];
		if (file) {
			var form = new FormData(document.getElementById('artigo_audio'));
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/salvarAudio/') . $artigo['id']; ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$('.player').removeClass('d-none');
						$('.source-player').attr('src', retorno.parametros.audio);
						var elAudio = document.querySelector('.audioplayer');
						if (elAudio) {
							elAudio.pause();
							elAudio.load();
							elAudio.play();
						}

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		}
	});
	}

	$('#enviar_informacoes_adicionais').on('click', function () {
		var form = new FormData(document.getElementById('artigo_informacoes_adicionais'));
		$.ajax({
			url: "<?= site_url('colaboradores/admin/artigoInformacoesAdicionais/' . $artigo['id']); ?>",
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
	})

	$('#enviar_producao').on('click', function () {
		var form = new FormData(document.getElementById('artigo_producao'));
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/produzir/' . $artigo['id']); ?>",
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
	})

	$('#enviar_publicacao').on('click', function () {
		var form = new FormData(document.getElementById('artigo_publicacao'));
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/publicar/' . $artigo['id']); ?>",
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
	})

	$("#btn-tags").on("click", function () {
		setTags();
	});

	function copiarTextoAreaFallback(texto) {
		var ta = document.getElementById('text-descricao-video-youtube');
		if (!ta) {
			return;
		}
		ta.value = texto;
		ta.focus();
		ta.select();
		try {
			document.execCommand('copy');
		} catch (e) { /* ignorar */ }
	}

	function setTags() {
		var form = new FormData();
		form.append('tags', $('#tags_video_youtube').val());
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/geraDescricaoVideo/' . $artigo['id']); ?>",
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
					var desc = retorno.descricao || '';
					$('.text-descricao').val(desc);
					$('.div-descricao').addClass('show');
					var ta = document.getElementById('text-descricao-video-youtube');
					if (ta && navigator.clipboard && navigator.clipboard.writeText) {
						navigator.clipboard.writeText(desc).catch(function () {
							copiarTextoAreaFallback(desc);
						});
					} else {
						copiarTextoAreaFallback(desc);
					}
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

	$('.submeter-revisao').on('click', function () {
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/submeter/') . $artigo['id']; ?>",
			method: "POST",
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					$('.submeter-revisao').attr('disabled', '');

					setTimeout(function () {
						<?php if ($artigo['fase_producao_id'] == '1'): ?>
							window.location.href = "<?= site_url('colaboradores/artigos/meusArtigos/'); ?>" + retorno.parametros['artigoId'];
						<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
							window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
						<?php endif; ?>
					}, 1500);

				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});


	$('.listagem-artigos-produzindo').on('click', function () {
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/artigosProduzindo'); ?>",
			method: "GET",
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.corpo-listar').html(retorno);
			}
		});
	});

</script>
<?php if (isset($artigo['id']) && $artigo['id'] !== null): ?>
	<?= view('template/colaboradores_comentarios_init', [
		'comentariosConfig' => [
			'endpoint'              => base_url('colaboradores/artigos/comentarios/' . $artigo['id']),
			'autoLoad'              => true,
			'accordionCollapseId'   => 'collapseComentariosArtigoAdmin',
		],
	]); ?>
	<?= view('template/colaboradores_historico_artigo_init', [
		'historicoArtigoConfig' => [
			'historicosUrl'               => site_url('colaboradores/artigos/historicos/' . $artigo['id']),
			'textoHistoricosListUrl'      => site_url('colaboradores/artigos/artigosTextoHistoricosList/' . $artigo['id']),
			'textoHistoricoItemUrlPrefix' => base_url('colaboradores/artigos/artigosTextoHistorico/'),
			'delegarCliqueHistoricoTexto' => false,
			'openModalProgrammatically'   => false,
			'bindReverterEditor'          => true,
		],
	]); ?>
<?php endif; ?>


<?= $this->endSection(); ?>