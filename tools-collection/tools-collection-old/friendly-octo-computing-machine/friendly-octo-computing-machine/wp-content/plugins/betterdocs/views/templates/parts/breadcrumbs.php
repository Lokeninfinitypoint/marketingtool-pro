<?php

if ( ! betterdocs()->settings->get( 'enable_breadcrumb' ) ) {
	return;
}

$view_object->get( 'widgets/breadcrumbs' );
