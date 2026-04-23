<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($artigos !== NULL && !empty($artigos)): ?>
	<h5>Artigos Pendentes de Pagamento</h5>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">Artigo</th>
				<th scope="col">Publicado em</th>
				<th scope="col">Escritor</th>
				<th scope="col">Revisor</th>
				<th scope="col">Narrador</th>
				<th scope="col">Produtor</th>
				<th scope="col">Pontuação Total</th>
			</tr>
		</thead>
		<tbody>
			<?php $total = 0; ?>
			<?php foreach ($artigos as $artigo): ?>
				<tr>
					<th scope="row">
						<a href="<?= site_url('colaboradores/artigos/detalhamento/' . rawurlencode((string) $artigo['id'])); ?>" target="_blank"><?= $artigo['titulo']; ?></a>
					</th>
					<td>
						<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
					</td>
					<td>
						<?= $artigo['escrito']; ?>
					</td>
					<td>
						<?= $artigo['revisado']; ?>
					</td>
					<td>
						<?= $artigo['narrado']; ?>
					</td>
					<td>
						<?= $artigo['produzido']; ?>
					</td>
					<td>
						<?= number_format($artigo['total_pontuacao'], 1, ',', '.'); ?>
						<?php $total += $artigo['total_pontuacao']; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<th scope="row" colspan="5">Total de Pontos</th>
				<td>
				<td>
					<?= number_format($total, 0, ',', '.');?>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif; ?>

<?php if ($usuarios !== NULL && !empty($usuarios)): ?>
	<h5>Descrição Pagamentos Usuários</h5>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">Apelido</th>
				<th scope="col">Pontos Escrita</th>
				<th scope="col">Pontos Revisão</th>
				<th scope="col">Pontos Narração</th>
				<th scope="col">Pontos Produção</th>
				<th scope="col">Pontuação Total</th>
				<th scope="col">Valor Repasse</th>
			</tr>
		</thead>
		<tbody>
			<?php $total = 0; ?>
			<?php foreach ($usuarios as $usuario): ?>
				<tr>
					<th scope="row">
						<?= $usuario['apelido']; ?>
						<?= ($usuario['endereco'] == NULL) ? ('*') : (''); ?>
					</th>
					<td>
						<?= number_format($usuario['pontos_escrita'], 2, ',', '.'); ?>
					</td>
					<td>
						<?= number_format($usuario['pontos_revisao'], 2, ',', '.'); ?>
					</td>
					<td>
						<?= number_format($usuario['pontos_narracao'], 2, ',', '.'); ?>
					</td>
					<td>
						<?= number_format($usuario['pontos_producao'], 2, ',', '.'); ?>
					</td>
					<td>
						<?= number_format($usuario['pontos_total'], 2, ',', '.'); ?>
						<?php $total += $usuario['pontos_total']; ?>
					</td>
					<td>
						<?= number_format($usuario['repasse'], 8, ',', '.'); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<th scope="row" colspan="5">Total de Pontos</th>
				<td>
				<td>
					<?= number_format($total, 0, ',', '.');?>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif; ?>

<?php if ($usuarios !== NULL && !empty($usuarios)): ?>
	<div class="d-flex justify-content-center mb-5 text-left">
		<div class="w-100" novalidate="yes" method="post" id="pagamentos_form">
			<div class="d-flex justify-content-between align-items-center gap-2 mb-1">
				<label class="form-label small text-muted mb-0" for="repasse_electrum">Copie e cole o pagamento na Electrum</label>
				<button type="button" class="btn btn-primary btn-sm" id="btn-copiar-repasse-electrum">
					<i class="fas fa-copy me-1" aria-hidden="true"></i>Copiar repasse
				</button>
			</div>
			<textarea id="repasse_electrum" class="form-control form-control-sm" <?= isset($pagamentos_id) ? ('disabled') : (''); ?>
				rows="10"><?= $repasse_string; ?></textarea>
		</div>

	</div>
	<script>
		$(document).ready(function () {
			$('#btn-copiar-repasse-electrum').on('click', function () {
				var valor = ($('#repasse_electrum').val() || '').toString();
				if (!valor) {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Não há conteúdo para copiar.', TOAST_STATUS.DANGER);
					}
					return;
				}
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(valor).then(function () {
						if (typeof popMessage === 'function') {
							popMessage('Sucesso!', 'Repasse copiado.', TOAST_STATUS.SUCCESS);
						}
					}).catch(function () {
						if (typeof popMessage === 'function') {
							popMessage('ATENÇÃO', 'Não foi possível copiar o repasse.', TOAST_STATUS.DANGER);
						}
					});
					return;
				}
				$('#repasse_electrum').trigger('select');
				try {
					document.execCommand('copy');
					if (typeof popMessage === 'function') {
						popMessage('Sucesso!', 'Repasse copiado.', TOAST_STATUS.SUCCESS);
					}
				} catch (e) {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Não foi possível copiar o repasse.', TOAST_STATUS.DANGER);
					}
				}
			});
		});
	</script>
<?php endif; ?>

<?php if ($usuarios == NULL || empty($usuarios)): ?>
	<h5>Não foi encontrado nenhum artigo para ser pago.</h5>
	<script>
		$(document).ready(function(){
			$('.collapse').hide();
		})
	</script>
<?php endif; ?>