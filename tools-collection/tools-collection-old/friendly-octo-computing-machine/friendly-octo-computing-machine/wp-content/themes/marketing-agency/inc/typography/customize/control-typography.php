<?php
/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */

class Marketing_Agency_Control_Typography extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'marketing-agency-typography';

	/**
	 * Array 
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $l10n = array();

	/**
	 * Set up our control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $id
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {

		// Let the parent class do its thing.
		parent::__construct( $manager, $id, $args );

		// Make sure we have labels.
		$this->l10n = wp_parse_args(
			$this->l10n,
			array(
				'color'       => esc_html__( 'Font Color', 'marketing-agency' ),
				'family'      => esc_html__( 'Font Family', 'marketing-agency' ),
				'size'        => esc_html__( 'Font Size',   'marketing-agency' ),
				'weight'      => esc_html__( 'Font Weight', 'marketing-agency' ),
				'style'       => esc_html__( 'Font Style',  'marketing-agency' ),
				'line_height' => esc_html__( 'Line Height', 'marketing-agency' ),
				'letter_spacing' => esc_html__( 'Letter Spacing', 'marketing-agency' ),
			)
		);
	}

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'marketing-agency-ctypo-customize-controls' );
		wp_enqueue_style(  'marketing-agency-ctypo-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// Loop through each of the settings and set up the data for it.
		foreach ( $this->settings as $setting_key => $setting_id ) {

			$this->json[ $setting_key ] = array(
				'link'  => $this->get_link( $setting_key ),
				'value' => $this->value( $setting_key ),
				'label' => isset( $this->l10n[ $setting_key ] ) ? $this->l10n[ $setting_key ] : ''
			);

			if ( 'family' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_families();

			elseif ( 'weight' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_weight_choices();

			elseif ( 'style' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_style_choices();
		}
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template() { ?>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<ul>

		<# if ( data.family && data.family.choices ) { #>

			<li class="typography-font-family">

				<# if ( data.family.label ) { #>
					<span class="customize-control-title">{{ data.family.label }}</span>
				<# } #>

				<select {{{ data.family.link }}}>

					<# _.each( data.family.choices, function( label, choice ) { #>
						<option value="{{ choice }}" <# if ( choice === data.family.value ) { #> selected="selected" <# } #>>{{ label }}</option>
					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.weight && data.weight.choices ) { #>

			<li class="typography-font-weight">

				<# if ( data.weight.label ) { #>
					<span class="customize-control-title">{{ data.weight.label }}</span>
				<# } #>

				<select {{{ data.weight.link }}}>

					<# _.each( data.weight.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.weight.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.style && data.style.choices ) { #>

			<li class="typography-font-style">

				<# if ( data.style.label ) { #>
					<span class="customize-control-title">{{ data.style.label }}</span>
				<# } #>

				<select {{{ data.style.link }}}>

					<# _.each( data.style.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.style.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.size ) { #>

			<li class="typography-font-size">

				<# if ( data.size.label ) { #>
					<span class="customize-control-title">{{ data.size.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.size.link }}} value="{{ data.size.value }}" />

			</li>
		<# } #>

		<# if ( data.line_height ) { #>

			<li class="typography-line-height">

				<# if ( data.line_height.label ) { #>
					<span class="customize-control-title">{{ data.line_height.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.line_height.link }}} value="{{ data.line_height.value }}" />

			</li>
		<# } #>

		<# if ( data.letter_spacing ) { #>

			<li class="typography-letter-spacing">

				<# if ( data.letter_spacing.label ) { #>
					<span class="customize-control-title">{{ data.letter_spacing.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.letter_spacing.link }}} value="{{ data.letter_spacing.value }}" />

			</li>
		<# } #>

		</ul>
	<?php }

	/**
	 * Returns the available fonts.  Fonts should have available weights, styles, and subsets.
	 *
	 * @todo Integrate with Google fonts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_fonts() { return array(); }

	/**
	 * Returns the available font families.
	 *
	 * @todo Pull families from `get_fonts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	function get_font_families() {

		return array(
			'' => __( 'No Fonts', 'marketing-agency' ),
        'Abril Fatface' => __( 'Abril Fatface', 'marketing-agency' ),
        'Acme' => __( 'Acme', 'marketing-agency' ),
        'Anton' => __( 'Anton', 'marketing-agency' ),
        'Architects Daughter' => __( 'Architects Daughter', 'marketing-agency' ),
        'Arimo' => __( 'Arimo', 'marketing-agency' ),
        'Arsenal' => __( 'Arsenal', 'marketing-agency' ),
        'Arvo' => __( 'Arvo', 'marketing-agency' ),
        'Alegreya' => __( 'Alegreya', 'marketing-agency' ),
        'Alfa Slab One' => __( 'Alfa Slab One', 'marketing-agency' ),
        'Averia Serif Libre' => __( 'Averia Serif Libre', 'marketing-agency' ),
        'Bangers' => __( 'Bangers', 'marketing-agency' ),
        'Boogaloo' => __( 'Boogaloo', 'marketing-agency' ),
        'Bad Script' => __( 'Bad Script', 'marketing-agency' ),
        'Bitter' => __( 'Bitter', 'marketing-agency' ),
        'Bree Serif' => __( 'Bree Serif', 'marketing-agency' ),
        'BenchNine' => __( 'BenchNine', 'marketing-agency' ),
        'Cabin' => __( 'Cabin', 'marketing-agency' ),
        'Cardo' => __( 'Cardo', 'marketing-agency' ),
        'Courgette' => __( 'Courgette', 'marketing-agency' ),
        'Cherry Swash' => __( 'Cherry Swash', 'marketing-agency' ),
        'Cormorant Garamond' => __( 'Cormorant Garamond', 'marketing-agency' ),
        'Crimson Text' => __( 'Crimson Text', 'marketing-agency' ),
        'Cuprum' => __( 'Cuprum', 'marketing-agency' ),
        'Cookie' => __( 'Cookie', 'marketing-agency' ),
        'Chewy' => __( 'Chewy', 'marketing-agency' ),
        'Days One' => __( 'Days One', 'marketing-agency' ),
        'Dosis' => __( 'Dosis', 'marketing-agency' ),
        'Droid Sans' => __( 'Droid Sans', 'marketing-agency' ),
        'Economica' => __( 'Economica', 'marketing-agency' ),
        'Fredoka One' => __( 'Fredoka One', 'marketing-agency' ),
        'Fjalla One' => __( 'Fjalla One', 'marketing-agency' ),
        'Francois One' => __( 'Francois One', 'marketing-agency' ),
        'Frank Ruhl Libre' => __( 'Frank Ruhl Libre', 'marketing-agency' ),
        'Gloria Hallelujah' => __( 'Gloria Hallelujah', 'marketing-agency' ),
        'Great Vibes' => __( 'Great Vibes', 'marketing-agency' ),
        'Handlee' => __( 'Handlee', 'marketing-agency' ),
        'Hammersmith One' => __( 'Hammersmith One', 'marketing-agency' ),
        'Inconsolata' => __( 'Inconsolata', 'marketing-agency' ),
        'Indie Flower' => __( 'Indie Flower', 'marketing-agency' ),
        'IM Fell English SC' => __( 'IM Fell English SC', 'marketing-agency' ),
        'Julius Sans One' => __( 'Julius Sans One', 'marketing-agency' ),
        'Josefin Slab' => __( 'Josefin Slab', 'marketing-agency' ),
        'Josefin Sans' => __( 'Josefin Sans', 'marketing-agency' ),
        'Kanit' => __( 'Kanit', 'marketing-agency' ),
        'Lobster' => __( 'Lobster', 'marketing-agency' ),
        'Lato' => __( 'Lato', 'marketing-agency' ),
        'Lora' => __( 'Lora', 'marketing-agency' ),
        'Libre Baskerville' => __( 'Libre Baskerville', 'marketing-agency' ),
        'Lobster Two' => __( 'Lobster Two', 'marketing-agency' ),
        'Merriweather' => __( 'Merriweather', 'marketing-agency' ),
        'Monda' => __( 'Monda', 'marketing-agency' ),
        'Montserrat' => __( 'Montserrat', 'marketing-agency' ),
        'Muli' => __( 'Muli', 'marketing-agency' ),
        'Marck Script' => __( 'Marck Script', 'marketing-agency' ),
        'Noto Serif' => __( 'Noto Serif', 'marketing-agency' ),
        'Open Sans' => __( 'Open Sans', 'marketing-agency' ),
        'Overpass' => __( 'Overpass', 'marketing-agency' ),
        'Overpass Mono' => __( 'Overpass Mono', 'marketing-agency' ),
        'Oxygen' => __( 'Oxygen', 'marketing-agency' ),
        'Orbitron' => __( 'Orbitron', 'marketing-agency' ),
        'Patua One' => __( 'Patua One', 'marketing-agency' ),
        'Pacifico' => __( 'Pacifico', 'marketing-agency' ),
        'Padauk' => __( 'Padauk', 'marketing-agency' ),
        'Playball' => __( 'Playball', 'marketing-agency' ),
        'Playfair Display' => __( 'Playfair Display', 'marketing-agency' ),
        'PT Sans' => __( 'PT Sans', 'marketing-agency' ),
        'Philosopher' => __( 'Philosopher', 'marketing-agency' ),
        'Permanent Marker' => __( 'Permanent Marker', 'marketing-agency' ),
        'Poiret One' => __( 'Poiret One', 'marketing-agency' ),
        'Quicksand' => __( 'Quicksand', 'marketing-agency' ),
        'Quattrocento Sans' => __( 'Quattrocento Sans', 'marketing-agency' ),
        'Raleway' => __( 'Raleway', 'marketing-agency' ),
        'Rubik' => __( 'Rubik', 'marketing-agency' ),
        'Rokkitt' => __( 'Rokkitt', 'marketing-agency' ),
        'Russo One' => __( 'Russo One', 'marketing-agency' ),
        'Righteous' => __( 'Righteous', 'marketing-agency' ),
        'Slabo' => __( 'Slabo', 'marketing-agency' ),
        'Source Sans Pro' => __( 'Source Sans Pro', 'marketing-agency' ),
        'Shadows Into Light Two' => __( 'Shadows Into Light Two', 'marketing-agency'),
        'Shadows Into Light' => __( 'Shadows Into Light', 'marketing-agency' ),
        'Sacramento' => __( 'Sacramento', 'marketing-agency' ),
        'Shrikhand' => __( 'Shrikhand', 'marketing-agency' ),
        'Tangerine' => __( 'Tangerine', 'marketing-agency' ),
        'Ubuntu' => __( 'Ubuntu', 'marketing-agency' ),
        'VT323' => __( 'VT323', 'marketing-agency' ),
        'Varela Round' => __( 'Varela Round', 'marketing-agency' ),
        'Vampiro One' => __( 'Vampiro One', 'marketing-agency' ),
        'Vollkorn' => __( 'Vollkorn', 'marketing-agency' ),
        'Volkhov' => __( 'Volkhov', 'marketing-agency' ),
        'Yanone Kaffeesatz' => __( 'Yanone Kaffeesatz', 'marketing-agency' )
		);
	}

	/**
	 * Returns the available font weights.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_weight_choices() {

		return array(
			'' => esc_html__( 'No Fonts weight', 'marketing-agency' ),
			'100' => esc_html__( 'Thin',       'marketing-agency' ),
			'300' => esc_html__( 'Light',      'marketing-agency' ),
			'400' => esc_html__( 'Normal',     'marketing-agency' ),
			'500' => esc_html__( 'Medium',     'marketing-agency' ),
			'700' => esc_html__( 'Bold',       'marketing-agency' ),
			'900' => esc_html__( 'Ultra Bold', 'marketing-agency' ),
		);
	}

	/**
	 * Returns the available font styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_style_choices() {

		return array(
			'' => esc_html__( 'No Fonts Style', 'marketing-agency' ),
			'normal'  => esc_html__( 'Normal', 'marketing-agency' ),
			'italic'  => esc_html__( 'Italic', 'marketing-agency' ),
			'oblique' => esc_html__( 'Oblique', 'marketing-agency' )
		);
	}
}
