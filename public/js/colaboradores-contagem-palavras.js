/**
 * Contagem de palavras no painel — mesma regra do helper PHP (via endpoint).
 * Config: window.VL_CONTAGEM_PALAVRAS
 */
(function ($) {
	'use strict';

	var timer = null;
	var seq = 0;
	var inited = false;

	function conf() {
		return window.VL_CONTAGEM_PALAVRAS || {};
	}

	function getTexto(c) {
		if (c.bindQuillWindowName && typeof window[c.bindQuillWindowName] !== 'undefined') {
			var q = window[c.bindQuillWindowName];
			if (q && typeof q.getText === 'function') {
				return q.getText(0, q.getLength()) || '';
			}
		}
		var sel = c.textareaSelector || '#texto';
		var $el = $(sel);
		return $el.length ? String($el.val() || '') : '';
	}

	function mensagemPlural(n) {
		return n === 1 ? '1 palavra' : String(n) + ' palavras';
	}

	function aplicarLimites(n) {
		var c = conf();
		if (c.minPalavras == null && c.maxPalavras == null) {
			return;
		}
		if (!c.submitSelector) {
			return;
		}
		var ok = true;
		if (c.minPalavras != null && n < c.minPalavras) {
			ok = false;
		}
		if (c.maxPalavras != null && n > c.maxPalavras) {
			ok = false;
		}
		$(c.submitSelector).prop('disabled', !ok);
	}

	function atualizarServidor() {
		var c = conf();
		if (!c.endpoint) {
			return;
		}
		var texto = getTexto(c);
		var mySeq = ++seq;
		$.ajax({
			url: c.endpoint,
			method: 'POST',
			data: { texto: texto },
			dataType: 'json',
			success: function (ret) {
				if (mySeq !== seq) {
					return;
				}
				if (!ret || ret.status !== true) {
					return;
				}
				var n = parseInt(ret.palavras, 10);
				if (isNaN(n)) {
					n = 0;
				}
				if (c.outputSelector) {
					$(c.outputSelector).text(mensagemPlural(n));
				}
				aplicarLimites(n);
			},
		});
	}

	function agendar() {
		var c = conf();
		clearTimeout(timer);
		timer = setTimeout(atualizarServidor, c.debounceMs || 200);
	}

	function init() {
		if (inited) {
			return;
		}
		var c = conf();
		if (!c.endpoint) {
			return;
		}
		inited = true;

		if (c.bindQuillWindowName && typeof window[c.bindQuillWindowName] !== 'undefined') {
			var q = window[c.bindQuillWindowName];
			if (q && typeof q.on === 'function') {
				q.on('text-change', agendar);
			}
		} else if (c.textareaSelector) {
			$(document).on('input', c.textareaSelector, agendar);
		}

		atualizarServidor();
	}

	window.VL_contagemPalavrasAtualizar = function () {
		clearTimeout(timer);
		atualizarServidor();
	};

	window.VL_CONTAGEM_PALAVRAS_INIT = init;
})(jQuery);
