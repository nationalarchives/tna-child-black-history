<?php

require_once 'src/setThemeGlobals.php';
require_once 'src/identifyEnvironmentFromIP.php';

// For Breadcrumbs and URLs
$environment = identifyEnvironmentFromIP($_SERVER['SERVER_ADDR'], $_SERVER['REMOTE_ADDR']);
setThemeGlobals($environment);

// Dequeue parent styles for re-enqueuing in the correct order
function dequeue_parent_style()
{
    wp_dequeue_style('tna-styles');
    wp_deregister_style('tna-styles');
}

add_action('wp_enqueue_scripts', 'dequeue_parent_style', 9999);
add_action('wp_head', 'dequeue_parent_style', 9999);

// Enqueue styles in correct order
function tna_child_styles()
{
    wp_register_style('tna-parent-styles', get_template_directory_uri() . '/css/base-sass.min.css', array(),
        EDD_VERSION, 'all');
    wp_register_style('tna-child-styles', get_stylesheet_directory_uri() . '/style.css', array(), '0.1', 'all');
    wp_enqueue_style('tna-parent-styles');
    wp_enqueue_style('tna-child-styles');
}
add_action('wp_enqueue_scripts', 'tna_child_styles');

function tna_child_scripts() {
    // if ( is_front_page() ) {
        wp_register_script( 'black-history', get_stylesheet_directory_uri() . '/js/black-history.js', array(),
            EDD_VERSION, true );
        wp_register_script( 'equal-heights', get_template_directory_uri() . '/js/jQuery.equalHeights.js', array(),
            EDD_VERSION, true );
        wp_register_script( 'equal-heights-var', get_template_directory_uri() . '/js/equalHeights.js', array(),
            EDD_VERSION, true );
        wp_register_script( 'moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js', '', '', true );
        wp_enqueue_script( 'black-history' );
        wp_enqueue_script( 'equal-heights' );
        wp_script_add_data( 'equal-heights', 'conditional', 'lte IE 9' );
        wp_enqueue_script( 'equal-heights-var' );
        wp_script_add_data( 'equal-heights-var', 'conditional', 'lte IE 9' );
        wp_enqueue_script( 'moment-js' );
    // }
}
add_action( 'wp_enqueue_scripts', 'tna_child_scripts' );

// Dynamic blog content via RSS feed
function tna_posts_via_rss( $rss_url, $content_source_name, $number_of_posts, $id ) {
        // Get feed source
        $file_headers = @get_headers($rss_url);
        if ( !$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found' ) {
            $content = false;
        } else {
            $content = file_get_contents( $rss_url );
        }
        if ( $content !== false ) {
            $x = new SimpleXmlElement( $content );
            $n = 0;
            $html = '';

            // Loop through each feed item and display each item
            foreach ( $x->channel->item as $item ) {
                if ( $n == $number_of_posts ) {
                    break;
                }
                if ( isset( $item->enclosure['url'] ) ) {
                    $enclosure  = $item->enclosure['url'];
                } else {
                    $enclosure = 'https://placehold.it/360x180?text=' . $content_source_name;
                }
                $namespaces = $item->getNameSpaces( true );
                $dc         = $item->children( $namespaces['dc'] );
                $pubDate    = $item->pubDate;
                $pubDate    = date( "l d M Y", strtotime( $pubDate ) );
                $description = $item->description;
                if ( strlen( $description ) > 128 ) {
                    $description = substr( $description, 0, 128 ) . '...';
                    if ( substr ( $description, -1, 1 ) != ' ') {
                        $description = substr( $description, 0, strrpos( $description, " " ) ) . '...';
                    }
                }
                $html .= '<div id="' . $id . '-' . $n . '" class="col-sm-4 col-md-4"><div class="card clearfix">';
                $html .= '<div class="entry-thumbnail" style="background: url(' . $enclosure . ') no-repeat center center;background-size: cover;">';
                $html .= '<a href="' . $item->link . '" title="' . $item->title . '">';
                // $html .= '<img src="' . $enclosure . '" alt="' . $item->title . '" class="hidden">';
                $html .= '</a></div>';
                $html .= '<div class="entry-content"><div class="type"><small>' . $content_source_name . '</small></div><h3><a href="' . $item->link . '">';
                $html .= $item->title;
                $html .= '</a></h3>';
                $html .= '<small>' . $dc->creator . ' | ' . $pubDate . '</small>';
                $html .= '<p>' . $description . '</p></div>';
                $html .= '</div></div>';
                $n ++;
            }

            echo $html;
        }
        else {
            echo '<div class="col-md-4"><div class="card"><div class="entry-content"><h2>Black British History ' . $content_source_name . '</h2><ul class="sibling"><li><a href="' . str_replace( 'feed/', '', $rss_url ) . '">More ' . $content_source_name . '</a></li></ul></div></div></div>';
        }
}

// Home page content boxes
function black_history_meta_boxes() {
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } else {
        if (isset($_POST['post_ID'])) {
            $post_id = $_POST['post_ID'];
        } else {
            $post_id = '';
        }
    }
    $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
    if ($template_file == 'page-black-history-landing.php') {
        $meta_boxes[] = array(
            'id'       => 'bh-research-section',
            'title'    => 'Research guide content',
            'pages'    => 'page',
            'context'  => 'normal',
            'priority' => 'high',
            'fields'   => array(
                array(
                    'name' => 'Research guide title',
                    'desc' => '',
                    'id'   => 'research_guide_heading',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Title link',
                    'desc' => 'Full URL path',
                    'id'   => 'research_guide_link',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Research guide description',
                    'desc' => '',
                    'id'   => 'research_guide_description',
                    'type' => 'textarea',
                    'std'  => ''
                )
            )
        );
        $meta_boxes[] = array(
            'id'       => 'bh-first-section',
            'title'    => 'First content section',
            'pages'    => 'page',
            'context'  => 'normal',
            'priority' => 'high',
            'fields'   => array(
                array(
                    'name' => 'First section heading',
                    'desc' => 'If the heading field is empty this content section will be disabled',
                    'id'   => 'first_section_heading',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Title 1',
                    'desc' => '',
                    'id'   => 'sec_1_title_1',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Link 1',
                    'desc' => '',
                    'id'   => 'sec_1_link_1',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Image URL 1',
                    'desc' => '',
                    'id'   => 'sec_1_image_1',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Short description 1',
                    'desc' => '',
                    'id'   => 'sec_1_description_1',
                    'type' => 'textarea',
                    'std'  => ''
                ),
                array(
                    'name' => 'Title 2',
                    'desc' => '',
                    'id'   => 'sec_1_title_2',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Link 2',
                    'desc' => '',
                    'id'   => 'sec_1_link_2',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Image URL 2',
                    'desc' => '',
                    'id'   => 'sec_1_image_2',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Short description 2',
                    'desc' => '',
                    'id'   => 'sec_1_description_2',
                    'type' => 'textarea',
                    'std'  => ''
                ),
                array(
                    'name' => 'Title 3',
                    'desc' => '',
                    'id'   => 'sec_1_title_3',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Link 3',
                    'desc' => '',
                    'id'   => 'sec_1_link_3',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Image URL 3',
                    'desc' => '',
                    'id'   => 'sec_1_image_3',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Short description 3',
                    'desc' => '',
                    'id'   => 'sec_1_description_3',
                    'type' => 'textarea',
                    'std'  => ''
                )
            )
        );
        $meta_boxes[] = array(
            'id'       => 'bh-second-section',
            'title'    => 'Second content section',
            'pages'    => 'page',
            'context'  => 'normal',
            'priority' => 'high',
            'fields'   => array(
                array(
                    'name' => 'Display section content boxes',
                    'desc' => 'Select &#39;disabled&#39; to hide these boxes',
                    'id' => 'second_section_display',
                    'type' => 'select',
                    'options' => array('disabled', 'enabled')
                ),
                array(
                    'name' => 'Title 1',
                    'desc' => '',
                    'id'   => 'content_title_1',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Sub title 1',
                    'desc' => '',
                    'id'   => 'content_sub_title_1',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Image URL 1',
                    'desc' => '',
                    'id'   => 'content_image_1',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Content 1',
                    'desc' => '',
                    'id'   => 'content_1',
                    'type' => 'textarea',
                    'std'  => ''
                ),
                array(
                    'name' => 'Title 2',
                    'desc' => '',
                    'id'   => 'content_title_2',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Sub title 2',
                    'desc' => '',
                    'id'   => 'content_sub_title_2',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Image URL 2',
                    'desc' => '',
                    'id'   => 'content_image_2',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Content 2',
                    'desc' => '',
                    'id'   => 'content_2',
                    'type' => 'textarea',
                    'std'  => ''
                ),
                array(
                    'name' => 'Title 3',
                    'desc' => '',
                    'id'   => 'content_title_3',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Sub title 3',
                    'desc' => '',
                    'id'   => 'content_sub_title_3',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Image URL 3',
                    'desc' => '',
                    'id'   => 'content_image_3',
                    'type' => 'text',
                    'std'  => ''
                ),
                array(
                    'name' => 'Content 3',
                    'desc' => '',
                    'id'   => 'content_3',
                    'type' => 'textarea',
                    'std'  => ''
                )
            )
        );
        // Adds meta box to page
        foreach ( $meta_boxes as $meta_box ) {
            $form_box = new CreateMetaBox( $meta_box );
        }
    }
}
add_action( 'init', 'black_history_meta_boxes' );
