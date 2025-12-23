<?php

/**
 * Provide a admin area view for ai assistant
 *
 *
 * @link       https://hostinger.com
 * @since      1.0.0
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin/partials
 */

$content    = new Hostinger_Ai_Assistant_Content_Generation();
$post_types = $content->get_public_post_types();
$menu_icon  = get_post_type_object( $post_types[0] )->menu_icon ?? 'dashicons-admin-post';
$helper     = new Hostinger_Ai_Assistant_Helper();

?>
<div class="hts-ai-assistant">
    <div class="wrapper">
        <div class="hts-ai-tab-head">
            <div class="hts-heading">
                <h2><?php echo __( 'AI Content Creator', 'hostinger-ai-assistant' ); ?></h2>
            </div>
            <div class="hts-ai-tutorials">
                <div class="hts-button-wrapper">
                <a href="https://www.hostinger.com/tutorials/how-to-use-hostinger-ai-plugin" class="hts-btn hts-secondary-btn" target="_blank">
                    <?php echo __( 'Open guide', 'hostinger-ai-assistant' ); ?>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.2478C2.5 3.72902 3.73122 2.4978 5.25 2.4978H6C6.41421 2.4978 6.75 2.83359 6.75 3.2478C6.75 3.66202 6.41421 3.9978 6 3.9978H5.25C4.55964 3.9978 4 4.55745 4 5.2478V10.6859C4 11.3763 4.55964 11.9359 5.25 11.9359H10.7508C11.4411 11.9359 12.0008 11.3763 12.0008 10.6859V10C12.0008 9.58579 12.3366 9.25 12.7508 9.25C13.165 9.25 13.5008 9.58579 13.5008 10V10.6859C13.5008 12.2047 12.2696 13.4359 10.7508 13.4359H5.25C3.73122 13.4359 2.5 12.2047 2.5 10.6859V5.2478ZM12 5.06077L8.03033 9.03044C7.73744 9.32333 7.26256 9.32333 6.96967 9.03044C6.67678 8.73754 6.67678 8.26267 6.96967 7.96977L10.9393 4.00011L9 4.0001C8.58579 4.0001 8.25 3.66432 8.25 3.2501C8.25 2.83589 8.58579 2.5001 9 2.5001L12.25 2.50011C12.9404 2.50011 13.5 3.05975 13.5 3.75011V7.0001C13.5 7.41432 13.1642 7.7501 12.75 7.7501C12.3358 7.7501 12 7.41432 12 7.0001V5.06077Z" fill="#673DE6"/>
                    </svg>
                </a>
                <a href="<?php echo get_site_url(); ?>" class="hts-btn hts-secondary-btn" target="_blank">
                    <svg width="16" height="16" viewBox="0 0 16 16" class="reverse" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99998 6.23374C7.02266 6.23374 6.2304 7.02601 6.2304 8.00332C6.2304 8.98063 7.02266 9.7729 7.99998 9.7729C8.97729 9.7729 9.76956 8.98063 9.76956 8.00332C9.76956 7.02601 8.97729 6.23374 7.99998 6.23374ZM4.69123 8.00332C4.69123 6.17595 6.17261 4.69457 7.99998 4.69457C9.82735 4.69457 11.3087 6.17595 11.3087 8.00332C11.3087 9.83069 9.82735 11.3121 7.99998 11.3121C6.17261 11.3121 4.69123 9.83069 4.69123 8.00332Z" fill="currentColor"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99508 3.92088C6.23615 3.92088 3.12832 4.78339 1.55391 7.93872C1.5343 7.97801 1.53407 8.02604 1.55433 8.06651C3.12861 11.2094 6.1426 12.0858 8.00495 12.0858C9.76389 12.0858 12.8717 11.2233 14.4461 8.06799C14.4657 8.0287 14.466 7.98067 14.4457 7.9402C12.8714 4.79726 9.85743 3.92088 7.99508 3.92088ZM0.176668 7.25152C2.09445 3.40802 5.86487 2.38171 7.99508 2.38171C10.2434 2.38171 13.909 3.43186 15.8219 7.25088C16.0585 7.72326 16.0598 8.28126 15.8234 8.75519C13.9056 12.5987 10.1352 13.625 8.00495 13.625C5.7566 13.625 2.09108 12.5749 0.178153 8.75583C-0.058457 8.28345 -0.0598124 7.72545 0.176668 7.25152Z" fill="#673DE6"/>
                    </svg>
                    <?php echo __( 'Preview site', 'hostinger-ai-assistant' ); ?>
                </a>
                <?php if ( $helper->get_edit_site_url() ) : ?>
                <a href="<?php echo $helper->get_edit_site_url(); ?>" class="hts-btn hts-secondary-btn" target="_blank">
                    <svg width="16" height="16" viewBox="0 0 16 16" class="reverse" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.85691 14.1816C6.85691 13.7674 7.14483 13.4316 7.5 13.4316H13.6069C13.9621 13.4316 14.25 13.7674 14.25 14.1816C14.25 14.5958 13.9621 14.9316 13.6069 14.9316H7.5C7.14483 14.9316 6.85691 14.5958 6.85691 14.1816Z" fill="currentColor"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.29084 12.6658L13.0587 3.80312C13.0835 3.77806 13.1068 3.75446 13.1289 3.7321C13.1066 3.70997 13.083 3.68661 13.0579 3.66183L12.2098 2.82257C12.1847 2.79775 12.1611 2.77438 12.1387 2.75227C12.1165 2.77459 12.0932 2.7982 12.0683 2.82325L6.68596 8.25301L3.29957 11.6881C3.29328 11.6945 3.28738 11.7004 3.28183 11.7061C3.27962 11.7137 3.27728 11.7217 3.27478 11.7303L2.87798 13.0951L4.24799 12.6914C4.25676 12.6888 4.26497 12.6864 4.27271 12.6841C4.27839 12.6784 4.28441 12.6723 4.29084 12.6658ZM5.61974 7.19793L2.23046 10.6359C2.12466 10.7432 2.07176 10.7969 2.02795 10.857C1.98904 10.9104 1.95553 10.9675 1.9279 11.0275C1.89679 11.0951 1.87576 11.1674 1.8337 11.3121L1.24307 13.3436C1.05693 13.9838 0.963865 14.3039 1.04577 14.5217C1.11715 14.7115 1.26745 14.8611 1.45773 14.9316C1.67608 15.0125 1.99607 14.9182 2.63606 14.7296L4.67252 14.1295C4.81992 14.086 4.89362 14.0643 4.96229 14.0322C5.02327 14.0036 5.0812 13.969 5.13522 13.9288C5.19605 13.8836 5.25007 13.829 5.3581 13.7198L14.1259 4.85713C14.5195 4.45935 14.7162 4.26046 14.7891 4.03197C14.8533 3.83099 14.8521 3.61488 14.7859 3.41459C14.7105 3.1869 14.5117 2.99011 14.1139 2.59653L13.2658 1.75727C12.8677 1.36333 12.6687 1.16637 12.4399 1.09331C12.2386 1.02905 12.0222 1.03009 11.8216 1.09629C11.5935 1.17155 11.3964 1.37042 11.0021 1.76817L5.61974 7.19793ZM12.4708 2.43329L12.4689 2.43476L12.4708 2.43329ZM11.8055 2.43797L11.8035 2.43651L11.8055 2.43797ZM13.4478 4.06357L13.4463 4.06157L13.4478 4.06357ZM13.4428 3.39923L13.4443 3.39725L13.4428 3.39923Z" fill="#673DE6"/>
                    </svg>
                    </svg>
                    <?php echo __( 'Edit site', 'hostinger-ai-assistant' ); ?>
                </a>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="hts-container">
            <div class="hts-description">
                <h3><?php echo __( 'What do you want to make today?', 'hostinger-ai-assistant' ); ?></h3>
            </div>
            <div class="hts-inputs-wrapper">
                <div class="hts-input-item">
                    <div class="container">
                        <div class="setting-description">
                            <div class="setting-description-text">
                                <span><?php echo __( 'Content Type', 'hostinger-ai-assistant' ); ?></span>
                            </div>
                        </div>
                        <div class="wrapper-dropdown" id="dropdown">
                            <?php if ( isset( $post_types[0] ) ) : ?>
                                <span class="selected-display dashicons-before <?php echo $menu_icon; ?>" id="hts-posttype" data-value="<?php echo $post_types[0]; ?>"><?php echo get_post_type_object( $post_types[0] )->label; ?></span>
                            <?php endif; ?>
                            <svg id="drp-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="hts-arrow transition-all ml-auto">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 10L12 15L17 10H7Z" fill="#727586"/>
                            </svg>
                            <ul class="dropdown">
                                <?php
                                $first_iteration = true;
                                foreach ( $post_types as $post_type ) :
                                    ?>
                                    <li class="item dashicons-before
                                    <?php
                                    echo get_post_type_object( $post_type )->menu_icon . ' ';
                                    if ( $first_iteration ) {
                                        echo 'active';}
                                    ?>
                                    " data-value="<?php echo $post_type; ?>"><?php echo get_post_type_object( $post_type )->label; ?></li>
                                    <?php
                                    $first_iteration = false;
                                endforeach;
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="hts-input-description">
                        <p><?php echo __( 'Choose the type of content that will be generated', 'hostinger-ai-assistant' ); ?></p>
                    </div>
                </div>
                <div class="hts-input-item">
                    <div class="container">
                        <div class="setting-description">
                            <div class="setting-description-text">
                                <span><?php echo __( 'Tone of voice', 'hostinger-ai-assistant' ); ?></span>
                            </div>
                        </div>
                        <div class="row hts-voice-wrapper">
                            <select name="sel-01" id="hts-voice" class="select2-multiple-voice" multiple>
                                <option value="neutral"><?php echo __( 'Neutral', 'hostinger-ai-assistant' ); ?></option>
                                <option value="formal"><?php echo __( 'Formal', 'hostinger-ai-assistant' ); ?></option>
                                <option value="trustworthy"><?php echo __( 'Trustworthy', 'hostinger-ai-assistant' ); ?></option>
                                <option value="friendly"><?php echo __( 'Friendly', 'hostinger-ai-assistant' ); ?></option>
                                <option value="witty"><?php echo __( 'Witty', 'hostinger-ai-assistant' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="hts-input-description">
                        <p><?php echo __( 'Choose your desired emotional impact on readers', 'hostinger-ai-assistant' ); ?></p>
                    </div>
                </div>
                <div class="hts-input-item hts-content-length">
                    <div class="container">
                        <div class="setting-description">
                            <div class="setting-description-text">
                                <span><?php echo __( 'Content length', 'hostinger-ai-assistant' ); ?></span>
                            </div>
                        </div>
                        <div class="wrapper-dropdown" id="dropdown">
                                <span class="selected-display" id="hts-content-length" data-value="short"><?php echo __( 'Short', 'hostinger-ai-assistant' ); ?></span>
                            <svg id="drp-arrow" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="hts-arrow transition-all ml-auto">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 10L12 15L17 10H7Z" fill="#727586"/>
                            </svg>
                            <ul class="dropdown">
                                <li class="item active" data-value="short">
                                    <span><?php echo __( 'Short', 'hostinger-ai-assistant' ); ?></span>
                                    <div class="hts-select-description">
                                        <p><?php echo __( 'Usually used for video, infographics, or product descriptions', 'hostinger-ai-assistant' ); ?></p>
                                    </div>
                                </li>
                                <li class="item" data-value="medium">
                                    <span><?php echo __( 'Medium', 'hostinger-ai-assistant' ); ?></span>
                                    <div class="hts-select-description">
                                        <p><?php echo __( 'Balances depth and readability, engaging audiences and driving leads.', 'hostinger-ai-assistant' ); ?></p>
                                    </div>
                                </li>
                                <li class="item" data-value="long">
                                    <span><?php echo __( 'Long', 'hostinger-ai-assistant' ); ?></span>
                                    <div class="hts-select-description">
                                        <p><?php echo __( 'Usually used to make high-ranked articles that will generate more leads', 'hostinger-ai-assistant' ); ?></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="hts-input-description">
                        <p><?php echo __( 'Choose the length of generated content', 'hostinger-ai-assistant' ); ?></p>
                    </div>
                </div>
            </div>
            <div class="hts-description">
                <h3><?php echo __( 'What is your content about?', 'hostinger-ai-assistant' ); ?></h3>
            </div>
            <div class="wrapper">
                <div class="hts-input-textarea">
                    <div class="hts-label">
                        <?php echo __( 'Content main idea', 'hostinger-ai-assistant' ); ?>
                    </div>
                    <textarea id="hts-ai-description-input"><?php echo __( 'Let us know more about your content idea. For example: Article about how to use WordPress to dive into website development including tutorials how to use it in a simple way...', 'hostinger-ai-assistant' ); ?></textarea>
                </div>
            </div>
            <div class="progress-bar-wrapper">
                <div class="progress-bar">
                    <div class="progress-bar-step"></div>
                    <div class="progress-bar-step"></div>
                    <div class="progress-bar-step"></div>
                </div>
            </div>
            <div id="hts-input-message">
                <?php echo __( 'Enter at least 10 characters', 'hostinger-ai-assistant' ); ?>
            </div>
            <div class="hts-focus-keywords">
                <div class="hts-description">
                    <h3><?php echo __( 'What are the focus keywords of your content?', 'hostinger-ai-assistant' ); ?></h3> <span><?php echo __( 'Optional', 'hostinger-ai-assistant' ); ?></span>
                </div>
                <div class="hts-input-description">
                    <?php echo __( 'If you skip this part, AI will automatically generate keyword suggestions after you generate the content', 'hostinger-ai-assistant' ); ?>
                </div>
                <select id="hts-focus-keywords" multiple></select>
                <div class="hts-input-description">
                    <?php echo __( 'Press Enter key to finalize a keyword', 'hostinger-ai-assistant' ); ?>
                </div>
            </div>
            <a class="hts-submit-button hts-btn hts-primary-btn hts-disabled" rel="noopener noreferrer">
                <?php wp_nonce_field( 'generate_content', 'generate_content_nonce' ); ?>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.3 8.12501L18.225 5.62501L15.625 4.47501L18.225 3.35001L19.3 0.975006L20.375 3.35001L22.975 4.47501L20.375 5.62501L19.3 8.12501ZM19.3 23L18.225 20.6L15.625 19.475L18.225 18.35L19.3 15.825L20.375 18.35L22.975 19.475L20.375 20.6L19.3 23ZM8.325 19.15L6.025 14.225L1 11.975L6.025 9.72501L8.325 4.82501L10.65 9.72501L15.65 11.975L10.65 14.225L8.325 19.15Z"
                            fill="#1D1E20"/>
                </svg>
                <?php echo __( 'Create content', 'hostinger-ai-assistant' ); ?>
            </a>
        </div>
        <div class="hts-ai-assistant-result">
            <div class="hts-loader-wrapper">
                <div class="hts-loader">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.95 12.1874L27.3375 8.4374L23.4375 6.7124L27.3375 5.0249L28.95 1.4624L30.5625 5.0249L34.4625 6.7124L30.5625 8.4374L28.95 12.1874ZM28.95 34.4999L27.3375 30.8999L23.4375 29.2124L27.3375 27.5249L28.95 23.7374L30.5625 27.5249L34.4625 29.2124L30.5625 30.8999L28.95 34.4999ZM12.4875 28.7249L9.0375 21.3374L1.5 17.9624L9.0375 14.5874L12.4875 7.2374L15.975 14.5874L23.475 17.9624L15.975 21.3374L12.4875 28.7249Z"
                                fill="#2F1C6A"/>
                    </svg>
                    <h3><?php echo __( 'Brewing content with magic', 'hostinger-ai-assistant' ); ?></h3>
                </div>
                <div class="hts-loader-container">
                    <div id="hts-loader-progress-bar" class="hts-loader-progress"></div>
                </div>
            </div>
            <div id="hts-loader-response-container">

                <div class="hts-response-content">
                    <?php require_once 'hostinger-ai-assistant-seo-meta-view.php'; ?>
                    <div class="hts-response-data"></div>
                </div>

                <div class="hts-bottom-content">
                    <div class="hts-words">
                        <div id="hts-content-words"><span></span> <?php echo __( 'words', 'hostinger-ai-assistant' ); ?></div>
                        <span class="hts-separator">|</span>
                        <div id="hts-content-chars"><span></span> <?php echo __( 'characters', 'hostinger-ai-assistant' ); ?>
                        </div>
                    </div>
                    <div class="hts-btn-wrapper">
                    <div class="hts-btn hts-secondary-btn" id="hts-edit-as-draft">
                        <?php wp_nonce_field( 'create_post', 'create_post_nonce' ); ?>
                        <span class="button__text">
                            <?php echo __( 'Edit as a draft', 'hostinger-ai-assistant' ); ?>
                        </span>
                    </div>
                    <div class="hts-btn hts-primary-btn" id="hts-publish-post">
                        <?php wp_nonce_field( 'publish_post', 'publish_post_nonce' ); ?>
                        <span class="button__text">
                            <?php echo __( 'Publish', 'hostinger-ai-assistant' ); ?>
                        </span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hts-existing-content-popup">
            <h3>
                <?php echo __( 'Are you sure you want to replace your existing content with a new one ?', 'hostinger-ai-assistant' ); ?>
            </h3>
            <p>
                <?php echo __( 'Clicking <b>Generate new content</b> will permanently delete your existing content and generate a new one on your recent inputs.', 'hostinger-ai-assistant' ); ?>
            </p>
            <div class="hts-popup-buttons">
                <div class="hts-cancel">
                    <?php echo __( 'Cancel', 'hostinger-ai-assistant' ); ?>
                </div>
                <div class="hts-btn hts-primary-btn hts-confirm-btn">
                    <?php echo __( 'Generate new content', 'hostinger-ai-assistant' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hts-loader-modal">
    <div class="hts-loader-modal__content">
        <div class="hts-loader-modal__image-wrap">
            <img src="<?php echo HOSTINGER_AI_ASSISTANT_PLUGIN_URL . 'assets/img/loading-modal-icon.svg'; ?>" alt="<?php echo esc_attr( __( 'Generating content...', 'hostinger-ai-assistant' ) ); ?>">
        </div>
        <div class="hts-loader-modal__title">
            <?php echo __( 'Generating content...', 'hostinger-ai-assistant' ); ?>
        </div>
        <div class="hts-loader-modal__description">
            <?php echo __( 'This will only take a moment', 'hostinger-ai-assistant' ); ?>
        </div>
        <div class="hts-loader-modal__loader-wrap">
            <div class="hts-loader-modal__loader"></div>
        </div>
    </div>
</div>
