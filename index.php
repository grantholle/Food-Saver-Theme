<?php get_header(); the_post(); ?>
<div class="index-template-content">
  <div class="row">
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </div>
</div>
<?php get_footer(); ?>