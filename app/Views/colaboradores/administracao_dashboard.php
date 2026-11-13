<?php

$fmt = static function ($valor): string {
	return number_format((int) $valor, 0, ',', '.');
};

$linkArtigos = static function (array $params = []): string {
	$query = $params === [] ? '' : ('?' . http_build_query($params));

	return site_url('colaboradores/admin/artigos') . $query;
};

$desde = static function ($data): string {
	$texto = tempo_relativo($data);

	return $texto === '' ? '' : $texto;
};

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1">Administrativo</h1>
				<p class="text-muted small mb-0">Fila de produção, ritmo de publicação, colaboradores e contatos</p>
			</div>
		</div>

		<section class="mb-4" aria-labelledby="heading-fila">
			<h2 id="heading-fila" class="h5 mb-1">Fila do dia</h2>
			<p class="text-muted small mb-3">O que está parado agora, independente da data de criação</p>
			<div class="row g-3">
				<div class="col-md-6 col-xl-3">
					<a class="card card-body bg-danger bg-opacity-10 p-4 h-100 text-body text-decoration-none" href="<?= esc($linkArtigos(['fase' => 5])); ?>">
						<h3 class="h6 mb-2">Na fase Publicar</h3>
						<p class="fs-1 text-danger mb-1"><?= esc($fmt($fila['publicar']['total'])); ?></p>
						<?php if ((int) $fila['publicar']['total'] > 0 && !empty($fila['publicar']['titulo'])): ?>
							<span class="small d-block text-truncate" title="<?= esc($fila['publicar']['titulo']); ?>"><?= esc($fila['publicar']['titulo']); ?></span>
							<span class="small text-muted">parado <?= esc($desde($fila['publicar']['desde'])); ?></span>
						<?php else: ?>
							<span class="small text-muted">Nenhum artigo nesta fase</span>
						<?php endif; ?>
					</a>
				</div>
				<div class="col-md-6 col-xl-3">
					<a class="card card-body bg-warning bg-opacity-10 p-4 h-100 text-body text-decoration-none" href="<?= esc($pode_financeiro ? site_url('colaboradores/admin/financeiro') : $linkArtigos(['fase' => 6])); ?>">
						<h3 class="h6 mb-2">Aguardando pagamento</h3>
						<p class="fs-1 text-warning mb-1"><?= esc($fmt($fila['pagar']['total'])); ?></p>
						<span class="small d-block"><?= esc($fmt($fila['pagar']['pontos'])); ?> pontos em aberto</span>
						<?php if ((int) $fila['pagar']['total'] > 0 && !empty($fila['pagar']['titulo'])): ?>
							<span class="small text-muted d-block text-truncate" title="<?= esc($fila['pagar']['titulo']); ?>">mais antigo <?= esc($desde($fila['pagar']['desde'])); ?></span>
						<?php endif; ?>
					</a>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-secondary bg-opacity-10 p-4 h-100">
						<h3 class="h6 mb-2">Marcações vencidas</h3>
						<p class="fs-1 mb-1"><?= esc($fmt($fila['travados']['total'])); ?></p>
						<span class="small d-block text-muted">travadas há mais de <?= esc($bloqueio); ?></span>
						<?php if ((int) $fila['travados']['total'] > 0 && !empty($fila['travados']['titulo'])): ?>
							<span class="small d-block text-truncate" title="<?= esc($fila['travados']['titulo']); ?>"><?= esc($fila['travados']['titulo']); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<?php if ($pode_pautas): ?>
						<a class="card card-body bg-info bg-opacity-10 p-4 h-100 text-body text-decoration-none" href="<?= esc(site_url('colaboradores/pautas/fechar')); ?>">
					<?php else: ?>
						<div class="card card-body bg-info bg-opacity-10 p-4 h-100">
					<?php endif; ?>
						<h3 class="h6 mb-2">Pautas reservadas</h3>
						<p class="fs-1 text-info mb-1"><?= esc($fmt($fila['pautas'])); ?></p>
						<span class="small text-muted">aguardando fechamento</span>
					<?php if ($pode_pautas): ?>
						</a>
					<?php else: ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<section class="mb-4" aria-labelledby="heading-funil">
			<h2 id="heading-funil" class="h5 mb-1">Produção agora</h2>
			<p class="text-muted small mb-3">Clique numa fase para abrir a listagem</p>
			<div class="card border rounded-3 shadow-sm">
				<div class="card-body p-2 p-sm-3">
					<div class="row row-cols-2 row-cols-sm-3 row-cols-lg-6 g-2">
						<?php foreach ($funil as $fase): ?>
							<div class="col">
								<a class="admin-fase-link w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body text-decoration-none"
									href="<?= esc($linkArtigos(['fase' => $fase['id']])); ?>">
									<span class="admin-fase-icon rounded-2 p-2 bg-body-secondary flex-shrink-0"><i class="<?= esc($fase['icone']); ?>" aria-hidden="true"></i></span>
									<span class="text-start admin-fase-text">
										<span class="d-block fw-bold lh-sm"><?= esc($fmt($fase['total'])); ?></span>
										<span class="d-block small text-muted text-truncate"><?= esc($fase['nome']); ?></span>
										<?php if ((int) $fase['total'] > 0 && !empty($fase['desde'])): ?>
											<span class="d-block small text-muted text-truncate">mais antigo <?= esc($desde($fase['desde'])); ?></span>
										<?php endif; ?>
									</span>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="mb-4" aria-labelledby="heading-ritmo">
			<div class="row g-4">
				<div class="col-lg-5">
					<h2 id="heading-ritmo" class="h5 mb-1">Publicação</h2>
					<p class="text-muted small mb-3">Pela data em que o artigo foi ao ar</p>
					<div class="row g-3">
						<div class="col-6">
							<div class="card card-body h-100">
								<h3 class="h6 text-muted mb-2">7 dias</h3>
								<p class="fs-2 mb-0"><?= esc($fmt($ritmo['sete'])); ?></p>
							</div>
						</div>
						<div class="col-6">
							<div class="card card-body h-100">
								<h3 class="h6 text-muted mb-2">30 dias</h3>
								<p class="fs-2 mb-0"><?= esc($fmt($ritmo['trinta'])); ?></p>
								<?php if ($ritmo['variacao'] !== null): ?>
									<span class="small <?= ((int) $ritmo['variacao'] >= 0) ? 'text-success' : 'text-danger'; ?>">
										<?= ((int) $ritmo['variacao'] > 0) ? '+' : ''; ?><?= esc((string) $ritmo['variacao']); ?>% em relação aos 30 dias anteriores
									</span>
								<?php elseif ((int) $ritmo['trinta'] > 0): ?>
									<span class="small text-muted">sem publicação nos 30 dias anteriores</span>
								<?php endif; ?>
							</div>
						</div>
						<div class="col-4">
							<div class="card card-body h-100 py-3">
								<h3 class="h6 text-muted mb-1">Teóricos</h3>
								<p class="fs-4 mb-0"><?= esc($fmt($ritmo['teoricos'])); ?></p>
								<span class="small text-muted">30 dias</span>
							</div>
						</div>
						<div class="col-4">
							<div class="card card-body h-100 py-3">
								<h3 class="h6 text-muted mb-1">Notícias</h3>
								<p class="fs-4 mb-0"><?= esc($fmt($ritmo['noticias'])); ?></p>
								<span class="small text-muted">30 dias</span>
							</div>
						</div>
						<div class="col-4">
							<div class="card card-body h-100 py-3">
								<h3 class="h6 text-muted mb-1">Descartados</h3>
								<p class="fs-4 mb-0"><?= esc($fmt($ritmo['descartados'])); ?></p>
								<span class="small text-muted">30 dias</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-7 d-flex flex-column">
					<h2 class="h5 mb-1">Últimos publicados</h2>
					<p class="text-muted small mb-3">Cinco artigos mais recentes que já têm data de publicação</p>
					<div class="card border rounded-3 shadow-sm flex-grow-1">
						<?php if ($ritmo['ultimos'] === []): ?>
							<div class="card-body text-muted">Nenhum artigo publicado ainda.</div>
						<?php else: ?>
							<ul class="list-group list-group-flush">
								<?php foreach ($ritmo['ultimos'] as $artigo): ?>
									<li class="list-group-item">
										<a class="text-body text-decoration-none d-flex flex-column flex-lg-row justify-content-lg-between gap-1 min-w-0" href="<?= esc(site_url('colaboradores/admin/artigoEditar/' . $artigo['id'])); ?>">
											<span class="text-truncate min-w-0"><?= esc($artigo['titulo']); ?></span>
											<span class="small text-muted">
												<?= esc($artigo['apelido'] ?? 'Sem escritor'); ?>
												<?php $quando = app_time($artigo['publicado']); ?>
												<?php if ($quando !== null): ?>
													· <?= esc($quando->toLocalizedString('dd MMM yyyy')); ?>
												<?php endif; ?>
											</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="mb-4" aria-labelledby="heading-colaboradores">
			<div class="d-flex justify-content-between align-items-end gap-3 mb-3">
				<div>
					<h2 id="heading-colaboradores" class="h5 mb-1">Colaboradores</h2>
					<p class="text-muted small mb-0">Quem está ativo e quem sugeriu notícias que a redação usou</p>
				</div>
				<?php if ($pode_colaboradores): ?>
					<a class="small" href="<?= esc(site_url('colaboradores/admin/permissoes')); ?>">Abrir lista</a>
				<?php endif; ?>
			</div>
			<div class="row g-3 mb-3">
				<div class="col-6 col-md-4 col-xl-2">
					<div class="card card-body h-100 py-3">
						<h3 class="h6 text-muted mb-1">Ativos</h3>
						<p class="fs-4 mb-0"><?= esc($fmt($colaboradores['ativos'])); ?></p>
						<span class="small text-muted">confirmados e sem bloqueio</span>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-2">
					<div class="card card-body h-100 py-3">
						<h3 class="h6 text-muted mb-1">E-mail pendente</h3>
						<p class="fs-4 mb-0"><?= esc($fmt($colaboradores['pendentes'])); ?></p>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-2">
					<div class="card card-body h-100 py-3">
						<h3 class="h6 text-muted mb-1">Bloqueados</h3>
						<p class="fs-4 mb-0"><?= esc($fmt($colaboradores['bloqueados'])); ?></p>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-2">
					<div class="card card-body h-100 py-3">
						<h3 class="h6 text-muted mb-1">Contratados</h3>
						<p class="fs-4 mb-0"><?= esc($fmt($colaboradores['contratados'])); ?></p>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-2">
					<div class="card card-body h-100 py-3">
						<h3 class="h6 text-muted mb-1">Novos</h3>
						<p class="fs-4 mb-0"><?= esc($fmt($colaboradores['novos'])); ?></p>
						<span class="small text-muted">30 dias</span>
					</div>
				</div>
				<div class="col-6 col-md-4 col-xl-2">
					<div class="card card-body h-100 py-3">
						<h3 class="h6 text-muted mb-1">No ar em 30 dias</h3>
						<p class="fs-4 mb-0"><?= esc($fmt($colaboradores['produziram'])); ?></p>
						<span class="small text-muted"><?= esc($fmt($colaboradores['marcados'])); ?> com artigo marcado</span>
					</div>
				</div>
			</div>

			<div class="card border rounded-3 shadow-sm">
				<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
					<h3 class="h6 mb-1">Notícias sugeridas que foram usadas</h3>
					<p class="text-muted small mb-0">A pessoa é a sugestora da pauta que virou notícia. Usadas são as que não foram descartadas. Publicadas já têm data de publicação.</p>
				</div>
				<?php if ($sugestoes === []): ?>
					<div class="card-body text-muted">Nenhuma notícia sugerida entrou em produção.</div>
				<?php else: ?>
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead>
								<tr>
									<th scope="col">Colaborador</th>
									<th scope="col" class="text-end">Usadas</th>
									<th scope="col" class="text-end">Publicadas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($sugestoes as $pessoa): ?>
									<tr>
										<td>
											<a class="text-body" href="<?= esc($linkArtigos(['papel' => 'A', 'colaborador' => $pessoa['apelido'], 'tipo' => 'N'])); ?>"><?= esc($pessoa['apelido']); ?></a>
										</td>
										<td class="text-end"><?= esc($fmt($pessoa['usadas'])); ?></td>
										<td class="text-end"><?= esc($fmt($pessoa['publicadas'])); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<section class="mb-4" aria-labelledby="heading-contatos">
			<div class="d-flex justify-content-between align-items-end gap-3 mb-3">
				<div>
					<h2 id="heading-contatos" class="h5 mb-1">Contatos</h2>
					<p class="text-muted small mb-0">Mensagens do formulário público</p>
				</div>
				<a class="small" href="<?= esc(site_url('colaboradores/admin/contatos')); ?>">Ver mensagens</a>
			</div>
			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<a class="card card-body h-100 text-body text-decoration-none" href="<?= esc(site_url('colaboradores/admin/contatos')); ?>">
						<h3 class="h6 text-muted mb-1">Sem resposta</h3>
						<p class="fs-2 mb-0 <?= ((int) $contatos['sem_resposta'] > 0) ? 'text-danger' : ''; ?>"><?= esc($fmt($contatos['sem_resposta'])); ?></p>
					</a>
				</div>
				<div class="col-md-4">
					<div class="card card-body h-100">
						<h3 class="h6 text-muted mb-1">Recebidos em 7 dias</h3>
						<p class="fs-2 mb-0"><?= esc($fmt($contatos['sete'])); ?></p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card card-body h-100">
						<h3 class="h6 text-muted mb-1">Recebidos em 30 dias</h3>
						<p class="fs-2 mb-0"><?= esc($fmt($contatos['trinta'])); ?></p>
					</div>
				</div>
			</div>
			<div class="card border rounded-3 shadow-sm">
				<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
					<h3 class="h6 mb-0">Sem resposta há mais tempo</h3>
				</div>
				<?php if ($contatos['antigos'] === []): ?>
					<div class="card-body text-muted">Nenhuma mensagem aguardando resposta.</div>
				<?php else: ?>
					<ul class="list-group list-group-flush">
						<?php foreach ($contatos['antigos'] as $contato): ?>
							<li class="list-group-item d-flex flex-column flex-sm-row justify-content-sm-between gap-1 min-w-0">
								<span class="min-w-0 text-break">
									<span class="fw-semibold"><?= esc($contato['assunto'] ?? 'Sem assunto'); ?></span>
									<span class="text-muted">· <?= esc($contato['email']); ?></span>
								</span>
								<span class="small text-muted flex-shrink-0"><?= esc($desde($contato['criado'])); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
	</div>
</div>

<style>
	a.admin-fase-link:hover {
		background-color: var(--bs-secondary-bg);
		border-color: var(--bs-border-color) !important;
	}
	.admin-fase-icon {
		font-size: 1rem;
		line-height: 1;
	}
	.admin-fase-text {
		min-width: 0;
	}
</style>

<?= $this->endSection(); ?>
