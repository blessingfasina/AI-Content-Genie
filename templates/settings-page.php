<div class="wrap">
    <h1><?php _e('AI Content Genie Settings', 'ai-content-genie'); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields('ai_content_genie_settings'); ?>
        <?php do_settings_sections('ai_content_genie_settings'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('OpenAI API Key', 'ai-content-genie'); ?></th>
                <td><input type="text" name="ai_content_genie_openai_api_key" value="<?php echo esc_attr(get_option('ai_content_genie_openai_api_key')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Unsplash API Key', 'ai-content-genie'); ?></th>
                <td><input type="text" name="ai_content_genie_unsplash_api_key" value="<?php echo esc_attr(get_option('ai_content_genie_unsplash_api_key')); ?>" /></td>
            </tr>
        </table>

        <p><?php _e('To get your API keys, sign up at the following sites:', 'ai-content-genie'); ?></p>
        <ul>
            <li><a href="https://beta.openai.com/signup/" target="_blank"><?php _e('OpenAI', 'ai-content-genie'); ?></a></li>
            <li><a href="https://unsplash.com/developers" target="_blank"><?php _e('Unsplash', 'ai-content-genie'); ?></a></li>
        </ul>

        <?php submit_button(); ?>
    </form>
</div>
