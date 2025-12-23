<li class="nav-item<?php print( $tabNavCounter == 0 ? ' tab-active' : '' ); ?>">
	<span class="text"><?php print( isset( $section['title'] ) ? esc_html( $section['title'] ) : '' ); ?></span>
	<span class="number"><?php print( isset( $section['sub_title'] ) ? esc_html( $section['sub_title'] ) : '' ); ?></span>
</li>
