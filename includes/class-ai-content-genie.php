<?php

if (!class_exists('AI_Content_Genie')) {
    class AI_Content_Genie
    {
        // Singleton instance.
        private static $instance = null;

        // Constructor.
        private function __construct()
        {
            $this->define_hooks();
        }

        // Get the singleton instance.
        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // Define hooks and filters.
        private function define_hooks()
        {
            add_action('admin_menu', [$this, 'add_admin_menu']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
            add_action('wp_ajax_generate_ai_content', [$this, 'generate_ai_content']);
            add_action('admin_init', [$this, 'register_settings']);
        }

        // Add admin menu.
        public function add_admin_menu()
        {
            add_menu_page(
                __('AI Content Genie', 'ai-content-genie'),
                __('AI Content Genie', 'ai-content-genie'),
                'manage_options',
                'ai-content-genie',
                [$this, 'display_admin_page'],
                'dashicons-admin-generic',
                6
            );
            add_submenu_page(
                'ai-content-genie',
                __('Settings', 'ai-content-genie'),
                __('Settings', 'ai-content-genie'),
                'manage_options',
                'ai-content-genie-settings',
                [$this, 'display_settings_page']
            );
        }

        // Register settings.
        public function register_settings()
        {
            register_setting('ai_content_genie_settings', 'ai_content_genie_openai_api_key');
            register_setting('ai_content_genie_settings', 'ai_content_genie_unsplash_api_key');
        }

        // Enqueue admin scripts and styles.
        public function enqueue_admin_scripts($hook)
        {
            if ($hook != 'toplevel_page_ai-content-genie') {
                return;
            }
            wp_enqueue_style('ai-content-genie-admin', plugins_url('assets/css/admin.css', __FILE__), [], AI_CONTENT_GENIE_VERSION);
            wp_enqueue_script('ai-content-genie-admin', plugins_url('assets/js/admin.js', __FILE__), ['jquery'], AI_CONTENT_GENIE_VERSION, true);
        }

        // Display admin page.
        public function display_admin_page()
        {
            require_once AI_CONTENT_GENIE_PATH . 'templates/admin-page.php';
        }

        // Display settings page.
        public function display_settings_page()
        {
            require_once AI_CONTENT_GENIE_PATH . 'templates/settings-page.php';
        }

        // AJAX handler to generate AI content.
        public function generate_ai_content()
        {
            check_ajax_referer('ai_content_genie_nonce');

            $keyword = sanitize_text_field($_POST['keyword']);
            $template = sanitize_text_field($_POST['template']);
            $include_image = sanitize_text_field($_POST['include_image']);
            
            // Integrate with AI content generation API.
            $generated_content = $this->get_ai_content($keyword, $template);

            // Optionally integrate with an image API.
            if ($include_image === 'yes') {
                $image_url = $this->get_related_image($keyword);
                if ($image_url) {
                    $generated_content = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($keyword) . '">' . $generated_content;
                }
            }

            echo wp_kses_post($generated_content);
            wp_die();
        }

        // Function to integrate with AI content generation API.
        private function get_ai_content($keyword, $template)
        {
            $api_key = get_option('ai_content_genie_openai_api_key');
            if (!$api_key) {
                return '<p>' . __('No OpenAI API key found. Please set your API key in the settings.', 'ai-content-genie') . '</p>';
            }

            $api_url = 'https://api.openai.com/v1/engines/davinci-codex/completions';
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key
            ];
            $prompt = $this->generate_prompt($keyword, $template);
            $data = [
                'prompt' => $prompt,
                'max_tokens' => 500
            ];

            $response = wp_remote_post($api_url, [
                'headers' => $headers,
                'body' => json_encode($data)
            ]);

            if (is_wp_error($response)) {
                return '<p>' . __('Failed to generate content. Please try again later.', 'ai-content-genie') . '</p>';
            }

            $body = wp_remote_retrieve_body($response);
            $result = json_decode($body, true);

            $this->log_api_response($result); // Log the API response for debugging

            return isset($result['choices'][0]['text']) ? $result['choices'][0]['text'] : '<p>' . __('Failed to generate content. Please try again later.', 'ai-content-genie') . '</p>';
        }

        // Function to generate AI prompt based on template.
        private function generate_prompt($keyword, $template)
        {
            switch ($template) {
                case 'product-description':
                    return "Write a detailed product description for the keyword: $keyword";
                case 'social-media':
                    return "Write a catchy social media post for the keyword: $keyword";
                case 'blog-post':
                default:
                    return "Write a detailed blog post for the keyword: $keyword";
            }
        }

        // Function to fetch related image from Unsplash API.
        private function get_related_image($keyword)
        {
            $api_key = get_option('ai_content_genie_unsplash_api_key');
            if (!$api_key) {
                return false;
            }

            $api_url = 'https://api.unsplash.com/photos/random?query=' . urlencode($keyword) . '&client_id=' . $api_key;

            $response = wp_remote_get($api_url);

            if (is_wp_error($response)) {
                return false;
            }

            $body = wp_remote_retrieve_body($response);
            $result = json_decode($body, true);

            return isset($result['urls']['regular']) ? $result['urls']['regular'] : false;
        }

        // Function to log API response for debugging.
        private function log_api_response($response)
        {
            $log = AI_CONTENT_GENIE_PATH . 'api-response.log';
            file_put_contents($log, print_r($response, true), FILE_APPEND);
        }
    }
}
