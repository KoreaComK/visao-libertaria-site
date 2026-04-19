<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/_main'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
   integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"></script>

<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>

<div class="container-fluid py-3 vl-site-videos">
   <div class="container">

      <section class="pt-4 pb-4 margin-top-ultra">
         <div class="row">
            <div class="col-12">
               <nav class="custom-breadcrumb" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item">
                        <a href="<?= site_url(); ?>">
                           <i class="bi bi-house-fill pe-1"></i>Home
                        </a>
                     </li>
                     <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-play-circle-fill pe-1"></i>Vídeos
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </section>

      <!-- Filtros por projeto -->
      <div class="mb-4">
         <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('site/videos'); ?>"
               class="gen-button <?= !isset($projeto_atual) ? '' : 'gen-button-outline'; ?>">
               <div class="gen-button-block">
                  <span class="gen-button-line-left"></span>
                  <span class="gen-button-text">Todos os Projetos</span>
               </div>
            </a>
            <?php if (isset($projetos) && is_array($projetos)): ?>
               <?php foreach ($projetos as $proj): ?>
                  <a href="<?= site_url('site/videos/' . urlencode($proj['nome'])); ?>"
                     class="gen-button <?= (isset($projeto_atual) && $projeto_atual === $proj['nome']) ? '' : 'gen-button-outline'; ?>">
                     <div class="gen-button-block">
                        <span class="gen-button-line-left"></span>
                        <span class="gen-button-text"><?= esc($proj['nome']); ?></span>
                     </div>
                  </a>
               <?php endforeach; ?>
            <?php endif; ?>
         </div>
      </div>

      <div class="list-videos row">
         <?php if (isset($videosList['videos']) && is_array($videosList['videos'])): ?>
            <?php foreach ($videosList['videos'] as $video): ?>
               <div class="col-lg-3 col-md-4 col-sm-6 mb-4 video-item">
                  <div class="card video-card h-100">
                     <div class="video-thumbnail">
                        <img src="<?= cria_url_thumb($video['video_id']); ?>" alt="<?= esc($video['titulo']); ?>"
                           class="card-img-top">
                        <div class="play-overlay">
                           <i class="bi bi-play-circle-fill play-icon"></i>
                           <a href="<?= cria_link_watch($video['video_id']); ?>" class="gen-video-popup"
                              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></a>
                        </div>
                        <?php if (!isset($projeto_atual)): ?>
                           <div class="project-badge">
                              <?= esc($video['projeto_nome'] ?? 'Projeto'); ?>
                           </div>
                        <?php endif; ?>
                     </div>
                     <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?= esc($video['titulo']); ?></h6>
                        <p class="card-text text-muted small">
                           <?= Time::parse($video['publicado'])->toLocalizedString('dd/MM/yyyy'); ?>
                        </p>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         <?php else: ?>
            <div class="col-12 text-center">
               <p class="text-muted">Nenhum vídeo encontrado.</p>
            </div>
         <?php endif; ?>
      </div>

      <!-- Paginação (escondida para infinite scroll) -->
      <?php if (isset($videosList['pager'])): ?>
         <div class="d-none">
            <?= $videosList['pager']->links('videos', 'default_template') ?>
         </div>
      <?php endif; ?>

      <div class="page-load-status">
         <div class="infinite-scroll-request">
            <div class="spinner-border" role="status">
               <span class="visually-hidden">Carregando...</span>
            </div>
         </div>
         <p class="infinite-scroll-last">Fim do conteúdo</p>
         <p class="infinite-scroll-error">Erro ao carregar</p>
      </div>
   </div>
</div>

<script>
   $(document).ready(function () {
      // Inicializar Masonry
      var $grid = $('.list-videos').masonry({
         itemSelector: '.video-item'
      });

      // Inicializar Infinite Scroll
      $grid.infiniteScroll({
         path: '?page={{#}}',
         append: '.video-item',
         history: false,
         outlayer: $grid.data('masonry'),
         status: '.page-load-status',
         scrollThreshold: 100
      });

      // Configurar o carregamento de novos vídeos
      $grid.on('load.infiniteScroll', function (event, response) {
         if (response && response.html) {
            var $newItems = $(response.html);
            $grid.append($newItems).masonry('appended', $newItems);

            // Aguardar o Masonry terminar de posicionar os itens
            $grid.one('layoutComplete', function () {
               // Reinicializar Magnific Popup nos novos vídeos com a mesma configuração
               var $popupLinks = $newItems.find('.gen-video-popup');

               $popupLinks.magnificPopup({
                  type: 'iframe',
                  mainClass: 'mfp-fade',
                  removalDelay: 160,
                  preloader: false,
                  fixedContentPos: false
               });
            });
         }
      });

      // Magnific Popup para vídeo
      $('.gen-video-popup').magnificPopup({
         type: 'iframe',
         mainClass: 'mfp-fade',
         removalDelay: 160,
         preloader: false,
         fixedContentPos: false
      });

      // Backup: Evento de clique global para garantir funcionamento
      $(document).on('click', '.gen-video-popup', function (e) {
         e.preventDefault();
         var url = $(this).attr('href');

         if (url && url.includes('youtube.com/watch?v=')) {
            var videoId = url.split('v=')[1];
            if (videoId) {
               var embedUrl = 'https://www.youtube.com/watch?v=' + videoId + '?autoplay=1';

               $.magnificPopup.open({
                  items: {
                     src: embedUrl
                  },
                  type: 'iframe',
                  mainClass: 'mfp-fade',
                  removalDelay: 160,
                  preloader: false,
                  fixedContentPos: false
               });
            }
         }
      });


   });
</script>

<?= $this->endSection(); ?>