<?php

function get_page_id_by_title($fitness_elementor_pagename){
  $fitness_elementor_args = array(
 'post_type' => 'page',
 'posts_per_page' => 1,
 'title' => $fitness_elementor_pagename
  );
  $fitness_elementor_query = new WP_Query( $fitness_elementor_args );    $fitness_elementor_page_id = '1';
 if (isset($fitness_elementor_query->post->ID)) {
      $fitness_elementor_page_id = $fitness_elementor_query->post->ID;
  } return $fitness_elementor_page_id;
}
//about theme info
add_action( 'admin_menu', 'fitness_elementor_gettingstarted' );
function fitness_elementor_gettingstarted() {
	add_theme_page( esc_html__('Fitness Elementor', 'fitness-elementor'), esc_html__('Fitness Elementor', 'fitness-elementor'), 'edit_theme_options', 'fitness_elementor_about', 'fitness_elementor_mostrar_guide');
}

// Add a Custom CSS file to WP Admin Area
function fitness_elementor_admin_theme_style() {
	wp_enqueue_style('fitness-elementor-custom-admin-style', esc_url(get_template_directory_uri()) . '/includes/getstart/getstart.css');
	wp_enqueue_script('fitness-elementor-tabs', esc_url(get_template_directory_uri()) . '/includes/getstart/js/tab.js');
	wp_enqueue_style( 'font-awesome-css', get_template_directory_uri().'/assets/css/fontawesome-all.css' );

	// Admin notice code START
	wp_register_script('fitness-elementor-notice', esc_url(get_template_directory_uri()) . '/includes/getstart/js/notice.js', array('jquery'), time(), true);
	wp_enqueue_script('fitness-elementor-notice');
	// Admin notice code END
}
add_action('admin_enqueue_scripts', 'fitness_elementor_admin_theme_style');

// Changelog
if ( ! defined( 'FITNESS_ELEMENTOR_CHANGELOG_URL' ) ) {
    define( 'FITNESS_ELEMENTOR_CHANGELOG_URL', get_template_directory() . '/readme.txt' );
}

function fitness_elementor_changelog_screen() {
	global $wp_filesystem;
	$fitness_elementor_changelog_file = apply_filters( 'fitness_elementor_changelog_file', FITNESS_ELEMENTOR_CHANGELOG_URL );

	if ( $fitness_elementor_changelog_file && is_readable( $fitness_elementor_changelog_file ) ) {
		WP_Filesystem();
		$fitness_elementor_changelog = $wp_filesystem->get_contents( $fitness_elementor_changelog_file );
		$fitness_elementor_changelog_list = fitness_elementor_parse_changelog( $fitness_elementor_changelog );

		
		echo '<div id="fitness-elementor-changelog-container">';
		echo wp_kses_post( $fitness_elementor_changelog_list );
		echo '</div>';
		echo '<button id="fitness-elementor-load-more" class="button button-primary" style="margin-top:15px;">Load More</button>';
	}
}

function fitness_elementor_parse_changelog( $fitness_elementor_content ) {
	$fitness_elementor_content = explode ( '== ', $fitness_elementor_content );
	$fitness_elementor_changelog_isolated = '';

	foreach ( $fitness_elementor_content as $key => $fitness_elementor_value ) {
		if ( strpos( $fitness_elementor_value, 'Changelog ==' ) === 0 ) {
	    	$fitness_elementor_changelog_isolated = str_replace( 'Changelog ==', '', $fitness_elementor_value );
	    }
	}

	$fitness_elementor_changelog_array = explode( '= ', $fitness_elementor_changelog_isolated );
	unset( $fitness_elementor_changelog_array[0] );

	$fitness_elementor_changelog = '<div class="changelog">';
	foreach ( $fitness_elementor_changelog_array as $fitness_elementor_value ) {
		$fitness_elementor_value = preg_replace( '/\n+/', '</span><span>', $fitness_elementor_value );
		$fitness_elementor_value = '<div class="block-changelog"><span class="heading">= ' . $fitness_elementor_value . '</span></div>';
		$fitness_elementor_changelog .= str_replace( '<span></span>', '', $fitness_elementor_value );
	}
	$fitness_elementor_changelog .= '</div>';

	return wp_kses_post( $fitness_elementor_changelog );
}

//guidline for about theme
function fitness_elementor_mostrar_guide() { 
	//custom function about theme customizer
	$fitness_elementor_return = add_query_arg( array()) ;
	$fitness_elementor_theme = wp_get_theme( 'fitness-elementor' );
	?>
<div class="container-getstarted">
		<div class="inner-side-content1">
			<div class="tab-outer-box">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/includes/getstart/images/sticky-header-logo.png" />
			</div>
		    <div class="coupon-container-box-left">
			    <div class="iner-sidebar-pro-btn">
				    <span class="premium-btn"><a href="<?php echo esc_url( FITNESS_ELEMENTOR_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Premium Theme', 'fitness-elementor'); ?></a>
				    </span>
			    </div>
		    </div>
        </div>					
   <div class="top-head">
	    <div class="top-title">
		     <h2><?php esc_html_e( 'Fitness Elementor', 'fitness-elementor' ); ?></h2>
		     <h4><?php esc_html_e( 'Welcome to WP Elemento Theme!', 'fitness-elementor' ); ?></h4>
		     <p><?php esc_html_e( 'Click on the quick start button to import the demo.', 'fitness-elementor' ); ?></p>
			    <div class="iner-sidebar-pro-btn">
					<?php if(!class_exists('WPElemento_Importer_ThemeWhizzie')){
						$fitness_elementor_plugin_ins = Fitness_Elementor_Plugin_Activation_WPElemento_Importer::get_instance();
						$fitness_elementor_actions = $fitness_elementor_plugin_ins->fitness_elementor_recommended_actions;
					?>
					<div class="fitness-elementor-recommended-plugins ">
						<div class="fitness-elementor-action-list">
							<?php if ($fitness_elementor_actions): foreach ($fitness_elementor_actions as $fitness_elementor_key => $fitness_elementor_actionValue): ?>
									<div class="fitness-elementor-action" id="<?php echo esc_attr($fitness_elementor_actionValue['id']);?>">
										<div class="action-inner plugin-activation-redirect">
											<?php echo wp_kses_post($fitness_elementor_actionValue['link']); ?>
										</div>
									</div>
								<?php endforeach;
							endif; ?>
						</div>
					</div>
				   <?php }else{ ?>
					<span class="quick-btn">
				    <?php if (isset($_GET['imported']) && $_GET['imported'] == 'true'): ?>
                        <a href="<?php echo esc_url( site_url() ); ?>" target="_blank"><?php esc_html_e('Visit Site', 'fitness-elementor'); ?></a>
						<?php
						$fitness_elementor_page_id = get_page_id_by_title('Home');
						?>
						<a href="<?php echo esc_url( admin_url('post.php?post=' . $fitness_elementor_page_id . '&action=elementor') ); ?>" 
							target="_blank" class="elementor-edit-btn"><?php esc_html_e('Edit With Elementor', 'fitness-elementor'); ?>
						</a>
                    <?php else: ?>
                        <a href="<?php echo esc_url( admin_url('admin.php?page=wpelementoimporter-wizard') ); ?>"><?php esc_html_e('Quick Start', 'fitness-elementor'); ?></a>
                    <?php endif; ?>
					<?php } ?>
				   </span>
				    <span class="premium-btn"><a href="<?php echo esc_url( FITNESS_ELEMENTOR_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Premium', 'fitness-elementor'); ?></a>
				    </span>
				    <span class="demo-btn"><a href="<?php echo esc_url( FITNESS_ELEMENTOR_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'fitness-elementor'); ?></a>
				    </span>
				    <span class="doc-btn"><a href="<?php echo esc_url( FITNESS_ELEMENTOR_THEME_BUNDLE ); ?>" target="_blank"><?php esc_html_e('Theme Bundle at $79', 'fitness-elementor'); ?></a>
				    </span>
			    </div>
            </div>			
		<div class="inner-side-content">
			<div class="tab-outer-box">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/screenshot.png" />
			</div>
			<div class="top-right">
			  <span class="version"><?php esc_html_e( 'Version', 'fitness-elementor' ); ?>: <?php echo esc_html($fitness_elementor_theme['Version']);?></span>
		    </div>
		</div>
    </div>
    <div class="inner-cont">
	    <div class="tab-outer-box1">
		   <div class="tab-inner-box">
			   <div class= "bundle-box">
				    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/includes/getstart/images/bundle.png"/>
				    <h1><?php esc_html_e('ELEMENTOR WORDPRESS THEME BUNDLE', 'fitness-elementor'); ?></h1>
			     <div>
				    <p class="product-price"><?php esc_html_e('Price:', 'fitness-elementor'); ?>
                        <span class="regular-price"><?php esc_html_e('$1,999.00', 'fitness-elementor'); ?></span>
                        <span class="sale-price"><?php esc_html_e('$79.00', 'fitness-elementor'); ?></span>
                    </p>
					<p><?php esc_html_e('The Elementor WordPress Theme Bundle offers a stunning collection of 76+ Premium Elementor Themes', 'fitness-elementor'); ?></p>
                 </div>
				</div> 
			    <div class="offer-box"> 
				    <div class="offer1-box">
                       <span class="off-text1"><a href="<?php echo esc_url( FITNESS_ELEMENTOR_THEME_BUNDLE ); ?>" target="_blank"><?php esc_html_e('Buy Bundle at 20% Discount', 'fitness-elementor'); ?></a></span>
				    </div> 
		        </div>
			</div>	
		</div>	
		<div class="tab-outer-box2">
			<div class="tab-outer-box-2-1">
			  <h3><?php esc_html_e( 'Customizer Setting', 'fitness-elementor' ); ?></h3>
			  <div class="lite-theme-inner">
				<div>
					<h3><?php esc_html_e('Theme Customizer', 'fitness-elementor'); ?></h3>
					<p><?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'fitness-elementor'); ?></p>
					<div class="info-link">
					   <a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Open customizer', 'fitness-elementor'); ?></a>
					</div>
				</div>
				<div>
					<h3><?php esc_html_e('Help Docs', 'fitness-elementor'); ?></h3>
					<p><?php esc_html_e('The complete procedure to configure and manage a WordPress Website from the beginning is shown in this documentation .', 'fitness-elementor'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( FITNESS_ELEMENTOR_FREE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation', 'fitness-elementor'); ?></a>
					</div>
				</div>
				<div>
					<h3><?php esc_html_e('Need Support?', 'fitness-elementor'); ?></h3>
					<p><?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'fitness-elementor'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( FITNESS_ELEMENTOR_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'fitness-elementor'); ?></a>
					</div>
				</div>
				<div>
					<h3><?php esc_html_e('Reviews & Testimonials', 'fitness-elementor'); ?></h3>
					<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'fitness-elementor'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( FITNESS_ELEMENTOR_REVIEW ); ?>" target="_blank"><?php esc_html_e('Review', 'fitness-elementor'); ?></a>
					</div>
				</div>
            </div>	
		</div>
			<div class="tab-outer-box-2-2">
			  <h3><?php esc_html_e( 'Link to customizer', 'fitness-elementor' ); ?></h3>
				<div class="first-row">
					<div class="row-box">
						<div class="row-box1">
							<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your Website logo','fitness-elementor'); ?></a>
						</div>
						<div class="row-box2">
							<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Edit Your Menus','fitness-elementor'); ?></a>
						</div>
					</div>
							
					<div class="row-box">
						<div class="row-box1">
							<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=header_image') ); ?>" target="_blank"><?php esc_html_e('Add Header Image','fitness-elementor'); ?></a>
						</div>
						<div class="row-box2">
							<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Add Footer Widget','fitness-elementor'); ?></a>
						</div>
					</div>
				</div>
            </div>	
			<div class="tab-outer-box-2-3">
				<h3><?php esc_html_e( 'Change log', 'fitness-elementor' ); ?></h3>	
		     <?php fitness_elementor_changelog_screen(); ?>
          </div>	
        </div>
    </div>
</div>	
<?php } ?>