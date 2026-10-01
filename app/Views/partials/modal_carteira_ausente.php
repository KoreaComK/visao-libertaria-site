<?php
$urlPerfilCarteira = site_url('colaboradores/perfil') . '#painel-perfil';
?>
<div class="modal fade" id="modal-carteira-ausente" tabindex="-1" role="dialog"
	aria-labelledby="modal-carteira-ausente-label" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-carteira-ausente-label">Carteira Bitcoin não cadastrada</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<p class="mb-0">A carteira Bitcoin não foi cadastrada. É preciso cadastrá-la.</p>
			</div>
			<div class="modal-footer">
				<a class="btn btn-primary" id="btn-cadastrar-carteira" href="<?= esc($urlPerfilCarteira, 'attr'); ?>">Cadastrar carteira</a>
			</div>
		</div>
	</div>
</div>

<script>
	(function () {
		if (window.mostrarModalCarteiraAusente) {
			return;
		}

		var urlPerfilCarteira = <?= json_encode($urlPerfilCarteira) ?>;

		function abrirAbaPerfilCarteira() {
			var tabPerfil = document.getElementById('tab-perfil');
			if (!tabPerfil || !window.bootstrap || !bootstrap.Tab) {
				window.location.href = urlPerfilCarteira;
				return;
			}

			bootstrap.Tab.getOrCreateInstance(tabPerfil).show();
			var campo = document.getElementById('carteira');
			if (campo) {
				campo.focus();
			}
			if (window.history && history.replaceState) {
				history.replaceState(null, '', '#painel-perfil');
			}
		}

		window.mostrarModalCarteiraAusente = function () {
			var modalEl = document.getElementById('modal-carteira-ausente');
			if (modalEl && window.bootstrap && bootstrap.Modal) {
				bootstrap.Modal.getOrCreateInstance(modalEl).show();
			}
		};

		var botao = document.getElementById('btn-cadastrar-carteira');
		if (!botao) {
			return;
		}

		botao.addEventListener('click', function (e) {
			if (!document.getElementById('tab-perfil')) {
				return;
			}

			e.preventDefault();
			var modalEl = document.getElementById('modal-carteira-ausente');
			if (modalEl && window.bootstrap && bootstrap.Modal) {
				modalEl.addEventListener('hidden.bs.modal', abrirAbaPerfilCarteira, { once: true });
				bootstrap.Modal.getOrCreateInstance(modalEl).hide();
				return;
			}

			abrirAbaPerfilCarteira();
		});
	})();
</script>
