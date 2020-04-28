
<?php
/** TOC
*
*- load stylesheets
*- add string translations 
*- change section order on front page
*- enable header image on front page
*- import google fonts
*- customizing CTA front page section
*- customizing tours front page section
*- customizing all trips page
*- strip archives from archives page title
*- front page about me section
*- testimonial section

**/
?>

<?php
       add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
       function enqueue_parent_theme_style() {
             wp_enqueue_style( 'parent-style',    get_template_directory_uri().'/style.css' );
       }   
     ?>

<?php

/**
 * Outputs localized string if polylang exists or  output's not translated one as a fallback
 *
 * @param $string
 *
 * @return  void
 */
function pl_e( $string = '' ) {
    if ( function_exists( 'pll_e' ) ) {
        pll_e( $string );
    } else {
        echo $string;
    }
}

/**
 * Returns translated string if polylang exists or  output's not translated one as a fallback
 *
 * @param $string
 *
 * @return string
 */
function pl__( $string = '' ) {
    if ( function_exists( 'pll__' ) ) {
        return pll__( $string );
    }

    return $string;
}

// these function prefixes can be either you are comfortable with.

        //add polylang string translations
        if ( function_exists( 'pll_register_string' ) ) {
        add_action('init', function() {
            pll_register_string('allwalks', 'All Walks');
            pll_register_string('Getintouch', 'Get in Touch!');
            pll_register_string('DiscoverOurTours', 'Discover our Walks');
            pll_register_string('Aboutme', 'About me');
            pll_register_string('checkusout', 'Check us out on:');
            pll_register_string('Explore', 'Explore');
            pll_register_string('readmore', 'Read More.');
            pll_register_string('Trip Enquiry', 'Trip Enquiry');
            
          });
        }

        ?>


<?php
 // changing the order of sections on the front page. function originally in /inc/options.php

function travel_ultimate_sortable_sections() {
    $sections = array(
        'slider'    => esc_html__( 'Slider', 'travel-ultimate' ),
        'cta'    => esc_html__( 'CTA', 'travel-ultimate' ),
        'package'   => esc_html__( 'Package', 'travel-ultimate' ),
        'search'    => esc_html__( 'Search', 'travel-ultimate' ),
        'about'     => esc_html__( 'About Us', 'travel-ultimate' ),
        'destination'     => esc_html__( 'Destination', 'travel-ultimate' ),
        'testimonial' => esc_html__( 'Testimonial', 'travel-ultimate' ),
        'event' => esc_html__( 'Event', 'travel-ultimate' ),
    );
    return apply_filters( 'travel_ultimate_sortable_sections', $sections );
}
?>



<?php
// enabling the header image on the frontpage 

function travel_ultimate_header_image() {
    $header_image = get_header_image();
    $class = '';
    $site_description = get_bloginfo( 'description', 'display' );
	$site_name = get_bloginfo( 'name' );
    if ( is_singular() ) :  
        $class = ( has_post_thumbnail() || ! empty( $header_image ) ) ? '' : 'header-media-disabled';
    else :
        $class = ! empty( $header_image ) ? '' : 'header-media-disabled';
    endif;
    if ( is_singular() && has_post_thumbnail() ) : 
        $header_image = get_the_post_thumbnail_url( get_the_id(), 'full' );
    endif;
    if ( travel_ultimate_is_frontpage() ):
        ?>
            <div id="page-site-header" class="relative" style="background-image: url('<?php echo esc_url( $header_image ); ?>');">
                <div class="overlay"></div>
                <div class="wrapper">
                    <header class="page-header">
                    <div>
                    <h1 id="frontpagetitle" ><?php echo $site_name; ?></h1>
					<p id="frontpagetagline"><?php echo esc_html( $site_description ); /* WPCS: xss ok. */ ?></p>
					</div>
                        <?php 
                        //this needs some cleaning up
                        //echo travel_ultimate_site_branding(); ?>
                    </header>
                    <?php  travel_ultimate_add_breadcrumb(); ?>
                </div><!-- .wrapper -->
            </div><!-- #page-site-header -->
        <?php
    else:
        ?>
        <div id="page-site-header" class="relative" style="background-image: url('<?php echo esc_url( $header_image ); ?>');">
            <div class="overlay"></div>
            <div class="wrapper">
                <header class="page-header">
                    <?php echo travel_ultimate_custom_header_banner_title(); ?>
                </header>
                <?php  travel_ultimate_add_breadcrumb(); ?>
            </div><!-- .wrapper -->
        </div><!-- #page-site-header -->
    <?php
    endif;
}
?>

<?php
//enable google fonts

function add_google_fonts() {
    wp_enqueue_style( 'google_web_fonts', 'https://fonts.googleapis.com/css?family=Kaushan+Script|Raleway' );
  }
  add_action( 'wp_enqueue_scripts', 'add_google_fonts' );


// customizing the CTA front page section

function travel_ultimate_get_cta_section_details( $input ) {
    $options = travel_ultimate_get_theme_options();
    $content = array();
    $page_id = ! empty( $options['cta_content_page'] ) ? $options['cta_content_page'] : '';
    $args = array(
        'post_type'         => 'page',
        'page_id'           => $page_id,
        'posts_per_page'    => 1,
    );
        
    // Run The Loop.
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) : 
        while ( $query->have_posts() ) : $query->the_post();
            $page_post['id']        = get_the_id();
            $page_post['title']     = get_the_title();
            $page_post['url']       = get_the_permalink();
            $page_post['excerpt']   = travel_ultimate_trim_content( 88 );
            $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'large' ) : '';

            // Push to the main array.
            array_push( $content, $page_post );
        endwhile;
    endif;
    wp_reset_postdata();
        
    if ( ! empty( $content ) ) {
        $input = $content;
    }
    return $input;
}
?>

<?php
// cta section content details.
add_filter( 'travel_ultimate_filter_cta_section_details', 'travel_ultimate_get_cta_section_details' );

function travel_ultimate_render_cta_section( $content_details = array() ) {
    $options = travel_ultimate_get_theme_options();
    $btn_label = ! empty( $options['cta_btn_label'] ) ? $options['cta_btn_label'] : esc_html__( 'Learn More', 'travel-ultimate' );

    if ( empty( $content_details ) ) {
        return;
    } ?>

    <div id="call-to-action" class="relative page-section">
            <div class="wrapper">
                <?php foreach ( $content_details as $content ) : ?>
                    <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                        <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                        </div>

                        <div class="cta-custom-wrapper">

                           <!-- commenting out the title
                                 <header class="entry-header">
                                <h2 class="entry-title"><?php // echo esc_html( $content['title'] ); ?></h2>
                            </header> -->

                            <div class="cta-text-left">
                                <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                            </div><!-- .entry-content -->                                

                            <?php if ( ! empty( $options['cta_btn_label'] ) ) : ?>
                                <div class="cta-text-right">
                                    <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn"><?php pl_e( esc_html( $options['cta_btn_label'] ) );?></a>
                                </div><!-- .view-all -->
                            <?php endif; ?>
                        </div><!-- .entry-container -->
                    </article>
                    
                <?php endforeach; ?>
        </div><!-- .wrapper -->
    </div><!-- .hero-cta-wrapper -->
<?php }
?>

<?php
// package section front page details
function travel_ultimate_render_package_section( $content_details = array() ) {
    $options = travel_ultimate_get_theme_options();
    $content_type = $options['package_content_type'];
    $i = 1;        

    if ( empty( $content_details ) ) {
        return;
    } ?>

    <div id="recommended-packages" class="relative page-section">
        <div class="wrapper">
            <div class="section-header align-center">
                <?php if ( ! empty( $options['package_sub_title'] ) ) : ?>
                    <span class="section-subtitle"><?php pl_e( esc_html( $options['package_sub_title'] ) ); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $options['package_title'] ) ) : ?>
                    <h2 class="section-title"><?php pl_e( esc_html( $options['package_title'] )); ?></h2>
                <?php endif; ?>
            </div><!-- .section-header -->

            <div class="section-content clear col-3">
                <?php 
                $i = 1;
                foreach ( $content_details as $content ) : ?>
                    <article class="has-post-thumbnail">
                        <div class="package-wrapper">
                            <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['img'] ); ?>');">
                                <?php if ( 'trip' === $content_type && class_exists( 'WP_Travel' ) ) { ?>
                                    <div class="clearfix">
                                        <?php wp_travel_single_trip_rating( $content['id'] );  ?>
                                    </div><!-- .clearfix -->
                                <?php } ?>
                            </div><!-- .featured-image -->

                            <div class="entry-container">
                                <span class="location">
                                    <?php 
                                    if ( 'trip' === $content_type ) { 
                                        $terms = get_the_terms( $content['id'], 'travel_locations' );
                                        if ( is_array( $terms ) && count( $terms ) > 0 ) {
                                            foreach ( $terms as $term ) {
                                            ?>
                                            <a href="<?php echo esc_url( get_term_link( $term->term_id ) ) ?>"><?php echo esc_html( $term->name ); ?></a>
                                            <?php 
                                            }
                                        } 
                                    } elseif ( 'page' != $content_type ) {
                                        $cats = get_the_category( $content['id'] );
                                        foreach ( $cats as $cat ) { 
                                            ?>
                                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( get_cat_name( $cat->term_id ) ); ?></a>
                                    <?php 
                                        } 
                                    }
                                    ?>
                                </span>

                                <h4 class="post-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h4>
<!-- comment out duration of walk and price of walk metas -->
                                <div class="trip-metas clear">
                                    <div class="wp-travel-trip-time trip-fixed-departure">
                                        <?php
                                        if ( 'trip' === $content_type && class_exists( 'WP_Travel' ) ) {
                                            // commented out trip duration here so it doesn't show
                                            //wp_travel_get_trip_duration( $content['id'] );
                                        } else {
                                            //echo '<i class="fa fa-calendar"></i>';
                                           // echo esc_html( get_the_date( get_option( 'date_format' ), $content['id'] ) );
                                         }
                                        ?>
                                    </div>

                                    <?php 
                                    if ( 'trip' === $content_type && class_exists( 'WP_Travel' ) ) { 
                                         $trip_price = wp_travel_get_trip_price( $content['id'] );
                                        $settings        = wp_travel_get_settings();
                                        $currency_code   = ( isset( $settings['currency'] ) ) ? $settings['currency'] : '';
                                        $currency_symbol = wp_travel_get_currency_symbol( $currency_code );
                                        ?>
                                        <div class="price-meta">
                                            <!-- removed php classes here so no trip price is shown -->
                                            <span><span class="trip-price"></span></span>
                                            <!-- .trip-price -->
                                        </div> 
                                        <!-- .price-meta -->
                                    <?php } ?>
                                </div><!-- .trip-metas -->
                            </div><!-- .entry-container -->
                        </div><!-- .package-wrapper -->
                    </article>
                <?php $i++; 
                endforeach; ?>
            </div><!-- .packages-content -->   
        </div><!-- wrapper -->
    </div><!-- packages -->
    
<?php } ?>

<?php
// strip the text Archives:... from the page title for itineraries by changing a filter in the get_the_archive_title function 

// Simply remove anything that looks like an archive title prefix ("Archive:", "Foo:", "Bar:").
add_filter('get_the_archive_title', function ($title) {
    return preg_replace('/^\w+: /', '', "All Walks");
});

?>

<?php
// about me section on front page

function travel_ultimate_render_about_section( $content_details = array(), $tour_details = array() ) {
    $options = travel_ultimate_get_theme_options();

    if ( empty( $content_details ) && empty( $tour_details ) ) {
        return;
    } 
    ?>
    
   <div id="about-us" class="relative page-section" style="background-image: url(<?php echo get_template_directory_uri() . '/assets/uploads/gray-pattern.png'; ?>);">

        <?php if ( ! empty( $content_details ) ) { ?>
            <div class="wrapper">
                <?php foreach ( $content_details as $content ) : ?>
                    <div class="header-content-wrapper clear">
                        <div class="section-header">
                            <?php if ( ! empty( $content['title'] ) ) : ?>
                                <h2 class="section-title"><?php pl_e( esc_html( $content['title'] )); ?></h2>
                            <?php endif; ?>
                        </div><!-- .section-header -->

                        <?php if ( ! empty( $content['excerpt'] ) ) : ?>
                            <div class="section-content">
                                <p><?php echo wp_kses_post( $content['excerpt'] ); ?> <a href="/about/"><?php pl_e('Read More.'); ?></a></p>
                            </div><!-- .section-content -->
                        <?php endif; ?>
                    </div><!-- .header-content-wrapper -->
                <?php endforeach; ?>
            </div><!-- .wrapper -->
        <?php }; ?>

        <?php if ( ! empty( $tour_details ) ) { ?>
            <!-- use classes "classic-slider and modern-slider" -->
            <div class="tours-slider classic-slider" data-slick='{"slidesToShow": 4, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": false, "arrows":true, "autoplay": false, "draggable": true, "fade": false }'>
                <?php 
                $i = 1;
                foreach ( $tour_details as $tour ) : 
                    $img = ( ! empty( $options['tour_image_' . $i ] ) ) ? $options['tour_image_' . $i ] : '';
                    ?>
                    <article>
                        <div class="tour-item-wrapper" style="background-image: url('<?php echo esc_url( $img ); ?>');">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <?php if ( ! empty( $tour['name'] ) ) { ?>
                                        <a href="<?php echo esc_url( $tour['url'] ); ?>">
                                            <?php echo esc_html( $tour['name'] ); ?>
                                        </a>
                                    <?php } ?>
                                </h2>
                                <?php if ( ! empty( $tour['count'] ) ) { ?>
                                    <span><?php echo absint( $tour['count'] ) . esc_html__( ' Walks', 'travel-ultimate' ); ?></span>
                                <?php } ?>
                            </header>
                        </div><!-- .tour-item-wrapper -->
                    </article>
                <?php 
                $i++;
                endforeach; ?>
            </div><!-- .tours-slider -->
        <?php } ?>
    </div><!-- #skills -->

<?php
} ?>


<?php
//front page testimonial section

function travel_ultimate_render_testimonial_section( $content_details = array() ) {
    $options = travel_ultimate_get_theme_options();
    if ( empty( $content_details ) ) {
        return;
    } ?>

    <div id="client-testimonial" class="relative page-section" style="background-image: url(<?php echo get_template_directory_uri() . '/assets/uploads/gray-pattern.png'; ?> );">
            <div class="wrapper">
                <div class="section-header">
                    <?php if ( ! empty( $options['testimonial_title'] ) ) : ?>
                        <h2 class="section-title"><?php pl_e( esc_html( $options['testimonial_title'] )); ?></h2>
                    <?php endif; ?>
                </div><!-- .section-header -->

                <!-- supports col-1, col-2, col-3 -->
                <div class="section-content clear col-3">
                    <!--?php foreach ( $content_details as $content ) : ?> -->
                        <article>
                            <div class="image-title-wrapper clear">
                                <!-- ?php if ( ! empty( $content['image'] ) ) : ?> 
                                    <img src="?php echo esc_url( $content['image'] ); ?>">
                                ?php endif; ?> -->
                                <!-- add font awesome style --> 
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                                <header class="entry-header">
                                    <!-- ?php if ( ! empty( $content['title'] ) ) : ?> -->
                                        <h2 class="entry-title"><!--<a href="?php echo esc_url( $content['url'] ); ?>">?php echo esc_html( $content['title'] ); ?></a>--><a href="https://www.facebook.com/Walk-in-Bruges-111127243762999/" class="fa fa-facebook"></a></h2>
                                    <!-- ?php endif; ?> -->
                                </header>
                            </div><!-- .image-title-wrapper -->
                            

                            <?php if ( ! empty( $content['excerpt'] ) ) : ?>
                                <div class="entry-content">
                                    <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                </div><!-- .entry-content -->
                            <?php endif; ?>
                        </article>
                        <article>
                            <div class="image-title-wrapper clear">
                                <!-- ?php if ( ! empty( $content['image'] ) ) : ?> 
                                    <img src="?php echo esc_url( $content['image'] ); ?>">
                                ?php endif; ?> -->
                                <!-- add font awesome style --> 
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                                <header class="entry-header">
                                    <!-- ?php if ( ! empty( $content['title'] ) ) : ?> -->
                                        <h2 class="entry-title"><!--<a href="?php echo esc_url( $content['url'] ); ?>">?php echo esc_html( $content['title'] ); ?></a>--><a href="https://www.tripadvisor.be/Attraction_Review-g188671-d17610599-Reviews-Walk_in_Bruges-Bruges_West_Flanders_Province.html" class="fa fa-tripadvisor"></a></h2>
                                    <!-- ?php endif; ?> -->
                                </header>
                            </div><!-- .image-title-wrapper -->
                            

                            <?php if ( ! empty( $content['excerpt'] ) ) : ?>
                                <div class="entry-content">
                                    <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                </div><!-- .entry-content -->
                            <?php endif; ?>
                        </article>
                        <article>
                            <div class="image-title-wrapper clear">
                                <!-- ?php if ( ! empty( $content['image'] ) ) : ?> 
                                    <img src="?php echo esc_url( $content['image'] ); ?>">
                                ?php endif; ?> -->
                                <!-- add font awesome style --> 
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                                <header class="entry-header">
                                    <!-- ?php if ( ! empty( $content['title'] ) ) : ?> -->
                                        <h2 class="entry-title"><!--<a href="?php echo esc_url( $content['url'] ); ?>">?php echo esc_html( $content['title'] ); ?></a>--><a href="https://www.instagram.com/walkinbruges/" class="fa fa-instagram"></a></h2>
                                    <!-- ?php endif; ?> -->
                                </header>
                            </div><!-- .image-title-wrapper -->
                            

                            <?php if ( ! empty( $content['excerpt'] ) ) : ?>
                                <div class="entry-content">
                                    <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                </div><!-- .entry-content -->
                            <?php endif; ?>
                        </article>
                    <!-- ?php endforeach; ?> -->
                </div>
            </div><!-- .wrapper -->
        </div><!-- #clients-section -->

<?php } ?>