<?php
use CodeIgniter\I18n\Time;
/*
Variáveis:
dados = {
imagem,
url,
titulo,
publicacao
?class?
?class-img?
?class-div?
}
*/
?>

<div class="card shadow-0 col-12 <?= (isset($dados['class'])) ? ($dados['class']) : (''); ?> mb-3">
    <div class="row g-3">
        <div class="<?= (isset($dados['class-img'])) ? ($dados['class-img']) : ('col-5'); ?>">
        <?php if (isset($dados['link_video_youtube'])): ?>
            <img class="rounded" style="max-width: inherit;" src="<?= 'https://img.youtube.com/vi/'.preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $dados['link_video_youtube']).'/maxresdefault.jpg'; ?>" alt="<?= $dados['titulo']; ?>" alt="">
        <?php else: ?>
            <!-- <img class="rounded" style="max-width: inherit;" src="<?= $dados['imagem'] ?>" alt="<?= $dados['titulo']; ?>" alt=""> -->
        <?php endif; ?>
        </div>
        <div class="<?= (isset($dados['class-div'])) ? ($dados['class-div']) : ('col-7'); ?>">
            <h6 class="m-0">
                <?php if ($dados['tipo_conteudo'] == 'artigo'): ?>
                    <?php if (isset($dados['link_video_youtube'])): ?>
                        <a href="javascript:void(0);" class="btn-link stretched-link text-reset video-thumbnail"
                            data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $dados['link_video_youtube']); ?>">
                    <?php else: ?>
                        <a href="<?= site_url('colaboradores/artigos/detalhamento/' . $dados['id']); ?>"
                            class="btn-link stretched-link text-reset">
                    <?php endif; ?>
                        <?php elseif ($dados['tipo_conteudo'] == 'pauta'): ?>
                            <a href="<?= site_url('colaboradores/pautas/detalhamento/' . $dados['id']); ?>"
                                class="btn-link stretched-link text-reset">
                            <?php endif; ?>
                            <?= $dados['titulo'] ?></a>
            </h6>
            <!-- Card info -->
            <ul class="nav nav-divider align-items-center align-middle mt-1 small">
                <li class="nav-item">
                    <?php if ($dados['publicacao'] != NULL): ?>
                        <?= Time::createFromFormat('Y-m-d H:i:s', $dados['publicacao'])->toLocalizedString('dd MMM yyyy'); ?>
                    <?php else: ?>
                        Não publicado
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</div>