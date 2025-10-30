<?php
use CodeIgniter\I18n\Time;

/*
Variáveis:
dados = {
imagem,
url,
titulo,
autor,
publicacao,
texticulo
}
*/
?>

<div class="card shadow-0 col-sm-6 col-lg-3">
    <div class="bg-image hover-zoom rounded-3">
        <?php if ($dados['tipo_conteudo'] == 'pauta'): ?>
            <img class="w-100 object-fit-cover" src="<?= $dados['imagem']; ?>">
        <?php endif; ?>
    </div>
    <div class="card-body p-2">
        <h5 class="card-title fw-bold"><a class="btn-link h5" <?php if ($dados['tipo_conteudo'] == 'artigo'): ?>
                    href="<?= base_url() . 'site/artigo/' . $dados['url']; ?>">
                <?php elseif ($dados['tipo_conteudo'] == 'pauta'): ?>
                    href="<?= base_url() . 'colaboradores/pautas/detalhamento/' . $dados['id']; ?>">
                <?php endif; ?>
                <?= $dados['titulo']; ?></a>
        </h5>
        <div>
            <small>
                <ul class="nav nav-divider">
                    <li class="nav-item pointer">
                        <div class="d-flex text-muted">
                            <span class="">Por <a
                                    href="<?= site_url('site/escritor/'); ?><?= urlencode($dados['autor']); ?>"
                                    class="text-muted btn-link"><?= $dados['autor']; ?></a></span>
                        </div>
                    </li>
                    <li class="nav-item pointer text-muted">
                        <?= Time::createFromFormat('Y-m-d H:i:s', $dados['publicacao'])->toLocalizedString('dd MMM yyyy'); ?>
                    </li>
                </ul>
            </small>
            <p class="">
                <?= $dados['texticulo']; ?>
            </p>
        </div>
    </div>
</div>