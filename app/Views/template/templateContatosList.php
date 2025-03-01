<?php use CodeIgniter\I18n\Time; ?>
<?php helper('data') ?>
<?php if ($contatosList['contatos'] !== NULL && !empty($contatosList['contatos'])): ?>
	<table class="table align-middle p-4 mb-0 mt-2 table-hover table-shrink">
		<!-- Table head -->
		<thead class="table-dark">
			<tr>
				<th scope="col" class="border-0 rounded-start">Assunto</th>
				<th scope="col" class="border-0">E-mail</th>
				<th scope="col" class="border-0">Descrição</th>
				<th scope="col" class="border-0">Cadastrado</th>
				<th scope="col" class="border-0 rounded-end"></th>
			</tr>
		</thead>
		<!-- Table body START -->
		<tbody>
			<?php foreach ($contatosList['contatos'] as $contato): ?>
				<tr>
					<th scope="row">
						<?= $contato['assunto']; ?>
					</th>
					<th>
						<?= $contato['email']; ?>
						<?= ($contato['apelido'] !== NULL) ? ('<span class="badge bg-success bg-opacity-10 text-secondary">Usuário cadastrado</span>') : ('<span class="badge bg-secondary bg-opacity-10 text-secondary">Usuário não cadastrado</span>'); ?>
					</th>
					<td>
						<?= $contato['descricao']; ?>
					</td>
					<td>
						<?= Time::createFromFormat('Y-m-d H:i:s', $contato['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
					</td>
					<td>
						<div class="d-flex justify-content-center">
							<a href="<?= site_url('colaboradores/admin/contato/') . $contato['id']; ?>"
								class="btn btn-light btn-floating mb-0 btn-tooltip pr-2" data-toggle="tooltip"
								data-placement="top" title="Responder"><i class="fas fa-pencil"></i></a>
							<a class="btn btn-light btn-floating mb-0 btn-tooltip btn-excluir" data-toggle="tooltip"
								data-placement="top" title="Excluir" data-contatos-id="<?= $contato['id']; ?>"><i
									class="fas fa-trash-can"></i></a>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<!-- Table body END -->
	</table>
<?php else: ?>
	<div class="col-12 text-center mt-4">
		Não foi retornado nenhum contato.
	</div>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($contatosList['pager']): ?>
		<?= $contatosList['pager']->simpleLinks('contatos', 'default_template') ?>
	<?php endif; ?>

	<script>
		$(function () {
			$('.btn-tooltip').tooltip();
		});

		$(document).ready(function () {
			$('.page-link ').on('click', function (e) {
				e.preventDefault();
				$.ajax({
					url: e.target.href,
					type: 'get',
					dataType: 'html',
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (data) {
						$('.contatos-list').html(data);
					}
				});
			});
		});

		$(document).ready(function () {
			$("#modal-btn-si").on("click", function () {
				$("#mi-modal").modal('hide');
				$.ajax({
					url: "<?php echo base_url('colaboradores/admin/contatosExcluir/'); ?>" + contatosId,
					type: 'get',
					dataType: 'json',
					data: {
					},
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$(".btn-submeter").trigger("click");
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
						artigoId = null;
					}
				});
				return false;
			});
		});

		$(".btn-excluir").on("click", function (e) {
			$('.conteudo-modal').html('Deseja realmente descartar este artigo?');
			contatosId = $(e.currentTarget).attr('data-contatos-id');
			$("#mi-modal").modal('show');
		});
	</script>