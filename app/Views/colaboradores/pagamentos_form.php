<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1"><?= esc($titulo); ?></h1>
				<p class="text-muted small mb-0">Configure os parâmetros de pagamento e gere o detalhamento dos artigos vinculados</p>
			</div>
		</div>

		<section class="card border rounded-3 shadow-sm">
			<div class="card-body p-3">
				<form class="needs-validation w-100" novalidate="yes" method="post" id="pagamentos_form">
					<div class="row g-2 g-md-3">
						<div class="col-12 col-lg-8">
							<label class="form-label small text-muted mb-1" for="titulo">Título do pagamento</label>
							<input type="text" class="form-control form-control-sm" id="titulo"
								placeholder="Caso não seja informado nenhum título, será o mês/ano corrente" name="titulo"
								value="<?= (isset($pagamentos)) ? ($pagamentos['titulo']) : (''); ?>" <?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
						</div>
						<div class="col-12 col-lg-4">
							<label class="form-label small text-muted mb-1" for="quantidade_bitcoin">Qtde. Bitcoin</label>
							<div class="input-group input-group-sm">
								<input type="number" min="0" step="0.00000001" class="form-control" id="quantidade_bitcoin"
									name="quantidade_bitcoin" placeholder="0,12345678" required
									value="<?= (isset($pagamentos)) ? ($pagamentos['quantidade_bitcoin']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
								<?php if (!isset($pagamentos)): ?>
									<button type="button" class="btn btn-outline-secondary" id="btn_abrir_calc_btc"
										data-bs-toggle="modal" data-bs-target="#modalCalcBitcoin"
										title="Calcular quantidade em BTC">
										<i class="fas fa-calculator" aria-hidden="true"></i>
									</button>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="rounded-3 border bg-body-secondary bg-opacity-50 p-3 mt-3">
						<h2 class="h6 mb-2 text-muted">Multiplicadores (%) dos artigos</h2>
						<div class="row g-2 g-md-3">
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_escrito">Escrito</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_escrito" placeholder="Mult."
									required name="multiplicador_escrito" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_escrito']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_revisado">Revisado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_revisado" placeholder="Mult."
									required name="multiplicador_revisado" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_revisado']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_narrado">Narrado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_narrado" placeholder="Mult."
									required name="multiplicador_narrado" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_narrado']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_produzido">Produzido</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_produzido" placeholder="Mult."
									required name="multiplicador_produzido" value="150"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_produzido']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
						</div>
					</div>

					<div class="rounded-3 border bg-body-secondary bg-opacity-50 p-3 mt-3">
						<h2 class="h6 mb-2 text-muted">Multiplicadores (%) das notícias</h2>
						<div class="row g-2 g-md-3">
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_escrito_noticia">Escrito</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_escrito_noticia" placeholder="Mult."
									required name="multiplicador_escrito_noticia" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_escrito_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_revisado_noticia">Revisado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_revisado_noticia"
									placeholder="Mult." required name="multiplicador_revisado_noticia" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_revisado_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_narrado_noticia">Narrado</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_narrado_noticia" placeholder="Mult."
									required name="multiplicador_narrado_noticia" value="100"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_narrado_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
							<div class="col-12 col-sm-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="multiplicador_produzido_noticia">Produzido</label>
								<input type="number" class="form-control form-control-sm" id="multiplicador_produzido_noticia"
									placeholder="Mult." required name="multiplicador_produzido_noticia" value="150"
									value="<?= (isset($pagamentos)) ? ($pagamentos['multiplicador_produzido_noticia']) : (''); ?>"
									<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
							</div>
						</div>
					</div>

					<?php if (isset($pagamentos)): ?>
						<div class="mt-3 d-flex justify-content-end">
							<button class="btn btn-primary btn-sm buscar-detalhe" type="button">
								<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i>Mostrar detalhes do pagamento
							</button>
						</div>
					<?php else: ?>
						<div class="mt-3 d-flex justify-content-end">
							<button class="btn btn-primary btn-sm gerar-preview" type="button">
								<i class="fas fa-list-check me-1" aria-hidden="true"></i>Buscar artigos pendentes
							</button>
						</div>
					<?php endif; ?>

					<div class="pagamento-preview mt-3"></div>

					<div class="mb-3 mt-3 collapse">
						<label class="form-label small text-muted mb-1" for="hash_transacao">Hash da transação do pagamento</label>
						<input type="text" class="form-control form-control-sm" id="hash_transacao" required name="hash_transacao"
							value="<?= (isset($pagamentos)) ? ($pagamentos['hash_transacao']) : (''); ?>"
							<?= (isset($pagamentos)) ? ('disabled') : (''); ?>>
					</div>

					<?php if (!isset($pagamentos)): ?>
						<div class="mt-3 d-flex justify-content-end">
							<button class="btn btn-primary btn-sm collapse submit-pagamento" type="button">
								<i class="fas fa-floppy-disk me-1" aria-hidden="true"></i>Salvar pagamento
							</button>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</section>
	</div>
</div>

<?php if (!isset($pagamentos)): ?>
	<div class="modal fade" id="modalCalcBitcoin" tabindex="-1" aria-labelledby="modalCalcBitcoinLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title h5" id="modalCalcBitcoinLabel">Calcular quantidade em Bitcoin</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label small text-muted mb-1" for="calc_qtd_reais">Quantidade em reais</label>
						<input type="text" class="form-control form-control-sm" id="calc_qtd_reais" inputmode="decimal"
							autocomplete="off" value="11.000,00">
					</div>
					<div class="mb-3">
						<label class="form-label small text-muted mb-1" for="calc_participacao">Porcentagem de participação (%)</label>
						<input type="number" class="form-control form-control-sm" id="calc_participacao" min="0" max="100"
							step="1" value="70">
					</div>
					<div class="mb-3">
						<label class="form-label small text-muted mb-1" for="calc_btc_brl">Valor do bitcoin em reais</label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control form-control-sm" id="calc_btc_brl" inputmode="decimal"
								autocomplete="off" placeholder="0,00">
							<button type="button" class="btn btn-outline-primary" id="calc_buscar_preco_btc" title="Buscar cotação atual">
								<i class="fas fa-sync-alt me-1" aria-hidden="true"></i>Buscar valor
							</button>
						</div>
						<p class="form-text small text-muted mb-0 mt-1" id="calc_btc_fetch_msg" role="status"></p>
					</div>
					<div class="mb-0">
						<label class="form-label small text-muted mb-1" for="calc_btc_repassado">Valor repassado em bitcoin</label>
						<input type="text" class="form-control form-control-sm bg-body-secondary" id="calc_btc_repassado"
							readonly placeholder="—" inputmode="decimal">
					</div>
				</div>
				<div class="modal-footer flex-wrap gap-2 justify-content-between">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-primary btn-sm" id="calc_copiar_qtd_btc" disabled>
						<i class="fas fa-paste me-1" aria-hidden="true"></i>Copiar para Qtde. Bitcoin
					</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<script>
	<?php if (isset($pagamentos)): ?>

		$('.buscar-detalhe').on('click', function (e) {
			form = new FormData();
			form.append('pagamento_id', <?= $pagamentos['id']; ?>);
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/financeiro/detalhe'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.pagamento-preview').html(retorno);
					$('.pagamento-preview').find('#electrum_reveal_wrapper').addClass('d-none');
					$('.collapse').show();
				}
			});
		});

	<?php else: ?>

		function pagamentosBrlDisplayToNumber(str) {
			if (str == null || String(str).trim() === '') return NaN;
			const s = String(str).trim().replace(/\./g, '').replace(',', '.');
			const n = parseFloat(s);
			return isNaN(n) ? NaN : n;
		}
		function pagamentosFormatBrl(num) {
			return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}
		function pagamentosFormatBtc8(num) {
			if (!isFinite(num) || num <= 0) return '';
			return num.toFixed(8);
		}
		function pagamentosBtcInputToNumber(str) {
			if (str == null || String(str).trim() === '') return NaN;
			return parseFloat(String(str).trim().replace(/\s/g, '').replace(',', '.'));
		}

		$('.gerar-preview').on('click', function (e) {
			form = new FormData(document.getElementById('pagamentos_form'));
			$.ajax({
				url: "<?php echo base_url('colaboradores/admin/financeiro/preview'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.pagamento-preview').html(retorno);
					$('.pagamento-preview').find('#electrum_reveal_wrapper').addClass('d-none');
					const temArtigos = $('.pagamento-preview').find('#pagamento_preview_tem_artigos').length > 0;
					$('.pagamento-preview').find('#wrap_btn_finalizar_repasse').toggleClass('d-none', !temArtigos);
					if (typeof window.pagamentosAvulsosAposPreview === 'function') {
						window.pagamentosAvulsosAposPreview();
					}
					$('.collapse').show();
				}
			});
		});

		$('.submit-pagamento').on('click', function (e) {
			form = new FormData(document.getElementById('pagamentos_form'));
			$.ajax({
				url: "<?= base_url('colaboradores/admin/financeiro/salvar'); ?>",
				method: "POST",
				processData: false,
				contentType: false,
				data: form,
				cache: false,
				dataType: "JSON",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						setTimeout(function () {
							window.location.href = "<?= base_url('colaboradores/admin/financeiro'); ?>";
						}, 2000);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						$('#titulo').focus();
					}
				}
			});
		});

		(function () {
			const COINGECKO_BTC_BRL = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=brl';

			function atualizarCalcBtc() {
				const qtdReais = pagamentosBrlDisplayToNumber($('#calc_qtd_reais').val());
				const pct = parseFloat($('#calc_participacao').val());
				const btcBrl = pagamentosBrlDisplayToNumber($('#calc_btc_brl').val());
				const $out = $('#calc_btc_repassado');
				const $btn = $('#calc_copiar_qtd_btc');
				if (qtdReais > 0 && !isNaN(pct) && pct >= 0 && btcBrl > 0) {
					const btc = (qtdReais * (pct / 100)) / btcBrl;
					if (isFinite(btc) && btc >= 0) {
						$out.val(btc.toFixed(8));
						$btn.prop('disabled', false);
						return;
					}
				}
				$out.val('');
				$btn.prop('disabled', true);
			}

			$('#calc_qtd_reais').on('blur', function () {
				const n = pagamentosBrlDisplayToNumber($(this).val());
				if (!isNaN(n)) {
					$(this).val(pagamentosFormatBrl(n));
				}
				atualizarCalcBtc();
			});

			$('#calc_qtd_reais').on('input', function () {
				atualizarCalcBtc();
			});

			$('#calc_participacao').on('input change', function () {
				atualizarCalcBtc();
			});

			$('#calc_participacao').on('blur', function () {
				let v = parseFloat($(this).val());
				if (isNaN(v)) v = 70;
				v = Math.max(0, Math.min(100, v));
				$(this).val(v);
				atualizarCalcBtc();
			});

			$('#calc_btc_brl').on('blur', function () {
				const n = pagamentosBrlDisplayToNumber($(this).val());
				if (!isNaN(n)) {
					$(this).val(pagamentosFormatBrl(n));
				}
				atualizarCalcBtc();
			});

			$('#calc_btc_brl').on('input change', function () {
				atualizarCalcBtc();
			});

			$('#calc_buscar_preco_btc').on('click', function () {
				const $btn = $(this);
				const $msg = $('#calc_btc_fetch_msg');
				$btn.prop('disabled', true);
				$msg.text('Buscando cotação…');
				fetch(COINGECKO_BTC_BRL)
					.then(function (r) {
						if (!r.ok) throw new Error('Falha na resposta da API');
						return r.json();
					})
					.then(function (data) {
						const brl = data && data.bitcoin && typeof data.bitcoin.brl === 'number' ? data.bitcoin.brl : null;
						if (brl == null || !(brl > 0)) throw new Error('Cotação indisponível');
						$('#calc_btc_brl').val(pagamentosFormatBrl(brl));
						$msg.text('Cotação atualizada (CoinGecko).');
						atualizarCalcBtc();
					})
					.catch(function () {
						$msg.text('Não foi possível obter a cotação. Tente de novo ou informe o valor manualmente.');
					})
					.finally(function () {
						$btn.prop('disabled', false);
					});
			});

			$('#calc_copiar_qtd_btc').on('click', function () {
				const raw = $('#calc_btc_repassado').val();
				const num = parseFloat(String(raw).replace(',', '.'));
				if (isNaN(num)) return;
				$('#quantidade_bitcoin').val(num);
				const modalEl = document.getElementById('modalCalcBitcoin');
				const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
				inst.hide();
			});

			$('#modalCalcBitcoin').on('shown.bs.modal', function () {
				$('#calc_btc_fetch_msg').text('');
				atualizarCalcBtc();
			});
		})();

		(function () {
			const URL_BUSCA = <?= json_encode(base_url('colaboradores/admin/financeiro/buscarColaboradores')) ?>;
			let avulsosTimer = null;
			window.pagamentosAvulsosRows = window.pagamentosAvulsosRows || [];

			function esconderResumoPagamento() {
				$('.pagamento-preview').find('#electrum_reveal_wrapper').addClass('d-none');
			}

			function syncHiddenJson() {
				const $hidden = $('.pagamento-preview').find('#pagamentos_avulsos_json');
				if (!$hidden.length) {
					return;
				}
				const payload = window.pagamentosAvulsosRows
					.filter(function (r) {
						return r.colaboradores_id > 0 && r.valor_bitcoin > 0;
					})
					.map(function (r) {
						return {
							colaboradores_id: r.colaboradores_id,
							valor_btc_brl: r.valor_btc_brl,
							quantidade_reais: r.quantidade_reais,
							valor_bitcoin: r.valor_bitcoin
						};
					});
				$hidden.val(JSON.stringify(payload));
			}

			function recalcAvulsoRow(r) {
				if (r.quantidade_reais > 0 && r.valor_btc_brl > 0) {
					r.valor_bitcoin = r.quantidade_reais / r.valor_btc_brl;
				} else {
					r.valor_bitcoin = 0;
				}
			}

			function textoBtcRepasse(n) {
				if (!isFinite(n) || n <= 0) {
					return '';
				}
				return n.toFixed(8).replace('.', ',');
			}

			/** @returns {boolean} */
			function adicionarColaboradorAvulso($pv, id, apelido) {
				const ap = (apelido || '').trim();
				if (!id || !ap) {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Escolha um colaborador na lista de sugestões.', TOAST_STATUS.DANGER);
					}
					return false;
				}
				if (window.pagamentosAvulsosRows.some(function (r) { return r.colaboradores_id === id; })) {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Este colaborador já está na lista.', TOAST_STATUS.DANGER);
					}
					return false;
				}
				const btcBrlModal = pagamentosBrlDisplayToNumber($('#calc_btc_brl').val());
				if (!(btcBrlModal > 0)) {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Preencha o campo Valor do bitcoin em reais na calculadora (ícone ao lado de Qtde. Bitcoin) antes de incluir o colaborador.', TOAST_STATUS.DANGER);
					}
					return false;
				}
				window.pagamentosAvulsosRows.push({
					colaboradores_id: id,
					apelido: ap,
					valor_btc_brl: btcBrlModal,
					quantidade_reais: 0,
					valor_bitcoin: 0
				});
				$pv.find('#avulso_busca_apelido').val('');
				renderAvulsosTable();
				return true;
			}

			function renderAvulsosTable() {
				esconderResumoPagamento();
				const $pv = $('.pagamento-preview');
				const $tb = $pv.find('#avulsos_tbody');
				const $table = $pv.find('#avulsos_tabela');
				if (!$tb.length) {
					return;
				}
				$tb.empty();
				if (window.pagamentosAvulsosRows.length === 0) {
					$table.addClass('d-none');
					syncHiddenJson();
					return;
				}
				$table.removeClass('d-none');
				window.pagamentosAvulsosRows.forEach(function (r, idx) {
					recalcAvulsoRow(r);
					const $tr = $('<tr>').attr('data-idx', String(idx));
					$tr.append($('<td>').text(r.apelido));
					$tr.append($('<td>').append(
						$('<input>', { type: 'text', class: 'form-control form-control-sm avulso-brl-btc', autocomplete: 'off', inputmode: 'decimal', placeholder: '0,00' })
							.val(r.valor_btc_brl > 0 ? pagamentosFormatBrl(r.valor_btc_brl) : '')
					));
					$tr.append($('<td>').append(
						$('<input>', { type: 'text', class: 'form-control form-control-sm avulso-brl-qtd', autocomplete: 'off', inputmode: 'decimal', placeholder: '0,00' })
							.val(r.quantidade_reais > 0 ? pagamentosFormatBrl(r.quantidade_reais) : '')
					));
					$tr.append($('<td>').append(
						$('<input>', { type: 'text', class: 'form-control form-control-sm bg-body-secondary avulso-out-btc', autocomplete: 'off', readonly: true, placeholder: '—' })
							.val(textoBtcRepasse(r.valor_bitcoin))
					));
					$tr.append($('<td>', { class: 'text-end' }).append(
						$('<button>', { type: 'button', class: 'btn btn-outline-danger btn-sm avulso-remover', title: 'Remover' })
							.html('<i class="fas fa-trash" aria-hidden="true"></i>')
					));
					$tb.append($tr);
				});
				syncHiddenJson();
			}

			window.pagamentosAvulsosAposPreview = function () {
				const $j = $('.pagamento-preview').find('#pagamentos_avulsos_prelista_json');
				let pre = [];
				if ($j.length) {
					try {
						pre = JSON.parse($j.attr('data-json') || '[]');
					} catch (e) {
						pre = [];
					}
				}
				const btcBrlModal = pagamentosBrlDisplayToNumber($('#calc_btc_brl').val());
				const vb = (!isNaN(btcBrlModal) && btcBrlModal > 0) ? btcBrlModal : 0;
				if (Array.isArray(pre)) {
					pre.forEach(function (c) {
						const id = parseInt(c.id, 10);
						if (!id) {
							return;
						}
						if (window.pagamentosAvulsosRows.some(function (r) { return r.colaboradores_id === id; })) {
							return;
						}
						window.pagamentosAvulsosRows.push({
							colaboradores_id: id,
							apelido: (c.apelido || '').toString(),
							valor_btc_brl: vb,
							quantidade_reais: 0,
							valor_bitcoin: 0
						});
					});
				}
				renderAvulsosTable();
			};
			window.pagamentosAvulsosSyncHidden = syncHiddenJson;

			$(document).on('input', '#avulso_busca_apelido', function () {
				esconderResumoPagamento();
				const q = $(this).val().trim();
				clearTimeout(avulsosTimer);
				const $pv = $('.pagamento-preview');
				$pv.find('#avulso_sugestoes').empty();
				if (q.length < 1) {
					$pv.find('#avulso_sugestoes_wrap').addClass('d-none');
					return;
				}
				avulsosTimer = setTimeout(function () {
					fetch(URL_BUSCA + '?q=' + encodeURIComponent(q))
						.then(function (r) { return r.json(); })
						.then(function (lista) {
							const $pv2 = $('.pagamento-preview');
							$pv2.find('#avulso_sugestoes').empty();
							if (!Array.isArray(lista) || !lista.length) {
								$pv2.find('#avulso_sugestoes_wrap').addClass('d-none');
								return;
							}
							$pv2.find('#avulso_sugestoes_wrap').removeClass('d-none');
							lista.forEach(function (c) {
								const label = c.apelido + (c.email ? ' · ' + c.email : '');
								const cid = parseInt(c.id, 10);
								const $b = $('<button type="button" class="list-group-item list-group-item-action py-2 px-2 bg-body text-start"></button>');
								$b.text(label);
								$b.on('click', function () {
									$pv2.find('#avulso_sugestoes_wrap').addClass('d-none');
									adicionarColaboradorAvulso($pv2, cid, c.apelido);
								});
								$pv2.find('#avulso_sugestoes').append($b);
							});
						})
						.catch(function () {
							$('.pagamento-preview').find('#avulso_sugestoes_wrap').addClass('d-none');
						});
				}, 280);
			});

			function atualizaLinhaAvulsoBrl(idx, $tr) {
				const r = window.pagamentosAvulsosRows[idx];
				recalcAvulsoRow(r);
				$tr.find('.avulso-out-btc').val(textoBtcRepasse(r.valor_bitcoin));
				syncHiddenJson();
				esconderResumoPagamento();
			}

			$(document).on('blur', '.pagamento-preview .avulso-brl-btc', function () {
				const $tr = $(this).closest('tr');
				const idx = parseInt($tr.attr('data-idx'), 10);
				const n = pagamentosBrlDisplayToNumber($(this).val());
				if (!isNaN(n)) {
					$(this).val(pagamentosFormatBrl(n));
				}
				window.pagamentosAvulsosRows[idx].valor_btc_brl = !isNaN(n) ? n : 0;
				atualizaLinhaAvulsoBrl(idx, $tr);
			});

			$(document).on('input', '.pagamento-preview .avulso-brl-btc', function () {
				const $tr = $(this).closest('tr');
				const idx = parseInt($tr.attr('data-idx'), 10);
				const n = pagamentosBrlDisplayToNumber($(this).val());
				window.pagamentosAvulsosRows[idx].valor_btc_brl = !isNaN(n) ? n : 0;
				atualizaLinhaAvulsoBrl(idx, $tr);
			});

			$(document).on('blur', '.pagamento-preview .avulso-brl-qtd', function () {
				const $tr = $(this).closest('tr');
				const idx = parseInt($tr.attr('data-idx'), 10);
				const n = pagamentosBrlDisplayToNumber($(this).val());
				if (!isNaN(n)) {
					$(this).val(pagamentosFormatBrl(n));
				}
				window.pagamentosAvulsosRows[idx].quantidade_reais = !isNaN(n) ? n : 0;
				atualizaLinhaAvulsoBrl(idx, $tr);
			});

			$(document).on('input', '.pagamento-preview .avulso-brl-qtd', function () {
				const $tr = $(this).closest('tr');
				const idx = parseInt($tr.attr('data-idx'), 10);
				const n = pagamentosBrlDisplayToNumber($(this).val());
				window.pagamentosAvulsosRows[idx].quantidade_reais = !isNaN(n) ? n : 0;
				atualizaLinhaAvulsoBrl(idx, $tr);
			});

			$(document).on('click', '.pagamento-preview .avulso-remover', function () {
				const idx = parseInt($(this).closest('tr').attr('data-idx'), 10);
				window.pagamentosAvulsosRows.splice(idx, 1);
				renderAvulsosTable();
			});

			$(document).on('click', function (e) {
				if (!$(e.target).closest('#avulso_busca_apelido, #avulso_sugestoes_wrap').length) {
					$('.pagamento-preview').find('#avulso_sugestoes_wrap').addClass('d-none');
				}
			});
		})();

	<?php endif; ?>

		$(document).on('click', '#btn_finalizar_repasse', function () {
			const $btn = $(this);
			const $pv = $('.pagamento-preview');
			const $w = $pv.find('#electrum_reveal_wrapper');
			if (!$w.length) {
				if (typeof popMessage === 'function') {
					popMessage('ATENÇÃO', 'Gere o preview do pagamento antes (Buscar artigos pendentes ou Mostrar detalhes do pagamento).', TOAST_STATUS.DANGER);
				}
				return;
			}
			const previewUrl = $btn.data('preview-url');
			const formEl = document.getElementById('pagamentos_form');
			if (!previewUrl || !formEl) {
				$w.removeClass('d-none');
				return;
			}
			if (typeof window.pagamentosAvulsosSyncHidden === 'function') {
				window.pagamentosAvulsosSyncHidden();
			}
			const formData = new FormData(formEl);
			$btn.prop('disabled', true);
			$('#modal-loading').show();
			$.ajax({
				url: previewUrl,
				method: 'POST',
				processData: false,
				contentType: false,
				data: formData,
				cache: false,
				dataType: 'html',
				complete: function () {
					$('#modal-loading').hide();
					$btn.prop('disabled', false);
				},
				success: function (retorno) {
					const wrap = document.createElement('div');
					wrap.innerHTML = retorno;
					const taNew = wrap.querySelector('#repasse_electrum');
					const $ta = $pv.find('#repasse_electrum');
					if ($ta.length && taNew) {
						$ta.val(taNew.value);
					}
					$w.removeClass('d-none');
				},
				error: function () {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Não foi possível atualizar o resumo de pagamento. Verifique a conexão e tente de novo.', TOAST_STATUS.DANGER);
					}
				}
			});
		});

		$(document).on('click', '#btn-copiar-repasse-electrum', function () {
			var $ta = $('.pagamento-preview').find('#repasse_electrum');
			if (!$ta.length) {
				$ta = $('#repasse_electrum');
			}
			var valor = ($ta.val() || '').toString();
			if (!valor.trim()) {
				if (typeof popMessage === 'function') {
					popMessage('ATENÇÃO', 'Não há resumo de pagamento para copiar.', TOAST_STATUS.DANGER);
				}
				return;
			}
			// BOM UTF-8: Excel no Windows reconhece acentos ao colar
			var paraExcel = '\uFEFF' + valor;
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(paraExcel).then(function () {
					if (typeof popMessage === 'function') {
						popMessage('Sucesso!', 'Resumo copiado (colunas separadas por tabulação, pronto para colar no Excel).', TOAST_STATUS.SUCCESS);
					}
				}).catch(function () {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Não foi possível copiar o resumo.', TOAST_STATUS.DANGER);
					}
				});
				return;
			}
			var $tmp = $('<textarea>', { readonly: true }).css({ position: 'fixed', left: '-9999px', top: '0', opacity: '0' }).appendTo('body');
			$tmp.val(paraExcel);
			$tmp[0].select();
			try {
				document.execCommand('copy');
				if (typeof popMessage === 'function') {
					popMessage('Sucesso!', 'Resumo copiado (colunas separadas por tabulação, pronto para colar no Excel).', TOAST_STATUS.SUCCESS);
				}
			} catch (e) {
				if (typeof popMessage === 'function') {
					popMessage('ATENÇÃO', 'Não foi possível copiar o resumo.', TOAST_STATUS.DANGER);
				}
			}
			$tmp.remove();
		});

</script>

<?= $this->endSection(); ?>