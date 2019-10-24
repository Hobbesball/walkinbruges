<!-- TOC

- load stylesheets
- change section order on front page
- enable header image on front page
- import google fonts
- customizing CTA front page section

-->

<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
 }


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
					<!--<p id="frontpagetagline"><?php echo esc_html( $site_description ); /* WPCS: xss ok. */ ?></p> -->
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
                                    <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn"><?php echo esc_html( $options['cta_btn_label'] ); ?></a>
                                </div><!-- .view-all -->
                            <?php endif; ?>
                        </div><!-- .entry-container -->
                    </article>
                    
                <?php endforeach; ?>
        </div><!-- .wrapper -->
    </div><!-- .hero-cta-wrapper -->
<?php }

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
                    <span class="section-subtitle"><?php echo esc_html( $options['package_sub_title'] ); ?></span>
                <?php endif; ?>

                <?php if ( ! empty( $options['package_title'] ) ) : ?>
                    <h2 class="section-title"><?php echo esc_html( $options['package_title'] ); ?></h2>
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
    
<?php }
