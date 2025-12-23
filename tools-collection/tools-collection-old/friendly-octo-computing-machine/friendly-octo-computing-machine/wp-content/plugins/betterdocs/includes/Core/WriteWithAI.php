<?php

namespace WPDeveloper\BetterDocs\Core;

use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Core\Settings;
use WPDeveloper\BetterDocs\Core\PostType;

use WPDeveloper\BetterDocs\Utils\Helper;

class WriteWithAI extends Base {

	public $settings;

	public function __construct( Settings $settings ) {
		$this->settings = $settings;
		// Get the post ID from the URL
        $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0; // phpcs:ignore

        if (!empty($_GET['post_type'])) { // phpcs:ignore
            $post_type = $_GET['post_type']; // phpcs:ignore
		} elseif ( $post_id > 0 ) {
			$post_type = get_post_type( $post_id );
		} else {
			$post_type = '';
		}

		if ( ! empty( $this->isEnabledWriteWithAI() ) && $post_type == 'docs' ) {
			add_action( 'admin_footer', [ $this, 'ai_autowrite_button' ] );
		}
		add_action( 'wp_ajax_generate_openai_content', [ $this, 'generate_openai_content_callback' ] );

	}

	public function isEnabledWriteWithAI() {
		$isEnableAutoWrite = $this->settings->get( 'enable_write_with_ai', true );
		return $isEnableAutoWrite;
	}

	public function isValidAPIKey( $apiKey ) {
		if ( empty( $apiKey ) ) {
			$api_response['valid']   = false;
			$api_response['message'] = 'Please Insert your <a href="/admin.php?page=betterdocs-settings">OpenAI API Key</a> to use this Write with AI feature.';

			return $api_response;
		}

		$ch = curl_init( 'https://api.openai.com/v1/engines' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_init
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			[ //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			'Content-Type: application/json',
			'Authorization: Bearer ' . $apiKey,
			]
		);

		$api_response = [];

		$response = curl_exec( $ch ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_exec
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_getinfo

		curl_close( $ch ); //phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_close

		if ( $httpCode == 200 ) {
			$api_response['valid']   = true;
			$api_response['message'] = 'Valid API Key';
		} else {
			$responseData = json_decode( $response, true );
			// Access the message data (replace 'data' with the actual key used in the response)
			$messageData             = $responseData['error'] ? $responseData['error'] : '';
			$api_response['valid']   = false;
			$api_response['message'] = $messageData['message'] ? $messageData['message'] : 'Invalid API Key';
		}

		// print_r($response);

		return $api_response;
	}

	public function get_api_key() {
		$api_key = $this->settings->get( 'ai_autowrite_api_key', '' );
		return $api_key;
	}


	public function generate_openai_response( $prompt, $keywords ) {
		try {
			$api_key    = $this->settings->get( 'ai_autowrite_api_key', '' );
			$max_tokens = $this->settings->get( 'ai_autowrite_max_token', 1500 );
			$model = $this->settings->get( 'write_with_ai_model', 'gpt-4o-mini' );

			$api_endpoint = 'https://api.openai.com/v1/chat/completions'; // Update the endpoint based on OpenAI API version

			$request_options = array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $api_key,
				),
				'body'    => json_encode(
					array( //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
					'model'      => $model, // Add the model parameter here
					'messages'   => array(
						array(
					'role'    => 'system',
					'content' => 'You are a helpful assistant who writes documentation for users.'
					),
						array(
					'role'    => 'user',
					'content' => $prompt
					),
					),
					'max_tokens' => $max_tokens,

					)
				),
				'timeout' => 50,
			);

			$response = wp_remote_post( $api_endpoint, $request_options );

			if ( is_wp_error( $response ) ) {
				return 'Error: ' . $response->get_error_message();
			} else {
				$body = wp_remote_retrieve_body( $response );

				$data = json_decode( $body, true );

				if ( ! empty( $data['error'] ) ) {
					return $data['error']['message'];
				}

				return $data['choices'][0]['message']['content']; // Update this line to get the assistant's message
			}
		} catch ( Exception $error ) {
			return 'Error: ' . $error->getMessage();
		}
	}



	public function generate_openai_content_callback() {
		// Verify the nonce
        if (!isset($_POST['ai_nonce']) || !wp_verify_nonce($_POST['ai_nonce'], 'generate_openai_content_nonce')) { //phpcs:ignore
			wp_send_json_error( 'Invalid nonce' );
			wp_die();
		}

        $prompt = sanitize_text_field($_POST['prompt']); //phpcs:ignore
		// $num_of_sections = sanitize_text_field($_POST['numOfSections']);
		// $num_of_paragraphs = sanitize_text_field($_POST['numOfParagraphs']);
        $keywords = sanitize_text_field($_POST['keywords']); //phpcs:ignore

		$ai_instance = new WriteWithAI( $this->settings );

		$generated_content = $ai_instance->generate_openai_response( $prompt, $keywords );

		// Send the generated content as the AJAX response
		wp_send_json_success( $generated_content );
		wp_die();
	}



	public function ai_autowrite_button() {

		?>
		<script>
			var docsTitle;

			function responseMessage(message) {
				return `<div class="warning-message">
					<span class="dashicons dashicons-warning"></span>
					<div class="footer-message">
						${message}
					</div>
				</div>`;
			}

			function generateArticle(e) {


				const title = document.getElementById('betterdocs-ai-title').value;
				const keywords = document.getElementById('betterdocs-ai-keyword').value;
				// const sectionOptions = document.getElementById('bd-autowrtite-content-section');
				// const numOfSections = sectionOptions.options[sectionOptions.selectedIndex].value;

				// const paragraphOptions = document.getElementById('bd-autowrtite-content-paragraph');
				// const numOfParagraphs = paragraphOptions.options[paragraphOptions.selectedIndex].value;

				const contentTextArea = document.getElementById('betterdocs-ai-content');
				const docGenerateBtnTxt = document.querySelector('.generate-btn span');

				// Add nonce value to the data being sent
				const nonce = document.querySelector('input[name="ai_nonce"]').value;
				const isOverwrite = document.getElementById('betterdocs-ai-checkbox').checked;
				const generateDocLabel = "<?php echo esc_html__( 'Generate Doc', 'betterdocs' ); ?>";
				const reGenerateDocLabel = "<?php echo esc_html__( 'Regenerate Doc', 'betterdocs' ); ?>";


				// contentTextArea.value = `Write an article about ${title} in English. The article is organized by the following exact ${numOfSections} headings. Write exact ${numOfParagraphs} number of paragraphs per heading.${keywords} Use Markdown for formatting. Add an introduction and a conclusion. Style: creative. Tone: cheerful.`;


				// Check if the title is not empty
				if (title.trim() !== '' && keywords.trim() !== '') {
					e.preventDefault();

					if (!jQuery('#betterdocs-ai-error-message').parent().hasClass('hidden')) {
						jQuery('#betterdocs-ai-error-message').parent().addClass('hidden');
					}

					docGenerateBtnTxt.innerHTML = "<?php echo esc_html__( 'Generating...', 'betterdocs' ); ?>";
					jQuery('.generate-btn').prop('disabled', true);

					jQuery.post({
						url: ajaxurl, // Make sure ajaxurl is defined in your script
						type: 'POST',
						data: {
							action: 'generate_openai_content',
							prompt: document.querySelector('#betterdocs-ai-content').value,
							// numOfSections: numOfSections,
							// numOfParagraphs: numOfParagraphs,
							keywords: keywords,
							ai_nonce: nonce, // Pass the nonce value
						},
						success: function(response) {
							// Update the content in the textarea

							if (response.success && (typeof response.data === 'string')) {
								docGenerateBtnTxt.innerHTML = "<?php echo esc_html__( 'Generated', 'betterdocs' ); ?>";
								setTimeout(() => {
									insertContentToEditor(title, response.data, isOverwrite, keywords);
									docGenerateBtnTxt.innerHTML = reGenerateDocLabel;
									jQuery('.generate-btn').removeAttr('disabled');

									// console.log(jQuery('.betterdocs-ai-autowrite-form-container').data('new-doc-page'));

									// if(jQuery('.betterdocs-ai-autowrite-form-container')?.data('new-doc-page')) {
									//     docGenerateBtnTxt.innerHTML = reGenerateDocLabel;
									// }
								}, 2000);
							} else {
								// alert(response.data.message);
								jQuery('#betterdocs-ai-error-message').parent().removeClass('hidden');
								jQuery('#betterdocs-ai-error-message').html(responseMessage(response.data.message));
								docGenerateBtnTxt.innerHTML = generateDocLabel;
								jQuery('.generate-btn').removeAttr('disabled');

							}
						},
						error: function(error) {
							console.error(error);
						},
					});

				}
			}

			// Function to update the textarea
			function updateTextarea() {
				const title = document.getElementById('betterdocs-ai-title').value;
				const keywords = document.getElementById('betterdocs-ai-keyword').value;
				const sectionOptions = document.getElementById('bd-autowrtite-content-section');
				let language = 'English';

				if (language === '') {
					language = 'English';
				}

				let keywordsText = keywords !== '' ? ` and incorporate the keywords: ${keywords}` : '';

				const contentTextArea = document.getElementById('betterdocs-ai-content');

				if (!jQuery('#betterdocs-ai-error-message').parent().hasClass('hidden')) {
					jQuery('#betterdocs-ai-error-message').parent().addClass('hidden');
				}

				contentTextArea.value = `Generate a documentation using HTML heading tags for '${title}'. Include relevant details on ${keywords} in the documentation. Ensure all content is enclosed with <p> tags and apply a <span> tag with the class 'highlight' to headings and topic tags for emphasis.`;
			}

			function convertMarkdownToHTML(markdownContent) {
				// Simple Markdown to HTML conversion (you may need to enhance this based on your needs)
				const htmlContent = markdownContent
					.replace(/^# (.+)$/gm, '<h1>$1</h1>')
					.replace(/^## (.+)$/gm, '<h2>$1</h2>')
					.replace(/^### (.+)$/gm, '<h3>$1</h3>')
					.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
					.replace(/\*(.+?)\*/g, '<em>$1</em>');

				return htmlContent;
			}

			function replaceHeadingTitle(content, title, overviewTitle) {
				let regex = new RegExp(title, 'g');
				let updateContent = content.replace(regex, overviewTitle);
				return updateContent;
			}

			function removeFirstHeading(htmlString) {
				var newDiv = document.createElement('div');
				newDiv.innerHTML = htmlString;
				var firstHeading = newDiv.querySelector('h1');
				if (firstHeading) {
					firstHeading.parentNode.removeChild(firstHeading);
				}
				return newDiv.innerHTML;
			}


			function wrapwithHeighlight(inputString, keywords) {
				let modifiedString = inputString.replace(/<(h\d)(.*?)>(.*?)<\/\1>/g, '<$1$2><span class="highlight">$3</span></$1>');

				const keywordArray = keywords.split(',').map(keyword => keyword.trim());

				keywordArray.forEach(keyword => {
					modifiedString = modifiedString.replace(new RegExp(`\\b(${keyword})\\b`, 'gi'), '<span class="highlight">$1</span>');
				});

				return modifiedString;
			}

			function getBodyContent(htmlString) {
				var match = htmlString.match(/<body[^>]*>([\s\S]*?)<\/body>/i);

				// Check if match is null before accessing match[1]
				if (match) {
					console.log(match[1].replace(/<p>\s+/g, '<p>'));
					return match[1].replace(/<p>\s+/g, '<p>');
				} else {
					return '';
				}
			}



			function insertContentToEditor(title, htmlContent, isOverwrite, keywords) {

				const {
					dispatch,
					select
				} = wp.data;

				htmlContent = getBodyContent(htmlContent);
				htmlContent = removeFirstHeading(htmlContent);
				htmlContent = wrapwithHeighlight(htmlContent, keywords);
                const isPostContent = `<?php echo isset($_GET['post']) ? esc_html(get_the_content($_GET['post'])) : ''; // phpcs:ignore ?>`;

				const blocks = wp.blocks.rawHandler({
					HTML: htmlContent
				});

				dispatch('core/editor').editPost({
					title: title,
				});

				const selectedBlockClientId = select('core/block-editor').getSelectedBlockClientId();

				const allBlocks = select('core/block-editor').getBlocks();

				if (isOverwrite || isPostContent === '') {

					allBlocks.forEach((block) => {
						dispatch('core/block-editor').removeBlock(block.clientId);
					});
					dispatch('core/block-editor').insertBlocks(blocks);

				} else if (selectedBlockClientId) {
					const selectedIndex = select('core/block-editor').getBlockIndex(selectedBlockClientId);
					dispatch('core/block-editor').insertBlocks(blocks, selectedIndex + 1);
				} else {
					dispatch('core/block-editor').insertBlocks(blocks);
				}

				closeWriteWithAIForm('afterInsertContent');
			}

			function closeWriteWithAIForm(after) {
				jQuery('.betterdocs-ai-autowrite-form-container').addClass('hidden');
				jQuery('#betterdocs-ai-autowrite-form-container-overlay').addClass('hidden');
				jQuery('.betterdocs-ai-button').addClass('regenerate-btn');
			}


			document.addEventListener('DOMContentLoaded', function() {

				// Subscribe to changes in the editor state
				let previousDocsTitle = '';

				wp.data.subscribe(() => {
					// Get the updated post title
					// const docsTitle = wp.data.select('core/editor').getEditedPostAttribute('title');

					const docsTitle = wp.data.select('core/editor').getEditedPostAttribute('title');

					// Check if the title has changed
					if (docsTitle !== previousDocsTitle) {
						let currentKeywords = jQuery('#betterdocs-ai-keyword').val();
						if (!currentKeywords) {
							currentKeywords = '{Documentation Keywords}';
						}
						const currentPrompt = `Generate documentation using HTML heading tags for '${docsTitle?docsTitle:'{Documentation title}'}'. Include relevant details on ${currentKeywords} in the documentation. Ensure all content is enclosed with <p> tags and apply a <span> tag with the class 'highlight' to headings and topic tags for emphasis.`;
						jQuery('#betterdocs-ai-content').val(currentPrompt);

						jQuery('#betterdocs-ai-title').val(docsTitle);

						previousDocsTitle = docsTitle;
					}


				});
			});


			// This example assumes that this code is executed when your script is loaded.
			// You may want to place it within the appropriate context in your application.


			function writeWithAIForm() {

                let title = `<?php echo isset($_GET['post']) ? esc_html(get_the_title($_GET['post'])) : ''; // phpcs:ignore ?>`;
				if (docsTitle) {
					title = docsTitle;
				}

                const promtTitle = `<?php echo isset($_GET['post']) ? esc_html(get_the_title($_GET['post'])) : '{Documentation Title}'; // phpcs:ignore ?>`;
				const titlePlaceholder = "<?php echo esc_attr__( 'Enter a descriptive title for your documentation.', 'betterdocs' ); ?>";
				const keywords = "<?php echo esc_attr( '{Documentation Keywords}' ); ?>";
				const keywordsPlaceholder = "<?php echo esc_attr__( 'Add keywords to generate precise & relevant documentation (comma-separated).', 'betterdocs' ); ?>";
				const language = "<?php echo esc_attr( 'English' ); ?>";
				const titleLabel = "<?php echo esc_html__( 'Documentation Title:', 'betterdocs' ); ?>";
				const keywordLabel = "<?php echo esc_html__( 'Keywords:', 'betterdocs' ); ?>";
				const secLabel = "<?php echo esc_html__( '# of Sections:', 'betterdocs' ); ?>";
				const paraLabel = "<?php echo esc_html__( '# of Paragraph Per Section: ', 'betterdocs' ); ?>";
				const langLabel = "<?php echo esc_html__( 'Language:', 'betterdocs' ); ?>";
				const promtLabel = "<?php echo esc_html__( 'Prompt:', 'betterdocs' ); ?>";
				const promtBtnLabel = "<?php echo esc_html__( 'Generate Prompt', 'betterdocs' ); ?>";
				let promtArticleLabel = "<?php echo esc_html__( 'Generate Doc', 'betterdocs' ); ?>";
				const checkboxLabel = "<?php echo esc_html__( 'Overwrite your existing Doc:', 'betterdocs' ); ?>";
				const nonce = "<?php echo esc_attr( wp_create_nonce( 'generate_openai_content_nonce' ) ); ?>";

				<?php
				$is_valid              = $this->get_api_key();
						$disable_field = 'disabled-input-field';
				if ( $this->get_api_key() ) {
					$disable_field = '';
				}
				?>

				let hiddenClass = 'hidden';
				let newDocPageAtt = 'data-new-doc-page="true"';
                const isPostContent = `<?php echo isset($_GET['post']) ? esc_html(get_the_content($_GET['post'])) : ''; // phpcs:ignore ?>`;

				if (isPostContent !== '') {
					hiddenClass = '';
					newDocPageAtt = '';
				}


				// const prompt = `Make a knowledge base article with html heading tags about [${title}] in [${language}]. Here is some topic to describe the article: [${keywords}]. and wrap the content with p tag also add a span tag with a class named 'highlight' to all the heading and topic tags in the article.`;

				// const prompt = `Create a details knowledge base article in [${language}] about [${title}] with html heading tags. Here is some topic to describe the article: [${keywords}]. And wrap the content with p tag also add a span tag with a class named 'highlight' to all the heading and topic tags in the article.`;

				const prompt = `Generate a documentation using HTML heading tags for '${promtTitle}'. Include relevant details on ${keywords} in the documentation. Ensure all content is enclosed with <p> tags and apply a <span> tag with the class 'highlight' to headings and topic tags for emphasis.`;

				const aiIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
						<g clip-path="url(#clip0_2460_1729)">
							<path d="M10.3956 18.8608L6.10991 19.2322L6.48134 14.9465L15.3956 6.08936C15.5287 5.95329 15.6876 5.84518 15.863 5.77137C16.0384 5.69756 16.2268 5.65954 16.4171 5.65954C16.6074 5.65954 16.7957 5.69756 16.9711 5.77137C17.1466 5.84518 17.3054 5.95329 17.4385 6.08936L19.2528 7.91793C19.3865 8.05083 19.4926 8.20886 19.565 8.38293C19.6375 8.55701 19.6747 8.74368 19.6747 8.93221C19.6747 9.12075 19.6375 9.30742 19.565 9.48149C19.4926 9.65557 19.3865 9.8136 19.2528 9.9465L10.3956 18.8608ZM1.7042 5.67507C1.20277 5.58793 1.20277 4.86793 1.7042 4.78079C2.59203 4.62626 3.41374 4.21085 4.06456 3.58751C4.71538 2.96417 5.16583 2.16113 5.35848 1.28079L5.38848 1.14221C5.49705 0.647929 6.20277 0.643643 6.31705 1.13936L6.35277 1.29936C6.55248 2.17579 7.0067 2.97367 7.65837 3.59281C8.31005 4.21195 9.13013 4.62475 10.0156 4.77936C10.5199 4.86507 10.5199 5.58936 10.0156 5.67793C9.13005 5.83218 8.3098 6.24465 7.65787 6.86354C7.00594 7.48243 6.5514 8.28014 6.35134 9.1565L6.3142 9.31793C6.20134 9.81221 5.49563 9.80936 5.38705 9.31364L5.35848 9.17507C5.16564 8.29433 4.71476 7.49102 4.06339 6.86764C3.41202 6.24426 2.58969 5.82908 1.70134 5.67507H1.7042Z" stroke="white" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round"/>
						</g>
						<defs>
							<clipPath id="clip0_2460_1729">
							<rect width="20" height="20" fill="white" transform="translate(0.5)"/>
							</clipPath>
						</defs>
						</svg>`;



				return `
						<div class="betterdocs-ai-autowrite-form-container hidden" ${newDocPageAtt}>
							<div class="betterdocs-ai-autowrite-form-content">
								<div class="betterdocs-ai-autowrite-top-part">
									<div class="autowrite-heading">
										<span class="autowrite-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
											<g clip-path="url(#clip0_2453_20882)">
												<path d="M15.8322 30.1765L8.97508 30.7708L9.56936 23.9136L23.8322 9.74219C24.0451 9.52448 24.2993 9.3515 24.58 9.23341C24.8606 9.11531 25.162 9.05447 25.4665 9.05447C25.771 9.05447 26.0724 9.11531 26.3531 9.23341C26.6337 9.3515 26.8879 9.52448 27.1008 9.74219L30.0037 12.6679C30.2176 12.8805 30.3874 13.1334 30.5033 13.4119C30.6192 13.6904 30.6788 13.9891 30.6788 14.2908C30.6788 14.5924 30.6192 14.8911 30.5033 15.1696C30.3874 15.4481 30.2176 15.701 30.0037 15.9136L15.8322 30.1765ZM1.92593 9.07933C1.12365 8.9399 1.12365 7.7879 1.92593 7.64848C3.34647 7.40124 4.6612 6.73658 5.70251 5.73923C6.74382 4.74188 7.46455 3.45703 7.77279 2.04848L7.82079 1.82676C7.99451 1.03591 9.12365 1.02905 9.30651 1.82219L9.36365 2.07819C9.68319 3.48048 10.4099 4.75709 11.4526 5.74772C12.4953 6.73834 13.8074 7.39881 15.2242 7.64619C16.0311 7.78333 16.0311 8.94219 15.2242 9.0839C13.8073 9.33071 12.4949 9.99066 11.4518 10.9809C10.4087 11.9711 9.68146 13.2474 9.36136 14.6496L9.30193 14.9079C9.12136 15.6988 7.99222 15.6942 7.81851 14.901L7.77279 14.6793C7.46424 13.2702 6.74284 11.9849 5.70064 10.9874C4.65845 9.99003 3.34273 9.32574 1.92136 9.07933H1.92593Z" stroke="#E31B54" stroke-width="2.28571" stroke-linecap="round" stroke-linejoin="round"/>
											</g>
											<defs>
												<clipPath id="clip0_2453_20882">
												<rect width="32" height="32" fill="white"/>
												</clipPath>
											</defs>
											</svg>
										</span>
										<h1><?php echo esc_html__( 'Write Documentation with BetterDocs AI', 'betterdocs' ); ?></h1>

									</div>
									<div class="autowrite-subheadding">
										<p><?php echo esc_html__( 'Generate documentation effortlessly with BetterDocs AI. Simply input your doc title, keywords, prompt and let the system automatically generate comprehensive documentation tailored to your needs.', 'betterdocs' ); ?></p>
									</div>

									<?php if ( empty( $this->get_api_key() ) ) : ?>
										<div id="betterdocs-ai-message">
											<div class="warning-message">
													<span class="dashicons dashicons-warning"></span>
													<div>
														<?php echo wp_kses_post( 'Please Insert your <a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=betterdocs-settings&tab=tab-ai-autowrite' ) ) . '">OpenAI API Key</a> to use this Write with AI feature.', 'betterdocs' ); ?>
													</div>
											</div>

										</div>
									<?php endif; ?>

									<div class="form-group hidden">
										<div id="betterdocs-ai-error-message"></div>
									</div>

								</div>
								<form id="betterdocs-ai-form" class="<?php echo esc_attr( $disable_field ); ?>">
									<div class="form-inner-content">
										<div class="form-group">
											<label for="betterdocs-ai-title">${titleLabel}</label>
											<input type="text" placeholder="${titlePlaceholder}" id="betterdocs-ai-title" name="betterdocs-ai-title" required value="${title}">
										</div>

										<div class="form-group">
											<label for="betterdocs-ai-keyword">${keywordLabel}</label>
											<input type="text" placeholder="${keywordsPlaceholder}" id="betterdocs-ai-keyword" name="betterdocs-ai-keyword" required>
										</div>

										<div class="form-group prompt-field">
											<label for="betterdocs-ai-content">${promtLabel}</label>
											<textarea id="betterdocs-ai-content" name="betterdocs-ai-content" rows="6" required>${prompt}</textarea>
											<p class="input-description"><?php echo esc_html__( 'Ensure you include a clear and detailed prompt to receive the desired output. Follow the guidelines provided for the best results.', 'betterdocs' ); ?></p>
										</div>
										<div class="betterdocs-ai-checkbox-field ${hiddenClass}">
											<div class="form-group">
												<div class="checkbox-field-label">
												<label for="betterdocs-ai-checkbox">${checkboxLabel}</label>
												<p class="input-description"><?php echo esc_html__( 'Overwrite the existing doc with the AI Generated Content. If Disabled, new AI Generated content will be added in the section you are currently editing.', 'betterdocs' ); ?></p>
												</div>

												<div class="checkbox-input-field">
													<input type="checkbox" id="betterdocs-ai-checkbox" name="betterdocs-ai-checkbox" data-overwrite="false">
													<label for="betterdocs-ai-checkbox" class="toggle-label"></label>
												</div>
											</div>
										</div>

										<input type="hidden" name="ai_nonce" value="${nonce}" />
									</div>
									<div class="generate-button-container">
										<button type="submit" class="generate-btn" onclick="generateArticle(event)">
										${aiIcon} <span>${promtArticleLabel}</span>
										</button>
									</div>
								</form>
							</div>

							<div class="bd-close-button" onclick="closeWriteWithAIForm('afterClosedClicked')">✕</div>
						</div>

						<div id="betterdocs-ai-autowrite-form-container-overlay" class="hidden"></div>
					`;
			}

			jQuery(document).on('click', '.regenerate-btn', function() {
				jQuery('.betterdocs-ai-autowrite-form-container').removeClass('hidden');
				jQuery('#betterdocs-ai-autowrite-form-container-overlay').removeClass('hidden');
			});

			jQuery(document).ready(function($) {
				// Check if we are in the Edit Post screen

				if ($('.block-editor-page').length > 0) {

					const bdAIButton = $(`<div class="betterdocs-ai-button-container">
					<button class="betterdocs-ai-button">
						<img src="<?php echo esc_url( BETTERDOCS_ABSURL . 'assets/admin/images/betterdocs-icon-white.png' ); ?>" alt="<?php echo esc_attr__( 'betterdocs icon', 'betterdocs' ); ?>" />
						<span><?php echo esc_html__( 'Write with AI', 'betterdocs' ); ?></span>
					</button>
				</div>`);


					const closeBtn = $('bd-close-button');

					const formHtml = writeWithAIForm();
					$(formHtml).addClass('hidden');

					$(document).on('click', '.betterdocs-ai-button', function() {
						$('.betterdocs-ai-autowrite-form-container').removeClass('hidden');
						$('#betterdocs-ai-autowrite-form-container-overlay').removeClass('hidden');
					});

					// $(document).on('click', '.betterdocs-ai-button', function() {
					if ($('.betterdocs-ai-autowrite-form-container').length < 1) {
						$('body').append(formHtml);

						// Add event listeners to input elements
						document.getElementById('betterdocs-ai-title').addEventListener('input', updateTextarea);
						document.getElementById('betterdocs-ai-keyword').addEventListener('input', updateTextarea);
						// document.getElementById('bd-autowrtite-content-section').addEventListener('change', updateTextarea);
						// document.getElementById('bd-autowrtite-content-paragraph').addEventListener('change', updateTextarea);
						// document.getElementById('betterdocs-ai-language').addEventListener('input', updateTextarea);
					}
					// });
					const btn = document.createElement('button');
					wp.data.subscribe(function() {
						setTimeout(() => {
							if ($('.edit-post-header__settings').length > 0) {
								$('.edit-post-header__settings').prepend(bdAIButton);
							} else if ($('.editor-header__settings').length > 0) {
								$('.editor-header__settings').prepend(bdAIButton);
							}

						}, 1);
					});
				}

				$(document).on('click', '#betterdocs-ai-autowrite-form-container-overlay', function() {
					closeWriteWithAIForm();
				});
			});
		</script>

		<style>
			.hidden {
				display: none !important;
			}

			.disabled-input-field input,
			.disabled-input-field textarea,
			.disabled-input-field button,
			.disabled-input-field label {
				pointer-events: none;
				opacity: 0.6;
			}

			.disabled-input-field label {
				opacity: 1;
			}

			.betterdocs-ai-button-container {
				margin-left: 10px;
				white-space: nowrap;
				max-width: 145px;
			}

			.autowrite-icon {
				display: flex;
				align-items: center;
				width: 55px;
				height: 55px;
				background: #FFE4E8;
				justify-content: center;
				border-radius: 50px;
			}

			.autowrite-icon svg {
				width: 28px;
				height: 28px;
			}

			.autowrite-heading {
				display: flex;
				gap: 10px;
				align-items: center;
			}

			.autowrite-heading h1 {
				color: #1D2939;
				font-family: 'IBM Plex Sans', sans-serif;
				font-size: 24px;
				font-style: normal;
				font-weight: 500;
				line-height: normal;
				margin: 0;
			}

			.autowrite-heading p,
			form#betterdocs-ai-form p {
				margin: 0;
				margin-bottom: 24px;
			}

			.autowrite-subheadding p {
				font-family: 'IBM Plex Sans', sans-serif;
				font-size: 14px;
			}

			.prompt-field .input-description {
				margin: 0 !important;
			}

			.autowrite-heading p {
				color: #475467;
				font-family: 'IBM Plex Sans', sans-serif;
				font-size: 16px;
				font-style: normal;
				font-weight: 400;
			}

			.betterdocs-ai-button {
				background: #00B884;
				border: 1px solid transparent;
				color: #fff;
				box-shadow: none;
				font-size: 12px;
				margin-right: 8px;
				padding: 0px 15px;
				cursor: pointer;
				border-radius: 3px;
				height: 38px;
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 10px;
				width: 135px;
			}

			.betterdocs-ai-button img {
				width: 20px;
				height: 20px;
			}

			.betterdocs-ai-autowrite-form-container {
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				z-index: 100001;
				width: 650px;
				margin: 0 auto;
				background-color: #fff;
				border-radius: 5px;
				font-family: 'IBM Plex Sans', sans-serif;
				/* max-height: 600px; */
				max-height: 750px;
				max-width: 100%;
			}

			.betterdocs-ai-autowrite-form-content {
				background-color: #fff;
				padding: 20px 0px;
				border-radius: 5px;
				padding-bottom: 0;
				overflow: auto;
				/* max-height: 700px; */
				height: 100%;

			}

			.betterdocs-ai-autowrite-top-part {
				/* padding-bottom: 20px; */
				border-bottom: 1px solid #ddd;
				/* margin-bottom: 20px; */
				padding: 0 40px;
			}

			#betterdocs-ai-form {
				display: flex;
				flex-direction: column;
				/* height: 100%; */
				overflow: hidden;
			}

			.form-inner-content {
				overflow: auto;
				height: 100%;
				max-height: 435px;
				/* max-height: 330px; */
				padding: 0 40px;
			}

			.form-inner-content>.form-group {
				margin-top: 20px;
			}


			#betterdocs-ai-autowrite-form-container-overlay {
				position: fixed;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background-color: rgba(0, 0, 0, .7);
				z-index: 100000;
			}



			#betterdocs-ai-form {
				display: flex;
				flex-direction: column;
			}

			#betterdocs-ai-form .form-group {
				margin-bottom: 24px;
			}

			#betterdocs-ai-form .bd-aiform-flex {
				display: flex;
				justify-content: space-between;
			}

			#betterdocs-ai-form label {
				font-weight: 600;
				margin-bottom: 5px;
				display: block;
				font-size: 14px;
				line-height: 20px;
			}

			select#bd-autowrtite-content-section,
			#bd-autowrtite-content-paragraph,
			#betterdocs-ai-language {
				width: 150px !important;
				height: 40px;
			}

			#betterdocs-ai-form input[type="text"],
			#betterdocs-ai-form textarea {
				width: 100%;
				padding: 8px;
				box-sizing: border-box;
				border: 1px solid #D0D5DD;
				border-radius: 4px;
				color: #667085;
				font-weight: 400;
			}

			/* Override autofill text color */
			#betterdocs-ai-form input:-webkit-autofill {
				-webkit-text-fill-color: #667085 !important;
			}

			#betterdocs-ai-form input::placeholder {
				color: #acb2bf;
			}

			#betterdocs-ai-form textarea {
				color: #667085;
			}

			#betterdocs-ai-form input:focus,
			#betterdocs-ai-form textarea:focus {
				box-shadow: none;
			}

			#betterdocs-ai-form textarea:focus {
				color: inherit;
				box-shadow: none;

			}


			.generate-button-container {
				position: sticky;
				bottom: 0px;
				width: 100%;
				margin-left: 0;
				z-index: 999999 !important;
				padding: 20px 0;
				display: flex;
				justify-content: end;
				border-top: 1px solid #ddd;
				background: white;
				border-bottom-right-radius: 5px;
				border-bottom-left-radius: 5px;
			}

			.generate-button-container button {
				display: flex;
				gap: 8px;
				align-items: center;
				justify-content: center;
				opacity: 1 !important;
				margin-right: 40px;
			}

			.input-description {
				font-size: 14px;
				color: #667085;
			}

			.betterdocs-ai-checkbox-field .form-group {
				display: flex;
				align-items: start;
				/* gap: 200px; */
				justify-content: space-between;
			}

			.betterdocs-ai-checkbox-field input {
				width: 20px;
				height: 20px;
			}

			/* Add this CSS to your existing stylesheet or create a new one */

			.betterdocs-ai-checkbox-field {
				position: relative;
				font-size: 16px;
				/* Adjust font size as needed */
			}

			.betterdocs-ai-checkbox-field input[type=checkbox]:checked::before {
				padding: 2px;
			}

			.betterdocs-ai-checkbox-field input[type=checkbox] {
				border: 1px solid #00b884;
			}

			.betterdocs-ai-checkbox-field input[type=checkbox]:checked::before {
				content: '\2713';
				color: #00b884;
			}

			/* Change the default checkbox color */
			.betterdocs-ai-checkbox-field input[type="checkbox"]:hover {
				-webkit-appearance: none;
				/* WebKit/Blink Browsers */
				-moz-appearance: none;
				/* Firefox */
				appearance: none;
				border: 1px solid #00b884;
				/* Set the height of the checkbox */
				/* Optional: Round the corners */
				outline: none;
				/* Remove the default outline */
			}

			/* Style the checked state */
			.betterdocs-ai-checkbox-field input[type="checkbox"]:checked {
				/* Set the background color when checked */
				border: 1px solid #00b884;
				/* Set the border color when checked */
			}

			.checkbox-field-label {
				width: calc(100% - 150px);
			}

			.checkbox-field-label p {
				margin: 0 !important;
			}

			.checkbox-input-field {
				width: 300px;
				text-align: right;
			}

			.checkbox-input-field {
				position: relative;
				display: inline-block;
				width: 60px;
				height: 34px;
			}

			.checkbox-input-field input {
				display: none;
			}

			.toggle-label {
				position: absolute;
				cursor: pointer;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-color: #ccc;
				border-radius: 34px;
				transition: background-color 0.3s;
				scale: .9;
				width: 52px;
				height: 26px;
			}


			.checkbox-input-field input:checked+.toggle-label {
				background-color: #00b884;
			}

			.toggle-label:after {
				content: "";
				position: absolute;
				height: 22px;
				width: 22px;
				left: 2px;
				bottom: 2px;
				background-color: white;
				border-radius: 50%;
				transition: transform 0.3s;
			}

			.checkbox-input-field input:checked+.toggle-label:after {
				transform: translateX(26px);
			}

			.generate-btn,
			.prompt-btn,
			.keep-btn {
				background-color: #00b884;
				color: #fff;
				border: none;
				padding: 10px 15px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				cursor: pointer;
				border-radius: 4px;
			}

			.generate-btn[disabled] {
				cursor: unset;
			}

			.bd-close-button {
				cursor: pointer;
				font-size: 18px;
				font-weight: bold;
				float: right;
				position: absolute;
				top: -16px;
				border-radius: 50%;
				background: #ffffff;
				width: 40px;
				height: 40px;
				display: flex;
				align-items: center;
				justify-content: center;
				color: #281617;
				right: -42px;
			}

			div#betterdocs-ai-error-message,
			#betterdocs-ai-message {
				font-size: 14px;
				font-family: 'IBM Plex Sans', sans-serif;
				color: #667085;
				margin-bottom: 20px;
			}

			div#betterdocs-ai-error-message a,
			#betterdocs-ai-message a {
				text-decoration: none;
				font-weight: 600;
			}

			div#betterdocs-ai-error-message a:focus,
			#betterdocs-ai-message a:focus {
				outline: none;
				box-shadow: none;
				color: #2271b1;
			}

			#betterdocs-ai-message span {
				color: #d63638;
			}

			.warning-message {
				display: flex;
				align-items: center;
				gap: 10px;
				background: #fbebed;
				padding: 12px;
				border-radius: 5px;
				font-size: 14px;
				line-height: 1.4em;
				border-left: 4px solid #d63638;
			}

			.warning-message svg {
				width: 20px;
				height: 20px;
			}

			.warning-message span {
				color: #d63638;
			}

			.warning-message .footer-message {
				width: calc(100% - 20px);
			}

			@media only screen and (max-width: 991px) {
				.betterdocs-ai-autowrite-form-container {
					left: 0;
					right: 0;
					transform: translate(0%, -50%);
				}

				.betterdocs-ai-autowrite-form-content {
					overflow-x: auto;
				}
			}

			@media only screen and (max-width: 740px) {

				/* .bd-close-button {
					right: px;
					top: 1px;
					border-radius: 5px;
					width: 12px;
					height: 12px;
					color: #ffffff;
					background: #00b884;
				} */
				.bd-close-button {
					right: 0px;
					top: 0px;
					width: 12px;
					height: 12px;
					font-size: 14px;
				}

			}
		</style>
		<?php
	}
}
