<?= $this->extend('layouts/_main'); ?>

<?= $this->section('content'); ?>

   <?php if (isset($videos_destaque) && !empty($videos_destaque)): ?>
      <section class="banner-section">
         <div class="owl-carousel" data-dots="false" data-nav="true" data-desk_num="1" data-lap_num="1" data-tab_num="1"
            data-mob_num="1" data-mob_sm="1" data-autoplay="true" data-loop="true" data-margin="0">
            <?php foreach ($videos_destaque as $video_destaque): ?>
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
            <?php endforeach; ?>
         </div>
      </section>
   <?php endif; ?>

   <section class="video-carousel-section container pt-5">
      <div class="section-title-holder">
         <h2>Visão Libertária</h2>
         <div class="gen-btn-container">
         <a href="<?= site_url('site/videos/Visão Libertária'); ?>" class="gen-button">
               <div class="gen-button-block">
                  <span class="gen-button-line-left"></span>
                  <span class="gen-button-text">Mais Vídeos</span>
               </div>
            </a>
         </div>
      </div>
      <div class="owl-carousel" data-dots="false" data-nav="true" data-desk_num="4" data-lap_num="3" data-tab_num="2"
         data-mob_num="1" data-margin="20">
      <?php if (isset($ultimos_artigos) && !empty($ultimos_artigos)): ?>
         <?php foreach ($ultimos_artigos as $ua): ?>
               <div class="item">
                  <div class="movie-card">
                     <div class="movie-card-img-container">
                     <img src="<?= cria_url_thumb(extrair_id_video_youtube($ua['link_video_youtube'])) ?>"
                        alt="<?= esc($ua['titulo']) ?>">
                        <div class="movie-card-overlay">
                           <i class="bi bi-play-circle-fill play-icon"></i>
                           <!-- O link do popup envolve tudo para ser clicável -->
                        <a href="<?= cria_link_watch(extrair_id_video_youtube($ua['link_video_youtube'])); ?>"
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
         <?php endif; ?>
      </div>
   </section>

   <!-- Novo Slider com cor primária -->
   <section class="custom-slider-section container py-5">
      <div class="custom-slider-wrapper owl-carousel" id="custom-slider-owl"
         style="background: #161616; border-left: 3px solid var(--primary-color); border-radius: 4px; overflow: hidden; position: relative;">
         <!-- Slide 1 -->
         <div class="row align-items-center" style="min-height: 400px;">
            <div class="col-lg-7 p-5">
               <h2 class="fw-bold text-white" style="font-size: 3rem;">ESCREVA E GANHE SATOSHINHOS</h2>
               <p class="text-white-50 mb-4 mt-4" style="max-width: 700px;">Transforme seus artigos em vídeos no Visão
                  Libertária e ganhe satoshinhos por isso!</p>
            <a href="<?= site_url('site/cadastrar'); ?>" class="custom-slider-btn"
                  style="background: var(--primary-color); color: #fff; padding: 16px 32px; font-weight: bold; border-radius: 2px; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; display: inline-block; transition: background 0.2s;">CADASTRE-SE
                  AGORA</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block" style="position: relative; min-height: 400px;">
               <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=800&q=80"
                  alt="Stories of the Dark"
                  style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; border-radius: 0 4px 4px 0; position: absolute; top: 0; left: 0;">
            </div>
         </div>
         <!-- Slide 2 -->
         <div class="row align-items-center" style="min-height: 400px;">
            <div class="col-lg-7 p-5">
               <h2 class="fw-bold text-white" style="font-size: 3rem;">SUGIRA PAUTAS</h2>
               <p class="text-white-50 mb-4 mt-4" style="max-width: 600px;">Faça seu cadastro e veja seu apelido sendo
                  falado nos vídeos do Peter</p>
            <a href="<?= site_url('site/cadastrar'); ?>" class="custom-slider-btn"
                  style="background: var(--primary-color); color: #fff; padding: 16px 32px; font-weight: bold; border-radius: 2px; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; display: inline-block; transition: background 0.2s;">FAZER
                  CADASTRO</a>
            </div>
            <div class="col-lg-5 d-none d-lg-block" style="position: relative; min-height: 400px;">
               <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80"
                  alt="Usuário no computador"
                  style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; border-radius: 0 4px 4px 0; position: absolute; top: 0; left: 0;">
            </div>
         </div>
         <!-- Slide 2 -->
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
                  alt="Usuário no computador"
                  style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; border-radius: 0 4px 4px 0; position: absolute; top: 0; left: 0;">
            </div>
         </div>
      </div>
   </section>
<?php foreach ($videos_por_projeto as $indice => $vp): ?>
   <section class="video-carousel-section container pt-5">
      <div class="section-title-holder">
         <h2><?= $indice; ?></h2>
         <div class="gen-btn-container">
            <a href="<?= site_url('site/videos/'.$indice); ?>" class="gen-button">
               <div class="gen-button-block">
                  <span class="gen-button-line-left"></span>
                  <span class="gen-button-text">Mais Vídeos</span>
               </div>
            </a>
         </div>
      </div>
      <div class="owl-carousel" data-dots="false" data-nav="true" data-desk_num="4" data-lap_num="3" data-tab_num="2"
         data-mob_num="1" data-margin="20">
         <?php if (isset($vp) && !empty($vp)): ?>
            <?php foreach ($vp['videos'] as $v): ?>
               <div class="item">
                  <div class="movie-card">
                     <div class="movie-card-img-container">
                        <img src="<?= cria_url_thumb(extrair_id_video_youtube($v['video_id'])) ?>"
                           alt="<?= esc($v['titulo']) ?>">
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
         <?php endif; ?>
      </div>
   </section>
            <?php endforeach; ?>

   <!-- 5. Scripts customizados -->
   <script>
      $(document).ready(function () {
         // Inicialização do novo slider customizado
         $('#custom-slider-owl').owlCarousel({
            items: 1,
            nav: false,
            dots: false,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: false,
            smartSpeed: 800,
            margin: 0
         });

         // Inicialização dos outros Owl Carousel (exceto o customizado)
         $('.owl-carousel').not('#custom-slider-owl').each(function () {
            var $this = $(this);
            var options = {
               dots: false,
               nav: false,
               items: $this.data('desk_num') || 1,
               loop: $this.data('loop') === true,
               autoplay: $this.data('autoplay') === true,
               autoplayTimeout: 5000,
               margin: $this.data('margin') || 0,
               responsive: {
                  0: { items: $this.data('mob_sm') || 1 },
                  480: { items: $this.data('mob_num') || 1 },
                  768: { items: $this.data('tab_num') || 1 },
                  1024: { items: $this.data('lap_num') || 1 },
                  1200: { items: $this.data('desk_num') || 1 }
               }
            };
            $this.owlCarousel(options);
         });

         // Magnific Popup para vídeo
         $('.gen-video-popup').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
         });
      });
   </script>

<?= $this->endSection(); ?>