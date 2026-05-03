<div class="rounded-3 border bg-body-secondary p-3 mt-4" id="secao_pagamentos_avulsos">
	<h2 class="h6 mb-2 text-muted">Pagamentos avulsos para colaboradores</h2>
	<p class="small text-muted mb-3 mb-lg-2">Repasse extra por colaborador, além da divisão por pontos. Ao escolher um nome na busca, ele entra na tabela abaixo. Use o <strong>Valor do bitcoin em reais</strong> já preenchido na calculadora (modal). Preencha a <strong>Quantidade em reais</strong> do repasse; o valor em bitcoin é calculado automaticamente (não é gravado no banco além do BTC do repasse).</p>
	<div class="row g-2">
		<div class="col-12 col-lg-6 col-xl-5">
			<label class="form-label small text-muted mb-1" for="avulso_busca_apelido">Apelido</label>
			<input type="text" class="form-control form-control-sm" id="avulso_busca_apelido"
				autocomplete="off" placeholder="Digite e clique no colaborador para incluir na lista">
			<div class="position-relative d-none" id="avulso_sugestoes_wrap" aria-live="polite">
				<div class="list-group position-absolute w-100 mt-1 small bg-body border rounded shadow-sm"
					id="avulso_sugestoes" style="z-index: 25; max-height: 220px; overflow-y: auto;"></div>
			</div>
		</div>
	</div>
	<div class="table-responsive mt-3">
		<table class="table table-sm table-bordered align-middle mb-0 d-none" id="avulsos_tabela">
			<thead class="table-light">
				<tr>
					<th scope="col">Colaborador</th>
					<th scope="col">Valor do bitcoin em reais</th>
					<th scope="col">Quantidade em reais</th>
					<th scope="col">Valor do pagamento em bitcoin</th>
					<th scope="col" class="text-end">Ações</th>
				</tr>
			</thead>
			<tbody id="avulsos_tbody"></tbody>
		</table>
	</div>
	<input type="hidden" name="pagamentos_avulsos_json" id="pagamentos_avulsos_json" value="[]">
</div>
<div id="wrap_btn_finalizar_repasse" class="mt-3 d-none text-center">
	<button type="button" class="btn btn-outline-primary btn-sm" id="btn_finalizar_repasse"
		data-preview-url="<?= esc(base_url('colaboradores/admin/financeiro/preview'), 'attr'); ?>">
		<i class="fas fa-circle-check me-1" aria-hidden="true"></i>Finalizar repasse
	</button>
</div>
