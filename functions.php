<?php

add_action( 'after_setup_theme', 'ucla_setup' );

function ucla_setup() {

  add_theme_support( 'title-tag' );
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'html5', array( 'search-form' ) );

  /**
 * Enable features from Soil when plugin is activated
 * @link https://roots.io/plugins/soil/
 */
  add_theme_support('soil-clean-up');
  add_theme_support('soil-js-to-footer');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-relative-urls');

  global $content_width;


  if ( ! isset( $content_width ) ) { $content_width = 1920; }
    register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'ucla' ) ) );
  }

  // Load Theme Scripts and Styles
  add_action( 'wp_enqueue_scripts', 'ucla_load_scripts' );
  function ucla_load_scripts() {
    // CDN jQuery from Google
    wp_enqueue_script( 'jq', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
    // Install the UCLA Component library styles
    wp_enqueue_style( 'lib-style', '/wp-content/themes/ucla-sc/dist/css/ucla-components-library/ucla-lib.min.css' );
    // Install the UCLA Component Library  scripts
    wp_enqueue_script( 'lib-script', '/wp-content/themes/ucla-sc/dist/js/ucla-components-library/ucla-lib-scripts.min.js' );
    // Install the WordPress Theme Styles
    wp_enqueue_style( 'theme-style', '/wp-content/themes/ucla-sc/dist/css/global.css' );
    // Install the WordPress Theme Scripts
    wp_enqueue_script( 'theme-script', '/wp-content/themes/ucla-sc/dist/js/scripts.js' );
  }

  // Load ADMIN Login Styles
  add_action( 'login_enqueue_scripts', 'my_login_page_remove_back_to_link' );
  function my_login_page_remove_back_to_link() {
    // Path the admin page login styles
    wp_enqueue_style( 'login-style', '/wp-content/themes/ucla-sc/admin-styles.css' );
  }

  // Breadcrumbs
  function get_breadcrumb() {
      echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
      if ( is_single()) {
          echo "&nbsp;&nbsp;&#47;&nbsp;&nbsp;";
          echo get_post_type( get_the_ID() );
              if (is_single()) {
                  echo " &nbsp;&nbsp;&#47;&nbsp;&nbsp; ";
                  the_title();
              }
      } elseif (is_page()) {
          echo "&nbsp;&nbsp;&#47;&nbsp;&nbsp;";
          echo the_title();
      } elseif (is_search()) {
          echo "&nbsp;&nbsp;&#47;&nbsp;&nbsp;Search Results for... ";
          echo '"<em>';
          echo the_search_query();
          echo '</em>"';
      }
  }

  // Categories for pages
  add_action( 'init', 'ucla_page_categories' );
  function ucla_page_categories() {
    register_taxonomy_for_object_type( 'category', 'page' );
  }

  // Taxonomy for pages
  add_action( 'init', 'ucla_page_tags');
  function ucla_page_tags() {
    register_taxonomy_for_object_type( 'post_tag', 'page' );
  }

  // Title Seperator
  add_filter( 'document_title_separator', 'ucla_document_title_separator' );
  function ucla_document_title_separator( $sep ) {
    $sep = '|';
    return $sep;
  }

  // Title
  add_filter( 'the_title', 'ucla_title' );
  function ucla_title( $title ) {
    if ( $title == '' ) {
      return '...';
    } else {
      return $title;
    }
  }

  // Read More Link
  add_filter( 'the_content_more_link', 'ucla_read_more_link' );
  function ucla_read_more_link() {
    if ( ! is_admin() ) {
      return '';
      // return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">More on this topic.</a>';
    }
  }

  // The Excerpt Link
  add_filter( 'excerpt_more', 'ucla_excerpt_read_more_link' );
  function ucla_excerpt_read_more_link( $more ) {
    if ( ! is_admin() ) {
      global $post;
      return '';
      // return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">More on this topic.</a>';
    }
  }

  // Filter the except length to 20 words.
  // function wpdocs_custom_excerpt_length( $length ) {
  //     return 20;
  // }
  // add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

  // Image Sizing
  add_filter( 'intermediate_image_sizes_advanced', 'ucla_image_insert_override' );
  function ucla_image_insert_override( $sizes ) {
    unset( $sizes['medium_large'] );
    return $sizes;
  }

  // Add Sidebar widget
  add_action( 'widgets_init', 'ucla_widgets_init' );
  function ucla_widgets_init() {
    register_sidebar( array(
      'name' => esc_html__( 'Sidebar Widget Area', 'ucla' ),
      'id' => 'primary-widget-area',
      'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
      'after_widget' => '</li>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ) );

    register_sidebar( array(
      'name' => esc_html__( 'View Updates by Tags', 'ucla' ),
      'id' => 'tags-widget-area',
      'before_widget' => '<span id="%1$s" class="widget-container %2$s">',
      'after_widget' => '</span>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ) );
  }
