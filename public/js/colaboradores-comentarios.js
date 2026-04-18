/**
 * Comentários (artigos / pautas) — lógica única.
 * Configuração: window.VL_COMENTARIOS (definida antes deste script).
 */
(function ($) {
	'use strict';

	function conf() {
		return window.VL_COMENTARIOS || {};
	}

	function endpointUrl() {
		var c = conf();
		if (c.endpoint) {
			return c.endpoint;
		}
		if (c.endpointPrefix && c.entityIdSelector) {
			var v = $(c.entityIdSelector).val();
			if (v === undefined || v === null || String(v) === '') {
				return '';
			}
			return c.endpointPrefix + v;
		}
		return '';
	}

	function ajaxErrorOption() {
		if (conf().useAjaxError && typeof tratarErroAjax === 'function') {
			return { error: tratarErroAjax };
		}
		return {};
	}

	function getComentarios() {
		var ep = endpointUrl();
		if (!ep) {
			return;
		}
		$.ajax($.extend({
			url: ep,
			method: 'GET',
			dataType: 'html',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				$('.div-list-comentarios').html(retorno);
				var accId = conf().accordionCollapseId;
				if (accId) {
					var temComentarios = $('.div-list-comentarios')
						.find('p[class^="comentario-"], p[class*=" comentario-"]').length > 0;
					var el = document.getElementById(accId);
					if (el && window.bootstrap && bootstrap.Collapse) {
						var col = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
						if (temComentarios) {
							col.show();
						} else {
							col.hide();
						}
					}
				}
			}
		}, ajaxErrorOption()));
	}

	function showAttention(msg) {
		if (conf().useInlineMessage) {
			$('.mensagem-comentario').show().html(msg).addClass('bg-danger');
			return;
		}
		if (typeof popMessage === 'function' && typeof TOAST_STATUS !== 'undefined') {
			popMessage('ATENÇÃO', msg, TOAST_STATUS.DANGER);
		}
	}

	function showSuccessToast(msg) {
		if (typeof popMessage === 'function' && typeof TOAST_STATUS !== 'undefined') {
			popMessage('Sucesso!', msg, TOAST_STATUS.SUCCESS);
		}
	}

	function enviarComentario() {
		var texto = ($('#comentario').val() || '').trim();
		if (texto === '') {
			showAttention('É necessário preencher o comentário antes de enviar.');
			return;
		}
		var form = new FormData();
		form.append('comentario', $('#comentario').val());
		if ($('#id_comentario').val() === '') {
			form.append('metodo', 'inserir');
		} else {
			form.append('metodo', 'alterar');
			form.append('id_comentario', $('#id_comentario').val());
		}
		var ep = endpointUrl();
		$.ajax($.extend({
			url: ep,
			method: 'POST',
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					if (conf().useInlineMessage) {
						$('.mensagem-comentario').hide().removeClass('bg-danger');
					} else {
						showSuccessToast(retorno.mensagem);
					}
					getComentarios();
					$('#comentario').val('');
					$('#id_comentario').val('');
				} else if (conf().useInlineMessage) {
					$('.mensagem-comentario').show().html(retorno.mensagem).addClass('bg-danger');
				} else {
					showAttention(retorno.mensagem);
				}
			}
		}, ajaxErrorOption()));
	}

	function excluirComentario(idComentario) {
		var form = new FormData();
		form.append('id_comentario', idComentario);
		form.append('metodo', 'excluir');
		var ep = endpointUrl();
		$.ajax($.extend({
			url: ep,
			method: 'POST',
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					if ($('#id_comentario').val() === idComentario) {
						$('#id_comentario').val('');
						$('#comentario').val('');
					}
					getComentarios();
					if (conf().useInlineMessage) {
						$('.mensagem-comentario').hide().removeClass('bg-danger');
					} else {
						showSuccessToast(retorno.mensagem);
					}
				} else if (conf().useInlineMessage) {
					$('.mensagem-comentario').show().html(retorno.mensagem).addClass('bg-danger');
				} else {
					showAttention(retorno.mensagem);
				}
			}
		}, ajaxErrorOption()));
	}

	window.getComentarios = getComentarios;
	window.excluirComentario = excluirComentario;

	function bind() {
		$('#btn-comentarios').off('click.vlComentarios').on('click.vlComentarios', function (e) {
			e.preventDefault();
			getComentarios();
		});
		$('#enviar-comentario').off('click.vlComentarios').on('click.vlComentarios', function (e) {
			e.preventDefault();
			enviarComentario();
		});
	}

	$(function () {
		bind();
		if (conf().autoLoad) {
			$('#btn-comentarios').trigger('click');
		}
	});
})(jQuery);
