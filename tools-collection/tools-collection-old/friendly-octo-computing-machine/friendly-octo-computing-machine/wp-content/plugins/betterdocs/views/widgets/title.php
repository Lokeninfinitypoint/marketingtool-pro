<?php

$tag = empty( $tag ) ? 'h2' : $tag;
printf( '<%1$s id="betterdocs-entry-title" class="betterdocs-entry-title" %2$s>%3$s</%1$s>', strtolower( $tag ), $wrapper_attr, wp_kses_post( $title ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
