<?php if (empty($lista_artigos)): ?>
	<tr>
		<td colspan="4" class="text-center">Não houve colaborações suas no mês informado.</td>
	</tr>
<?php else: ?>
	<?php foreach ($lista_artigos as $chave => $artigo): ?>
		<?php $total = 0; ?>
		<tr>
			<th scope="row">
				<?= $chave + 1 ?>
			</th>
			<td>
				<a href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['artigos_id']); ?>">
					<?= esc($artigo['titulo']); ?>
				</a>
			</td>
			<td>
				<?php if ($artigo['escrito_colaboradores_id'] == $colaborador_id): ?>
					<?php $total += $artigo['palavras_escritor'] * $artigo['multiplicador_escrito'] / 100; ?>
					<label class="badge bg-info m-1">Escritor</label>
				<?php endif; ?>
				<?php if ($artigo['revisado_colaboradores_id'] == $colaborador_id): ?>
					<?php $total += $artigo['palavras_revisor'] * $artigo['multiplicador_revisado'] / 100; ?>
					<label class="badge bg-info m-1">Revisor</label>
				<?php endif; ?>
				<?php if ($artigo['narrado_colaboradores_id'] == $colaborador_id): ?>
					<?php $total += $artigo['palavras_narrador'] * $artigo['multiplicador_narrado'] / 100; ?>
					<label class="badge bg-info m-1">Narrador</label>
				<?php endif; ?>
				<?php if ($artigo['produzido_colaboradores_id'] == $colaborador_id): ?>
					<?php $total += $artigo['palavras_produtor'] * $artigo['multiplicador_produzido'] / 100; ?>
					<label class="badge bg-info m-1">Produtor</label>
				<?php endif; ?>
			</td>
			<td>
				<?= number_format($total, 0, ',', '.'); ?>
			</td>
		</tr>
	<?php endforeach; ?>
<?php endif; ?>
