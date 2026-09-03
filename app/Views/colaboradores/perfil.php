<?php
$pct_diario = $limites['limite_pautas_diario'] > 0
	? min(100, ($limites['limite_pautas_diario_usadas'] / $limites['limite_pautas_diario']) * 100)
	: 0;
$pct_semanal = $limites['limite_pautas_semanal'] > 0
	? min(100, ($limites['limite_pautas_semanal_usadas'] / $limites['limite_pautas_semanal']) * 100)
	: 0;
$temAvatarPersonalizado = avatar_personalizado($colaboradores['avatar'] ?? null);
$avatarSrc = avatar_url($colaboradores['avatar'] ?? null);
?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>
<?php helper('duracao'); ?>

<div class="container mb-3">
	<div class="main-body">
		<div class="row">
			<div class="col-lg-4">
				<div class="card mb-3">
					<div class="card-body">
						<div class="d-flex flex-column align-items-center text-center">
							<img src="<?= esc($avatarSrc); ?>"
								id="avatar_perfil" class="rounded-circle p-1 bg-primary" width="110"
								height="110" alt="Avatar">
							<div class="mt-3">
								<h4 class="apelido_colaborador">
									<?= esc($colaboradores['apelido']); ?>
								</h4>
								<p class="text-muted font-size-sm mb-2">Colaborador desde
									<?= date_format(new DateTime($colaboradores['criado']), 'd') . ' ' . month_helper(date_format(new DateTime($colaboradores['criado']), 'F'), 3) . '. ' . date_format(new DateTime($colaboradores['criado']), 'Y'); ?>
								</p>
								<p class="text-secondary mb-0">
									<?php foreach ($atribuicoes as $atribuicao): ?>
										<label class="badge bg-<?= esc($atribuicao['cor']); ?>"><?= esc($atribuicao['nome']); ?></label>
									<?php endforeach; ?>
								</p>
							</div>
						</div>
						<hr class="my-4">
						<p class="fs-5 mb-2">Páginas públicas</p>
						<ul class="list-group list-group-flush">
							<li class="list-group-item px-0">
								<a href="<?= site_url('site/escritor/' . rawurlencode($colaboradores['apelido'])); ?>">Artigos publicados</a>
							</li>
							<li class="list-group-item px-0">
								<a href="<?= site_url('site/colaborador/' . rawurlencode($colaboradores['apelido'])); ?>">Pautas utilizadas</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-lg-8">
				<ul class="nav nav-tabs mb-3" id="perfil-tabs" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tab-recados" data-bs-toggle="tab"
							data-bs-target="#painel-recados" type="button" role="tab"
							aria-controls="painel-recados" aria-selected="true">
							Recados<?php if (($recados_nao_lidos ?? 0) > 0): ?>
								<span class="badge bg-danger ms-1" id="badge-recados-nao-lidos"><?= (int) $recados_nao_lidos; ?></span>
							<?php endif; ?>
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-contribuicoes" data-bs-toggle="tab"
							data-bs-target="#painel-contribuicoes" type="button" role="tab"
							aria-controls="painel-contribuicoes" aria-selected="false">Contribuições</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-pagamentos" data-bs-toggle="tab"
							data-bs-target="#painel-pagamentos" type="button" role="tab"
							aria-controls="painel-pagamentos" aria-selected="false">Pagamentos</button>
					</li>
					<?php if (!empty($eh_contratado)): ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-remuneracao" data-bs-toggle="tab"
							data-bs-target="#painel-remuneracao" type="button" role="tab"
							aria-controls="painel-remuneracao" aria-selected="false">Remuneração</button>
					</li>
					<?php endif; ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-perfil" data-bs-toggle="tab"
							data-bs-target="#painel-perfil" type="button" role="tab"
							aria-controls="painel-perfil" aria-selected="false">Perfil</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-seguranca" data-bs-toggle="tab"
							data-bs-target="#painel-seguranca" type="button" role="tab"
							aria-controls="painel-seguranca" aria-selected="false">Segurança</button>
					</li>
				</ul>

				<div class="tab-content" id="perfil-tabs-content">
					<div class="tab-pane fade show active" id="painel-recados" role="tabpanel"
						aria-labelledby="tab-recados" tabindex="0">
						<div class="card mb-3">
							<div class="card-body">
								<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
									<h5 class="mb-0">Recados</h5>
									<?php if (($recados_nao_lidos ?? 0) > 0): ?>
										<button type="button" class="btn btn-outline-primary btn-sm" id="btn-marcar-recados-lidos">
											Marcar todos como lidos
										</button>
									<?php endif; ?>
								</div>
								<div id="lista-recados"
									data-offset="<?= (int) ($recados_offset ?? 0); ?>"
									data-tem-mais="<?= !empty($recados_tem_mais) ? '1' : '0'; ?>">
									<?php if ($notificacoes !== false && !empty($notificacoes)): ?>
										<?= view('colaboradores/perfil_recados_itens', [
											'notificacoes' => $notificacoes,
											'colaboradores' => $colaboradores,
											'idx_inicio' => 0,
										]); ?>
									<?php else: ?>
										<p class="text-center text-muted mb-0" id="recados-vazio">Não há recados para o seu usuário.</p>
									<?php endif; ?>
								</div>
								<div id="recados-sentinel" class="py-2 text-center<?= empty($recados_tem_mais) ? ' d-none' : ''; ?>">
									<span class="small text-muted" id="recados-loading" style="display:none;">Carregando mais recados…</span>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="painel-contribuicoes" role="tabpanel"
						aria-labelledby="tab-contribuicoes" tabindex="0">
						<div class="card mb-3">
							<div class="card-body">
								<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
									<h5 class="mb-0">Limites de pautas</h5>
									<a class="btn btn-primary" href="<?= site_url('site/noticias'); ?>">Sugerir pauta</a>
								</div>
								<div class="row">
									<div class="col-md-6 mb-3 mb-md-0">
										<p class="fw-semibold mb-2">Limite diário</p>
										<?php if ($limites['limite_pautas_diario_usadas'] >= $limites['limite_pautas_diario']): ?>
											<p class="small text-muted mb-2">
												Limites serão renovados em
												<?= app_time($limites['limite_pautas_diario_permitido'])->toLocalizedString('dd MMMM yyyy'); ?>
											</p>
										<?php endif; ?>
										<div class="progress mb-2">
											<div class="progress-bar" role="progressbar"
												style="width: <?= number_format($pct_diario, 0, ',', '.'); ?>%"
												aria-valuenow="<?= (int) round($pct_diario); ?>" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
										<p class="mb-0 small">
											<?= ($limites['limite_pautas_diario_usadas'] < 10) ? '0' : ''; ?><?= $limites['limite_pautas_diario_usadas']; ?>
											envio<?= ($limites['limite_pautas_diario_usadas'] > 1) ? 's' : ''; ?>
											de
											<?= ($limites['limite_pautas_diario'] < 10) ? '0' : ''; ?><?= $limites['limite_pautas_diario']; ?>
											pautas.
										</p>
									</div>
									<div class="col-md-6">
										<p class="fw-semibold mb-2">Limite semanal</p>
										<?php if ($limites['limite_pautas_semanal_usadas'] >= $limites['limite_pautas_semanal']): ?>
											<p class="small text-muted mb-2">
												Limites serão renovados em
												<?= app_time($limites['limite_pautas_semanal_permitido'])->toLocalizedString('dd MMMM yyyy'); ?>
											</p>
										<?php endif; ?>
										<div class="progress mb-2">
											<div class="progress-bar" role="progressbar"
												style="width: <?= number_format($pct_semanal, 0, ',', '.'); ?>%"
												aria-valuenow="<?= (int) round($pct_semanal); ?>" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
										<p class="mb-0 small">
											<?= ($limites['limite_pautas_semanal_usadas'] < 10) ? '0' : ''; ?><?= $limites['limite_pautas_semanal_usadas']; ?>
											envio<?= ($limites['limite_pautas_semanal_usadas'] > 1) ? 's' : ''; ?>
											de
											<?= ($limites['limite_pautas_semanal'] < 10) ? '0' : ''; ?><?= $limites['limite_pautas_semanal']; ?>
											pautas.
										</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card mb-3">
							<div class="card-body">
								<h5 class="mb-3">Contribuições aprovadas e pendentes</h5>
								<div class="row text-center mb-3">
									<div class="col-4 border-end">
										<p class="text-muted mb-1">Colaborações (mês)</p>
										<p class="fs-3 fw-bold mb-0">
											<?= number_format($contribuicoes_mensal['colaboracoes'], 0, ',', '.'); ?>
										</p>
									</div>
									<div class="col-4 border-end">
										<p class="text-muted mb-1">Pontos (mês)</p>
										<p class="fs-3 fw-bold mb-0">
											<?= number_format($contribuicoes_mensal['pontos'], 0, ',', '.'); ?>
										</p>
									</div>
									<div class="col-4">
										<p class="text-muted mb-1">Total histórico</p>
										<p class="fs-3 fw-bold mb-0">
											<?= number_format($contribuicoes_total, 0, ',', '.'); ?>
										</p>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Título</th>
												<th scope="col">Atribuição</th>
												<th scope="col">Pontos</th>
											</tr>
										</thead>
										<tbody>
											<?php if (empty($lista_artigos_mes)): ?>
												<tr>
													<td colspan="4" class="text-center">Não há colaborações pendentes até o momento</td>
												</tr>
											<?php else: ?>
												<?php foreach ($lista_artigos_mes as $chave => $artigo): ?>
													<?php $total = 0; ?>
													<tr>
														<th scope="row"><?= $chave + 1 ?></th>
														<td>
															<a href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']); ?>">
																<?= esc($artigo['titulo']); ?>
															</a>
														</td>
														<td>
															<?php if ($artigo['escrito'] == $colaboradores['id']): ?>
																<?php $total += $artigo['pontos_escritor']; ?>
																<label class="badge bg-info m-1">Escritor</label>
															<?php endif; ?>
															<?php if ($artigo['revisado'] == $colaboradores['id']): ?>
																<?php $total += $artigo['pontos_revisor']; ?>
																<label class="badge bg-info m-1">Revisor</label>
															<?php endif; ?>
															<?php if ($artigo['narrado'] == $colaboradores['id']): ?>
																<?php $total += $artigo['pontos_narrador']; ?>
																<label class="badge bg-info m-1">Narrador</label>
															<?php endif; ?>
															<?php if ($artigo['produzido'] == $colaboradores['id']): ?>
																<?php $total += $artigo['pontos_produtor']; ?>
																<label class="badge bg-info m-1">Produtor</label>
															<?php endif; ?>
														</td>
														<td><?= number_format($total, 0, ',', '.'); ?></td>
													</tr>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="painel-pagamentos" role="tabpanel"
						aria-labelledby="tab-pagamentos" tabindex="0">
						<div class="card mb-3">
							<div class="card-body">
								<h5 class="mb-3">Pagamentos pelas contribuições</h5>
								<p class="text-muted small">Clique nos seus pontos para ver os artigos daquele lote.</p>
								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th scope="col">Data Pagamento</th>
												<th scope="col">Hash da Transação</th>
												<th scope="col">Seus pontos</th>
												<th scope="col">Sats/Pontos</th>
											</tr>
										</thead>
										<tbody>
											<?php if (empty($lista_pagamentos)): ?>
												<tr>
													<td colspan="4" class="text-center">Não há pagamentos feitos até o momento</td>
												</tr>
											<?php else: ?>
												<?php foreach ($lista_pagamentos as $pagamento): ?>
													<tr>
														<th scope="row">
															<?= app_time($pagamento['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
														</th>
														<td>
															<a href="https://mempool.space/pt/tx/<?= esc($pagamento['hash_transacao'], 'url'); ?>"
																target="_blank" rel="noopener noreferrer">
																<?= esc(substr($pagamento['hash_transacao'], 0, 5)); ?>...<?= esc(substr($pagamento['hash_transacao'], -5, 5)); ?>
															</a>
														</td>
														<td>
															<a href="#" class="listar-colaboracoes-fechadas"
																id="<?= (int) $pagamento['id']; ?>"
																data-bs-toggle="modal"
																data-bs-target="#modal-colaboracoes-fechadas"
																title="Veja suas contribuições deste pagamento">
																<?= number_format((float) $pagamento['pontos_colaborador'], 0, ',', '.'); ?>
															</a>
														</td>
														<td>
															<?php if ((float) $pagamento['pontuacao_total'] > 0): ?>
																<?= number_format(($pagamento['quantidade_bitcoin'] * 100000000) / $pagamento['pontuacao_total'], 0, ',', '.'); ?> sats
															<?php else: ?>
																—
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<?php if (!empty($eh_contratado)): ?>
					<?php
						$remuneracaoAtual = $remuneracao_atual ?? null;
						$remuneracaoHistorico = $remuneracao_historico ?? [];
						$remuneracaoCompetencia = $remuneracao_competencia ?? date('Y-m');
						$remuneracaoBloqueada = is_array($remuneracaoAtual) && !empty($remuneracaoAtual['pagamentos_id']);
						$tipoAtual = (is_array($remuneracaoAtual) && ($remuneracaoAtual['tipo'] ?? '') === 'F') ? 'F' : 'H';
						$valorExibicao = (is_array($remuneracaoAtual) && isset($remuneracaoAtual['valor_reais']))
							? number_format((float) $remuneracaoAtual['valor_reais'], 2, ',', '.')
							: '';
						$horasExibicao = (is_array($remuneracaoAtual) && isset($remuneracaoAtual['horas_trabalhadas']) && $remuneracaoAtual['horas_trabalhadas'] !== null && $remuneracaoAtual['horas_trabalhadas'] !== '')
							? decimal_para_duracao_hhmm($remuneracaoAtual['horas_trabalhadas'])
							: '';
						$partesCompetencia = explode('-', $remuneracaoCompetencia);
						$labelCompetencia = $remuneracaoCompetencia;
						if (count($partesCompetencia) === 2) {
							$tsCompetencia = mktime(0, 0, 0, (int) $partesCompetencia[1], 1, (int) $partesCompetencia[0]);
							$labelCompetencia = month_helper(date('F', $tsCompetencia)) . ' de ' . $partesCompetencia[0];
						}
						$formatarCompetencia = static function (string $comp): string {
							$partes = explode('-', $comp);
							if (count($partes) !== 2) {
								return $comp;
							}
							$ts = mktime(0, 0, 0, (int) $partes[1], 1, (int) $partes[0]);
							return month_helper(date('F', $ts)) . ' de ' . $partes[0];
						};
					?>
					<div class="tab-pane fade" id="painel-remuneracao" role="tabpanel"
						aria-labelledby="tab-remuneracao" tabindex="0">
						<div class="card mb-3">
							<div class="card-body">
								<h5 class="mb-1">Remuneração do mês</h5>
								<p class="text-muted small mb-3">Informe uma vez por mês o valor a receber referente a <?= esc($labelCompetencia); ?>.</p>
								<?php if ($remuneracaoBloqueada): ?>
									<div class="alert alert-info" role="alert">
										Este envio já foi incluído em um pagamento e não pode mais ser alterado.
									</div>
								<?php endif; ?>
								<form class="needs-validation" method="post" id="colaboradores_remuneracao"
									enctype="multipart/form-data">
									<fieldset <?= $remuneracaoBloqueada ? 'disabled' : ''; ?>>
										<div class="mb-3">
											<span class="form-label d-block">Tipo de remuneração</span>
											<div class="form-check">
												<input class="form-check-input" type="radio" name="tipo" id="remuneracao_tipo_horas"
													value="H" <?= $tipoAtual === 'H' ? 'checked' : ''; ?>>
												<label class="form-check-label" for="remuneracao_tipo_horas">Por horas</label>
											</div>
											<div class="form-check">
												<input class="form-check-input" type="radio" name="tipo" id="remuneracao_tipo_fixo"
													value="F" <?= $tipoAtual === 'F' ? 'checked' : ''; ?>>
												<label class="form-check-label" for="remuneracao_tipo_fixo">Valor fixo</label>
											</div>
										</div>

										<div class="mb-3">
											<label for="remuneracao_valor_reais" class="form-label">Valor a receber (R$)</label>
											<input type="text" class="form-control" id="remuneracao_valor_reais"
												name="valor_reais" inputmode="decimal" autocomplete="off"
												placeholder="0,00" value="<?= esc($valorExibicao); ?>" required>
										</div>

										<div id="remuneracao_campos_horas" class="<?= $tipoAtual === 'H' ? '' : 'd-none'; ?>">
											<div class="mb-3">
												<label for="remuneracao_horas" class="form-label">Horas trabalhadas</label>
												<input type="text" class="form-control" id="remuneracao_horas"
													name="horas_trabalhadas" inputmode="numeric" autocomplete="off"
													placeholder="160:30" value="<?= esc($horasExibicao); ?>">
												<div class="form-text">Formato horas:minutos, por exemplo 160:30.</div>
											</div>
											<div class="mb-3">
												<label for="remuneracao_arquivo" class="form-label">Arquivo de detalhamento</label>
												<input type="file" class="form-control" id="remuneracao_arquivo" name="arquivo"
													accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png">
												<div class="form-text">
													PDF, JPG ou PNG, até 5&nbsp;MB.
													<?php if (!empty($remuneracaoAtual['arquivo'])): ?>
														Arquivo atual:
														<a href="<?= esc(base_url('colaboradores/perfil/downloadRemuneracao/' . (int) $remuneracaoAtual['id']), 'attr'); ?>">
															<?= esc($remuneracaoAtual['arquivo_nome'] ?: 'Baixar arquivo'); ?>
														</a>
														(envie outro para substituir).
													<?php endif; ?>
												</div>
											</div>
										</div>
									</fieldset>
									<?php if (!$remuneracaoBloqueada): ?>
										<div class="text-end">
											<button class="btn btn-primary" type="submit">
												<?= $remuneracaoAtual === null ? 'Enviar remuneração do mês' : 'Atualizar remuneração do mês'; ?>
											</button>
										</div>
									<?php endif; ?>
								</form>
							</div>
						</div>

						<div class="card mb-3">
							<div class="card-body">
								<h5 class="mb-3">Meses anteriores</h5>
								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th scope="col">Mês</th>
												<th scope="col">Tipo</th>
												<th scope="col">Valor</th>
												<th scope="col">Horas</th>
												<th scope="col">Arquivo</th>
												<th scope="col">Situação</th>
											</tr>
										</thead>
										<tbody>
											<?php if (empty($remuneracaoHistorico)): ?>
												<tr>
													<td colspan="6" class="text-center">Não há envios de meses anteriores.</td>
												</tr>
											<?php else: ?>
												<?php foreach ($remuneracaoHistorico as $item): ?>
													<tr>
														<td><?= esc($formatarCompetencia((string) $item['competencia'])); ?></td>
														<td><?= ($item['tipo'] ?? '') === 'F' ? 'Valor fixo' : 'Por horas'; ?></td>
														<td>R$ <?= number_format((float) $item['valor_reais'], 2, ',', '.'); ?></td>
														<td>
															<?php if (($item['tipo'] ?? '') === 'H' && $item['horas_trabalhadas'] !== null && $item['horas_trabalhadas'] !== ''): ?>
																<?= esc(decimal_para_duracao_hhmm($item['horas_trabalhadas'])); ?>
															<?php else: ?>
																—
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($item['arquivo'])): ?>
																<a href="<?= esc(base_url('colaboradores/perfil/downloadRemuneracao/' . (int) $item['id']), 'attr'); ?>">
																	<?= esc($item['arquivo_nome'] ?: 'Baixar'); ?>
																</a>
															<?php else: ?>
																—
															<?php endif; ?>
														</td>
														<td>
															<?php if (!empty($item['pagamentos_id'])): ?>
																<span class="badge bg-success">Pago</span>
															<?php else: ?>
																<span class="badge bg-secondary">Aguardando pagamento</span>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<div class="tab-pane fade" id="painel-perfil" role="tabpanel"
						aria-labelledby="tab-perfil" tabindex="0">
						<div class="card mb-3">
							<div class="card-body">
								<h5 class="mb-3">Dados do perfil</h5>
								<form class="needs-validation" method="post" id="colaboradores_perfil"
									enctype="multipart/form-data">
									<div class="mb-3">
										<label for="apelido" class="form-label">Nome público</label>
										<input type="text" class="form-control" id="apelido" placeholder="Digite seu apelido"
											value="<?= esc($colaboradores['apelido']); ?>" name="apelido" required>
									</div>

									<div class="mb-3">
										<label for="twitter" class="form-label">Usuário no X (antigo Twitter)</label>
										<div class="input-group">
											<span class="input-group-text">@</span>
											<input type="text" class="form-control" id="twitter"
												placeholder="Digite seu @ para usar o AncapsuBot"
												value="<?= esc($colaboradores['twitter'] ?? ''); ?>" name="twitter">
										</div>
									</div>

									<div class="mb-3">
										<label for="carteira" class="form-label">Carteira Bitcoin</label>
										<input type="text" class="form-control" id="carteira" name="carteira"
											placeholder="Digite sua carteira bitcoin"
											value="<?= esc($colaboradores['carteira'] ?? ''); ?>">
										<div class="form-text">
											Endereços válidos começam com 1, 3 ou bc1.
										</div>
									</div>

									<div class="mb-3">
										<label for="avatar" class="form-label">Alterar avatar</label>
										<input type="file" class="form-control" id="avatar" name="avatar"
											onchange="onFileUpload(this);" aria-label="Avatar" accept=".png,image/png">
										<div class="form-text">
											Apenas PNG, até 1&nbsp;MB, no máximo 2048×2048 pixels.
										</div>
										<button type="button"
											class="btn btn-link btn-sm px-0 mt-1<?= $temAvatarPersonalizado ? '' : ' d-none'; ?>"
											id="btn-remover-avatar">
											Remover avatar e usar o padrão
										</button>
									</div>

									<div class="text-end">
										<button class="btn btn-primary" type="submit">Salvar dados do perfil</button>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="painel-seguranca" role="tabpanel"
						aria-labelledby="tab-seguranca" tabindex="0">
						<div class="card mb-3">
							<div class="card-body">
								<h5 class="mb-3">Alterar senha</h5>
								<form class="needs-validation" method="post" id="colaboradores_senha">
									<div class="mb-3">
										<label for="senha_antiga" class="form-label">Senha atual</label>
										<input type="password" id="senha_antiga" name="senha_antiga"
											placeholder="Digite sua senha atual" class="form-control">
									</div>
									<div class="mb-3">
										<label for="senha_nova" class="form-label">Nova senha</label>
										<input type="password" class="form-control" id="senha_nova" name="senha_nova"
											placeholder="Digite sua nova senha">
										<div class="form-text">Mínimo de 10 caracteres.</div>
									</div>
									<div class="mb-3">
										<label for="senha_nova_confirmacao" class="form-label">Repetir nova senha</label>
										<input type="password" class="form-control" id="senha_nova_confirmacao"
											name="senha_nova_confirmacao" placeholder="Digite novamente a nova senha">
									</div>
									<button class="btn btn-primary" type="submit">Trocar senha</button>
								</form>
							</div>
						</div>

						<div class="card border-danger mb-3">
							<div class="card-body">
								<h5 class="mb-2 text-danger">Zona de risco</h5>
								<p class="mb-3 text-muted">
									A exclusão só é concluída depois que você confirma pelo link enviado ao seu e-mail.
									Até lá, a conta continua ativa.
								</p>
								<div id="alerta-exclusao-enviada" class="alert alert-warning d-none mb-3" role="alert">
									Verifique seu e-mail e clique no link de confirmação para concluir a exclusão.
									Se não confirmar, nada será alterado na sua conta.
								</div>
								<button type="button" class="btn btn-outline-danger" id="btn-abrir-excluir" data-bs-toggle="modal"
									data-bs-target="#modal-excluir">Solicitar exclusão da conta</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="modal-colaboracoes-fechadas" tabindex="-1" role="dialog"
	aria-labelledby="modal-colaboracoes-fechadas-label" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-colaboracoes-fechadas-label">Colaborações já pagas</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Título</th>
							<th scope="col">Atribuição</th>
							<th scope="col">Pontos</th>
						</tr>
					</thead>
					<tbody id="tbody-modal-colaboracoes-fechadas">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-excluir" tabindex="-1" role="dialog" aria-labelledby="modal-excluir-label"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-excluir-label">Solicitar exclusão da conta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>
					Ao confirmar abaixo, <strong>apenas enviaremos um e-mail</strong> com um link de confirmação.
					Sua conta permanece ativa até você clicar nesse link.
				</p>
				<p>Depois de confirmar pelo e-mail:</p>
				<ul class="mb-3">
					<li>seus dados serão anonimizados (o e-mail é mantido no sistema);</li>
					<li>sua carteira Bitcoin será removida, então contribuições pendentes não serão pagas;</li>
					<li>você não conseguirá mais acessar a conta;</li>
					<li>essa confirmação <strong>não pode ser desfeita</strong>.</li>
				</ul>
				<p class="mb-0 text-muted small">
					Se você não clicar no link do e-mail, nenhuma alteração será feita.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger excluir">Enviar e-mail de confirmação</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function () {
		$('.listar-colaboracoes-fechadas').tooltip();
	});

	var csrfName = <?= json_encode(csrf_token()) ?>;
	var csrfHash = <?= json_encode(csrf_hash()) ?>;

	function appendCsrf(formData) {
		formData.append(csrfName, csrfHash);
		return formData;
	}

	var avatarPerfilOriginal = $('#avatar_perfil').attr('src');
	var avatarMenuOriginal = $('#avatar_menu').attr('src');
	var avatarPreviewPendente = false;
	var avatarPadraoUrl = <?= json_encode(avatar_padrao_url()) ?>;
	var temAvatarPersonalizado = <?= $temAvatarPersonalizado ? 'true' : 'false' ?>;

	function aplicarAvatar(src) {
		$('#avatar_perfil').attr('src', src);
		if ($('#avatar_menu').length) {
			$('#avatar_menu').attr('src', src);
		}
		avatarPerfilOriginal = src;
		avatarMenuOriginal = src;
		avatarPreviewPendente = false;
	}

	function definirBotaoRemoverAvatar(visivel) {
		$('#btn-remover-avatar').toggleClass('d-none', !visivel);
	}

	function restaurarAvatarPreview() {
		if (avatarPreviewPendente) {
			$('#avatar_perfil').attr('src', avatarPerfilOriginal);
			if (avatarMenuOriginal !== undefined) {
				$('#avatar_menu').attr('src', avatarMenuOriginal);
			}
			avatarPreviewPendente = false;
		}
	}

	function confirmarAvatarPreview() {
		avatarPerfilOriginal = $('#avatar_perfil').attr('src');
		avatarMenuOriginal = $('#avatar_menu').attr('src');
		avatarPreviewPendente = false;
	}

	function onFileUpload(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#avatar_perfil').attr('src', e.target.result);
				$('#avatar_menu').attr('src', e.target.result);
				avatarPreviewPendente = true;
				definirBotaoRemoverAvatar(true);
			};
			reader.readAsDataURL(input.files[0]);
		}
	}

	$('.listar-colaboracoes-fechadas').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: "<?php echo base_url('colaboradores/perfil/fechadas/'); ?>" + e.currentTarget.id,
			method: "POST",
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				$('#tbody-modal-colaboracoes-fechadas').html(retorno);
			}
		});
	});

	$(document).ready(function () {
		var $listaRecados = $('#lista-recados');
		var recadosOffset = parseInt($listaRecados.data('offset'), 10) || 0;
		var recadosTemMais = String($listaRecados.data('tem-mais')) === '1';
		var recadosCarregando = false;

		function carregarMaisRecados() {
			if (!recadosTemMais || recadosCarregando) {
				return;
			}
			if (!$('#painel-recados').hasClass('active') && !$('#painel-recados').hasClass('show')) {
				return;
			}

			recadosCarregando = true;
			$('#recados-loading').show();

			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil/recadosMais'); ?>",
				method: "GET",
				data: { offset: recadosOffset },
				dataType: "json",
				success: function (retorno) {
					if (!retorno || retorno.status !== true) {
						return;
					}
					if (retorno.html) {
						$('#recados-vazio').remove();
						$listaRecados.append(retorno.html);
					}
					recadosOffset = retorno.proximo_offset || recadosOffset;
					recadosTemMais = !!retorno.tem_mais;
					$listaRecados.attr('data-offset', recadosOffset);
					$listaRecados.attr('data-tem-mais', recadosTemMais ? '1' : '0');
					if (!recadosTemMais) {
						$('#recados-sentinel').addClass('d-none');
					}
				},
				complete: function () {
					recadosCarregando = false;
					$('#recados-loading').hide();
					// Se o fim da lista ainda está na tela, carrega o próximo lote
					if (recadosTemMais) {
						var el = document.getElementById('recados-sentinel');
						if (el) {
							var rect = el.getBoundingClientRect();
							if (rect.top < window.innerHeight + 200) {
								carregarMaisRecados();
							}
						}
					}
				}
			});
		}

		if ('IntersectionObserver' in window) {
			var recadosObserver = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						carregarMaisRecados();
					}
				});
			}, { root: null, rootMargin: '200px', threshold: 0 });

			var sentinel = document.getElementById('recados-sentinel');
			if (sentinel) {
				recadosObserver.observe(sentinel);
			}
		} else {
			$(window).on('scroll', function () {
				if (!recadosTemMais || recadosCarregando) {
					return;
				}
				var $sentinel = $('#recados-sentinel');
				if (!$sentinel.length || $sentinel.hasClass('d-none')) {
					return;
				}
				var rect = $sentinel[0].getBoundingClientRect();
				if (rect.top < window.innerHeight + 200) {
					carregarMaisRecados();
				}
			});
		}

		$(document).on('click', '.recado-toggle-pauta', function (e) {
			e.preventDefault();
			var $link = $(this);
			var painelSel = $link.data('target-panel');
			var $painel = $(painelSel);
			var pautaId = $link.data('pauta-id');
			var $conteudo = $painel.find('.recado-pauta-conteudo');

			if ($painel.hasClass('show')) {
				$painel.collapse('hide');
				$link.attr('aria-expanded', 'false');
				return;
			}

			$painel.collapse('show');
			$link.attr('aria-expanded', 'true');

			if ($painel.attr('data-loaded') === '1') {
				return;
			}

			$.ajax({
				url: "<?php echo base_url('colaboradores/pautas/resumo/'); ?>" + encodeURIComponent(pautaId),
				method: "GET",
				dataType: "html",
				beforeSend: function () {
					$conteudo.html('Carregando…');
				},
				success: function (html) {
					$conteudo.removeClass('text-muted').html(html);
					$painel.attr('data-loaded', '1');
				},
				error: function () {
					$conteudo.html('Não foi possível carregar o resumo desta pauta.');
				}
			});
		});

		$('#btn-marcar-recados-lidos').on('click', function (e) {
			e.preventDefault();
			var data = {};
			data[csrfName] = csrfHash;
			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil/marcarRecadosLidos'); ?>",
				method: "POST",
				data: data,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (retorno) {
					if (retorno.status == true) {
						$('.recado-nao-lido .list-group-flush').removeClass('border border-primary');
						$('.recado-nao-lido').removeClass('recado-nao-lido');
						$('#btn-marcar-recados-lidos').remove();
						$('#badge-recados-nao-lidos').remove();
						$('.avatar-recados-indicator').addClass('d-none');
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: function () {
					popMessage('ATENÇÃO', 'Não foi possível marcar os recados. Recarregue a página e tente novamente.', TOAST_STATUS.DANGER);
				}
			});
		});

		$('#btn-remover-avatar').on('click', function (e) {
			e.preventDefault();
			$('#avatar').val('');

			if (!temAvatarPersonalizado) {
				aplicarAvatar(avatarPadraoUrl);
				definirBotaoRemoverAvatar(false);
				return;
			}

			var data = {};
			data[csrfName] = csrfHash;
			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil/removerAvatar'); ?>",
				method: "POST",
				data: data,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (retorno) {
					if (retorno.status == true) {
						var novoSrc = (retorno.parametros && retorno.parametros.avatar)
							? retorno.parametros.avatar
							: avatarPadraoUrl;
						aplicarAvatar(novoSrc);
						temAvatarPersonalizado = false;
						definirBotaoRemoverAvatar(false);
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: function () {
					popMessage('ATENÇÃO', 'Não foi possível remover o avatar. Recarregue a página e tente novamente.', TOAST_STATUS.DANGER);
				}
			});
		});

		$('#colaboradores_perfil').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil/atualizarPerfil'); ?>",
				method: "POST",
				data: appendCsrf(new FormData(this)),
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (retorno) {
					if (retorno.status == true) {
						var enviouAvatar = $('#avatar')[0].files && $('#avatar')[0].files.length > 0;
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$('.apelido_colaborador').text($('#apelido').val());
						confirmarAvatarPreview();
						$('#avatar').val('');
						if (enviouAvatar) {
							temAvatarPersonalizado = true;
							definirBotaoRemoverAvatar(true);
						}
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						restaurarAvatarPreview();
						definirBotaoRemoverAvatar(temAvatarPersonalizado);
					}
				},
				error: function () {
					restaurarAvatarPreview();
					definirBotaoRemoverAvatar(temAvatarPersonalizado);
					popMessage('ATENÇÃO', 'Não foi possível salvar o perfil. Recarregue a página e tente novamente.', TOAST_STATUS.DANGER);
				}
			});
		});

		$('#colaboradores_senha').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil/trocarSenha'); ?>",
				method: "POST",
				data: appendCsrf(new FormData(this)),
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (retorno) {
					if (retorno.status == true) {
						$('#colaboradores_senha')[0].reset();
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: function () {
					popMessage('ATENÇÃO', 'Não foi possível alterar a senha. Recarregue a página e tente novamente.', TOAST_STATUS.DANGER);
				}
			});
		});

		function atualizarCamposRemuneracaoPorTipo() {
			var porHoras = $('#remuneracao_tipo_horas').is(':checked');
			$('#remuneracao_campos_horas').toggleClass('d-none', !porHoras);
			$('#remuneracao_horas, #remuneracao_arquivo').prop('disabled', !porHoras);
		}

		if ($('#colaboradores_remuneracao').length) {
			atualizarCamposRemuneracaoPorTipo();
			$('#colaboradores_remuneracao input[name="tipo"]').on('change', atualizarCamposRemuneracaoPorTipo);

			$('#remuneracao_horas').on('blur', function () {
				var v = String($(this).val() || '').trim().replace(/[hH\s]/g, '').replace(/[.,]/g, ':');
				if (/^\d{1,4}$/.test(v)) {
					v = v + ':00';
				} else if (/^(\d{1,4}):(\d)$/.test(v)) {
					v = v.replace(/:(\d)$/, ':0$1');
				}
				$(this).val(v);
			});

			if (window.location.hash === '#painel-remuneracao') {
				var tabRemuneracao = document.getElementById('tab-remuneracao');
				if (tabRemuneracao && window.bootstrap && bootstrap.Tab) {
					bootstrap.Tab.getOrCreateInstance(tabRemuneracao).show();
				}
			}

			$('#colaboradores_remuneracao').on('submit', function (e) {
				e.preventDefault();
				$.ajax({
					url: "<?php echo base_url('colaboradores/perfil/salvarRemuneracao'); ?>",
					method: "POST",
					data: appendCsrf(new FormData(this)),
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (retorno) {
						if (retorno.status == true) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							window.location.hash = 'painel-remuneracao';
							setTimeout(function () {
								window.location.reload();
							}, 800);
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
					},
					error: function () {
						popMessage('ATENÇÃO', 'Não foi possível salvar a remuneração. Recarregue a página e tente novamente.', TOAST_STATUS.DANGER);
					}
				});
			});
		}

		$('.excluir').on('click', function (e) {
			e.preventDefault();
			var data = {};
			data[csrfName] = csrfHash;
			$.ajax({
				url: "<?php echo base_url('site/excluir'); ?>",
				method: "POST",
				data: data,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (retorno) {
					if (retorno.status == true) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$('#alerta-exclusao-enviada').removeClass('d-none');
						$('#btn-abrir-excluir').prop('disabled', true).text('E-mail de confirmação enviado');
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
					$('#modal-excluir').modal('hide');
				},
				error: function () {
					popMessage('ATENÇÃO', 'Não foi possível solicitar a exclusão. Recarregue a página e tente novamente.', TOAST_STATUS.DANGER);
					$('#modal-excluir').modal('hide');
				}
			});
		});
	});
</script>

<?= $this->endSection(); ?>
