<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto py-5">
	<div class="py-2 px-4 mb-3">
		<h3 class="m-0 mb-3">Exclusão de conta</h3>
		<?php if (!empty($mensagem)): ?>
			<p class="mb-4"><?= esc($mensagem); ?></p>
		<?php else: ?>
			<p class="mb-4">Não foi possível concluir a exclusão. Faça login novamente ou solicite um novo e-mail de confirmação.</p>
		<?php endif; ?>
		<a class="btn btn-primary" href="<?= site_url('site'); ?>">Voltar ao site</a>
	</div>
</div>

<?= $this->endSection(); ?>
