<?php
    /** @var $forms */
    /** @var $pageUrl */
    /** @var $modeFilter */
    /** @var $textFilter */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-payment-forms">
        <?php include('partials/wpfs-header-with-create-button.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <form name="wpfs-search-forms" action="<?php echo $pageUrl; ?>" method="post">
            <div class="wpfs-page-controls">
                <div class="wpfs-form-search wpfs-page-controls__control wpfs-page-controls__control--w320 js-form-search">
                    <input class="wpfs-form-control wpfs-form-search__input js-form-list-name-filter" type="text" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_FORM_TEXT_FILTER ?>" value="<?php echo !empty( $textFilter ) ? $textFilter : "";  ?>" placeholder="<?php _e( 'Search...', 'wp-full-stripe-free' ); ?>">
                    <button class="wpfs-form-search__btn">
                        <span class="wpfs-icon-search"></span>
                    </button>
                </div>
                <div class="wpfs-ui wpfs-form-select wpfs-page-controls__control wpfs-page-controls__control--w200">
                    <select class="js-selectmenu js-form-list-mode-filter" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_FORM_MODE_FILTER ?>" id="mode" data-selectmenu-prefix="<?php _e( 'Mode: ', 'wp-full-stripe-free' ); ?>">
                        <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL; ?>" <?php echo $modeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL ? "selected" : "";  ?>><?php _e( 'All', 'wp-full-stripe-free' ); ?></option>
                        <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE; ?>" <?php echo $modeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE ? "selected" : "";  ?>><?php _e( 'Live', 'wp-full-stripe-free' ); ?></option>
                        <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_TEST; ?>" <?php echo $modeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_TEST ? "selected" : "";  ?>><?php _e( 'Test', 'wp-full-stripe-free' ); ?></option>
                    </select>
                </div>
            </div>
        </form>
        <table id="wpfs-form-list" class="wpfs-data-table">
            <thead>
            <tr class="wpfs-data-table__tr">
                <th class="wpfs-data-table__th wpfs-data-table__th--w40"></th>
                <th class="wpfs-data-table__th"><?php _e( 'Name / ID', 'wp-full-stripe-free' ); ?></th>
                <th class="wpfs-data-table__th"><?php _e( 'Type / Layout', 'wp-full-stripe-free' ); ?></th>
                <th class="wpfs-data-table__th"><?php _e( 'Last used', 'wp-full-stripe-free' ); ?></th>
                <th class="wpfs-data-table__th"><?php _e( 'Shortcode', 'wp-full-stripe-free' ); ?></th>
                <th class="wpfs-data-table__th"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $formIdx = 0;
            foreach ( $forms as $form ) {
            ?>
            <tr class="wpfs-data-table__tr" data-form-display-name="<?php echo htmlspecialchars( $form->displayName ); ?>" data-form-name="<?php echo $form->name; ?>" data-form-id="<?php echo $form->id; ?>" data-form-type="<?php echo $form->type; ?>" data-form-layout="<?php echo $form->layout; ?>">
                <td class="wpfs-data-table__td wpfs-data-table__td--w40">
                    <?php
                        $typeClass = "wpfs-illu-book-close-bookmark";

                        if ( $form->type == MM_WPFS::FORM_TYPE_PAYMENT ) {
                            $typeClass = "wpfs-illu-book-close-bookmark";
                        } elseif ( $form->type == MM_WPFS::FORM_TYPE_SUBSCRIPTION ) {
                            $typeClass = "wpfs-illu-subscription";
                        } elseif ( $form->type == MM_WPFS::FORM_TYPE_DONATION ) {
                            $typeClass = "wpfs-illu-donation";
                        } elseif ( $form->type == MM_WPFS::FORM_TYPE_SAVE_CARD ) {
                            $typeClass = "wpfs-illu-credit-card";
                        }
                    ?>
                    <div class="<?php echo $typeClass ?>"></div>
                </td>
                <td class="wpfs-data-table__td">
                    <a class="wpfs-btn wpfs-btn-link" href="<?php echo $form->editUrl;  ?>"><?php echo htmlspecialchars( $form->displayName ); ?></a>
                    <br>
                    <div class="wpfs-typo-body wpfs-typo-body--sm"><?php echo $form->name; ?></div>
                </td>
                <td class="wpfs-data-table__td">
                    <?php
                        $typeLabel   = "[Invalid type]";
                        $layoutLabel = "[Invalid layout]";

                        if ( $form->type === MM_WPFS::FORM_TYPE_PAYMENT ) {
                            $typeLabel = __( 'One-time payment', 'wp-full-stripe-free' );
                        } elseif ( $form->type === MM_WPFS::FORM_TYPE_SUBSCRIPTION ) {
                            $typeLabel = __( 'Subscription', 'wp-full-stripe-free' );
                        } elseif ( $form->type === MM_WPFS::FORM_TYPE_DONATION ) {
                            $typeLabel = __( 'Donation', 'wp-full-stripe-free' );
                        } elseif ( $form->type === MM_WPFS::FORM_TYPE_SAVE_CARD ) {
                            $typeLabel = __( 'Save card', 'wp-full-stripe-free' );
                        }

                        if ( $form->layout === MM_WPFS::FORM_LAYOUT_INLINE ) {
                            $layoutLabel = __( 'Inline', 'wp-full-stripe-free' );
                        } elseif ( $form->layout === MM_WPFS::FORM_LAYOUT_CHECKOUT ) {
                            $layoutLabel = __( 'Checkout', 'wp-full-stripe-free' );
                        }
                    ?>
                    <div class="wpfs-typo-body wpfs-typo-body--gunmetal"><?php echo $typeLabel; ?></div>
                    <div class="wpfs-typo-body wpfs-typo-body--sm"><?php echo $layoutLabel; ?></div>
                </td>
                <td class="wpfs-data-table__td">
                    <strong><?php echo $form->lastUsedAt; ?></strong>
                </td>
                <td class="wpfs-data-table__td">
                    <input class="wpfs-form-control wpfs-form-control--w240 js-copy-shortcode" type="text" value="<?php echo htmlspecialchars( $form->shortCode ); ?>" readonly>
                </td>
                <td class="wpfs-data-table__td wpfs-data-table__td--right wpfs-data-table__td--actions">
                    <a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip" href="<?php echo esc_url( $form->editUrl );  ?>" data-tooltip-content="edit-tooltip-<?php echo $formIdx; ?>">
                        <span class="wpfs-icon-edit"></span>
                    </a>
                    <div class="wpfs-tooltip-content" data-tooltip-id="edit-tooltip-<?php echo $formIdx; ?>">
                        <div class="wpfs-info-tooltip"><?php _e( 'Edit Form', 'wp-full-stripe-free' ); ?></div>
                    </div>
                    <a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-open-preview-popover" data-tooltip-content="preview-tooltip-<?php echo $formIdx; ?>" data-shortcode-value='<?php echo htmlspecialchars($form->shortCode); ?>'>
                        <span class="wpfs-icon-view"></span>
                    </a>
                    <div class="wpfs-tooltip-content" data-tooltip-id="preview-tooltip-<?php echo $formIdx; ?>">
                        <div class="wpfs-info-tooltip"><?php _e( 'Preview Form', 'wp-full-stripe-free' ); ?></div>
                    </div>
                    <a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-clone-form" data-tooltip-content="duplicate-tooltip-<?php echo $formIdx; ?>">
                        <span class="wpfs-icon-duplicate"></span>
                    </a>
                    <div class="wpfs-tooltip-content" data-tooltip-id="duplicate-tooltip-<?php echo $formIdx; ?>">
                        <div class="wpfs-info-tooltip"><?php _e( 'Clone', 'wp-full-stripe-free' ); ?></div>
                    </div>
                    <a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-delete-form" data-tooltip-content="trash-tooltip-<?php echo $formIdx; ?>">
                        <span class="wpfs-icon-trash"></span>
                    </a>
                    <div class="wpfs-tooltip-content" data-tooltip-id="trash-tooltip-<?php echo $formIdx; ?>">
                        <div class="wpfs-info-tooltip"><?php _e( 'Delete', 'wp-full-stripe-free' ); ?></div>
                    </div>
                </td>
            </tr>
            <?php
                $formIdx++;
            }
            ?>
            </tbody>
        </table>
        <div id="wpfs-success-message-container"></div>
    </div>
    <div id="wpfs-dialog-container"></div>
    <script type="text/template" id="wpfs-modal-preview-form">
        <div class="wpfs-dialog-scrollable">
            <p class="wpfs-dialog-content-text"><iframe src="<%= previewUrl %>" width="100%" height="600px" style="border:none;"></iframe></p>
        </div>
    </script>
    <script type="text/template" id="wpfs-modal-delete-form">
        <div class="wpfs-dialog-scrollable">
            <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
        </div>
        <div class="wpfs-dialog-content-actions">
            <button class="wpfs-btn wpfs-btn-danger js-delete-form-dialog"><?php _e( 'Delete form', 'wp-full-stripe-free'); ?></button>
            <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep form', 'wp-full-stripe-free' ); ?></button>
        </div>
    </script>
    <script type="text/template" id="wpfs-modal-delete-form-in-progress">
        <div class="wpfs-dialog-scrollable">
            <p class="wpfs-dialog-content-text"><?php _e('After deleting this form, you will be able to find related payments, subscriptions, or customers in Stripe.', 'wp-full-stripe-free'); ?></p>
        </div>
        <div class="wpfs-dialog-content-actions">
            <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php _e( 'Delete form', 'wp-full-stripe-free'); ?></button>
        </div>
    </script>
    <script type="text/template" id="wpfs-modal-dialog-error">
        <div class="wpfs-dialog-scrollable">
            <div class="wpfs-inline-message wpfs-inline-message--error">
                <div class="wpfs-inline-message__inner">
                    <strong><%- errorMessage %></strong>
                </div>
            </div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <a class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e('Close', 'wp-full-stripe-free'); ?></a>
        </div>
    </script>
    <script type="text/template" id="wpfs-modal-clone-form">
        <div class="wpfs-dialog-scrollable">
            <p class="wpfs-dialog-content-text"><?php _e( 'Clone a form and all its settings.', 'wp-full-stripe-free' ); ?></p>
            <div class="wpfs-form-group">
                <label for="" class="wpfs-form-label"><?php _e( 'New form name', 'wp-full-stripe-free' ); ?></label>
                <input class="wpfs-form-control" type="text" name="wpfs-new-form-display-name" value="<%- newFormDisplayName %>">
            </div>
            <div class="wpfs-form-group">
                <label for="" class="wpfs-form-label"><?php _e( 'New form id', 'wp-full-stripe-free' ); ?></label>
                <input class="wpfs-form-control" type="text" name="wpfs-new-form-name" value="<%- newFormName %>">
            </div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <a class="wpfs-btn wpfs-btn-primary js-clone-form-dialog"><?php _e( 'Clone', 'wp-full-stripe-free' ); ?></a>
            <a class="wpfs-btn wpfs-btn-primary js-clone-and-edit-form-dialog"><?php _e( 'Clone & Edit', 'wp-full-stripe-free' ); ?></a>
            <a class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Discard', 'wp-full-stripe-free' ); ?></a>
        </div>
    </script>
    <script type="text/template" id="wpfs-modal-clone-form-in-progress">
        <div class="wpfs-dialog-scrollable">
            <p class="wpfs-dialog-content-text"><?php _e( 'Clone a form and all its settings.', 'wp-full-stripe-free' ); ?></p>
            <div class="wpfs-form-group">
                <label for="" class="wpfs-form-label"><?php _e( 'New form name', 'wp-full-stripe-free' ); ?></label>
                <input class="wpfs-form-control" type="text" value="<%- newFormDisplayName %>" disabled>
            </div>
            <div class="wpfs-form-group">
                <label for="" class="wpfs-form-label"><?php _e( 'New form id', 'wp-full-stripe-free' ); ?></label>
                <input class="wpfs-form-control" type="text" value="<%- newFormName %>" disabled>
            </div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <% if ( editNewForm ) { %>
            <a class="wpfs-btn wpfs-btn-primary wpfs-btn-primary--loader" disabled><?php _e( 'Clone & Edit', 'wp-full-stripe-free' ); ?></a>
            <% } else { %>
            <a class="wpfs-btn wpfs-btn-primary wpfs-btn-primary--loader" disabled><?php _e( 'Clone', 'wp-full-stripe-free' ); ?></a>
            <% } %>
        </div>
    </script>
    <script type="text/template" id="wpfs-success-message">
        <div class="wpfs-floating-message__inner">
            <div class="wpfs-floating-message__message"><%- successMessage %></div>
            <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
                <span class="wpfs-icon-close"></span>
            </button>
        </div>
    </script>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>
