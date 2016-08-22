<!DOCTYPE html>
<!--[if lte IE 9]><html lang="en" class="no-js lt-ie10<?php if (is_front_page()) { echo ' loading'; } ?>"><![endif]-->
<!--[if gt IE 9]><!--><html lang="en" class="no-js<?php if (is_front_page()) { echo ' loading'; } ?>"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#ee7421">

	<meta property="og:title" content="<?php the_field('front_page_site_title', 'option') ?>"/>
	<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
	<meta property="og:url" content="<?php echo site_url(); ?>"/>
	<meta property="og:image" content="<?php the_field('mobile_placeholder', 'option'); ?>"/>

	<?php if (is_front_page()) { ?>
		<title><?php the_field('front_page_site_title', 'option') ?></title>
	<?php } else { ?>
		<title><?php wp_title('&raquo;','true','right'); ?></title>
	<?php } ?>

	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

  <?php // Use http://realfavicongenerator.net/ ?>
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/favicon-194x194.png" sizes="194x194">
	<link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/manifest.json">
	<link rel="shortcut icon" href="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/favicon.ico">
	<meta name="apple-mobile-web-app-title" content="App Name">
	<meta name="application-name" content="App Name">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/mstile-144x144.png">
	<meta name="msapplication-config" content="<?php echo get_bloginfo('template_directory'); ?>/assets/favicons/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">

	<script>
		var console = console || {log:function(){},error:function(){}};

		// Set up site configuration
		window.config = window.config || {};

		// The base URL for the WordPress theme
		window.config.baseUrl = "<?php echo get_bloginfo('url'); ?>";
		window.config.ajaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
		window.config.thankYou = "<?php the_field('form_submission_message', 'option') ?>";

		// Empty default Gravity Forms spinner function
		var gformInitSpinner = function() {};
	</script>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KPWXWR"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KPWXWR');</script>

<?php // Only showing header things for the front page ?>
<?php if (is_front_page()) { ?>
	<section class="site-loader">
		<div class="loader-wrapper">
			<div id="site-image-loader" class="loader" data-src="<?php the_field('loading_gif', 'option'); ?>" data-delay="<?php the_field('loading_delay', 'option'); ?>"></div>
			<img class="fresh-fridge" src="<?php echo get_image_path('food-saver-new.svg'); ?>" alt="FoodSaver&reg; The Fresh Fridge Experience Live">
		</div>
	</section>

	<aside class="starter-kit-slideout">
		<div class="food-wants-heading">
			<?php echo get_svg('close') ?>
			<span class="food-wants">Get a</span>
			<span class="starter-kit">Starter Kit</span>
		</div>
		<img src="<?php echo get_image_path('FoodSaver-KitImage.jpg') ?>" alt="FoodSaver&reg; Starter Kit">
		<div class="wysiwyg-container">
			<?php the_field('sidebar_content'); ?>
		</div>
	</aside>

	<header>
		<div class="row">
			<div class="logo-wrapper large">
				<img src="<?php echo get_image_path('logo.png') ?>" alt="FoodSaver&reg; - The Fresh Fridge Experience">
			</div>
			<div class="logo-wrapper small">
				<img src="<?php echo get_image_path('header-logo.png') ?>" alt="FoodSaver&reg; - The Fresh Fridge Experience">
			</div>
		</div>
	</header>

	<div class="social-food-wants" id="sidebar-toggle">
		<div class="social-icons">
			<a href="<?php the_field('instagram_url', 'option') ?>" target="_blank"><?php echo get_svg('instagram') ?></a>
			<a href="<?php the_field('facebook_url', 'option') ?>" target="_blank"><?php echo get_svg('facebook') ?></a>
		</div>
		<div class="food-wants-heading">
			<div class="inner">
				<span class="food-wants">get a</span>
				<span class="starter-kit">Starter Kit</span>
				<?php echo get_svg('circle-long-arrow') ?>
			</div>
		</div>
	</div>
<?php } ?>