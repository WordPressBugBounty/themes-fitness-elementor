<?php

  $fitness_elementor_theme_custom_setting_css = '';

	// Global Color
	$fitness_elementor_theme_color = get_theme_mod('fitness_elementor_theme_color', '#C1E503');

	$fitness_elementor_theme_custom_setting_css .=':root {';
		$fitness_elementor_theme_custom_setting_css .='--primary-theme-color: '.esc_attr($fitness_elementor_theme_color ).'!important;';
	$fitness_elementor_theme_custom_setting_css .='}';

	// Scroll to top alignment
	$fitness_elementor_scroll_alignment = get_theme_mod('fitness_elementor_scroll_alignment', 'right');

    if($fitness_elementor_scroll_alignment == 'right'){
        $fitness_elementor_theme_custom_setting_css .='.scroll-up{';
            $fitness_elementor_theme_custom_setting_css .='right: 30px;!important;';
			$fitness_elementor_theme_custom_setting_css .='left: auto;!important;';
        $fitness_elementor_theme_custom_setting_css .='}';
    }else if($fitness_elementor_scroll_alignment == 'center'){
        $fitness_elementor_theme_custom_setting_css .='.scroll-up{';
            $fitness_elementor_theme_custom_setting_css .='left: calc(50% - 10px) !important;';
        $fitness_elementor_theme_custom_setting_css .='}';
    }else if($fitness_elementor_scroll_alignment == 'left'){
        $fitness_elementor_theme_custom_setting_css .='.scroll-up{';
            $fitness_elementor_theme_custom_setting_css .='left: 30px;!important;';
			$fitness_elementor_theme_custom_setting_css .='right: auto;!important;';
        $fitness_elementor_theme_custom_setting_css .='}';
    }

    // Related Product

	$fitness_elementor_show_related_product = get_theme_mod('fitness_elementor_show_related_product', true );

	if($fitness_elementor_show_related_product != true){
		$fitness_elementor_theme_custom_setting_css .='.related.products{';
			$fitness_elementor_theme_custom_setting_css .='display: none;';
		$fitness_elementor_theme_custom_setting_css .='}';
	}
    
    // Featured Image Hover Effect
    $fitness_elementor_show_featured = get_theme_mod('fitness_elementor_featured_image_hide_show', 1);
    $fitness_elementor_hover_effect = get_theme_mod('fitness_elementor_single_post_featured_image_hover','none');

    if ( $fitness_elementor_show_featured && $fitness_elementor_hover_effect !== 'none' ) {

    $fitness_elementor_theme_custom_setting_css .= '
    .post-img img{
        transition: all 0.4s ease;
    }';

    if ( $fitness_elementor_hover_effect === 'zoom-in' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img:hover img{
            transform: scale(1.2);
        }';
    }

    if ( $fitness_elementor_hover_effect === 'zoom-out' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img img{ transform: scale(1.2); }
        .post-img:hover img{ transform: scale(1); }';
    }

    if ( $fitness_elementor_hover_effect === 'grayscale' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img img{ filter: grayscale(100%); }
        .post-img:hover img{ filter: grayscale(0); }';
    }

    if ( $fitness_elementor_hover_effect === 'sepia' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img:hover img{ filter: sepia(100%); }';
    }

    if ( $fitness_elementor_hover_effect === 'blur' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img:hover img{ filter: blur(3px); }';
    }

    if ( $fitness_elementor_hover_effect === 'bright' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img:hover img{ filter: brightness(1.3); }';
    }

    if ( $fitness_elementor_hover_effect === 'translate' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img:hover img{ transform: translateY(-10px); }';
    }

    if ( $fitness_elementor_hover_effect === 'scale' ) {
        $fitness_elementor_theme_custom_setting_css .= '
        .post-img:hover img{ transform: scale(1.1); }';
    }
}
