	<footer>
		<img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/footer-logo.png" alt="FoodSaver&reg;">
		<p class="copyright">&copy; Sunbeam Products. All Rights Reserved.</p>
	</footer>

	<section class="video-overlay">
		<div class="video-overlay-inner">
			<div class="row">
				<div class="close-container">
					Back to Live Stream
					<?php echo get_svg('close'); ?>
				</div>
				<span class="live-since">Live since <span class="live-since-month-day"></span></span>
				<h2 class="save"></h2>
				<div class="playback-container stopped">
					<div class="image-wrapper">
						<?php echo get_svg('rewatch'); ?>
						<?php echo get_svg('play-icon'); ?>
						<?php echo get_svg('loader'); ?>
						<p class="loading-text">Loading...</p>
						<canvas id="canvas"></canvas>
						<div class="day-label">
							<span class="label">Day</span>
							<span class="day">0</span>
						</div>
					</div>
				</div>
				<div class="starter-pack">
					<div class="fifty">
						<?php echo get_svg('leaf') ?>
						<h2>The FoodSaver&reg;</h2>
						<div class="rule"></div>
						<h2 class="pack">Starter Kit</h2>
						<div class="rule"></div>
						<img src="<?php echo get_image_path('FoodSaver-KitImage.jpg') ?>" alt="The FoodSaver&reg; Systems Starter Kit">
					</div>
					<div class="fifty">
						<h3>Includes</h3>
						<ul>
							<li>FoodSaver&reg; V4865 2-in-1 Vacuum-Sealing System</li>
							<li>Bags, rolls, and other goodies included</li>
						</ul>
						<div class="text-center">
							<a href="<?php the_field('get_it_now', 'option') ?>" class="btn">Get it now <?php echo get_svg('button-arrow') ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php wp_footer(); ?>

	<?php if ( WP_ENV != 'production' ) { ?>
		<script type='text/javascript'>
			(function (d, t) {
				var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
				bh.type = 'text/javascript';
				bh.src = '//www.bugherd.com/sidebarv2.js?apikey=iy6d8q3jlx09uxdx5jewng';
				s.parentNode.insertBefore(bh, s);
				})(document, 'script');
			</script>
	<?php } ?>
</body>
</html>