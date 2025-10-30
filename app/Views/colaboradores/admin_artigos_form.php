<?php use CodeIgniter\I18n\Time; ?>
<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
	/* Set default font-family */
	#editor {
		font-family: 'roboto';
		font-size: 16px;
		height: 250px;
	}
</style>

<div class="container w-auto">
	<div class="row py-4">
		<div class="col-12">
			<h1 class="mb-0 h2">Atualização de artigos</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="accordion accordion-flush" id="accordionFlushExample">
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
							<div class="card border">
								<!-- Card body -->
								<div class="card-body">
									<!-- Form START -->
									<form class="w-100" novalidate="yes" method="post" id="artigo_form"
										enctype='multipart/form-data'>
										<input type="hidden" name="admin" id="admin" value="true" />
										<!-- Main form -->
										<div class="row">
											<div class="col-12">
												<div class="mb-3">
													<label class="form-label" for="tipo_artigo">Tipo de
														artigo</label>
													<div class="input-group">
														<select class="form-control" name="tipo_artigo"
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
													<div class="mb-3">
														<label class="form-label">
															<input type="text" class="form-control d-none" id="link"
																name="link"
																value="<?= (isset($pauta['link'])) ? ($pauta['link']) : (''); ?>">
															<a class="btn btn-sm btn-primary" href="<?= $pauta['link']; ?>"
																target="_blank">Acessar a
																notícia</a>
															<?= $pauta['titulo']; ?>
														</label>
														<small class="d-block text-danger aviso-pauta"></small>
													</div>
												<?php else: ?>
													<div class="mb-3">
														<label class="form-label" for="link">Link da Notícia</label>
														<div class="input-group">
															<div class="input-group-text"><i class="fas fa-link"></i>
															</div>
															<input type="text" class="form-control" id="link"
																placeholder="Link da notícia para pauta" name="link"
																value="<?= (isset($artigo['link'])) ? ($artigo['link']) : (''); ?>">
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
												<div class="mb-3">
													<label class="form-label" for="titulo">Título</label>
													<input type="text" class="form-control" id="titulo" name="titulo"
														placeholder="Título do artigo" maxlength="100"
														value="<?= str_replace('"', "'", $artigo['titulo']); ?>">
													<small>O título deve ser chamativo. Deixe as palavras-chave em
														maiúsculo</small>
												</div>
											</div>
											<!-- Short description -->
											<div class="col-12">
												<div class="mb-3" for="gancho">
													<label class="form-label">Gancho </label>
													<textarea class="form-control" rows="3"
														placeholder="Texto curto antes da vinheta" id="gancho"
														maxlength="255"
														name="gancho"><?= str_replace('"', "'", $artigo['gancho']); ?></textarea>
													<small>Uma frase chamativa que cative o telespectador. Máximo
														255
														caracteres.</small>
												</div>
											</div>

											<!-- Main toolbar -->
											<div class="col-md-12">
												<!-- Subject -->
												<div class="mb-3">
													<label class="form-label" for="texto">Corpo do artigo</label>
													<div class="rounded-3" id="editor">
													</div>
													<textarea id="texto" name="texto"
														class="d-none"><?= $artigo['texto']; ?></textarea>
													<div class="col-md-12 d-flex justify-content-between">
														<small class="ps-1">
															<span class="">Artigo deve ter entre
																<?= $config['artigo_tamanho_minimo']; ?> e
																<?= $config['artigo_tamanho_maximo']; ?>
																palavras.</span>
														</small>
														<small class="pe-1"> <span
																class="pull-right label label-default"
																id="count_message"></span></small>
													</div>
												</div>
											</div>

											<!-- <?php if (!$cadastro): ?>
												<div class="col-12 mt-4">
													<div class="mb-3">
														<div class="row align-items-center mb-2">
															<div class="col-4 col-md-2">
																<div class="position-relative">
																	<img class="img-fluid" id="preview"
																		src="<?= $artigo['imagem']; ?>" />
																</div>
															</div>
															<div class="col-sm-8 col-md-10 position-relative">
																<h6 class="my-2">Imagem de capa</h6>
																<label class="w-100" style="cursor:pointer;">
																	<span>
																		<input class="form-control stretched-link"
																			type="file" name="imagem" id="imagem"
																			accept="image/gif, image/jpeg, image/png">
																	</span>
																</label>
																<p class="small mb-0 mt-2"><b>Aviso:</b> Apenas JPG,
																	JPEG e
																	PNG. Sugerimos
																	tamanhos de
																	1.280 x 720. Tamanhos diferentes da proporção 16:9
																	serão
																	cortadas.</p>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?> -->

											<!-- Short description -->
											<div class="col-12">
												<div class="mb-3" for="referencias">
													<label class="form-label">Referências </label>
													<textarea class="form-control" rows="3"
														placeholder="Referências para embasar seu texto"
														id="referencias"
														name="referencias"><?= str_replace('"', "'", $artigo['referencias']); ?></textarea>
													<small>Todos os links utilizados para dar embasamento para
														escrever
														o artigo, menos
														a pauta.</small>
												</div>
											</div>

											<!--
						<div class="col-lg-7">
							
							<div class="mb-3">
								<label class="form-label">Tags</label>
								<textarea class="form-control" rows="1" placeholder="business, sports ..."
									data-np-intersection-state="visible"></textarea>
								<small>Maximum of 14 keywords. Keywords should all be in lowercase and separated
									by commas. e.g. javascript, react, marketing.</small>
							</div>
						</div>
						<div class="col-lg-5">
							
							<div class="mb-3">
								<label class="form-label">Category</label>
								<select class="form-select" aria-label="Default select example"
									data-np-intersection-state="visible">
									<option selected="">Lifestyle</option>
									<option value="1">Technology</option>
									<option value="2">Travel</option>
									<option value="3">Business</option>
									<option value="4">Sports</option>
									<option value="5">Marketing</option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" value="" id="postCheck">
								<label class="form-check-label" for="postCheck">
									Make this post featured?
								</label>
							</div>
						</div> -->

											<div class="d-flex justify-content-center">
												<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_artigo"
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
							<div class="col-12">
								<!-- Chart START -->
								<div class="card border">
									<!-- Card body -->
									<div class="card-body">
										<!-- Form START -->
										<form class="w-100" novalidate="yes" method="post" id="artigo_audio"
											enctype='multipart/form-data'>
											<input type="hidden" name="admin" id="admin" value="true" />
											<div class="mb-3">
												<label for="audio">Arquivo de áudio</label>
												<div class="custom-file">
													<input type="file" class="form-control" id="audio" name="audio"
														required aria-describedby="audio" accept=".mp3">
													<small class="">O arquivo precisa ser do formato .mp3</small>
												</div>
											</div>
											<div
												class="d-block mb-2 text-left <?= ($artigo['arquivo_audio'] == NULL) ? ('d-none') : (''); ?> player">
												<audio controls class="w-100 rounded-3 bg-primary audioplayer">
													<source
														src="<?= ($artigo['arquivo_audio'] != NULL) ? ($artigo['arquivo_audio']) : (''); ?>"
														type="audio/mp3" class="source-player">
												</audio>
											</div>
										</form>
									</div>
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
							<div class="card border">
								<!-- Card body -->
								<div class="card-body">
									<form class="w-100" novalidate="yes" method="post" id="artigo_producao">
										<input type="hidden" name="admin" id="admin" value="true" />
										<div class="mb-3">
											<label for="username">Link do Vídeo no YouTube</label>
											<div class="input-group">
												<span class="input-group-text"><i class="fas fa-link"></i></span>
												<input type="text" class="form-control" id="video_link"
													name="video_link" placeholder="Link do Vídeo no YouTube"
													value="<?= $artigo['link_produzido']; ?>" required>
											</div>
										</div>
										<div class="mb-3">
											<label for="username">Link do Shorts no YouTube</label>
											<div class="input-group">
												<span class="input-group-text"><i class="fas fa-link"></i></span>
												<input type="text" class="form-control" id="shorts_link"
													value="<?= $artigo['link_shorts']; ?>" name="shorts_link"
													placeholder="Link do Shorts no YouTube" required>
											</div>
										</div>
										<div class="d-flex justify-content-center">
											<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_producao"
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
							<div class="card border">
								<!-- Card body -->
								<div class="card-body">
									<form class="needs-validation w-100" id="artigo_publicacao" novalidate="yes"
										method="post">
										<input type="hidden" name="admin" id="admin" value="true" />
										<div class="mb-3">
											<label for="username">Tags do Vídeo no YouTube <span
													class="text-muted">(colocar
													#
													na frente
													da tag, e separá-las com espaço)</span></label>
											<div class="input-group">
												<input type="text" class="form-control" id="tags_video_youtube"
													name="tags_video_youtube" placeholder="Tags do Vídeo" required>
											</div>
										</div>

										<div class="d-flex justify-content-center mb-3">
											<button class="btn btn-primary mt-2" id="btn-tags" type="button">Gerar
												descrição do vídeo</button>
										</div>

										<div class="mb-3 div-descricao collapse">
											<div class="input-group">
												<textarea class="form-control text-descricao"
													style="height:100px;"></textarea>
											</div>
										</div>

										<div class="mb-3">
											<label for="username">Link do Vídeo no YouTube (Visão Libertária)</label>
											<div class="input-group">
												<span class="input-group-text"><i class="fas fa-link"></i></span>
												<input type="text" class="form-control" id="link_video_youtube"
													name="link_video_youtube"
													value="<?= $artigo['link_video_youtube']; ?>"
													placeholder="Link do Vídeo no Canal do Visão Libertária" required>
											</div>
										</div>
										<!-- <div class="col-12 mt-4">
											<div class="mb-3">
												<div class="row align-items-center mb-2">
													<div class="col-4 col-md-2">
														<div class="position-relative">
															<img class="img-fluid" id="preview"
																src="<?= $artigo['imagem']; ?>" />
														</div>
													</div>
													<div class="col-sm-8 col-md-10 position-relative">
														<h6 class="my-2">Imagem de capa</h6>
														<label class="w-100" style="cursor:pointer;">
															<span>
																<input class="form-control stretched-link" type="file"
																	name="imagem" id="imagem"
																	accept="image/gif, image/jpeg, image/png">
															</span>
														</label>
														<p class="small mb-0 mt-2"><b>Aviso:</b> Apenas JPG, JPEG e PNG.
															Sugerimos
															tamanhos de
															1.280 x 720. Tamanhos diferentes da proporção 16:9 serão
															cortadas.
														</p>
													</div>
												</div>
											</div>
										</div> -->
										<div class="d-flex justify-content-center">
											<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_publicacao"
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
							<div class="card border">
								<!-- Card body -->
								<div class="card-body">
									<form class="needs-validation w-100" id="artigo_informacoes_adicionais"
										novalidate="yes" method="post">
										<input type="hidden" name="admin" id="admin" value="true" />
										<div class="mb-3">
											<label class="form-label" for="revisado_colaboradores_id">Revisor:</label>
											<div class="input-group">
												<select class="form-control" name="revisado_colaboradores_id"
													id="revisado_colaboradores_id">
													<option value="" <?= ($artigo['revisado_colaboradores_id'] == NULL) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($revisores as $colaborador): ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['revisado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= $colaborador['apelido']; ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label class="form-label" for="narrado_colaboradores_id">Narrador:</label>
											<div class="input-group">
												<select class="form-control" name="narrado_colaboradores_id"
													id="narrado_colaboradores_id">
													<option value="" <?= ($artigo['narrado_colaboradores_id'] == NULL) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($narradores as $colaborador): ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['narrado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= $colaborador['apelido']; ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label class="form-label" for="produzido_colaboradores_id">Produtor:</label>
											<div class="input-group">
												<select class="form-control" name="produzido_colaboradores_id"
													id="produzido_colaboradores_id">
													<option value="" <?= ($artigo['produzido_colaboradores_id'] == NULL) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($produtores as $colaborador): ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['produzido_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= $colaborador['apelido']; ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label class="form-label"
												for="publicado_colaboradores_id">Publicado:</label>
											<div class="input-group">
												<select class="form-control" name="publicado_colaboradores_id"
													id="publicado_colaboradores_id">
													<option value="" <?= ($artigo['publicado_colaboradores_id'] == NULL) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($publicadores as $colaborador): ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['publicado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= $colaborador['apelido']; ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label class="form-label" for="marcado_colaboradores_id">Marcado:</label>
											<div class="input-group">
												<select class="form-control" name="marcado_colaboradores_id"
													id="marcado_colaboradores_id">
													<option value="" <?= ($artigo['marcado_colaboradores_id'] == NULL) ? ('selected="true"') : (''); ?>>DESMARCADO</option>
													<?php foreach ($colaboradores as $colaborador): ?>
														<option value="<?= $colaborador['id']; ?>"
															<?= ($artigo['marcado_colaboradores_id'] == $colaborador['id']) ? ('selected="true"') : (''); ?>><?= $colaborador['apelido']; ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="mb-3">
											<label class="form-label" for="descartado">Descartado:</label>
											<div class="input-group">
												<select class="form-control" name="descartado" id="descartado">
													<option value="false" <?= ($artigo['descartado'] == NULL) ? ('selected="true"') : (''); ?>>Ativo</option>
													<option value="true" <?= ($artigo['descartado'] != NULL) ? ('selected="true"') : (''); ?>>Descartado</option>
												</select>
											</div>
										</div>

										<div class="mb-3">
											<label class="form-label" for="fase_producao_id">Fase da produção:</label>
											<div class="input-group">
												<select class="form-control" name="fase_producao_id"
													id="fase_producao_id">
													<?php foreach ($fase_producao as $fp): ?>
														<option value="<?= $fp['id']; ?>"
															<?= ($artigo['fase_producao_id'] == $fp['id']) ? ('selected="true"') : (''); ?>><?= $fp['nome']; ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="d-flex justify-content-center">
											<button class="btn btn-primary btn-lg btn-block mb-3"
												id="enviar_informacoes_adicionais" type="button">Salvar informações
												adicionais</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ($historico !== NULL && !empty($historico)): ?>
				<div class="col-12 mb-3 mt-3">
					<!-- Chart START -->
					<div class="card border">
						<div class="">
							<div class="accordion" id="accordionHistorico">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoList" aria-expanded="true"
											aria-controls="historicoList">
											Histórico do artigo:
										</button>
									</h2>
									<div id="historicoList" class="accordion-collapse collapse"
										data-bs-parent="#accordionHistorico">
										<div class="accordion-body">

											<ul class="list-group fw-light lista-historico">
												<?php foreach ($historico as $h): ?>
													<li class="list-group-item p-1 border-0">
														<small>
															<?= $h['apelido']; ?>
															<?= $h['acao']; ?>
															<span class="badge badge-pill badge-secondary fw-light">
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
				</div>
			<?php endif; ?>

			<?php if ($historicoTexto !== NULL && !empty($historicoTexto)): ?>
				<div class="col-12 mb-3 mt-3">
					<!-- Chart START -->
					<div class="card border">
						<div class="">
							<div class="accordion" id="accordionHistoricoArtigo">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoArtigoList" aria-expanded="true"
											aria-controls="historicoArtigoList">
											Histórico do texto:
										</button>
									</h2>
									<div id="historicoArtigoList" class="accordion-collapse collapse"
										data-bs-parent="#accordionHistoricoArtigo">
										<div class="accordion-body">
											<ul class="list-group fw-light lista-historico-artigo">
												<?php foreach ($historicoTexto as $h): ?>
													<li class="list-group-item p-1 border-0">
														<small><a class="btn-link btn-texto-historico"
																href="javascript:void(0);" onclick="mostraHistoricoTexto(this);"
																data-bs-toggle="modal" data-bs-target="#modalVerTextoHistorico"
																id="btn-historico" data-historico-texto-id="<?= $h['id']; ?>">
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
				</div>
			<?php endif; ?>

			<?php if (isset($artigo['id']) && $artigo['id'] !== null): ?>
				<div class="col-12 mb-3 mt-3">
					<div class="card border">
						<div class="">
							<div class="accordion" id="accordionHistoricoArtigo">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#comentarios" aria-expanded="true" aria-controls="comentarios">
											Comentários do artigo:
										</button>
									</h2>
									<div id="comentarios" class="accordion-collapse collapse"
										data-bs-parent="#accordionHistoricoArtigo">
										<div class="accordion-body">
											<div class="row">
												<div class="col-12 text-center">
													<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3"
														id="btn-comentarios" type="button">Atualizar
														Comentários</button>
												</div>
												<div class="col-12 d-flex justify-content-center">

													<div class="col-12 div-comentarios">
														<div class="col-12">
															<div class="mb-3">
																<input type="hidden" id="id_comentario"
																	name="id_comentario" />
																<textarea id="comentario" name="comentario"
																	class="form-control" rows="5"
																	placeholder="Digite seu comentário aqui"></textarea>
															</div>
															<div class="mb-3 text-center">
																<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3"
																	id="enviar-comentario" type="button">Enviar
																	comentário</button>
															</div>
														</div>
														<div class="card m-3 div-list-comentarios"></div>
													</div>
												</diV>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>


<div class="modal fade" id="modalListagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Artigos em produção</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body corpo-listar">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-left" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalVerTextoHistorico" aria-hidden="true"
	id="modalVerTextoHistorico">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Histórico do artigo</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<h1 class="display-2" id="modal-artigo-titulo"></h1>
					<p class="lead" id="modal-artigo-gancho"></p>
					<div class="position-relative mb-3">
						<div class="pt-3 pb-3">
							<div>
								<p id="modal-artigo-texto"></p>
								<h4 class="mb-3">Referências:</h4>
								<p id="modal-artigo-referencias"></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between">
				<button type="button" class="me-auto btn btn-outline-danger btn-reverter">Reverter
					artigo</button>
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"
					id="modal-btn-close">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function contapalavras() {
		var texto = $("#texto").val().replaceAll('\n', " ");
		texto = texto.replace(/[0-9]/gi, "");
		var matches = texto.split(" ");
		number = matches.filter(function (word) {
			return word.length > 0;
		}).length;
		var s = "";
		if (number > 1) {
			s = 's'
		} else {
			s = '';
		}
		$('#count_message').html(number + " palavra" + s)
	}

	function verificaPautaEscrita() {
		form = new FormData(artigo_form);
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
					$('.aviso-pauta').html(retorno.mensagem);
				} else {
					$('.aviso-pauta').html('');
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
	quill.on('text-change', () => {
		$('#texto').html(quill.getText(0, quill.getLength()));
		contapalavras();
	});
	<?php if ($artigo['texto'] !== null): ?>
		quill.setContents([
			{ insert: "<?= preg_replace('/\s\s+/', '\n\n', addslashes(htmlspecialchars_decode($artigo['texto']))); ?>" },
		])
	<?php endif; ?>

	$('#count_message').html('0 palavra');
	$(document).ready(function () {
		contapalavras();
		verificaPautaEscrita();
	})

	$('#link').on('change', function (e) {
		verificaPautaEscrita();
	});

	$('#enviar_artigo').on('click', function () {
		form = new FormData(artigo_form);
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

	audio.onchange = evt => {
		const [file] = audio.files
		if (file) {
			form = new FormData(artigo_audio);
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
						var audio = $(".audioplayer")[0];
						audio.pause();
						audio.load();
						audio.play();

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		}
	}

	$('#enviar_informacoes_adicionais').on('click', function () {
		form = new FormData(artigo_informacoes_adicionais);
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
		form = new FormData(artigo_producao);
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
		form = new FormData(artigo_publicacao);
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

	function setTags() {
		form = new FormData();
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
					$('.text-descricao').html(retorno.descricao);
					$('.div-descricao').show();
					$('.text-descricao').select();
					document.execCommand('copy');
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
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.corpo-listar').html(retorno);
			}
		});
	});

	function atualizaHistorico() {
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/historicos/') . $artigo['id']; ?>",
			method: "GET",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.lista-historico').html(retorno);
			}
		});
	}

	function atualizaArtigoHistorico() {
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/artigosTextoHistoricosList/') . $artigo['id']; ?>",
			method: "GET",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.lista-historico-artigo').html(retorno);
			}
		});
	}

	imagem.onchange = evt => {
		const [file] = imagem.files
		if (file) {
			preview.src = URL.createObjectURL(file);
			form = new FormData(artigo_form);
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/salvarImagem/') . $artigo['id']; ?>",
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
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		}
	}

	$("#btn-comentarios").on("click", function () {
		getComentarios();
	});
	$('#btn-comentarios').trigger('click');

	function getComentarios() {
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
			method: "GET",
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.div-list-comentarios').html(retorno);
			}
		});
	}

	$("#enviar-comentario").on("click", function () {
		form = new FormData();
		form.append('comentario', $('#comentario').val());
		if ($('#id_comentario').val() == '') {
			form.append('metodo', 'inserir');
		} else {
			form.append('metodo', 'alterar');
			form.append('id_comentario', $('#id_comentario').val());
		}

		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
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
					getComentarios()
					$('#comentario').val('');
					$('#id_comentario').val('');
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});

	function excluirComentario(id_comentario) {
		form = new FormData();
		form.append('id_comentario', id_comentario);
		form.append('metodo', 'excluir');

		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
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
					getComentarios()
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

	function mostraHistoricoTexto(e) {
		console.log(e.dataset.historicoTextoId);
		form = new FormData();
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/artigosTextoHistorico/'); ?>" + e.dataset.historicoTextoId,
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
					$('#modal-artigo-titulo').html(retorno.parametros.titulo);
					$('#modal-artigo-gancho').html(retorno.parametros.gancho);
					$('#modal-artigo-texto').html(retorno.parametros.texto);
					$('#modal-artigo-referencias').html(retorno.parametros.referencias);
					$('.btn-reverter').attr('data-historico-texto-id', e.dataset.historicoTextoId);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

</script>


<?= $this->endSection(); ?>