<?php

namespace WPDeveloper\BetterDocs\Utils;

use WPDeveloper\BetterDocs\Core\Settings;

class AIHelper {

	/**
	 * Settings instance
	 *
	 * @var Settings
	 */
	private $settings;

	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Get OpenAI API key from settings
	 *
	 * @return string
	 */
	public function get_api_key() {
		return $this->settings->get( 'ai_autowrite_api_key', '' );
	}

	/**
	 * Check if OpenAI API key is configured
	 *
	 * @return bool
	 */
	public function has_api_key() {
		$api_key = $this->get_api_key();
		return ! empty( $api_key );
	}

	/**
	 * Validate OpenAI API key
	 *
	 * @param string $api_key Optional API key to validate, uses stored key if not provided
	 * @return array Array with 'valid' boolean and 'message' string
	 */
	public function validate_api_key( $api_key = '' ) {
		if ( empty( $api_key ) ) {
			$api_key = $this->get_api_key();
		}

		if ( empty( $api_key ) ) {
			return [
				'valid'   => false,
				'message' => 'Please Insert your <a href="/admin.php?page=betterdocs-settings">OpenAI API Key</a> to use AI features.'
			];
		}

		$ch = curl_init( 'https://api.openai.com/v1/models' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_init
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
		curl_setopt( //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			$ch,
			CURLOPT_HTTPHEADER,
			[
				'Content-Type: application/json',
				'Authorization: Bearer ' . $api_key,
			]
		);

		$response = curl_exec( $ch ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_exec
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_getinfo
		curl_close( $ch ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_close

		if ( $httpCode == 200 ) {
			return [
				'valid'   => true,
				'message' => 'Valid API Key'
			];
		} else {
			$responseData = json_decode( $response, true );
			$messageData = $responseData['error'] ?? '';
			return [
				'valid'   => false,
				'message' => $messageData['message'] ?? 'Invalid API Key'
			];
		}
	}

	/**
	 * Make a request to OpenAI API
	 *
	 * @param array $messages Array of messages for the chat completion
	 * @param array $options Optional parameters (model, max_tokens, temperature, etc.)
	 * @return string|\WP_Error API response content or error
	 */
	public function make_openai_request( $messages, $options = [] ) {
		$api_key = $this->get_api_key();
		$max_tokens = $this->settings->get( 'article_summary_max_token', 1500 );
		$model = $this->settings->get( 'article_summary_model', 'gpt-4o-mini' );

		if ( empty( $api_key ) ) {
			return new \WP_Error( 'no_api_key', 'OpenAI API key is not configured.' );
		}

		// Default options
		$defaults = [
			'model'       => $model,
			'max_tokens'  => $max_tokens,
			'temperature' => 0.7,
			'timeout'     => 50
		];

		$options = wp_parse_args( $options, $defaults );

		$api_endpoint = 'https://api.openai.com/v1/chat/completions';

		$request_body = [
			'model'      => $options['model'],
			'messages'   => $messages,
			'max_tokens' => $options['max_tokens'],
			'temperature' => $options['temperature']
		];

		$request_options = [
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $api_key,
			],
			'body'    => json_encode( $request_body ), //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
			'timeout' => $options['timeout'],
		];

		$response = wp_remote_post( $api_endpoint, $request_options );

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'api_error', 'Failed to connect to OpenAI API: ' . $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! empty( $data['error'] ) ) {
			return new \WP_Error( 'openai_error', $data['error']['message'] );
		}

		if ( empty( $data['choices'][0]['message']['content'] ) ) {
			return new \WP_Error( 'no_content', 'No content received from OpenAI.' );
		}

		return $data['choices'][0]['message']['content'];
	}

	/**
	 * Create a system message for OpenAI
	 *
	 * @param string $content System message content
	 * @return array Message array
	 */
	public function create_system_message( $content ) {
		return [
			'role'    => 'system',
			'content' => $content
		];
	}

	/**
	 * Create a user message for OpenAI
	 *
	 * @param string $content User message content
	 * @return array Message array
	 */
	public function create_user_message( $content ) {
		return [
			'role'    => 'user',
			'content' => $content
		];
	}

	/**
	 * Create messages array for article summarization
	 *
	 * @param string $title Article title
	 * @param string $content Article content
	 * @return array Messages array
	 */
	public function create_summary_messages( $title, $content ) {
		$system_message = $this->create_system_message(
			'You are a helpful assistant that creates concise, informative summaries of documentation articles. Always format your response in clean HTML with paragraph tags. Do not use markdown formatting, code blocks, or backticks. Return only the HTML content without any wrapper formatting.'
		);

		$user_prompt = "Please provide a concise summary of the following article titled '{$title}'. The summary should be 2-3 paragraphs long, highlighting the main points and key takeaways. Format the response in HTML with proper paragraph tags. Do not wrap the response in markdown code blocks or use any markdown formatting.\n\nArticle content:\n{$content}";

		$user_message = $this->create_user_message( $user_prompt );

		return [ $system_message, $user_message ];
	}

	/**
	 * Create messages array for content generation
	 *
	 * @param string $prompt User prompt
	 * @param string $keywords Optional keywords
	 * @return array Messages array
	 */
	public function create_content_messages( $prompt, $keywords = '' ) {
		$system_message = $this->create_system_message(
			'You are a helpful assistant who writes documentation for users.'
		);

		$user_message = $this->create_user_message( $prompt );

		return [ $system_message, $user_message ];
	}

	/**
	 * Sanitize and prepare content for AI processing
	 *
	 * @param string $content Raw content
	 * @param int $max_length Maximum length to keep
	 * @return string Sanitized content
	 */
	public function prepare_content_for_ai( $content, $max_length = 4000 ) {
		// Strip HTML tags and decode entities
		$content = wp_strip_all_tags( $content );
		$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );

		// Remove extra whitespace
		$content = preg_replace( '/\s+/', ' ', $content );
		$content = trim( $content );

		// Limit length
		if ( strlen( $content ) > $max_length ) {
			$content = substr( $content, 0, $max_length );
			// Try to cut at a word boundary
			$last_space = strrpos( $content, ' ' );
			if ( $last_space !== false && $last_space > $max_length * 0.8 ) {
				$content = substr( $content, 0, $last_space );
			}
			$content .= '...';
		}

		return $content;
	}

	/**
	 * Check if AI features are enabled
	 *
	 * @return bool
	 */
	public function is_ai_enabled() {
		return $this->settings->get( 'enable_write_with_ai', true ) && $this->has_api_key();
	}

	/**
	 * Get AI usage statistics (placeholder for future implementation)
	 *
	 * @return array Usage statistics
	 */
	public function get_usage_stats() {
		// This could be implemented to track API usage, costs, etc.
		return [
			'requests_today' => 0,
			'tokens_used'    => 0,
			'cost_estimate'  => 0
		];
	}
}
