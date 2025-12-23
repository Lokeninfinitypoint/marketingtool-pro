<?php
	if (! defined('ABSPATH')) {
		exit;
	}

	$base = get_option( 'woocommerce_email_base_color' );
?>

.ct-product-table {
	width: 100%;
	margin: 0 0 20px 0;
}

.ct-align-end {
	text-align: end;
}

.ct-image-column {
	width: 35px;
}

.ct-add-to-cart {
	display: inline-block;
	color: #fff;
	text-decoration: none;
	white-space: nowrap;
	padding: 10px 15px;
	border-radius: 3px;
	background-color: <?php echo esc_attr( $base ); ?>;
}