<?php

use CodeIgniter\I18n\Time;
?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0"><?= $titulo; ?></h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<div class="d-flex justify-content-center mb-5 text-left">
		<form class="needs-validation w-50" novalidate="yes" method="post" id="pautas_form">
			<div class="mb-1">
				<label class="mb-0"><small>Apelido</small></label>
				<div class="input-group">				
					<input type="text" class="form-control form-control-sm" disabled value="<?=$colaboradores['apelido']; ?>">
				</div>
			</div>
			<div class="mb-1">
				<label class="mb-0"><small>Email</small></label>
				<div class="input-group">				
					<input type="text" class="form-control form-control-sm" disabled value="<?=$colaboradores['email']; ?>">
				</div>
			</div>
			<div class="mb-1">
				<label class="mb-0"><small>Carteira</small></label>
				<div class="input-group">				
					<input type="text" class="form-control form-control-sm" disabled value="<?=$colaboradores['carteira']; ?>">
				</div>
			</div>
			<div class="mb-1">
				<label class="mb-0"><small>Email confirmado em</small></label>
				<div class="input-group">				
					<input type="text" class="form-control form-control-sm" disabled value="<?= Time::createFromFormat('Y-m-d H:i:s', $colaboradores['confirmado_data'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>">
				</div>
			</div>
			<div class="mb-1">
				<label class="mb-0"><small>Criado em</small></label>
				<div class="input-group">				
					<input type="text" class="form-control form-control-sm" disabled value="<?= Time::createFromFormat('Y-m-d H:i:s', $colaboradores['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>">
				</div>
			</div>
			<div class="mb-1">
				<label class="mb-0"><small>Última Atualização</small></label>
				<div class="input-group">				
					<input type="text" class="form-control form-control-sm" disabled value="<?= Time::createFromFormat('Y-m-d H:i:s', $colaboradores['atualizado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>">
				</div>
			</div>
			<?php if($colaboradores['excluido'] != NULL): ?>
				<div class="mb-1">
					<label class="mb-0"><small>Excluído em</small></label>
					<div class="input-group">				
						<input type="text" class="form-control form-control-sm" disabled value="<?= Time::createFromFormat('Y-m-d H:i:s', $colaboradores['excluido'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>">
					</div>
				</div>
			<?php endif; ?>
			<div class="mb-3">
				<label class="mb-0"><small>Atribuições</small></label>
				<div class="d-flex flex-wrap m-n1">
					<?php foreach ($atribuicoes as $atribuicao) : ?>
						<div class="btn-group-toggle p-1" data-toggle="buttons">
							<label class="atribuicoes btn btn-secondary btn-sm vl-bg-c">
								<input value="<?= $atribuicao['id']; ?>" name="atribuicoes[<?= $atribuicao['id']; ?>]" type="checkbox" <?= in_array($atribuicao['id'], $colaboradores_atribuicoes) ? ('checked') : (''); ?>> <?= $atribuicao['nome']; ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</form>
	</div>
</div>

<script>

$('.atribuicoes').on('click', function (e) { 
		$('.mensagem').hide();
		form = new FormData(pautas_form);
		form.append('colaborador_id', <?=$colaboradores['id']?>);

		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/permissoes'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			success: function (retorno) {
				if (retorno.status) {
					$('.mensagem').removeClass('bg-danger');
					$('.mensagem').addClass('bg-success');
				} else {
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
				}
				$('.mensagem').html(retorno.mensagem);
				$('.mensagem').show();
			}
		});

});
</script>

<?= $this->endSection(); ?>