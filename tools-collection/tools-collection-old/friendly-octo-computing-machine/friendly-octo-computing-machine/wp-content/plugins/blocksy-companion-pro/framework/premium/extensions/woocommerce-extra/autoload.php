<?php

$autoload = [
	'Storage' => 'includes/storage.php',

	'FloatingCart' => 'features/floating-cart/feature.php',
	'CartPage' => 'features/cart-page/feature.php',
	'OffcanvasFilters' => 'features/offcanvas-filters/feature.php',
	'ShippingProgress' => 'features/shipping-progress/feature.php',
	'SkuSearch' => 'features/sku-search/feature.php',
	'Brands' => 'features/brands/feature.php',
	'CustomBadges' => 'features/custom-badges/feature.php',
	'ProductSaleCountdown' => 'features/product-sale-countdown/feature.php',
	'Checkout' => 'features/checkout/feature.php',

	'QuickView' => 'features/quick-view/feature.php',
	'QuickViewIntegrations' => 'features/quick-view/integrations.php',

	'RelatedSlideshow' => 'features/related-slideshow/feature.php',
	'WishList' => 'features/wish-list/feature.php',

	'CompareView' => 'features/compare/feature.php',
	'CompareTable' => 'features/compare/views/table.php',

	'AffiliateProduct' => 'features/affiliate/feature.php',
	'ShareBoxLayer' => 'features/share-box-layer/feature.php',
	'ProductGallery' => 'features/product-gallery/feature.php',
	'CustomTabs' => 'features/custom-tabs/feature.php',
	'CustomThankYouPage' => 'features/custom-thank-you-page/feature.php',
	'OrderDetailsBlock' => 'features/custom-thank-you-page/order-details-block/block.php',
	'SizeGuide' => 'features/size-guide/feature.php',
	'AdvancedReviews' => 'features/advanced-reviews/feature.php',
	'StockScarcity' => 'features/stock-scarcity/feature.php',
	'AddedToCartPopup' => 'features/added-to-cart-popup/feature.php',

	'SKULayer' => 'features/sku-layer.php',
	'AttributesLayer' => 'features/attributes-layer.php',

	'Swatches' => 'features/swatches/feature.php',
	'SwatchesApi' => 'features/swatches/includes/swatches-api.php',
	'SwatchesFrontend' => 'features/swatches/includes/frontend.php',
	'SwatchesPersistAttributes' => 'features/swatches/includes/persist-attributes.php',
	'SwatchesConfig' => 'features/swatches/includes/config.php',
	'SwatchElementRender' => 'features/swatches/includes/swatch-element-render.php',
	'SwatchesLoopVariableProduct' => 'features/swatches/includes/loop-variable-product.php',

	'Filters' => 'features/filters/feature.php',

	'FilterPresenter' => 'features/filters/includes/filter-presenter.php',

	'BaseFilter' => 'features/filters/includes/base-filter.php',

	'TaxonomiesFilter' => 'features/filters/filter-types/taxonomies.php',
	'AttributesFilter' => 'features/filters/filter-types/attributes.php',
	'ProductTermsCountTrait' => 'features/filters/includes/product-terms-count.php',

	'CommonWCFilter' => 'features/filters/filter-types/common-wc.php',
	'PriceFilter' => 'features/filters/filter-types/price.php',
	'StatusFilter' => 'features/filters/filter-types/status.php',
	'RatingFilter' => 'features/filters/filter-types/rating.php',

	'ApplyFilters' => 'features/filters/includes/apply-filters.php',
	'FiltersUtils' => 'features/filters/includes/utils.php',

	'ActiveFilters' => 'features/filters/includes/active-filters.php',
	'FiltersBlock' => 'features/filters/includes/block-filters.php',
	'PriceBlock' => 'features/filters/includes/block-price.php',
	'StatusBlock' => 'features/filters/includes/block-status.php',
	'RatingBlock' => 'features/filters/includes/block-rating.php',

	'QueryManager' => 'features/filters/includes/query-manager.php',

	'FiltersTaxonomiesProductsLookupTable' => 'features/filters/includes/taxonomies-products-lookup-table.php',
	'FiltersTaxonomiesProductsLookupStore' => 'features/filters/includes/taxonomies-products-lookup-store.php',
	'TaxonomiesProductsLookupHooks' => 'features/filters/includes/taxonomies-products-lookup-hooks.php',

	'WooTermsImportExport' => 'includes/woo-terms-import-export.php',
	'WooHelpers' => 'includes/woo-helpers.php',

	'ArchiveCard' => 'features/archive-card/feature.php',
	'OffcanvasCart' => 'features/offcanvas-cart/feature.php',

	'ProductWaitlistDb' => 'features/product-waitlist/includes/waitlist-db.php',
	'ProductWaitlistAccount' => 'features/product-waitlist/includes/waitlist-account.php',
	'Waitlist_Table' => 'features/product-waitlist/includes/waitlist-table.php',
	'Waitlist_Users_Table' => 'features/product-waitlist/includes/waitlist-users-table.php',
	'ProductWaitlistLayer' => 'features/product-waitlist/includes/waitlist-layer.php',
	'ProductWaitlistDashboard' => 'features/product-waitlist/includes/waitlist-dashboard.php',

	'WaitlistEmail' => 'features/product-waitlist/includes/emails/waitlist-email.php',
	'ConfirmSubscriptionEmail' => 'features/product-waitlist/includes/emails/confirm-subscription.php',
	'SubscriptionConfirmedEmail' => 'features/product-waitlist/includes/emails/subscription-confirmed.php',
	'BackInStockEmail' => 'features/product-waitlist/includes/emails/back-in-stock.php',
	'ProductWaitlistMailer' => 'features/product-waitlist/includes/waitlist-mailer.php',
	'BackInStockEmailScheduler' => 'features/product-waitlist/includes/back-in-stock-scheduler.php',

	'ProductWaitlist' => 'features/product-waitlist/feature.php',

	'CartSuggestedProducts' => 'features/cart-suggested-products/feature.php',

	'CartReservedTimer' => 'features/cart-reserved-timer/feature.php',

	'Utils' => 'utils.php'
];

