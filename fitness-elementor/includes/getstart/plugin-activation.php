<?php
if ( ! class_exists( 'Fitness_Elementor_Plugin_Activation_WPElemento_Importer' ) ) {
    /**
     * Fitness_Elementor_Plugin_Activation_WPElemento_Importer initial setup
     *
     * @since 1.6.2
     */

    class Fitness_Elementor_Plugin_Activation_WPElemento_Importer {

        private static $fitness_elementor_instance;
        public $fitness_elementor_action_count;
        public $fitness_elementor_recommended_actions;

        /** Initiator **/
        public static function get_instance() {
          if ( ! isset( self::$fitness_elementor_instance) ) {
            self::$fitness_elementor_instance = new self();
          }
          return self::$fitness_elementor_instance;
        }

        /*  Constructor */
        public function __construct() {

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            // ---------- wpelementoimpoter Plugin Activation -------
            add_filter( 'fitness_elementor_recommended_plugins', array($this, 'fitness_elementor_recommended_elemento_importer_plugins_array') );

            $fitness_elementor_actions                   = $this->fitness_elementor_get_recommended_actions();
            $this->fitness_elementor_action_count        = $fitness_elementor_actions['count'];
            $this->fitness_elementor_recommended_actions = $fitness_elementor_actions['actions'];

            add_action( 'wp_ajax_create_pattern_setup_builder', array( $this, 'create_pattern_setup_builder' ) );
        }

        public function fitness_elementor_recommended_elemento_importer_plugins_array($fitness_elementor_plugins){
            $fitness_elementor_plugins[] = array(
                    'name'     => esc_html__('WPElemento Importer', 'fitness-elementor'),
                    'slug'     =>  'wpelemento-importer',
                    'function' => 'WPElemento_Importer_ThemeWhizzie',
                    'desc'     => esc_html__('We highly recommend installing the WPElemento Importer plugin for importing the demo content with Elementor.', 'fitness-elementor'),               
            );
            return $fitness_elementor_plugins;
        }

        public function enqueue_scripts() {
            wp_enqueue_script('updates');      
            wp_register_script( 'fitness-elementor-plugin-activation-script', esc_url(get_template_directory_uri()) . '/includes/getstart/js/plugin-activation.js', array('jquery') );
            wp_localize_script('fitness-elementor-plugin-activation-script', 'fitness_elementor_plugin_activate_plugin',
                array(
                    'installing' => esc_html__('Installing', 'fitness-elementor'),
                    'activating' => esc_html__('Activating', 'fitness-elementor'),
                    'error' => esc_html__('Error', 'fitness-elementor'),
                    'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                    'wpelementoimpoter_admin_url' => esc_url(admin_url('admin.php?page=wpelemento-importer-tgmpa-install-plugins')),
                    'addon_admin_url' => esc_url(admin_url('admin.php?page=wpelementoimporter-wizard'))
                )
            );
            wp_enqueue_script( 'fitness-elementor-plugin-activation-script' );

        }

        // --------- Plugin Actions ---------
        public function fitness_elementor_get_recommended_actions() {

            $fitness_elementor_act_count  = 0;
            $fitness_elementor_actions_todo = get_option( 'recommending_actions', array());

            $fitness_elementor_plugins = $this->fitness_elementor_get_recommended_plugins();

            if ($fitness_elementor_plugins) {
                foreach ($fitness_elementor_plugins as $fitness_elementor_key => $fitness_elementor_plugin) {
                    $fitness_elementor_action = array();
                    if (!isset($fitness_elementor_plugin['slug'])) {
                        continue;
                    }

                    $fitness_elementor_action['id']   = 'install_' . $fitness_elementor_plugin['slug'];
                    $fitness_elementor_action['desc'] = '';
                    if (isset($fitness_elementor_plugin['desc'])) {
                        $fitness_elementor_action['desc'] = $fitness_elementor_plugin['desc'];
                    }

                    $fitness_elementor_action['name'] = '';
                    if (isset($fitness_elementor_plugin['name'])) {
                        $fitness_elementor_action['title'] = $fitness_elementor_plugin['name'];
                    }

                    $fitness_elementor_link_and_is_done  = $this->fitness_elementor_get_plugin_buttion($fitness_elementor_plugin['slug'], $fitness_elementor_plugin['name'], $fitness_elementor_plugin['function']);
                    $fitness_elementor_action['link']    = $fitness_elementor_link_and_is_done['button'];
                    $fitness_elementor_action['is_done'] = $fitness_elementor_link_and_is_done['done'];
                    if (!$fitness_elementor_action['is_done'] && (!isset($fitness_elementor_actions_todo[$fitness_elementor_action['id']]) || !$fitness_elementor_actions_todo[$fitness_elementor_action['id']])) {
                        $fitness_elementor_act_count++;
                    }
                    $fitness_elementor_recommended_actions[] = $fitness_elementor_action;
                    $fitness_elementor_actions_todo[]        = array('id' => $fitness_elementor_action['id'], 'watch' => true);
                }
                return array('count' => $fitness_elementor_act_count, 'actions' => $fitness_elementor_recommended_actions);
            }

        }

        public function fitness_elementor_get_recommended_plugins() {

            $fitness_elementor_plugins = apply_filters('fitness_elementor_recommended_plugins', array());
            return $fitness_elementor_plugins;
        }

        public function fitness_elementor_get_plugin_buttion($slug, $name, $function) {
                $fitness_elementor_is_done      = false;
                $fitness_elementor_button_html  = '';
                $fitness_elementor_is_installed = $this->is_plugin_installed($slug);
                $fitness_elementor_plugin_path  = $this->get_plugin_basename_from_slug($slug);
                $fitness_elementor_is_activeted = (class_exists($function)) ? true : false;
                if (!$fitness_elementor_is_installed) {
                    $fitness_elementor_plugin_install_url = add_query_arg(
                        array(
                            'action' => 'install-plugin',
                            'plugin' => $slug,
                        ),
                        self_admin_url('update.php')
                    );
                    $fitness_elementor_plugin_install_url = wp_nonce_url($fitness_elementor_plugin_install_url, 'install-plugin_' . esc_attr($slug));
                    $fitness_elementor_button_html        = sprintf('<a class="fitness-elementor-plugin-install install-now button-secondary button" data-slug="%1$s" href="%2$s" aria-label="%3$s" data-name="%4$s">%5$s</a>',
                        esc_attr($slug),
                        esc_url($fitness_elementor_plugin_install_url),
                        sprintf(esc_html__('Install %s Now', 'fitness-elementor'), esc_html($name)),
                        esc_html($name),
                        esc_html__('Install & Activate', 'fitness-elementor')
                    );
                } elseif ($fitness_elementor_is_installed && !$fitness_elementor_is_activeted) {

                    $fitness_elementor_plugin_activate_link = add_query_arg(
                        array(
                            'action'        => 'activate',
                            'plugin'        => rawurlencode($fitness_elementor_plugin_path),
                            'plugin_status' => 'all',
                            'paged'         => '1',
                            '_wpnonce'      => wp_create_nonce('activate-plugin_' . $fitness_elementor_plugin_path),
                        ), self_admin_url('plugins.php')
                    );

                    $fitness_elementor_button_html = sprintf('<a class="fitness-elementor-plugin-activate activate-now button-primary button" data-slug="%1$s" href="%2$s" aria-label="%3$s" data-name="%4$s">%5$s</a>',
                        esc_attr($slug),
                        esc_url($fitness_elementor_plugin_activate_link),
                        sprintf(esc_html__('Activate %s Now', 'fitness-elementor'), esc_html($name)),
                        esc_html($name),
                        esc_html__('Activate', 'fitness-elementor')
                    );
                } elseif ($fitness_elementor_is_activeted) {
                    $fitness_elementor_button_html = sprintf('<div class="action-link button disabled"><span class="dashicons dashicons-yes"></span> %s</div>', esc_html__('Active', 'fitness-elementor'));
                    $fitness_elementor_is_done     = true;
                }

                return array('done' => $fitness_elementor_is_done, 'button' => $fitness_elementor_button_html);
            }
        public function is_plugin_installed($slug) {
            $fitness_elementor_installed_plugins = $this->get_installed_plugins(); // Retrieve a list of all installed plugins (WP cached).
            $fitness_elementor_file_path         = $this->get_plugin_basename_from_slug($slug);
            return (!empty($fitness_elementor_installed_plugins[$fitness_elementor_file_path]));
        }
        public function get_plugin_basename_from_slug($slug) {
            $fitness_elementor_keys = array_keys($this->get_installed_plugins());
            foreach ($fitness_elementor_keys as $fitness_elementor_key) {
                if (preg_match('|^' . $slug . '/|', $fitness_elementor_key)) {
                    return $fitness_elementor_key;
                }
            }
            return $slug;
        }

        public function get_installed_plugins() {

            if (!function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            return get_plugins();
        }
        public function create_pattern_setup_builder() {

            $edit_page = admin_url().'post-new.php?post_type=page&create_pattern=true';
            echo json_encode(['page_id'=>'','edit_page_url'=> $edit_page ]);

            exit;
        }

    }
}
/**
 * Kicking this off by calling 'get_instance()' method
 */
Fitness_Elementor_Plugin_Activation_WPElemento_Importer::get_instance();