<?php
use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container w-auto">

	<!-- page content -->
	<div class="my-3 p-3 bg-white rounded box-shadow">
		<div class="row">
			<div class="offset-lg-1 col-lg-10 col-md-12 col-12 mb-4">

				<?php if ($notificacoes !== NULL && !empty($notificacoes)): ?>
					<?php foreach ($notificacoes as $n): ?>

						<div class="card mb-2">
							<!-- list group -->
							<ul class="list-group list-group-flush <?=($n['data_visualizado']==null)?('border border-primary'):('');?>">
								<!-- list group item -->
								<li class="list-group-item p-2">
									<div class="d-flex align-items-center">
										<!-- img -->
										<div class="mr-3">
											<img src="<?= ($n['avatar'] != NULL) ? ($n['avatar']) : (site_url('public/assets/avatar-default.png')); ?>"
												alt="Image" class="avatar-sm rounded-circle" style="height:auto; width:4.5rem;">
										</div>
										<div class="ms-3">
											<p class="mb-0 font-weight-medium col-12 col-md-12">
												<?= ($n['sujeito_colaboradores_id'] == $_SESSION['colaboradores']['id']) ? ('Você ') : ($n['apelido']); ?>
												<?= $n['acao']; ?>
												<?php if ($n['objeto'] == 'pautas'): ?>
													<?= str_replace('{link}', '<a href="' . base_url("colaboradores/pautas/detalhe/" . $n["id_objeto"]) . '">', str_replace('{/link}', '</a>', $n['notificacao'])); ?>
												<?php elseif ($n['objeto'] == 'artigos'): ?>
													<?= str_replace('{link}', '<a href="' . base_url("colaboradores/artigos/detalhe/" . $n["id_objeto"]) . '">', str_replace('{/link}', '</a>', $n['notificacao'])); ?>
												<?php endif; ?>
												<?= $n['tempo']; ?>
											</p>
										</div>
									</div>
								</li>
							</ul>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="card mb-2">
						<!-- list group -->
						<ul class="list-group list-group-flush">
							<!-- list group item -->
							<li class="list-group-item p-2">
								<div class="d-flex align-items-center">
									<!-- img -->
									<div class="ms-3 col-12 col-md-12">
										<p class="mb-0 font-weight-medium text-center">
											Não há notificações para o seu usuário.
										</p>
									</div>
								</div>
							</li>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>