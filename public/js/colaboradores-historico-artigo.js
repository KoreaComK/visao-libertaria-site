/**
 * Histórico do artigo (listas + modal de texto + opcional reverter no editor).
 * Config: window.VL_HISTORICO_ARTIGO
 */
(function ($) {
	'use strict';

	function conf() {
		return window.VL_HISTORICO_ARTIGO || {};
	}

	function escapeHtmlHistorico(s) {
		const d = document.createElement('div');
		d.textContent = s;
		return d.innerHTML;
	}

	function htmlCorpoHistoricoArtigo(texto) {
		if (texto == null || texto === '') {
			return '';
		}
		const t = String(texto);
		if (/<[a-z][\s\S]*>/i.test(t)) {
			return t;
		}
		return '<p class="mb-0 text-break" style="white-space: pre-wrap;">' + escapeHtmlHistorico(t) + '</p>';
	}

	function htmlReferenciasHistorico(ref) {
		if (ref == null || ref === '') {
			return '';
		}
		const t = String(ref);
		if (/<[a-z][\s\S]*>/i.test(t)) {
			return t;
		}
		return '<p class="mb-0 text-break" style="white-space: pre-wrap;">' + escapeHtmlHistorico(t) + '</p>';
	}

	function preencheModalHistorico(p, historicoTextoId) {
		$('#modal-artigo-titulo').html(p.titulo || '');
		$('#modal-artigo-gancho').html(p.gancho || '');
		$('#modal-artigo-texto').html(htmlCorpoHistoricoArtigo(p.texto));
		$('#modal-artigo-referencias').html(htmlReferenciasHistorico(p.referencias));
		$('.btn-reverter').attr('data-historico-texto-id', historicoTextoId);
	}

	window.atualizaHistorico = function () {
		var u = conf().historicosUrl;
		if (!u) {
			return;
		}
		$.ajax({
			url: u,
			method: 'GET',
			processData: false,
			contentType: false,
			cache: false,
			dataType: 'html',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				$('.lista-historico').html(retorno);
			}
		});
	};

	window.atualizaArtigoHistorico = function () {
		var u = conf().textoHistoricosListUrl;
		if (!u) {
			return;
		}
		$.ajax({
			url: u,
			method: 'GET',
			processData: false,
			contentType: false,
			cache: false,
			dataType: 'html',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				$('.lista-historico-artigo').html(retorno);
			}
		});
	};

	window.mostraHistoricoTexto = function (el) {
		var id = el && el.dataset ? el.dataset.historicoTextoId : null;
		if (!id) {
			return;
		}
		var prefix = conf().textoHistoricoItemUrlPrefix;
		if (!prefix) {
			return;
		}
		var formData = new FormData();
		$.ajax({
			url: prefix + id,
			method: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			cache: false,
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (!retorno.status) {
					if (typeof popMessage === 'function' && typeof TOAST_STATUS !== 'undefined') {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
					return;
				}
				var p = retorno.parametros || null;
				if (!p) {
					if (typeof popMessage === 'function' && typeof TOAST_STATUS !== 'undefined') {
						popMessage('ATENÇÃO', 'Não foi possível carregar o texto histórico deste item.', TOAST_STATUS.DANGER);
					}
					return;
				}
				preencheModalHistorico(p, id);
				if (conf().openModalProgrammatically) {
					var modalHistoricoEl = document.getElementById('modalVerTextoHistorico');
					if (modalHistoricoEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
						bootstrap.Modal.getOrCreateInstance(modalHistoricoEl).show();
					}
				}
			}
		});
	};

	$(function () {
		if (conf().delegarCliqueHistoricoTexto) {
			$(document).off('click.vlHistArt', '.btn-texto-historico').on('click.vlHistArt', '.btn-texto-historico', function () {
				window.mostraHistoricoTexto(this);
			});
		}

		if (conf().bindReverterEditor && typeof window.quill !== 'undefined' && window.quill) {
			$(document).off('click.vlHistRev', '.btn-reverter').on('click.vlHistRev', '.btn-reverter', function (e) {
				var hid = e.currentTarget.dataset.historicoTextoId;
				if (!hid) {
					return;
				}
				var formData = new FormData();
				var prefix = conf().textoHistoricoItemUrlPrefix;
				if (!prefix) {
					return;
				}
				$.ajax({
					url: prefix + hid,
					method: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					cache: false,
					dataType: 'json',
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (retorno) {
						if (!retorno.status) {
							if (typeof popMessage === 'function' && typeof TOAST_STATUS !== 'undefined') {
								popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
							}
							return;
						}
						var parametros = retorno.parametros || null;
						if (!parametros) {
							if (typeof popMessage === 'function' && typeof TOAST_STATUS !== 'undefined') {
								popMessage('ATENÇÃO', 'Não foi possível reverter para o texto histórico selecionado.', TOAST_STATUS.DANGER);
							}
							return;
						}
						$('#titulo').val(parametros.titulo || '');
						$('#gancho').val(parametros.gancho || '');
						$('#referencias').val(parametros.referencias || '');
						window.quill.setText(parametros.texto || '');
					}
				});
				$('#modal-btn-close').trigger('click');
			});
		}
	});
})(jQuery);
