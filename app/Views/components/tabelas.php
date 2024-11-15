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
    pesquisa = {
        ajax_default
        url
    }
}
*/
?>

<div class="col-12">
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
                                class="btn bg-transparent border-0 px-4 py-2 position-absolute top-50 end-0 translate-middle-y btn-pesquisar-<?= $dados['cabecalho']['sufixo'] ?>"
                                type="submit"><i class="fas fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="table-responsive border-0 tabela-dados-<?= $dados['cabecalho']['sufixo'] ?>"></div>
        </div>
    </div>
</div>

<script>
    function refreshTable_<?= $dados['cabecalho']['sufixo'] ?>(url) {
        var formPesquisa = $('#tablesearch').serialize();
        <?php if(isset($dados['pesquisa']['ajax_default'])): ?>
            formPesquisa += '&<?= $dados['pesquisa']['ajax_default'] ?>'
        <?php endif; ?>

        var url_ajax = '<?= $dados['pesquisa']['url'] ?>';
        if(url!=false) { url_ajax = url; }
        $.ajax({
            url: url_ajax,
            method: "get",
			data: formPesquisa,
			processData: true,
			cache: false,
			dataType: "html",
            beforeSend: function () { $('#modal-loading').show(); },
            complete: function () { $('#modal-loading').hide() },
            success: function (data) {
                $('.tabela-dados-<?= $dados['cabecalho']['sufixo'] ?>').html(data);
            }
        });
    }

    $(document).ready(function () {
        $('.btn-pesquisar-<?= $dados['cabecalho']['sufixo'] ?>').on('click', function (e) {
            refreshTable_<?= $dados['cabecalho']['sufixo'] ?>(false);
        });
        $(".btn-pesquisar-<?= $dados['cabecalho']['sufixo'] ?>").trigger("click");
    });
</script>