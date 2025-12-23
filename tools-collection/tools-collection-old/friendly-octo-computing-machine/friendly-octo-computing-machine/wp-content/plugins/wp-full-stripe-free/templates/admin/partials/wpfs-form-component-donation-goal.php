<?php
/** @var $view MM_WPFS_Admin_DonationFormView */
/** @var $form */
?>
<div class="wpfs-form-group" id="wpfs-donation-goal">
    <label for="<?php $view->donationGoal()->id(); ?>" class="wpfs-form-label"><?php $view->donationGoal()->label(); ?></label>
    <div id="wpfs-donation-goal-amount-container"></div>
</div>
<script type="text/javascript">
    var wpfsDonationGoalAmount = "<?php echo $form->donationGoal; ?>";
</script>
<script type="text/template" id="wpfs-fragment-donation-goal-amount-template">
    <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '1' ) { %>
    <div class="wpfs-input-group-prepend">
        <span class="wpfs-input-group-text"><%= currencySymbol %></span>
    </div>
    <% } %>
    <input id="<?php $view->donationGoal()->id(); ?>" class="wpfs-input-group-form-control" type="text" name="<?php $view->donationGoal()->name(); ?>" value="<%= donationGoal %>">
    <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '0' ) { %>
    <div class="wpfs-input-group-append">
        <span class="wpfs-input-group-text"><%= currencySymbol %></span>
    </div>
    <% } %>
</script>
