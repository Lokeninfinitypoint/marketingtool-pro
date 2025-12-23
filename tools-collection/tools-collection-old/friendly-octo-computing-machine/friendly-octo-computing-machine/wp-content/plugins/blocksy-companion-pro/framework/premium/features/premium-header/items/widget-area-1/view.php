<?php

if (! isset($class)) {
	$class = 'widget-area-1';
}

if (! isset($sidebar)) {
	$sidebar = 'ct-header-sidebar-1';
}

echo '<div data-id="widget-area-1">';
dynamic_sidebar($sidebar);
echo '</div>';

