<?php
  get_header();

  // Time variables used for creating the image file names
  date_default_timezone_set('America/Chicago');
  $now = DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i'));
  $now = $now->sub(new DateInterval('PT1M'));
  $format = 'Y-m-d-H-i';

  $is_live = isset(get_field('video_live', 'option')[0]);

  $live_start_date = get_field('live_since_date', 'option');
  $live_since = DateTime::createFromFormat('m/d/Y', $live_start_date);

  // Food archive query
  $archive_query = new WP_Query(array(
    'post_type' => 'time-lapse',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'order' => 'desc',
    'orderby' => 'meta_value',
    'meta_type' => 'DATE',
    'meta_key' => 'end_date',
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'live',
        'value' => array(''),
        'compare' => 'IN'
      ),
      array(
        'key' => 'end_date',
        'value' => array(''),
        'compare' => 'NOT IN'
      ),
      array(
        'key' => 'end_time',
        'value' => array(''),
        'compare' => 'NOT IN'
      )
    )
  ));
?>

<section class="live-video-container">

  <div class="live-video-placeholder">
    <img src="<?php the_field('mobile_placeholder', 'option'); ?>" alt="FoodSaver&reg; - The Fresh Fridge Experience">
    <a target="_blank" href="http://www.ustream.tv/embed/<?php the_field('live_video_id', 'option') ?>?v=3&amp;wmode=direct&amp;controls=false&amp;showtitle=false&amp;autoplay=true" class="watch-now">
      <?php echo get_svg('button-play'); ?>
      Watch the fridge <span class="live">Live</span>
    </a>
  </div>

<?php if ($is_live) { ?>
  <div class="live-video">
    <iframe id="live-stream-iframe" data-src="http://www.ustream.tv/embed/<?php the_field('live_video_id', 'option') ?>?autoplay=true&amp;controls=false&amp;showtitle=false" allowfullscreen></iframe>

  <?php
    $hover_areas = array('left', 'center', 'right');
    $camera = 0;

    foreach ($hover_areas as $area) {
      if (empty(get_field($area . '_popup_quote')))
        continue;
  ?>
    <div class="<?php echo $area ?>-hover hover-area">
      <div class="face-container">
        <div class="popover" data-camera="<?php echo $camera++; ?>">
          <div class="popover-inner">
            <div class="live">
              <div class="mid-underline left"></div>
              <span class="live-since-label">Live since <?php echo $live_since->format('M j'); ?></span>
              <div class="mid-underline right"></div>
            </div>
            <div class="popover-quote"><?php the_field($area . '_popup_quote') ?></div>
          </div>
          <div class="popover-footer"><?php the_field($area . '_footer') ?></div>
        </div>
        <?php echo get_svg(get_field($area . '_icon')) ?>
      </div>
    </div>
  <?php } ?>
  <?php // Just a static Instagram link ?>
    <div class="bag-hover hover-area">
      <div class="face-container">
        <a class="popover" href="<?php the_field('instagram_url', 'option') ?>" target="_blank">
          <div class="popover-inner">
            <div class="popover-quote">Interact with the fridge by following us on Instagram @FoodSaver or by using #FoodSaverFridge.</div>
          </div>
          <div class="popover-footer">Follow us on Instagram</div>
        </a>
        <div class="svg-wrapper">
          <?php echo get_svg('instagram') ?>
        </div>
      </div>
    </div>
  </div>
<?php } else { // video is not live ?>
  <img src="<?php the_field('video_placeholder', 'option') ?>" alt="FoodSaver&reg;Fridge Live" class="not-live-video-placeholder">
<?php } ?>
</section>

<?php // If there is not live video that got queried correctly or the video isn't, don't show the clock ?>
<div class="clock-bar-wrapper <?php echo $is_live ? 'live' : 'not-live'; ?>">
  <?php if (!empty($live_start_date) && $is_live) { ?>
    <div class="clock" data-date-then="<?php echo $live_start_date; ?> <?php the_field('live_since_time', 'option'); ?>" data-date-now="<?php echo date('m/d/Y H:i'); ?>">
      <div class="day">
        <span class="label">Day</span>
        <span class="day-diff value">0</span>
      </div>
      <div class="divider"></div>
      <div class="hours">
        <span class="label">Hour</span>
        <span class="value"><span class="hour-diff">0</span><span class="flash">:</span><span class="min-diff">0</span></span>
      </div>
    </div>
  <?php } ?>

  <section class="contest-bar">

    <div class="contest-bar-inner">
      <div class="show-us-your-fridge">
        <span class="top-part">Enter your email for a chance to win a</span>
        <div class="squiggle"><?php echo get_svg('squiggle'); ?></div>
        foodsaver<sup>&reg;</sup> starter kit
        <div class="pointing-arrow"><?php echo get_svg('arrow-long'); ?></div>
      </div>

      <div class="contest-form">
        <form action="#" id="contest-form">
          <label for="email-input">Email</label>
          <input type="email" id="email-input" placeholder="EMAIL">
          <div class="button-wrapper">
            <?php echo get_svg('loader'); ?>
            <button type="submit">Enter</button>
          </div>
        </form>
      </div>

      <div class="chance-to-win">
        <a href="<?php the_field('rules_link', 'option'); ?>"><span>Rules and</span> Privacy Policy <?php echo get_svg('button-arrow') ?></a>
      </div>
    </div>

  </section>
</div>

<div class="scroll-to-top">
  <?php echo get_svg('up-arrow') ?>
</div>

<section class="food-content<?php echo ($archive_query->have_posts() ? '' : ' no-archive'); ?>">

  <section class="contenders">
    <div class="meet-the-contenders">
      <span class="meet-the">Meet the</span>
      <?php echo get_svg('meet-the-contenders') ?>
      <!-- <img src="<?php echo get_image_path('meet-the-contenders.png') ?>" alt=""> -->
    </div>

    <div class="now-filming">
    <?php
      // Loop through the cameras a get the current items
      for ($i = 0; $i < 3; $i++) {
        $query = new WP_Query(array(
          'post_type' => 'time-lapse',
          'post_status' => 'publish',
          'posts_per_page' => 1,
          'order' => 'asc',
          'orderby' => 'meta_value',
          'meta_type' => 'DATE',
          'meta_key' => 'start_date',
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'key' => 'live',
              'value' => array(''),
              'compare' => 'NOT IN'
            ),
            array(
              'key' => 'end_date',
              'value' => array(''),
              'compare' => 'IN'
            ),
            array(
              'key' => 'camera',
              'value' => $i
            )
          )
        ));

        while ($query->have_posts()) {
          $query->the_post();
    ?>
      <div class="one-third">
        <article class="video-thumbnail">
          <div class="thumbnail-wrapper"
            data-lapse-id="<?php the_ID() ?>"
            data-start-time="<?php the_field('start_date') ?> <?php the_field('start_time') ?>"
            data-end-time="<?php echo $now->format('m/d/Y H:i'); ?>"
            data-camera="<?php echo $i; ?>"
            data-playback-interval="<?php the_field('playback_rate') ?>"
            data-fps="<?php the_field('fps') ?>"
            data-title="<?php the_title() ?>"
            data-is-live>
            <img src="//s3.amazonaws.com/food-saver-fridge/camera-<?php echo $i; ?>/<?php echo $now->format($format) ?>.jpg" alt="<?php the_title() ?>">
            <?php echo get_svg('play-icon') ?>
          </div>
          <div class="now-filming-label">Now filming</div>
          <h3 class="video-title"><?php the_title() ?></h3>
        </article>
      </div>
    <?php } } wp_reset_postdata(); ?>
    </div>
  </section>

  <?php
    $events = get_field('events');

    if (!empty($events)) {
  ?>
  <section class="coming-soon">
    <div class="section-icon">
      <?php echo get_svg('leaf-circle') ?>
    </div>
    <div class="section-heading">
      <span class="top">Contenders on</span>
      <span class="bottom">Deck</span>
      <div class="rule"></div>
    </div>

    <div class="events">
      <?php
        foreach($events as $event) {
      ?>
      <article class="<?php echo $event['event_type']; ?>">
        <div class="inner">
          <div class="inner-inner">
            <span class="how-long">How long</span>
            <?php echo get_svg('squiggle-long'); ?>
            <div class="item"><span><?php echo $event['item']; ?></span></div>
            <?php echo get_svg('squiggle-long'); ?>
            <span class="will-it-last">Will it last?</span>
          </div>
        </div>
      </article>
    <?php } ?>
    </div>
  </section>
  <?php } ?>

</section>

<?php if ($archive_query->have_posts()) { ?>
<section class="how-long-did-they-last">

  <div class="section-icon">
    <?php echo get_svg('clock') ?>
  </div>
  <div class="section-heading">
    <span class="top">How long did they</span>
    <span class="bottom">Last</span>
    <div class="rule"></div>
  </div>

  <div class="row">
    <div class="video-archive">
    <?php
      while($archive_query->have_posts()) {
        $archive_query->the_post();
        $end = DateTime::createFromFormat('m/d/Y H:i', get_field('end_date') . ' ' . get_field('end_time'));
    ?>
      <article class="video">
        <div class="thumbnail-wrapper"
          data-lapse-id="<?php the_ID() ?>"
          data-start-time="<?php the_field('start_date') ?> <?php the_field('start_time') ?>"
          data-end-time="<?php echo $end->format('m/d/Y H:i') ?>"
          data-camera="<?php the_field('camera') ?>"
          data-playback-interval="<?php the_field('playback_rate') ?>"
          data-title="<?php the_title() ?>"
          data-fps="<?php the_field('fps') ?>">
          <img src="//s3.amazonaws.com/food-saver-fridge/camera-<?php the_field('camera') ?>/<?php echo $end->format($format) ?>.jpg" alt="<?php the_title() ?>">
          <?php echo get_svg('play-icon') ?>
          <div class="duration">0:00</div>
        </div>
        <div class="box-wrapper-outer">
          <div class="box-wrapper">
            <div class="box-label">
              <span class="day"><?php the_field('days_in_air') ?></span>
              <span class="days">Days</span>
              <span class="label">Air</span>
            </div>
            <div class="box-label">
              <span class="day"><?php the_field('days_in_bag') ?></span>
              <span class="days">Days</span>
              <span class="label">Bag</span>
            </div>
          </div>
        </div>
        <div class="meta">
          <span class="days-ago"><?php echo $now->diff($end)->d ?> days ago</span>
          <span class="title"><?php the_title() ?></span>
        </div>
      </article>
    <?php } ?>
    </div>
  </div>

</section>
<?php } wp_reset_postdata(); ?>

<?php get_footer(); ?>