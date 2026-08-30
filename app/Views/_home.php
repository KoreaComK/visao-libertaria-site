<?= $this->extend('layouts/_main'); ?>

<?= $this->section('styles'); ?>
	<link rel="stylesheet" href="<?= site_url('public/vendor/splide/splide-core.min.css'); ?>" />
	<?= $this->include('partials/glightbox_styles'); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

   <?php if (isset($videos_destaque) && !empty($videos_destaque)): ?>
      <section class="banner-section">
         <div class="splide" data-dots="false" data-nav="false" data-desk-num="1" data-lap-num="1" data-tab-num="1"
            data-mob-num="1" data-mob-sm="1" data-autoplay="true" data-loop="true" data-margin="0"
            aria-label="Vídeos em destaque">
            <div class="splide__track">
               <div class="splide__list">
            <?php foreach ($videos_destaque as $video_destaque): ?>
               <div class="splide__slide">
               <div class="item vh-100 d-flex align-items-center"
                  style="background-image: url('<?= cria_url_thumb($video_destaque['video_id']); ?>'); background-size: cover; background-position: center;">
                  <div class="container">
                     <div class="row align-items-center">
                        <div class="col-lg-6 text-white">
                           <h5 class="text-primary-color fw-bold" style="letter-spacing: 2px;">MAIS RECENTE</h5>
                           <h1 class="display-3 fw-bold"><?= $video_destaque['nome']; ?></h1>
                           <p><?= $video_destaque['titulo']; ?></p>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center align-items-center flex-column zoom-parent">
                           <a href="<?= cria_link_watch($video_destaque['video_id']); ?>"
                              class="text-white text-decoration-none text-center gen-video-popup">
                              <i class="bi bi-play-fill zoom-on-hover"
                                 style="font-size: 2.5rem; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; padding-left: 5px;"></i>
                           </a>
                           <h4 class="mt-3 text-white fw-bold zoom-on-hover">Assista ao video</h4>
                        </div>
                     </div>
                  </div>
               </div>
               </div>
            <?php endforeach; ?>
               </div>
            </div>
         </div>
      </section>
   <?php endif; ?>

   <section class="video-carousel-section container pt-5">
      <div class="section-title-holder">
         <h2>Visão Libertária</h2>
         <div class="gen-btn-container">
         <?php if (!empty($visao_libertaria_projeto_slug)): ?>
         <a href="<?= site_url('site/videos/' . $visao_libertaria_projeto_slug); ?>" class="gen-button">
         <?php else: ?>
         <a href="<?= site_url('site/videos'); ?>" class="gen-button">
         <?php endif; ?>
               <div class="gen-button-block">
                  <span class="gen-button-line-left"></span>
                  <span class="gen-button-text">Mais Vídeos</span>
               </div>
            </a>
         </div>
      </div>
      <?php if (isset($ultimos_artigos) && !empty($ultimos_artigos)): ?>
      <div class="splide" data-dots="false" data-nav="false" data-desk-num="4" data-lap-num="3" data-tab-num="2"
         data-mob-num="1" data-margin="20" aria-label="Visão Libertária">
         <div class="splide__track">
            <div class="splide__list">
         <?php foreach ($ultimos_artigos as $ua): ?>
               <div class="splide__slide">
                  <div class="movie-card">
                     <div class="movie-card-img-container">
                     <?php
						$ytUa = extrair_id_video_youtube($ua['link_video_youtube'] ?? null);
						$ytUaThumb = $ytUa !== null ? cria_url_thumb($ytUa) : base_url('public/assets/imagem-default.png');
						?>
                     <img src="<?= esc($ytUaThumb, 'attr') ?>"
                        alt="<?= esc($ua['titulo']) ?>" loading="lazy">
                        <div class="movie-card-overlay">
                           <i class="bi bi-play-circle-fill play-icon"></i>
                           <!-- O link do popup envolve tudo para ser clicável -->
                        <a href="<?= $ytUa !== null ? esc(cria_link_watch($ytUa), 'attr') : esc($ua['link_video_youtube'] ?? '#', 'attr') ?>"
                           class="gen-video-popup"
                              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></a>
                        </div>
                     </div>
                     <div class="movie-card-info">
                     <h5><?= $ua['titulo'] ?></h5>
                        <p>
                        <span class="tag-primary">Visão Libertária</span>
                        </p>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
            </div>
         </div>
      </div>
      <?php endif; ?>
   </section>

   <!-- Novo Slider com cor primária -->
   <section class="custom-slider-section container py-5">
      <div class="custom-slider-wrapper splide" id="custom-slider-splide"
         data-dots="false" data-nav="false" data-desk-num="1" data-loop="true" data-autoplay="true" data-margin="0"
         aria-label="Chamadas para colaboração"
         style="background: #161616; border-left: 3px solid var(--primary-color); border-radius: 4px; overflow: hidden; position: relative;">
         <div class="splide__track">
            <div class="splide__list">
         <!-- Slide 1 -->
         <div class="splide__slide">
         <div class="row align-items-center" style="min-height: 400px;">
            <div class="col-lg-7 p-5">
               <h2 class="fw-bold text-white" style="font-size: 3rem;">ESCREVA E GANHE SATOSHINHOS</h2>
               <p class="text-white-50 mb-4 mt-4" style="max-width: 700px;">Transforme seus artigos em vídeos no Visão
                  Libertária e ganhe satoshinhos por isso!</p>
            <a href="<?= site_url('site/cadastre-se'); ?>" class="custom-slider-btn"
                  style="background: var(--primary-color); color: #fff; padding: 16px 32px; font-weight: bold; border-radius: 2px; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; display: inline-block; transition: background 0.2s;">CADASTRE-SE
                  AGORA</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block" style="position: relative; min-height: 400px;">
               <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=800&q=80"
                  alt="Stories of the Dark" loading="lazy"
                  style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; border-radius: 0 4px 4px 0; position: absolute; top: 0; left: 0;">
            </div>
         </div>
         </div>
         <!-- Slide 2 -->
         <div class="splide__slide">
         <div class="row align-items-center" style="min-height: 400px;">
            <div class="col-lg-7 p-5">
               <h2 class="fw-bold text-white" style="font-size: 3rem;">SUGIRA PAUTAS</h2>
               <p class="text-white-50 mb-4 mt-4" style="max-width: 600px;">Faça seu cadastro e veja seu apelido sendo
                  falado nos vídeos do Peter</p>
            <a href="<?= site_url('site/cadastre-se'); ?>" class="custom-slider-btn"
                  style="background: var(--primary-color); color: #fff; padding: 16px 32px; font-weight: bold; border-radius: 2px; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; display: inline-block; transition: background 0.2s;">FAZER
                  CADASTRO</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block" style="position: relative; min-height: 400px;">
               <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80"
                  alt="Usuário no computador" loading="lazy"
                  style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; border-radius: 0 4px 4px 0; position: absolute; top: 0; left: 0;">
            </div>
         </div>
         </div>
         <!-- Slide 3 -->
         <div class="splide__slide">
         <div class="row align-items-center" style="min-height: 400px;">
            <div class="col-lg-7 p-5">
               <h2 class="fw-bold text-white" style="font-size: 3rem;">COLABORE COM O PROJETO</h2>
               <p class="text-white-50 mb-4 mt-4" style="max-width: 600px;">Sabe narrar e produzir vídeos? Colabore com
                  o projeto e ganhe satoshinhos</p>
            <a href="<?= site_url('colaboradores/artigos/dashboard'); ?>" class="custom-slider-btn"
                  style="background: var(--primary-color); color: #fff; padding: 16px 32px; font-weight: bold; border-radius: 2px; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; display: inline-block; transition: background 0.2s;">COLABORE
                  AGORA</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block" style="position: relative; min-height: 400px;">
               <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=800&q=80"
                  alt="Usuário no computador" loading="lazy"
                  style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; border-radius: 0 4px 4px 0; position: absolute; top: 0; left: 0;">
            </div>
         </div>
         </div>
            </div>
         </div>
      </div>
   </section>
<?php foreach ($videos_por_projeto as $indice => $vp): ?>
   <section class="video-carousel-section container pt-5">
      <div class="section-title-holder">
         <h2><?= $indice; ?></h2>
         <div class="gen-btn-container">
            <a href="<?= site_url('site/videos/' . projeto_nome_para_url($indice)); ?>" class="gen-button">
               <div class="gen-button-block">
                  <span class="gen-button-line-left"></span>
                  <span class="gen-button-text">Mais Vídeos</span>
               </div>
            </a>
         </div>
      </div>
         <?php if (isset($vp['videos']) && !empty($vp['videos'])): ?>
      <div class="splide" data-dots="false" data-nav="false" data-desk-num="4" data-lap-num="3" data-tab-num="2"
         data-mob-num="1" data-margin="20" aria-label="<?= esc($indice, 'attr'); ?>">
         <div class="splide__track">
            <div class="splide__list">
            <?php foreach ($vp['videos'] as $v): ?>
               <div class="splide__slide">
                  <div class="movie-card">
                     <div class="movie-card-img-container">
                        <?php
						$ytV = extrair_id_video_youtube($v['video_id'] ?? null);
						$thumbV = $ytV !== null ? cria_url_thumb($ytV) : base_url('public/assets/imagem-default.png');
						?>
                        <img src="<?= esc($thumbV, 'attr') ?>"
                           alt="<?= esc($v['titulo']) ?>" loading="lazy">
                        <div class="movie-card-overlay">
                           <i class="bi bi-play-circle-fill play-icon"></i>
                           <!-- O link do popup envolve tudo para ser clicável -->
                           <a href="<?= cria_link_watch($v['video_id']); ?>" class="gen-video-popup"
                              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></a>
                        </div>
                     </div>
                     <div class="movie-card-info">
                        <h5><?= $v['titulo'] ?></h5>
                        <p>
                           <span class="tag-primary"><?= $indice; ?></span>
                        </p>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
            </div>
         </div>
      </div>
         <?php endif; ?>
   </section>
            <?php endforeach; ?>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
	<script defer src="<?= site_url('public/vendor/splide/splide.min.js'); ?>"></script>
	<?= $this->include('partials/glightbox_scripts'); ?>
   <script>
      document.addEventListener('DOMContentLoaded', function () {
         function toInt(value, fallback) {
            var n = parseInt(value, 10);
            return isNaN(n) || n < 1 ? fallback : n;
         }

         document.querySelectorAll('.splide').forEach(function (el) {
            if (!el.querySelector('.splide__slide')) {
               return;
            }

            var perPage = toInt(el.dataset.deskNum, 1);
            var slideCount = el.querySelectorAll('.splide__slide').length;
            var isLoop = el.dataset.loop === 'true' && slideCount > 1;
            var autoplay = el.dataset.autoplay === 'true' && slideCount > 1;
            var gap = el.dataset.margin ? toInt(el.dataset.margin, 0) + 'px' : 0;
            var isBanner = !!el.closest('.banner-section');

            new Splide(el, {
               type: isLoop ? 'loop' : 'slide',
               perPage: perPage,
               perMove: 1,
               gap: gap,
               arrows: false,
               pagination: el.dataset.dots === 'true',
               autoplay: autoplay,
               interval: 5000,
               pauseOnHover: false,
               speed: 800,
               rewind: !isLoop && autoplay,
               omitEnd: !isLoop,
               autoHeight: !isBanner && perPage === 1,
               breakpoints: {
                  479: { perPage: toInt(el.dataset.mobSm, toInt(el.dataset.mobNum, 1)) },
                  767: { perPage: toInt(el.dataset.mobNum, 1) },
                  1023: { perPage: toInt(el.dataset.tabNum, 1) },
                  1199: { perPage: toInt(el.dataset.lapNum, 1) }
               }
            }).mount();
         });
      });
   </script>
<?= $this->endSection(); ?>
