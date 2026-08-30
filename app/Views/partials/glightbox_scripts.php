<script defer src="<?= site_url('public/vendor/glightbox/glightbox.min.js'); ?>"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		if (typeof GLightbox !== 'function') {
			return;
		}

		window.vlGLightbox = GLightbox({
			selector: false,
			elements: [],
			openEffect: 'fade',
			closeEffect: 'fade',
			loop: false,
			autoplayVideos: true
		});

		document.addEventListener('click', function (e) {
			var link = e.target.closest('a.gen-video-popup');
			if (!link || !window.vlGLightbox) {
				return;
			}
			var href = link.getAttribute('href');
			if (!href || href === '#') {
				return;
			}
			e.preventDefault();

			var slide = { href: href, type: 'external', width: '900px', height: '506px' };
			try {
				var url = new URL(href, window.location.href);
				var id = url.searchParams.get('v');
				if (!id && url.hostname.indexOf('youtu.be') !== -1) {
					id = url.pathname.replace(/^\//, '').split('/')[0];
				}
				if (!id && url.pathname.indexOf('/embed/') !== -1) {
					id = url.pathname.split('/embed/')[1].split(/[/?]/)[0];
				}
				if (id) {
					id = id.split('&')[0];
					slide.href = 'https://www.youtube.com/embed/' + encodeURIComponent(id) + '?autoplay=1&rel=0';
				}
			} catch (err) { }

			window.vlGLightbox.setElements([slide]);
			window.vlGLightbox.openAt(0);
		});
	});
</script>
