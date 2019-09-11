<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
 }


 // changing the order of sections on the front page. function originally in /inc/options.php

function travel_ultimate_sortable_sections() {
    $sections = array(
        'slider'    => esc_html__( 'Slider', 'travel-ultimate' ),
        'package'   => esc_html__( 'Package', 'travel-ultimate' ),
        'cta'    => esc_html__( 'CTA', 'travel-ultimate' ),
        'search'    => esc_html__( 'Search', 'travel-ultimate' ),
        'about'     => esc_html__( 'About Us', 'travel-ultimate' ),
        'destination'     => esc_html__( 'Destination', 'travel-ultimate' ),
        'testimonial' => esc_html__( 'Testimonial', 'travel-ultimate' ),
        'event' => esc_html__( 'Event', 'travel-ultimate' ),
    );
    return apply_filters( 'travel_ultimate_sortable_sections', $sections );
}

// Custom 'Tour' post type
function create_posttype() {
 
    register_post_type( 'tours',
    // CPT Options
        array(  
            'labels' => array(
                'name' => __( 'Tours' ),
                'singular_name' => __( 'Tour' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'movies'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

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


// customizing packages section to show a custom content type


function travel_ultimate_get_package_section_details( $input ) {
    $options = travel_ultimate_get_theme_options();

    // Content type.
    $package_content_type  = $options['package_content_type'];
    
    $content = array();
    switch ( $package_content_type ) {
        
        case 'page':
            $page_ids = array();

            for ( $i = 1; $i <= 3; $i++ ) {
                if ( ! empty( $options['package_content_page_' . $i] ) )
                    $page_ids[] = $options['package_content_page_' . $i];
            }
            
            $args = array(
                'post_type'         => 'page',
                'post__in'          => ( array ) $page_ids,
                'posts_per_page'    => 3,
                'orderby'           => 'post__in',
                );                    
        break;

        case 'trip':

            if ( ! class_exists( 'WP_Travel' ) )
                return;
            
            $trip_ids = array();

            for ( $i = 1; $i <= 3; $i++ ) {
                if ( ! empty( $options['package_content_trip_' . $i] ) )
                    $trip_ids[] = $options['package_content_trip_' . $i];
            }
            
            $args = array(
                'post_type'         => 'itineraries',
                'post__in'          => ( array ) $trip_ids,
                'posts_per_page'    => 3,
                'orderby'           => 'post__in',
                'ignore_sticky_posts'   => true,
                );                    
        break;

        default:
        break;
    }


        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']     = get_the_ID();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['img']       = get_the_post_thumbnail_url( get_the_ID(),  'large' );
                $page_post['excerpt']   = travel_ultimate_trim_content( 15 );

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
// package section content details.
add_filter( 'travel_ultimate_filter_package_section_details', 'travel_ultimate_get_package_section_details' );



?>