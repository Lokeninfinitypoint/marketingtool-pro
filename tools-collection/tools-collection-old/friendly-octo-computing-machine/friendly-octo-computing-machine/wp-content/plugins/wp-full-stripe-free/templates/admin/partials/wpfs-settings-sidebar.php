<?php
/** @var $data */
/** @var $pageSlug */
?>
<div class="wpfs-page-settings-sidebar">
    <?php
    $currentGroup = '';
    foreach ($data->settingsItems as $item) {
    if ($currentGroup !== $item['group']['slug']) {
        if ($currentGroup !== '') {
        echo '</div></div>';
        }
        $currentGroup = $item['group']['slug'];
        echo '<div class="wpfs-page-settings-sidebar-group" id="group-' . $currentGroup . '"><h3>' . $item['group']['title'] . '</h3><div class="wpfs-page-settings-sidebar-items">';
    }
    ?>
    <?php if ($item['disabled']) { ?>
        <div class="wpfs-page-settings-sidebar__item" style="opacity: 0.5; cursor: not-allowed;">
        <div class="<?php echo $item['cssClasses']; ?> wpfs-page-settings-sidebar__icon"></div>
        <div class="wpfs-page-settings-sidebar__title">
        <?php echo $item['title']; ?>
        </div>
        </div>
    <?php } else { ?>
        <a class="wpfs-page-settings-sidebar__item<?php echo ( $item['slug'] === $pageSlug ) ? ' active' : ''; ?>" href="<?php echo $item['url']; ?>">
        <div class="<?php echo $item['cssClasses']; ?> wpfs-page-settings-sidebar__icon"></div>
        <div class="wpfs-page-settings-sidebar__title">
        <?php echo $item['title']; ?>
        </div>
        </a>
    <?php } ?>
    <?php } ?>
    </div></div>
</div>