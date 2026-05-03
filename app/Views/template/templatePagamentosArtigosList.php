<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($artigos !== NULL && !empty($artigos)): ?>
	<div id="pagamento_preview_tem_artigos" class="d-none" aria-hidden="true"></div>
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

<?php if (! isset($pagamentos_id)): ?>
	<?= view('colaboradores/partials/pagamentos_avulsos_colaboradores_form'); ?>
	<div id="pagamentos_avulsos_prelista_json" class="d-none" data-json="<?= esc(json_encode($colaboradores_contratados_avulsos ?? []), 'attr'); ?>"></div>
<?php endif; ?>

<?php if (! empty($pagamentos_avulsos ?? [])): ?>
	<?php $avulsosConsultaSalva = isset($pagamentos_id); ?>
	<h5 class="mt-4">Pagamentos avulsos</h5>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">Colaborador</th>
				<?php if (! $avulsosConsultaSalva): ?>
					<th scope="col">Valor do bitcoin em reais</th>
					<th scope="col">Quantidade em reais</th>
				<?php endif; ?>
				<th scope="col">Valor pagamento (BTC)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($pagamentos_avulsos as $av): ?>
				<tr>
					<th scope="row"><?= esc($av['apelido'] ?? ''); ?></th>
					<?php if (! $avulsosConsultaSalva): ?>
						<td><?= number_format((float) ($av['valor_btc_brl'] ?? 0), 2, ',', '.'); ?></td>
						<td><?= number_format((float) ($av['quantidade_reais'] ?? 0), 2, ',', '.'); ?></td>
					<?php endif; ?>
					<td><?= number_format((float) ($av['valor_bitcoin'] ?? 0), 8, ',', '.'); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php
$temRepasseElectrum = ($usuarios !== null && ! empty($usuarios))
	|| (isset($repasse_string) && trim((string) $repasse_string) !== '');
?>
<?php if ($temRepasseElectrum): ?>
	<div id="electrum_reveal_wrapper" class="d-none w-100 mb-5">
		<div class="d-flex justify-content-center text-left">
			<div class="w-100">
				<div class="d-flex justify-content-between align-items-center gap-2 mb-1">
					<label class="form-label small text-muted mb-0" for="repasse_electrum">Resumo de Pagamento</label>
					<button type="button" class="btn btn-primary btn-sm" id="btn-copiar-repasse-electrum">
						<i class="fas fa-copy me-1" aria-hidden="true"></i>Copiar resumo
					</button>
				</div>
				<textarea id="repasse_electrum" class="form-control form-control-sm" <?= isset($pagamentos_id) ? ('disabled') : (''); ?>
					rows="10"><?= $repasse_string; ?></textarea>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
$semArtigos = ($artigos === null || empty($artigos));
$semAvulsos = empty($pagamentos_avulsos ?? []);
?>
<?php if ($semArtigos && $semAvulsos): ?>
	<h5>Não foi encontrado nenhum artigo para ser pago.</h5>
	<script>
		$(document).ready(function () {
			$('.collapse').hide();
		});
	</script>
<?php elseif ($semArtigos && ! $semAvulsos): ?>
	<script>
		$(document).ready(function () {
			$('.collapse').show();
		});
	</script>
<?php endif; ?>