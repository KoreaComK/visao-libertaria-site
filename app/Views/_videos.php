<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/_main'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
   integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"></script>

<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>

<style>
   .page-load-status {
      display: none;
   }

   /* Garantir que o Masonry funcione corretamente */
   .list-videos {
      width: 100%;
   }

   .list-videos .col-lg-3 {
      width: 25%;
   }

   .margin-top-ultra {
      padding-top: 4.5rem !important;
   }

   @media (max-width: 991px) {
      .list-videos .col-lg-3 {
         width: 33.333%;
      }

      .margin-top-ultra {
         padding-top: 0.5rem !important;
      }
   }

   @media (max-width: 767px) {
      .list-videos .col-lg-3 {
         width: 50%;
      }
   }

   @media (max-width: 575px) {
      .list-videos .col-lg-3 {
         width: 100%;
      }
   }

   .video-card {
      transition: transform 0.3s ease;
      background: #1a1a1a !important;
      border: 1px solid #333 !important;
   }

   .video-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
   }

   .video-card .card-body {
      background: #1a1a1a !important;
      color: white !important;
   }

   .video-card .card-title {
      color: white !important;
   }

   .video-card .card-text {
      color: #cccccc !important;
   }

   /* Remover underline dos links */
   a {
      text-decoration: none !important;
   }

   a:hover {
      text-decoration: none !important;
   }

   /* Estilo do breadcrumb */
   .custom-breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
   }

   .custom-breadcrumb .breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
   }

   .custom-breadcrumb .breadcrumb-item {
      color: #666;
   }

   .custom-breadcrumb .breadcrumb-item a {
      color: var(--primary-color);
      text-decoration: none;
   }

   .custom-breadcrumb .breadcrumb-item.active {
      color: #999;
   }

   .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
      content: ">";
      color: #666;
      margin: 0 8px;
   }

   /* Remover numeração do breadcrumb */
   .custom-breadcrumb .breadcrumb {
      list-style: none;
      counter-reset: none;
   }

   .custom-breadcrumb .breadcrumb-item {
      list-style: none;
      counter-increment: none;
   }

   .custom-breadcrumb .breadcrumb-item::before {
      content: none;
   }

   .custom-breadcrumb .breadcrumb-item::after {
      content: none;
   }

   /* Centralizar o spinner */
   .page-load-status {
      width: 100%;
      text-align: center;
   }

   .infinite-scroll-request {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem 0;
      width: 100%;
   }

   .spinner-border {
      display: inline-block;
      margin: 0 auto;
   }

   .video-thumbnail {
      position: relative;
      overflow: hidden;
      border-radius: 8px;
   }

   .video-thumbnail img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: transform 0.3s ease;
   }

   .video-card:hover .video-thumbnail img {
      transform: scale(1.05);
   }

   .play-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.4s ease;
   }

   .video-card:hover .play-overlay {
      opacity: 1;
   }

   .play-icon {
      font-size: 4rem;
      color: white;
      transform: scale(0.7);
      opacity: 0;
      transition: transform 0.3s ease 0.1s, opacity 0.3s ease 0.1s;
   }

   .video-card:hover .play-icon {
      transform: scale(1);
      opacity: 1;
   }

   .project-badge {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: var(--primary-color);
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
   }

   /* Garante que o overlay e o popup do Magnific Popup fiquem acima de tudo */
   .mfp-bg,
   .mfp-wrap,
   .mfp-container,
   .mfp-content,
   .mfp-ready {
      z-index: 99999 !important;
   }

   .mfp-bg,
   .mfp-wrap,
   .mfp-container {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
   }
</style>

<div class="container-fluid py-3">
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
                     <li class="breadcrumb-item">
                        <a href="javascript:void(0);">
                           <i class="bi bi-play-circle-fill pe-1"></i>Vídeos
                        </a>
                     </li>
                     <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-grid-fill pe-1"></i>Todos os Vídeos
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