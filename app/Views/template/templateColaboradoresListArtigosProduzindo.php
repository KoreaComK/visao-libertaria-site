<?php use CodeIgniter\I18n\Time; ?>
<table class="table align-middle p-4 mb-0 table-hover table-shrink">
	<!-- Table head -->
	<thead class="table-dark">
		<tr>
			</th>
			<th scope="col" class="border-0 rounded-start">Título</th>
			<th scope="col" class="border-0 rounded-end">Status</th>
		</tr>
	</thead>

	<!-- Table body START -->
	<tbody class="border-top-0">
		<?php if ($artigos !== NULL && !empty($artigos)): ?>
			<?php foreach ($artigos as $artigo): ?>
				<tr>
					<td>
						<h6 class="mb-0"><a href="<?= ($artigo['fase_producao_id'] == '1' && $admin !== true)?($artigo['link']):(site_url('colaboradores/artigos/detalhamento/'.$artigo['id'])); ?>" class="btn-link" target="_blank"><?= $artigo['titulo']; ?></a></h6>
					</td>
					<td>
						<span
							class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?> mb-2"><?= $artigo['nome']; ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<!-- Table data -->
				<td colspan="6">
					<h6 class="text-center">Nenhum resultado foi encontrado</h6>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
	<!-- Table body END -->
</table>