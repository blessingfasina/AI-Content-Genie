<div class="wrap">
    <h1><?php _e('AI Content Genie', 'ai-content-genie'); ?></h1>
    <form id="ai-content-genie-form" method="post" action="">
        <?php wp_nonce_field('ai_content_genie_nonce', 'ai_content_genie_nonce_field'); ?>
        <label for="ai-keyword"><?php _e('Enter a keyword or topic:', 'ai-content-genie'); ?></label>
        <input type="text" id="ai-keyword" name="ai_keyword" required>
        
        <label for="ai-template"><?php _e('Choose a template:', 'ai-content-genie'); ?></label>
        <select id="ai-template" name="ai_template">
            <option value="blog-post"><?php _e('Blog Post', 'ai-content-genie'); ?></option>
            <option value="product-description"><?php _e('Product Description', 'ai-content-genie'); ?></option>
            <option value="social-media"><?php _e('Social Media Post', 'ai-content-genie'); ?></option>
        </select>

        <label for="include-image"><?php _e('Include related image:', 'ai-content-genie'); ?></label>
        <input type="checkbox" id="include-image" name="include_image" value="yes">
        
        <button type="submit" class="button button-primary"><?php _e('Generate Content', 'ai-content-genie'); ?></button>
    </form>
    <div id="ai-content-result"></div>
</div>
