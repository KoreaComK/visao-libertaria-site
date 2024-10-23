<?php
use CodeIgniter\I18n\Time;

/*
Variáveis:
dados = {
    cabecalho = {
        titulo
        ?botao? = {
            show
            label
            url
            target
        }
        ?pesquisa? = {
            tipo = {simples, custom}
            :simples
                campo
        }
    }
}
*/
?>

<div class="col-12">
    <!-- Post list table START -->
    <div class="card border bg-transparent rounded-3">

        <div class="card-header bg-transparent border-bottom p-3">
            <div class="d-sm-flex justify-content-between align-items-center">
                <h5 class="mb-2 mb-sm-0"><?= $dados['cabecalho']['titulo']; ?></h5>
                <?php if (isset($dados['cabecalho']['botao']) && $dados['cabecalho']['botao']['show'] == true): ?>
                    <a href="<?= $dados['cabecalho']['botao']['url']; ?>" target="<?= $dados['cabecalho']['botao']['target'] ?>"
                        class="btn btn-sm btn-primary mb-0">
                        <?= $dados['cabecalho']['botao']['label'] ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row g-3 align-items-center justify-content-between mb-3" data-np-autofill-form-type="other"
                data-np-checked="1" data-np-watching="1">
                <?php if (isset($dados['cabecalho']['pesquisa']) && $dados['cabecalho']['pesquisa']['tipo']=='simples'): ?>
                    <div class="col-md-12">
                        <form name="tablesearch" id="tablesearch" class="rounded position-relative"
                            data-np-autofill-form-type="other" data-np-checked="1" data-np-watching="1">
                            <input class="form-control pe-5 bg-transparent" type="text" id="<?= $dados['cabecalho']['pesquisa']['campo'] ?>"
                                name="<?= $dados['cabecalho']['pesquisa']['campo'] ?>" placeholder="Pesquisar" aria-label="Pesquisar">
                            <button
                                class="btn bg-transparent border-0 px-4 py-2 position-absolute top-50 end-0 translate-middle-y btn-pesquisar-publicado"
                                type="submit"><i class="fas fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="table-responsive border-0 tabela-publicado">

            </div>
        </div>
    </div>
</div>

<script>
    function refreshListPublicado(url) {
        form = new FormData(tablesearch);
        <?php if (isset($dados['cabecalho']['pesquisa']) && isset($dados['cabecalho']['pesquisa']['ajax_default'])): ?>
            <?php foreach($dados['cabecalho']['pesquisa']['ajax_default'] as $chave => $dado): ?>
                form.append('<?=$chave;?>','<?=$dado;?>');
            <?php endforeach; ?>
        <?php endif; ?>
        $.ajax({
            url: '<?php echo base_url('colaboradores/artigos/meusArtigosList'); ?>',
            method: "GET",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
            beforeSend: function () { $('#modal-loading').show(); },
            complete: function () { $('#modal-loading').hide() },
            success: function (data) {
                $('.tabela-publicado').html(data);
            }
        });
    }

    $(document).ready(function () {
        $('.btn-pesquisar-producao').on('click', function (e) {
            refreshListProducao();
        });
        $('.select-pesquisa').on('change', function (e) {
            refreshListProducao();
        });
        $('.btn-pesquisar-publicado').on('click', function (e) {
            refreshListPublicado(false);
        });
        $(".btn-pesquisar-producao").trigger("click");
        $(".btn-pesquisar-publicado").trigger("click");
    });
</script>