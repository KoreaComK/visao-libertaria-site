<?php 
use CodeIgniter\I18n\Time;
?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0"><?= $titulo; ?></h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center <?=(!isset($erros))?('collapse'):('bg-danger');?> col-12"><?=(!isset($erros))?('collapse'):($erros['mensagem']); ?></div>
	<div class="d-flex justify-content-center mb-5 text-left">
		<form class="needs-validation col-12 col-md-12" novalidate="yes" method="post" id="pautas_form">

			<?php if (isset($post['criado'])): ?>
				<div class="mb-3">
					<label>
						<span class="text-muted" target="_blank">Cadastrado dia: <?= Time::createFromFormat('Y-m-d H:i:s', $post['criado'])->toLocalizedString('dd MMMM yyyy'); ?></span>
					</label>
				</div>
			<?php endif; ?>
			<?php if (isset($post['pauta_antiga']) && $post['pauta_antiga']=='S'): ?> 
				<div class="mb-3">
					<label>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" alt="Pauta Antiga" fill="currentColor" class="bi bi-patch-exclamation-fill text-danger" viewBox="0 0 16 16">
							<path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
						</svg>
						<span class="text-muted" target="_blank">Atenção! Esta pauta é antiga.</span>
					</label>
				</div>
			<?php endif; ?>

			<?php if(isset($post) && !isset($readOnly) && isset($post['colaboradores_id']) && $post['colaboradores_id'] == $_SESSION['colaboradores']['id']) : ?>
				<div class="mb-3">
					<button class="btn btn-danger btn-lg btn-block excluir_pauta" type="button">Excluir pauta</button>
				</div>
			<?php endif; ?>

			<?php if(in_array('7',$_SESSION['colaboradores']['permissoes']) && !isset($readOnly)): ?>
			<div class="mb-3 form-check">
				<input class="form-check-input" type="checkbox" value="" id="enviarRedator" <?=(isset($post) && isset($post['redator_colaboradores_id']) && $post['redator_colaboradores_id']!==NULL)?('checked="true"'):(''); ?>>
				<label class="form-check-label" for="enviarRedator">
					Escolher Redator
				</label>
			</div>

			<div class="mb-3 div-redatores  <?=(isset($post) && isset($post['redator_colaboradores_id']) && $post['redator_colaboradores_id']!==NULL)?(''):('d-none'); ?>">
				<label for="username">Redatores</label>
				<div class="input-group">
					<select class="form-control" name="redatores" id="redatores">
						<option value="">Escolha o Redator</option>
						<?php
							if(isset($redatores) && !empty($redatores)) {
								foreach($redatores as $redator) {
						?>
									<option value="<?=$redator['id'];?>" <?=(isset($post) && isset($post['redator_colaboradores_id']) && $post['redator_colaboradores_id']==$redator['id'])?('selected="true"'):(''); ?>><?=$redator['apelido'];?></option>
						<?php
								}
							}
						?>
					</select>
				</div>
			</div>

			<?php endif; ?>
		
			<div class="mb-3">
				<label for="username">Link da Notícia</label> <?php if (isset($post)): ?> <a href="<?= $post['link']; ?>" class="col-md-12 text-muted" target="_blank">Ler notícia original</a><?php endif; ?>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
								fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
								<path
									d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
								<path
									d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
							</svg></span>
					</div>
					<input type="text" class="form-control" id="link" placeholder="Link da notícia para pauta" <?=(isset($post['id']))?('disabled'):(''); ?>
						name="link" onblur="getInformationLink(this.value)" required value="<?=(isset($post))?($post['link']):(''); ?>" <?= (isset($readOnly))?('disabled'):('');?>>
				</div>
			</div>

			<div class="mb-3">
				<label for="titulo">Título</label>
				<input type="hidden" id="pauta_antiga" name="pauta_antiga" value="N"/>
				<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título da pauta" required value="<?=(isset($post))?($post['titulo']):(''); ?>" <?= (isset($readOnly))?('disabled'):('');?>>
			</div>

			<div class="mb-3">
				<label for="address">Texto <span class="text-muted"><?php if(!in_array('7',$_SESSION['colaboradores']['permissoes'])): ?>Máx. <?=$config['pauta_tamanho_maximo']; ?> palavras. Mín. <?=$config['pauta_tamanho_minimo']; ?> palavras.</span><?php endif;?> (<span class="pull-right label label-default text-muted" id="count_message"></span>)</label>
				<textarea class="form-control" name="texto" id="texto" <?php if(in_array('7',$_SESSION['colaboradores']['permissoes'])): ?>rows="10"<?php endif;?> required <?= (isset($readOnly))?('disabled'):('');?>><?=(isset($post))?($post['texto']):((in_array('7',$_SESSION['colaboradores']['permissoes']))?("Importância:\n\n\nContraponto:\n\n\nVisão Libertária:"):('')); ?></textarea>
			</div>

			<div class="mb-3">
				<label for="address">Link da Imagem</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
								fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
								<path
									d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
								<path
									d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
							</svg></span>
					</div>
					<input type="text" class="form-control" id="imagem" name="imagem"
						placeholder="Link da imagem da notícia" required value="<?=(isset($post))?($post['imagem']):(''); ?>" <?= (isset($readOnly))?('disabled'):('');?>>
				</div>
			</div>

			<div class="d-flex justify-content-center preview_imagem_div mb-3 collapse">
				<image class="img-thumbnail" src="" data-toggle="tooltip" data-placement="top" id="preview_imagem"
					title="Preview da Imagem da Pauta" style="max-height: 200px;" />
			</div>

			<?php if (isset($readOnly)) : ?>
				<div class="row">
					<div class="col-12 text-center">
						<button class="btn btn-primary mt-3 mb-3 col-md-6" id="btn-comentarios" type="button">Atualizar Comentários</button>
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
									<button class="btn btn-primary mt-3 col-md-6" id="enviar-comentario" type="button">Enviar comentário</button>
								</div>
							</div>
							<div class="card m-3 div-list-comentarios"></div>
						</div>
					</diV>
				</div>
			<?php endif; ?>

			<?php if(!isset($readOnly)) : ?>
				<button class="btn btn-primary btn-lg btn-block enviar_pauta" type="submit"><?=isset($post)?('Atualizar'):('Sugerir'); ?> pauta</button>
			<?php else: ?>
				<a class="btn btn-primary btn-lg btn-block mb-3 enviar_pauta" href="<?= site_url('colaboradores/pautas'); ?>">Voltar</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<script>

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		$('#preview_imagem').attr('src', $('#imagem').val());
	})

	$('#texto').keyup(contapalavras);

	<?php if(!isset($readOnly)) : ?>

		<?php if(in_array('7',$_SESSION['colaboradores']['permissoes'])): ?>
			$("#enviarRedator").on("change", function(e) {
				if(e.currentTarget.checked) {
					$('.div-redatores').removeClass('d-none');
				} else {
					$('.div-redatores').addClass('d-none');
				}
			});
		<?php endif; ?>

	function getInformationLink(link) {
		redatores = null;
		redatores_checked = false;
		if($('#redatores').val() !== undefined) {
			redatores = $('#redatores').val();
		}
		if($('#enviarRedator').val() !== undefined && $('#enviarRedator').is(':checked')) {
			redatores_checked = true;
		}
		$('#pautas_form').trigger("reset");
		if(redatores !== null) {
			$('#redatores').val(redatores);
		}
		if(redatores_checked === true) {
			$("#enviarRedator").prop( "checked", true );
		}

		link = link.trim();
		link = link.substring(0, 254);
		$('#link').val(link);

		form = new FormData();
		form.append('link_pauta', link);

		if(link == '') {
			return false;
		}

		$.ajax({
			url: "<?= (isset($post['id']))?(site_url('colaboradores/pautas/cadastrar/'.$post['id'])):(site_url('colaboradores/pautas/cadastrar')); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				$('.mensagem').hide();
				$('.mensagem').removeClass('bg-success');
				$('.mensagem').removeClass('bg-alert');
				$('.mensagem').removeClass('bg-danger');
				if (retorno.status) {
					$('#titulo').val(retorno.titulo);
					<?php if(!in_array('7',$_SESSION['colaboradores']['permissoes'])): ?>
						$('#texto').val(retorno.texto);
					<?php endif; ?>
					$('#imagem').val(retorno.imagem);
					$('#preview_imagem').attr('src', retorno.imagem);
					$('.preview_imagem_div').show();
					contapalavras()
					if(retorno.mensagem == null) {
						$('#pauta_antiga').val('N');
					} else {
						$('#pauta_antiga').val('S');
						$('.mensagem').addClass('bg-info');
						$('.mensagem').html(retorno.mensagem);
						$('.mensagem').show();
					}
				} else {
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
					$(".enviar_pauta").prop('disabled', true);
					$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
					$('#preview_imagem').attr('src','<?= base_url('public/assets/imagem-default.png'); ?>');
					$('.mensagem').addClass(retorno.classe);
					$('.mensagem').html(retorno.mensagem);
					$('.mensagem').show();
				}
			}
		});
	}

	$('#imagem').change(function () {
		form = new FormData(pautas_form);
		$.ajax({
			url: "<?= site_url('colaboradores/pautas/verificaImagem'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				$('.mensagem').hide();
				$('.mensagem').removeClass('bg-success');
				$('.mensagem').removeClass('bg-alert');
				$('.mensagem').removeClass('bg-danger');
				if (retorno.status) {
					$('#preview_imagem').attr('src', $('#imagem').val());
				} else {
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
					$(".enviar_pauta").prop('disabled', true);
					$('#preview_imagem').attr('src','<?= base_url('public/assets/imagem-default.png'); ?>');
					$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
					$('.mensagem').addClass(retorno.classe);
					$('.mensagem').html(retorno.mensagem);
					$('.mensagem').show();
				}
			}
		});
	});

		<?php if(isset($post['id'])): ?>

			$(".excluir_pauta").on("click", function(e) {
				e.preventDefault();
				$.ajax({
					url: "<?= site_url('colaboradores/pautas/excluir/'.$post['id']); ?>",
					method: "POST",
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function() { $('#modal-loading').modal('show'); },
					complete: function() { $('#modal-loading').modal('hide'); },
					success: function (retorno) {
						$('.mensagem').hide();
						$('.mensagem').removeClass('bg-success');
						$('.mensagem').removeClass('bg-alert');
						$('.mensagem').removeClass('bg-danger');
						if (retorno.status) {
							window.location.href = '<?= site_url('colaboradores/pautas?status=true'); ?>';
						} else {
							$('.mensagem').removeClass('bg-success');
							$('.mensagem').addClass('bg-danger');
							$(".enviar_pauta").prop('disabled', true);
							$('#preview_imagem').attr('src', '');
							$('.mensagem').addClass(retorno.classe);
							$('.mensagem').html(retorno.mensagem);
							$('.mensagem').show();
						}
					}
				});
			});
		
		<?php endif; ?>

	<?php else: ?>

		$("#btn-comentarios").on("click", function() {
			getComentarios();
		});

		$('#btn-comentarios').trigger('click');

		function getComentarios() {
			$.ajax({
				url: "<?php echo base_url('colaboradores/pautas/comentarios/' . $post['id']); ?>",
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
				url: "<?php echo base_url('colaboradores/pautas/comentarios/' . $post['id']); ?>",
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
				url: "<?php echo base_url('colaboradores/pautas/comentarios/' . $post['id']); ?>",
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

	$(document).ready(function() {
		contapalavras();
	})

	function contapalavras() {
		var texto = $("#texto").val().replaceAll('\n', " ");
		texto = texto.replace(/[0-9]/gi,"");
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
		<?php if(!in_array('7',$_SESSION['colaboradores']['permissoes'])): ?>
			if (number > <?=$config['pauta_tamanho_maximo']; ?> || number < <?=$config['pauta_tamanho_minimo']; ?>) {
				$(".enviar_pauta").prop('disabled', true);
			} else {
				$(".enviar_pauta").prop('disabled', false);
			}
		<?php else: ?>
			$(".enviar_pauta").prop('disabled', false);
		<?php endif; ?>
		$('#count_message').html(number + " palavra" + s)
	}
</script>

<?= $this->endSection(); ?>
