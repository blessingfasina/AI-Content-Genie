jQuery(document).ready(function($) {
    $('#ai-content-genie-form').on('submit', function(e) {
        e.preventDefault();

        var keyword = $('#ai-keyword').val();
        var template = $('#ai-template').val();
        var include_image = $('#include-image').is(':checked') ? 'yes' : 'no';
        var nonce = $('#ai_content_genie_nonce_field').val();

        // AJAX call to generate content
        $.post(ajaxurl, {
            action: 'generate_ai_content',
            keyword: keyword,
            template: template,
            include_image: include_image,
            _ajax_nonce: nonce
        }, function(response) {
            $('#ai-content-result').html(response);
        });
    });
});
