<?php

class Hostinger_Frontend_Translations {
    protected $frontend_translations;
    protected $chatbot_translations;

    public function __construct() {
        $this->setup_translations();
    }

    public function get_frontend_translations(): array {
        return $this->frontend_translations;
    }

    public function get_chatbot_translations(): array {
        return $this->chatbot_translations;
    }

    protected function setup_translations(): void {
        $this->frontend_translations = array(
            'tones_selected'     => __( 'tones selected', 'hostinger-ai-assistant' ),
            'voice_tones'        => array(
                'neutral'     => __( 'Neutral', 'hostinger-ai-assistant' ),
                'formal'      => __( 'Formal', 'hostinger-ai-assistant' ),
                'trustworthy' => __( 'Trustworthy', 'hostinger-ai-assistant' ),
                'friendly'    => __( 'Friendly', 'hostinger-ai-assistant' ),
                'witty'       => __( 'Witty', 'hostinger-ai-assistant' ),
            ),
            'example_keywords'   => __( 'Example: website development, WordPress tutorial, ...', 'hostinger-ai-assistant' ),
            'at_least_ten'       => __( 'Enter at least 10 characters', 'hostinger-ai-assistant' ),
            'let_us_now_more'    => __( 'Let us now more about your post idea. Share more details for better results', 'hostinger-ai-assistant' ),
            'youre_good'         => __( 'You\'re good to go, but you can share more details for better results', 'hostinger-ai-assistant' ),
            'add_new_with_ai'    => __( 'Create Post with AI', 'hostinger-ai-assistant' ),
            'ai_generated_image' => __( 'AI-generated image', 'hostinger-ai-assistant' ),
            'use_image_as'       => __( 'Use this image as:', 'hostinger-ai-assistant' ),
            'set_as_featured'    => __( 'External featured image', 'hostinger-ai-assistant' ),
            'set_as_content'     => __( 'Insert this image inside content', 'hostinger-ai-assistant' ),
        );

        /* translators: %s: MCP plugin name */
        $mcp_subtitle    = __( 'To let Kodee manage your site on your behalf, we will install and pre-configure the %s for you. This allows Kodee to perform actions like creating pages or updating settings. You can revoke this permission at any time in your Hostinger Tools settings.', 'hostinger-ai-assistant' );
        $mcp_plugin_name = '<b>' . __( 'WordPress MCP plugin', 'hostinger-ai-assistant' ) . '</b>';
        $mcp_subtitle    = sprintf( $mcp_subtitle, $mcp_plugin_name );
        $mcp_subtitle    = strip_tags( $mcp_subtitle, '<b>' );

        $this->chatbot_translations = array(
            'main'            => array(
                'intro'                                       => __( 'Hi, I\'m Kodee, your personal AI assistant. You can ask me any questions you have regarding WordPress. I\'m still learning, so sometimes can make mistakes. What questions do you have?', 'hostinger-ai-assistant' ),
                'title'                                       => __( 'Kodee', 'hostinger-ai-assistant' ),
                'beta_badge'                                  => '',
                'tooltip_feedback'                            => __( 'Leave feedback', 'hostinger-ai-assistant' ),
                'tooltip_reset'                               => __( 'Restart chatbot', 'hostinger-ai-assistant' ),
                'tooltip_reset_disabled'                      => __( 'Cannot restart the chat when talking with the agent', 'hostinger-ai-assistant' ),
                'tooltip_start_new'                           => __( 'Start new chat', 'hostinger-ai-assistant' ),
                'tooltip_close'                               => __( 'Close', 'hostinger-ai-assistant' ),
                'tooltip_history'                             => __( 'History', 'hostinger-ai-assistant' ),
                'question_input_placeholder'                  => __( 'Write your question', 'hostinger-ai-assistant' ),
                'disclaimer'                                  => __( 'AI may produce inaccurate information', 'hostinger-ai-assistant' ),
                'button'                                      => __( 'Ask Kodee', 'hostinger-ai-assistant' ),
                'drag_over_overlay_text'                      => __( 'Drop files here', 'hostinger-ai-assistant' ),
                'unsupported_format_kodee'                    => __( 'Kodee only supports JPEG, JPG, PNG, GIF, HEIC, and DNG files', 'hostinger-ai-assistant' ),
                'unsupported_format_agent'                    => __( 'Selected file type is not supported', 'hostinger-ai-assistant' ),
                'file_upload_limit_error'                     => __( 'You can only upload up to 6 files', 'hostinger-ai-assistant' ),
                'tooltip_kodee_responding_disabled'           => __( 'Cannot restart the chat when Kodee is responding', 'hostinger-ai-assistant' ),
                'tooltip_kodee_responding_start_new_disabled' => __( 'Cannot start a new chat when Kodee is responding', 'hostinger-ai-assistant' ),
                'active_conversation'                         => __( 'Active', 'hostinger-ai-assistant' ),
                'thinking'                                    => __( 'Thinking', 'hostinger-ai-assistant' ),
            ),
            'start_screen'    => array(
                'title'    => __( 'Hello 👋', 'hostinger-ai-assistant' ),
                'subtitle' => __( 'How can I help you today?', 'hostinger-ai-assistant' ),
            ),
            'system_messages' => array(
                'conversation_closed' => __( 'Conversation was closed', 'hostinger-ai-assistant' ),
            ),
            'feedback'        => array(
                'comment_button'      => __( 'Leave feedback', 'hostinger-ai-assistant' ),
                'thanks_add_comment'  => __( 'Thanks for letting us know. Click the button below if you want to leave additional feedback.', 'hostinger-ai-assistant' ),
                'thank_you'           => __( 'Thanks for letting us know', 'hostinger-ai-assistant' ),
                'you_rated'           => __( 'You rated your conversation', 'hostinger-ai-assistant' ),
                'question'            => __( 'How can we improve your experience?', 'hostinger-ai-assistant' ),
                'score_poor'          => __( 'Poor', 'hostinger-ai-assistant' ),
                'score_excellent'     => __( 'Excellent', 'hostinger-ai-assistant' ),
                'comment_placeholder' => __( 'Write your feedback (optional)', 'hostinger-ai-assistant' ),
                'confirm_button'      => __( 'Send', 'hostinger-ai-assistant' ),
                'thanks_message'      => __( 'Thank you for your feedback', 'hostinger-ai-assistant' ),
            ),
            'modal_restart'   => array(
                'title'          => __( 'Clear chat', 'hostinger-ai-assistant' ),
                'description'    => __( 'After clearing history you won\'t be able to access previous chats.', 'hostinger-ai-assistant' ),
                'cancel_button'  => __( 'Cancel', 'hostinger-ai-assistant' ),
                'confirm_button' => __( 'Clear chat', 'hostinger-ai-assistant' ),
                'start_new'      => array(
                    'title'          => __( 'Start new chat', 'hostinger-ai-assistant' ),
                    'description'    => __( 'After starting new chat, you will be able to access previous chats from the history.', 'hostinger-ai-assistant' ),
                    'confirm_button' => __( 'Start new chat', 'hostinger-ai-assistant' ),
                ),
            ),
            'voice'           => array(
                'title'        => __( 'Voice feature is coming soon', 'hostinger-ai-assistant' ),
                'description'  => __( 'Talking to Kodee is in the works, we\'ll keep you posted once it\'s out!', 'hostinger-ai-assistant' ),
                'close_button' => __( 'Close', 'hostinger-ai-assistant' ),
            ),
            'error'           => array(
                'unavailable'      => __( 'Sorry, the AI Chatbot is currently unavailable. Please try again later.', 'hostinger-ai-assistant' ),
                'timeout'          => __( 'Sorry, the AI Chatbot request timed out. Please try again later.', 'hostinger-ai-assistant' ),
                'unclear_question' => __( 'I\'m sorry, I didn\'t understand your question. Could you please rephrase it or ask something different?', 'hostinger-ai-assistant' ),
            ),
            'mcp-modal'       => array(
                'title'    => __( 'Allow Kodee to manage your site', 'hostinger-ai-assistant' ),
                'subtitle' => $mcp_subtitle,
                'deny'     => __( 'No, thanks', 'hostinger-ai-assistant' ),
                'accept'   => __( 'Grant Permission', 'hostinger-ai-assistant' ),
            ),
            'suggestions'     => array(
                'wpAddPage'            => array(
                    'title'       => __( 'Add WordPress page', 'hostinger-ai-assistant' ),
                    'description' => __( 'Create and publish a new page on your WordPress site', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Add a new page to my WordPress website', 'hostinger-ai-assistant' ),
                ),
                'wpUpdatePage'         => array(
                    'title'       => __( 'Update WordPress page', 'hostinger-ai-assistant' ),
                    'description' => __( 'Edit an existing WordPress page by its ID', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Update an existing page on my WordPress website', 'hostinger-ai-assistant' ),
                ),
                'getSiteInfo'          => array(
                    'title'       => __( 'Get WordPress site information', 'hostinger-ai-assistant' ),
                    'description' => __( 'View site details like name, URL, description, admin email, plugins, themes, and users', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Retrieve detailed information about my WordPress site', 'hostinger-ai-assistant' ),
                ),
                'wpUsersSearch'        => array(
                    'title'       => __( 'List users with their roles', 'hostinger-ai-assistant' ),
                    'description' => __( 'Search and filter WordPress users with pagination', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Search WordPress users on my website', 'hostinger-ai-assistant' ),
                ),
                'wpAddPost'            => array(
                    'title'       => __( 'Add blog post', 'hostinger-ai-assistant' ),
                    'description' => __( 'Create and publish a new post on your WordPress site', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Add a new post to my WordPress website', 'hostinger-ai-assistant' ),
                ),
                'wpUpdatePost'         => array(
                    'title'       => __( 'Update blog post', 'hostinger-ai-assistant' ),
                    'description' => __( 'Edit an existing WordPress post by its ID', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Update an existing post on my WordPress site', 'hostinger-ai-assistant' ),
                ),
                'wpPostsSearch'        => array(
                    'title'       => __( 'Search blog posts', 'hostinger-ai-assistant' ),
                    'description' => __( 'Find and filter posts with pagination options', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Search posts on my WordPress website', 'hostinger-ai-assistant' ),
                ),
                'wpUpdateTag'          => array(
                    'title'       => __( 'Update post tag', 'hostinger-ai-assistant' ),
                    'description' => __( 'Edit a WordPress post tag (e.g., rename or update it)', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Update a post tag on my WordPress website', 'hostinger-ai-assistant' ),
                ),
                'wpUpdateCategory'     => array(
                    'title'       => __( 'Update post category', 'hostinger-ai-assistant' ),
                    'description' => __( 'Edit a WordPress post category by ID', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Update a post category on my WordPress website', 'hostinger-ai-assistant' ),
                ),
                'wcAddProduct'         => array(
                    'title'       => __( 'Add a product', 'hostinger-ai-assistant' ),
                    'description' => __( 'Add a new product to your WooCommerce store', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Create a new WooCommerce product for my store', 'hostinger-ai-assistant' ),
                ),
                'wcAddProductCategory' => array(
                    'title'       => __( 'Add a product category', 'hostinger-ai-assistant' ),
                    'description' => __( 'Create a new product category in WooCommerce', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Create a new WooCommerce product category for my store', 'hostinger-ai-assistant' ),
                ),
                'wcOrdersSearch'       => array(
                    'title'       => __( 'Get a list of orders', 'hostinger-ai-assistant' ),
                    'description' => __( 'Retrieve a list of WooCommerce orders with filters', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Search WooCommerce orders for my store', 'hostinger-ai-assistant' ),
                ),
                'wcReportsSales'       => array(
                    'title'       => __( 'Generate sales report', 'hostinger-ai-assistant' ),
                    'description' => __( 'Create a WooCommerce sales report for a chosen period', 'hostinger-ai-assistant' ),
                    'prompt'      => __( 'Generate WooCommerce sales report for my store', 'hostinger-ai-assistant' ),
                ),
            ),
        );
    }
}
