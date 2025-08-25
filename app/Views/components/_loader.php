<style>
   #gen-loading {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #161616;
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
   }

   #gen-loading.show {
      opacity: 1;
      visibility: visible;
   }

   #gen-loading img {
      max-width: 150px;
      border-radius: 50%;
   }

   @keyframes pulse {
      from {
         transform: scale(1);
      }

      to {
         transform: scale(1.05);
      }
   }

   #gen-loading-center {
      display: flex;
      align-items: center;
      justify-content: center;
      animation: pulse 0.25s infinite ease-in-out alternate;
      user-select: none;
   }

   #gen-loading-center div {
      color: white;
      font-size: 2rem;
      font-weight: bold;
      margin-left: 1rem;
   }
</style>
<div id="gen-loading">
   <div id="gen-loading-center">
      <img
         src="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>"
         alt="loading">
      <div><?= $_SESSION['site_config']['texto_nome']; ?></div>
   </div>
</div>
<script>
   const loader = document.getElementById('gen-loading');
   loader.classList.add('show');
   window.addEventListener('load', function () {
      loader.classList.remove('show');
   });
</script>