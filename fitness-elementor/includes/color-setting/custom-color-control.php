<?php

  $fitness_elementor_theme_custom_setting_css = '';

	// Global Color
	$fitness_elementor_theme_color = get_theme_mod('fitness_elementor_theme_color', '#C1E503');

	$fitness_elementor_theme_custom_setting_css .=':root {';
		$fitness_elementor_theme_custom_setting_css .='--primary-color: '.esc_attr($fitness_elementor_theme_color ).'!important;';
	$fitness_elementor_theme_custom_setting_css .='}';