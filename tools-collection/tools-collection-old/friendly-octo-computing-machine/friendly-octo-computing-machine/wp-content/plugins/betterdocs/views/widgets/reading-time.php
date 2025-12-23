<?php
$reading_text          = $attributes['ert_reading_text'];
$singular_reading_text = $attributes['singular_ert_reading_text'];
$reading_title         = $attributes['ert_reading_title'];
echo do_shortcode( '[betterdocs_reading_time singular_reading_text="' . $singular_reading_text . '" reading_text="' . $reading_text . '" reading_title="' . $reading_title . '"]' );
