<div class="row">
	<div class="col-12">
		<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
			<ul class="pagination justify-content-center">
				<?php if ($pager->hasPreviousPage()) : ?>
					<li class="page-item">
						<a class="page-link vl-bg-c" href="<?= $pager->getFirst() ?>" aria-label="Primeiro">
							Primeiro
						</a>
					</li>
					<li class="page-item">
						<a class="page-link vl-bg-c" href="<?= $pager->getPreviousPage() ?>" aria-label="Anterior">
							Anterior
						</a>
					</li>
				<?php else: ?>
					<li class="w-25"></li>
				<?php endif ?>


				<?php if ($pager->hasNextPage()) : ?>
					<li class="page-item">
						<a class="page-link vl-bg-c" href="<?= $pager->getNextPage() ?>" aria-label="Próximo">
							Próximo
						</a>
					</li>
					<li class="page-item">
						<a class="page-link vl-bg-c" href="<?= $pager->getLast() ?>" aria-label="Último">
							Último
						</a>
					</li>
					<?php else: ?>
					<li class="w-25"></li>
				<?php endif ?>
			</ul>
		</nav>
	</div>
</div>