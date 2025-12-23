<?php
/** @var $data */
$accountId = isset($_GET['accountId']) ? sanitize_text_field($_GET['accountId']) : null;
$mode = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']) : null;
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings">
        <?php include('partials/wpfs-header.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <?php if ($data->disabled): ?>
            <div class="wpfs-announcement">
                <?php esc_html_e('Settings are disabled until you have connected your Stripe account.', 'wp-full-stripe-free'); ?><br />
                Follow <a
                    href="https://docs.themeisle.com/article/2100-step-by-step-guide-to-setup-stripe-on-fullpay-v7">our
                    guide</a> for step-by-step instructions.
            </div>
        <?php endif; ?>
        <div class="wpfs-grid">
            <?php
            $currentGroup = '';
            foreach ($data->settingsItems as $item) {
            if ($currentGroup !== $item['group']['slug']) {
                if ($currentGroup !== '') {
                echo '</div></div>';
                }
                $currentGroup = $item['group']['slug'];
                echo '<div class="wpfs-grid-container" id="group-' . $currentGroup . '"><h2>' . $item['group']['title'] . '</h2><div class="wpfs-grid-items">';
            }
            ?>
            <?php if ($item['disabled']) { ?>
                <div class="wpfs-list__item" style="opacity: 0.5; cursor: not-allowed;">
                <div class="<?php echo $item['cssClasses']; ?> wpfs-list__icon"></div>
                <div class="wpfs-list__text">
                    <div class="wpfs-list__title">
                    <?php echo $item['title']; ?>
                    </div>
                    <div class="wpfs-list__desc">
                    <?php echo $item['description']; ?>
                    </div>
                </div>
                </div>
            <?php } else { ?>
                <a class="wpfs-list__item" href="<?php echo $item['url']; ?>">
                <div class="<?php echo $item['cssClasses']; ?> wpfs-list__icon"></div>
                <div class="wpfs-list__text">
                    <div class="wpfs-list__title">
                    <?php echo $item['title']; ?>
                    </div>
                    <div class="wpfs-list__desc">
                    <?php echo $item['description']; ?>
                    </div>
                </div>
                </a>
            <?php } ?>
            <?php } ?>
            </div></div>
        </div>

        <?php include('partials/wpfs-settings-test-data.php'); ?>
    </div>
    <script type="text/javascript">
        // Define a global JavaScript variable for the accountId
        var accountIdFromPHP = <?php echo json_encode($accountId); ?>;
        var accountModeFromPHP = <?php echo json_encode($mode); ?>;
    </script>
</div>