<?php use CodeIgniter\I18n\Time; ?>

<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container d-flex justify-content-center">
		<div class="col-lg-8">
			<h1 class="display-2"><?= $pauta['titulo']; ?></h1>

			<div class="bg-image hover-zoom rounded-6">
				<img class="w-100 img-fluid" height="auto" style="max-height:22rem; object-fit: cover;"
					src="<?= $pauta['imagem'] ?>"></img>
				<div class="card-img-overlay d-flex flex-column p-3 p-sm-4"
					style="background-color: rgba(0, 0, 0, 0.3);">
				</div>
			</div>
			<div class="position-relative mb-3">
				<div class="pt-3 pb-3">
					<!-- <div class="mb-3">
							<?php //foreach($artigo['categorias'] as $categoria): ?>
								<span class="badge vl-bg-c m-1 p-1">
									<a href="<? //base_url() . 'site/artigos/'.$categoria['id']; ?>"><? //$categoria['nome']; ?></a>
								</span>
							<?php //endforeach; ?>
						</div> -->
					<div>
						<div><?= str_replace("\n", '<br/>', $pauta['texto']); ?></div>
					</div>
				</div>
			</div>
			<div class="col-12 mb-3 mt-3">
				<!-- Chart START -->
				<div class="card border">
					<div class="card-body">
						<div class="row">
							<div class="col-12 text-center">
								<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="btn-comentarios"
									type="button">Atualizar
									Comentários</button>
							</div>
							<div class="col-12 d-flex justify-content-center">

								<div class="col-12 div-comentarios">
									<div class="col-12">
										<div class="mb-3">
											<input type="hidden" id="id_comentario" name="id_comentario" />
											<textarea id="comentario" name="comentario" class="form-control" rows="5"
												placeholder="Digite seu comentário aqui"></textarea>
										</div>
										<div class="mb-3 text-center">
											<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3"
												id="enviar-comentario" type="button">Enviar comentário</button>
										</div>
									</div>
									<div class="card m-3 div-list-comentarios"></div>
								</div>
							</diV>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= view('template/colaboradores_comentarios_init', [
	'comentariosConfig' => [
		'endpoint'   => base_url('colaboradores/pautas/comentarios/' . $pauta['id']),
		'autoLoad'   => true,
	],
]); ?>

<?= $this->endSection(); ?>