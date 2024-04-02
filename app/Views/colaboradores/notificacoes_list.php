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
				<div class="card bg-light mb-4">
					<!-- card body -->
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<h5 class="mb-0">Hoje</h5>
							</div>
						</div>
					</div>
				</div>
				<!-- card -->
				<div class="card mb-2">
					<!-- list group -->
					<ul class="list-group list-group-flush">
						<!-- list group item -->
						<li class="list-group-item p-2">
							<div class="d-flex align-items-center">
								<!-- img -->
								<div class="mr-3">
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Image"
										class="avatar-sm rounded-circle" style="height:auto; width:4.5rem;">
								</div>
								<div class="ms-3">
									<p class="mb-0
								font-weight-medium"><a href="#!">You</a> created
										a task for Development in <a href="#!">Front End
											Developer Team</a></p>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>