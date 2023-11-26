<?php

use CodeIgniter\I18n\Time;
?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0"><?= $titulo; ?></h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center <?= (!isset($retorno)) ? ('collapse') : (''); ?> <?= (isset($retorno) && $retorno['status'] === true) ? ('bg-success') : ('bg-danger'); ?> col-12"><?= (isset($retorno)) ? ($retorno['mensagem']) : (''); ?></div>
	<div class="mb-5 text-left">
	<?php if ($fase_producao == '1') : ?>
		<?= $config['artigo_regras_escrever']; ?>
	<?php endif; ?>
	<?php if ($fase_producao == '2') : ?>
		<?= $config['artigo_regras_revisar']; ?>
	<?php endif; ?>
	</div>

	<div class="d-flex justify-content-center mb-5 text-left">
		<form class="needs-validation w-100" novalidate="yes" method="post">
			<?php if (!empty($pauta)) : ?>
				<div class="mb-3">
					<label>
						<?= $pauta['titulo']; ?>
					</label> - <a href="<?= $pauta['link']; ?>" target="_blank">Link da Notícia</a>
				</div>
			<?php else : ?>
				<div class="mb-3">
					<label for="username">Link da Notícia</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
									<path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
									<path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
								</svg></span>
						</div>
						<input type="text" class="form-control" id="link" placeholder="Link da notícia para pauta" name="link" value="<?= (isset($artigo['link'])) ? ($artigo['link']) : (''); ?>">
					</div>
				</div>
			<?php endif; ?>

			<div class="mb-3">
				<label for="titulo">Título do Artigo</label>
				<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título do artigo" value="<?= str_replace('"',"'",$artigo['titulo']); ?>">
			</div>

			<div class="mb-3">
				<label for="gancho">Gancho <span class="text-muted">Texto curto antes da vinheta. Máx. 100
						palavras</span></label>
				<input type="text" class="form-control" id="gancho" name="gancho" value="<?= str_replace('"',"'",$artigo['gancho']); ?>" placeholder="Gancho do artigo">
			</div>

			<div class="mb-3">
				<label for="address">Link Imagem</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
								<path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
								<path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
							</svg></span>
					</div>
					<input type="text" class="form-control" id="imagem" name="imagem" value="<?= $artigo['imagem']; ?>" placeholder="Link da imagem da notícia">
				</div>
			</div>

			<div class="d-flex justify-content-center preview_imagem_div mb-3 collapse">
				<image class="img-thumbnail" src="" data-toggle="tooltip" data-placement="top" id="preview_imagem" title="Preview da Imagem da Pauta" style="max-height: 200px;" />
			</div>

			<!-- <div class="mb-3">
				<label for="imagem">Categorias</label>
				<div class="d-flex flex-wrap m-n1">
					<?php //foreach ($categorias as $categoria) : ?>
						<div class="btn-group-toggle p-1" data-toggle="buttons">
							<label class="btn btn-secondary vl-bg-c">
								<input id="categoria_<? //$categoria['id']; ?>" value="<? //$categoria['id']; ?>" name="categorias[<? //$categoria['id']; ?>]" type="checkbox" <? //in_array($categoria['id'], $categorias_artigo) ? ('checked') : (''); ?>> <? //$categoria['nome']; ?>
							</label>
						</div>
					<?php //endforeach; ?>
				</div>
			</div> -->

			<div class="mb-3">
				<label for="address w-100">Texto do artigo (<span class="pull-right label label-default text-muted" id="count_message"></span>) <span class="text-muted">Artigo deve ter entre <?=$config['artigo_tamanho_minimo'];?> e <?=$config['artigo_tamanho_maximo'];?>
						palavras.</span></label>
				<textarea id="texto_original" name="texto_original" class="form-control" rows="20"><?= $artigo['texto_original']; ?></textarea>
			</div>

			<div class="mb-3">
				<label for="address w-100">Referências: <span class="text-muted">Todos os links utilizados para dar
						embasamento
						para escrever o artigo, menos a pauta.</span></label>
				<textarea id="referencias" name="referencias" class="form-control" rows="5"><?= $artigo['referencias']; ?></textarea>
			</div>

			<?php if (isset($artigo['id']) && $artigo['id'] !== null) : ?>
				<div class="row">
					<div class="col-12 text-center">
						<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="btn-comentarios" type="button">Atualizar Comentários</button>
					</div>
					<div class="mensagem-comentario p-3 mb-2 rounded text-white text-center col-12"></div>
					<div class="col-12 d-flex justify-content-center">

						<div class="col-12 div-comentarios">
							<div class="col-12">
								<div class="mb-3">
									<input type="hidden" id="id_comentario" name="id_comentario"/>
									<textarea id="comentario" name="comentario" class="form-control" rows="5" placeholder="Digite seu comentário aqui"></textarea>
								</div>
								<div class="mb-3 text-center">
									<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="enviar-comentario" type="button">Enviar comentário</button>
								</div>
							</div>
							<div class="card m-3 div-list-comentarios"></div>
						</div>
					</diV>
				</div>
			<?php endif; ?>

			<?php if ($historico !== NULL && !empty($historico)) : ?>
			<div class="mb-5 text-left col-md-6">
				<h6>Histórico do artigo:</h6>
				<ul class="list-group">
					<?php foreach ($historico as $h) : ?>
						<li class="list-group-item p-1 border-0">
							<small><small><?= $h['apelido']; ?> <?= $h['acao']; ?> 
							<span class="badge badge-pill badge-secondary">
									<?= Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
							</span></small></small>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

			<?php if ($fase_producao == '1') : ?>
				<div class="d-flex justify-content-center">
					<button class="btn btn-primary btn-lg btn-block mb-3 w-50" id="enviar_artigo" type="submit">Enviar
						Artigo</button>
				</div>
			<?php endif; ?>

			<?php if ($fase_producao == '2') : ?>
				<div class="row">
					<div class="atencao pt-2 pb-2 mb-2 rounded text-center col-md-12 col-12 bg-warning collapse"></div>
					<div class="col-12 text-center">
						<button class="btn btn-danger mb-3 col-md-3 reverter" data-toggle="modal" data-target="#modalConfirmacao" id="reverter" type="button">Reverter para Escrita</button>
						<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3 salvar" id="salvar" type="submit">Salvar
							revisão</button>
						<button class="btn btn-success mb-3 col-md-3 continuar" data-toggle="modal" data-target="#modalConfirmacao" id="narrar" <?= (!isset($artigo['texto_revisado']) || $artigo['texto_revisado'] == null) ? ('disabled') : (''); ?> type="button">Enviar para
							Narração</button>
						<button class="btn btn-danger mb-3 col-md-3 descartar" data-toggle="modal" data-target="#modalConfirmacao" id="descartar" type="button">Descartar artigo</button>
					</div>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="modal fade" id="modalConfirmacao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p id="modal-texto"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-left" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary confirmacao-acao">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".reverter").on("click", function() {
		$("#modal-texto").html('Você tem certeza que deseja <bold>reverter</bold> o texto para a fase anterior?');
		$('.confirmacao-acao').removeClass('confirmacao-continuar');
		$('.confirmacao-acao').removeClass('confirmacao-descartar');
		$('.confirmacao-acao').addClass('confirmacao-reverter');
	});

	$(".continuar").on("click", function() {
		$("#modal-texto").html('Você tem certeza que deseja passar o texto para a <bold>próxima</bold> fase de produção?');
		$('.confirmacao-acao').removeClass('confirmacao-descartar');
		$('.confirmacao-acao').removeClass('confirmacao-reverter');
		$('.confirmacao-acao').addClass('confirmacao-continuar');
	});

	$(".descartar").on("click", function() {
		$("#modal-texto").html('Você tem certeza que deseja <bold>descartar</bold> o texto?');
		$('.confirmacao-acao').removeClass('confirmacao-continuar');
		$('.confirmacao-acao').removeClass('confirmacao-reverter');
		$('.confirmacao-acao').addClass('confirmacao-descartar');
	});

	$(".confirmacao-acao").on("click", function() {
		$(this).prop( "disabled", true);
		if ($('.confirmacao-acao').hasClass('confirmacao-descartar')) {
			window.location.href = location.href + '?descartar=true';
		}
		if ($('.confirmacao-acao').hasClass('confirmacao-continuar')) {
			window.location.href = location.href + '?proximo=true';
		}
		if ($('.confirmacao-acao').hasClass('confirmacao-reverter')) {
			window.location.href = location.href + '?anterior=true';
		}
	});

	var enabled = true;

	$('#count_message').html('0 palavra');
	$('#texto_original').keyup(contapalavras);
	$(document).ready(function() {
		contapalavras();
		<?php if ($fase_producao == '2') : ?>
			$('.salvar').attr('disabled', true);
		<?php endif; ?>
	})

	function contapalavras() {
		var texto = $("#texto_original").val().replaceAll('\n', " ");
		var matches = texto.split(" ");
		number = matches.filter(function(word) {
			return word.length > 0;
		}).length;
		var s = "";
		if (number > 1) {
			s = 's'
		} else {
			s = '';
		}
		if (number < <?=$config['artigo_tamanho_minimo'];?> || number > <?=$config['artigo_tamanho_maximo'];?>) {
			$("#enviar_artigo").attr('disabled', true);
			$("#narrar").attr('disabled', true);
			$('.atencao').removeClass('collapse');
			$('.atencao').html('O artigo não está dentro do tamanho permitido.');
		} else {
			if(enabled) {
				$("#narrar").removeAttr('disabled');
			}
			$("#enviar_artigo").removeAttr('disabled');
		}
		$('#count_message').html(number + " palavra" + s)
	}

	$(function() {
		$('[data-toggle="tooltip"]').tooltip()
		$('#preview_imagem').attr('src', $('#imagem').val());
	})

	$('#imagem').change(function() {
		$('#preview_imagem').attr('src', $('#imagem').val());
	});


		$("#btn-comentarios").on("click", function() {
			getComentarios();
		});

		$('#btn-comentarios').trigger('click');

		<?php if (isset($artigo['id'])) : ?>

		function getComentarios() {
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
				method: "GET",
				dataType: "html",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					$('.div-list-comentarios').html(retorno);
				}
			});
		}


		$("#enviar-comentario").on("click", function() {
			form = new FormData();
			form.append('comentario', $('#comentario').val());
			if($('#id_comentario').val()==''){
				form.append('metodo', 'inserir');
			} else {
				form.append('metodo', 'alterar');
				form.append('id_comentario', $('#id_comentario').val());
			}
			

			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					if (retorno.status) {
						getComentarios()
						$('.mensagem-comentario').hide();
						$('#comentario').val('');
						$('#id_comentario').val('');
						$('.mensagem-comentario').removeClass('bg-danger');
					} else {
						$('.mensagem-comentario').show();
						$('.mensagem-comentario').html(retorno.mensagem);
						$('.mensagem-comentario').addClass('bg-danger');
					}
				}
			});
		});

			function excluirComentario(id_comentario)
			{
				form = new FormData();
				form.append('id_comentario', id_comentario);
				form.append('metodo', 'excluir');

				$.ajax({
					url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
					method: "POST",
					data: form,
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function() { $('#modal-loading').modal('show'); },
					complete: function() { $('#modal-loading').modal('hide'); },
					success: function(retorno) {
						if (retorno.status) {
							getComentarios()
							$('mensagem-comentario').hide();
						} else {
							$('mensagem-comentario').show();
							$('mensagem-comentario').html(status.mensagem);
							$('.mensagem-comentario').addClass('bg-danger');
						}
					}
				});
			}
			<?php endif; ?>

	<?php if ($fase_producao == '2') : ?>
		$('input').on('click',function() {
			$('.continuar').attr('disabled', true);
			$('.salvar').removeAttr('disabled');
			$('.atencao').removeClass('collapse');
			$('.atencao').html('Salve o artigo antes de enviar para narração.');
			enabled = false;
		});
		$('textarea').on('click',function() {
			$('.continuar').attr('disabled', true);
			$('.salvar').removeAttr('disabled');
			$('.atencao').removeClass('collapse');
			$('.atencao').html('Salve o artigo antes de enviar para narração.');
			enabled = false;
		});
	<?php endif; ?>
</script>


<?= $this->endSection(); ?>
