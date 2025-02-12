<?php
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'display_post_states', 'bizpress_freshbooks_post_states', 10, 2 );
function bizpress_freshbooks_post_states( $post_states, $post ) {
	if( !function_exists( 'cxbc_get_option' ) ){
		return $post_states;
	}
	$freshbooksPageID = cxbc_get_option( 'bizink-client_basic', 'freshbooks_content_page' );
    if ( $freshbooksPageID == $post->ID ) {
        $post_states['bizpress_freshbooks'] = __('BizPress FreshBooks Resources','bizink-client');
    }
    return $post_states;
}

function freshbooks_settings_fields( $fields, $section ) {
	$pageselect = false;
	if(defined('CXBPC')){
		$bizpress = get_plugin_data( CXBPC );
		$v = intval(str_replace('.','',$bizpress['Version']));
		if($v >= 151){
			$pageselect = true;
		}
	}
	if('bizink-client_basic' == $section['id']){
		$fields['freshbooks_content_page'] = array(
			'id'      => 'freshbooks_content_page',
			'label'     => __( 'FreshBooks Resources', 'bizink-client' ),
			'type'      => $pageselect ? 'pageselect':'select',
			'desc'      => __( 'Select the page to show the content. This page must contain the <code>[bizpress-content]</code> shortcode.', 'bizink-client' ),
			'options'	=> cxbc_get_posts( [ 'post_type' => 'page' ] ),
			'required'	=> false,
			'default_page' => [
				'post_title' => 'FreshBooks Resources',
				'post_content' => '[bizpress-content]',
				'post_status' => 'publish',
				'post_type' => 'page'
			]
		);
	}
	
	if('bizink-client_content' == $section['id']){
		$fields['freshbooks_label'] = array(
			'id' => 'freshbooks',
	        'label'	=> __( 'FreshBooks Resources', 'bizink-client' ),
	        'type' => 'divider'
		);
		$fields['freshbooks_title'] = array(
			'id' => 'freshbooks_title',
			'label'     => __( 'FreshBooks Resources Title', 'bizink-client' ),
			'type'      => 'text',
			'default'   => __( 'FreshBooks Resources', 'bizink-client' ),
			'required'	=> true,
		);
		$fields['freshbooks_desc'] = array(
			'id'      	=> 'freshbooks_desc',
			'label'     => __( 'FreshBooks Resources Description', 'bizink-client' ),
			'type'      => 'textarea',
			'default'   => __( 'Free resources to help you use FreshBooks Resources.', 'bizink-client' ),
			'required'	=> true,
		);
	}

	return $fields;
}
add_filter( 'cx-settings-fields', 'freshbooks_settings_fields', 10, 2 );

function freshbooks_content( $types ) {
	$types[] = [
		'key' 	=> 'freshbooks_content_page',
		'type'	=> 'freshbooks-content'
	];

	return $types;
}
add_filter( 'bizink-content-types', 'freshbooks_content' );

if( !function_exists( 'bizink_get_freshbooks_page_object' ) ){
	function bizink_get_freshbooks_page_object(){
		if( !function_exists( 'cxbc_get_option' ) ){
			return false;
		}
		$post_id = cxbc_get_option( 'bizink-client_basic', 'freshbooks_content_page' );
		$post = get_post( $post_id );
		return $post;
	}
}

add_action( 'init', 'bizink_freshbooks_init');
function bizink_freshbooks_init(){
	$post = bizink_get_freshbooks_page_object();
	if( is_object( $post ) && get_post_type( $post ) == "page" ){
		add_rewrite_tag('%'.$post->post_name.'%', '([^&]+)', 'bizpress=');
		add_rewrite_rule('^'.$post->post_name . '/([^/]+)/?$','index.php?pagename=freshbooks-resources&bizpress=$matches[1]','top');
		add_rewrite_rule("^".$post->post_name."/([a-z0-9-]+)[/]?$",'index.php?pagename=freshbooks-resources&bizpress=$matches[1]','top');
		add_rewrite_rule("^".$post->post_name."/topic/([a-z0-9-]+)[/]?$",'index.php?pagename=freshbooks-resources&topic=$matches[1]','top');
		add_rewrite_rule("^".$post->post_name."/type/([a-z0-9-]+)[/]?$" ,'index.php?pagename=freshbooks-resources&type=$matches[1]','top');

		add_rewrite_tag('%freshbooks_resources.xml%', '([^&]+)', 'bizpressxml=');
		add_rewrite_rule('^(freshbooks_resources\.xml)?$','index.php?bizpressxml=freshbooks_resources','top');

		if(get_option('bizpress_freshbooks_flush_update',0) < 1){
			flush_rewrite_rules();
			update_option('bizpress_freshbooks_flush_update',1);
		}
	}
	
}

add_action('parse_request','bizpress_freshbooksxml_request', 10, 1);
function bizpress_freshbooksxml_request($wp){
	if ( array_key_exists( 'bizpressxml', $wp->query_vars ) && $wp->query_vars['bizpressxml'] == 'freshbooks_resources' ){
		$post = bizink_get_freshbooks_page_object();
		if( is_object( $post ) && get_post_type( $post ) == "page" ){
			$data = get_transient("bizinktype_".md5('freshbooks-content'));
			if(empty($data)){
				$data = bizink_get_content('freshbooks-content', 'topics');
				set_transient( "bizinktype_".md5('freshbooks-content'), $data, (DAY_IN_SECONDS * 2) );
			}
			header('Content-Type: text/xml; charset=UTF-8');
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			$url = get_home_url();
			$url = str_replace('https:','',$url);
			$url = str_replace('http:','',$url);
			echo '<?xml-stylesheet type="text/xsl" href="//'.$url.'/wp-content/plugins/wordpress-seo/css/main-sitemap.xsl"?>';
			echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
			echo '<url>';
			echo '<loc>'.get_home_url().'/'.$post->post_name.'</loc>';
			echo '</url>';
			
			if(empty($data->posts) == false){
				foreach($data->posts as $item){
					echo '<url>';
					echo '<loc>'.get_home_url().'/'.$post->post_name.'/'. $item->slug .'</loc>';
					if($item->thumbnail){
						echo '<image:image>';
						echo '<image:loc>'. $item->thumbnail .'</image:loc>';
						echo '</image:image>'; 
					}
					echo '</url>';
				}
			}
			echo '</urlset>';
		}
		die();
	}
}

add_filter('query_vars', 'bizpress_freshbooks_qurey');
function bizpress_freshbooks_qurey($vars) {
    $vars[] = "bizpress";
    return $vars;
}

add_filter('query_vars', 'bizpress_freshbooksxml_query');
function bizpress_freshbooksxml_query($vars) {
    $vars[] = "bizpressxml";
    return $vars;
}

function bizpress_freshbooks_sitemap_custom_items( $sitemap_custom_items ) {
    $sitemap_custom_items .= '
	<sitemap>
		<loc>'.get_home_url().'/freshbooks_resources.xml</loc>
	</sitemap>';
    return $sitemap_custom_items;
}

add_filter( 'wpseo_sitemap_index', 'bizpress_freshbooks_sitemap_custom_items' );

function bizpress_freshbooks_content_manager_fields($fields){
	$data = null;
	if(function_exists('bizink_get_content')){
		$data = bizink_get_content( 'freshbooks-content', 'topics' );
	}
	$fields['freshbooks'] = array(
		'id' => 'freshbooks',
		'label'	=> __( 'FreshBooks Resources', 'bizink-client' ),
		'posts' => $data ? $data->posts : array(),
	);
	return $fields;
}
add_filter('bizpress_content_manager_fields','bizpress_freshbooks_content_manager_fields');