<?php
/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */

class Software_Agency_Control_Typography extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'software-agency-typography';

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
				'color'       => esc_html__( 'Font Color', 'software-agency' ),
				'family'      => esc_html__( 'Font Family', 'software-agency' ),
				'size'        => esc_html__( 'Font Size',   'software-agency' ),
				'weight'      => esc_html__( 'Font Weight', 'software-agency' ),
				'style'       => esc_html__( 'Font Style',  'software-agency' ),
				'line_height' => esc_html__( 'Line Height', 'software-agency' ),
				'letter_spacing' => esc_html__( 'Letter Spacing', 'software-agency' ),
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
		wp_enqueue_script( 'software-agency-ctypo-customize-controls' );
		wp_enqueue_style(  'software-agency-ctypo-customize-controls' );
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
			'' => __( 'No Fonts', 'software-agency' ),
        'Abril Fatface' => __( 'Abril Fatface', 'software-agency' ),
        'Acme' => __( 'Acme', 'software-agency' ),
        'Anton' => __( 'Anton', 'software-agency' ),
        'Architects Daughter' => __( 'Architects Daughter', 'software-agency' ),
        'Arimo' => __( 'Arimo', 'software-agency' ),
        'Arsenal' => __( 'Arsenal', 'software-agency' ),
        'Arvo' => __( 'Arvo', 'software-agency' ),
        'Alegreya' => __( 'Alegreya', 'software-agency' ),
        'Alfa Slab One' => __( 'Alfa Slab One', 'software-agency' ),
        'Averia Serif Libre' => __( 'Averia Serif Libre', 'software-agency' ),
        'Bangers' => __( 'Bangers', 'software-agency' ),
        'Boogaloo' => __( 'Boogaloo', 'software-agency' ),
        'Bad Script' => __( 'Bad Script', 'software-agency' ),
        'Bitter' => __( 'Bitter', 'software-agency' ),
        'Bree Serif' => __( 'Bree Serif', 'software-agency' ),
        'BenchNine' => __( 'BenchNine', 'software-agency' ),
        'Cabin' => __( 'Cabin', 'software-agency' ),
        'Cardo' => __( 'Cardo', 'software-agency' ),
        'Courgette' => __( 'Courgette', 'software-agency' ),
        'Cherry Swash' => __( 'Cherry Swash', 'software-agency' ),
        'Cormorant Garamond' => __( 'Cormorant Garamond', 'software-agency' ),
        'Crimson Text' => __( 'Crimson Text', 'software-agency' ),
        'Cuprum' => __( 'Cuprum', 'software-agency' ),
        'Cookie' => __( 'Cookie', 'software-agency' ),
        'Chewy' => __( 'Chewy', 'software-agency' ),
        'Days One' => __( 'Days One', 'software-agency' ),
        'Dosis' => __( 'Dosis', 'software-agency' ),
        'Droid Sans' => __( 'Droid Sans', 'software-agency' ),
        'Economica' => __( 'Economica', 'software-agency' ),
        'Fredoka One' => __( 'Fredoka One', 'software-agency' ),
        'Fjalla One' => __( 'Fjalla One', 'software-agency' ),
        'Francois One' => __( 'Francois One', 'software-agency' ),
        'Frank Ruhl Libre' => __( 'Frank Ruhl Libre', 'software-agency' ),
        'Gloria Hallelujah' => __( 'Gloria Hallelujah', 'software-agency' ),
        'Great Vibes' => __( 'Great Vibes', 'software-agency' ),
        'Handlee' => __( 'Handlee', 'software-agency' ),
        'Hammersmith One' => __( 'Hammersmith One', 'software-agency' ),
        'Inconsolata' => __( 'Inconsolata', 'software-agency' ),
        'Indie Flower' => __( 'Indie Flower', 'software-agency' ),
        'IM Fell English SC' => __( 'IM Fell English SC', 'software-agency' ),
        'Julius Sans One' => __( 'Julius Sans One', 'software-agency' ),
        'Josefin Slab' => __( 'Josefin Slab', 'software-agency' ),
        'Josefin Sans' => __( 'Josefin Sans', 'software-agency' ),
        'Kanit' => __( 'Kanit', 'software-agency' ),
        'Lobster' => __( 'Lobster', 'software-agency' ),
        'Lato' => __( 'Lato', 'software-agency' ),
        'Lora' => __( 'Lora', 'software-agency' ),
        'Libre Baskerville' => __( 'Libre Baskerville', 'software-agency' ),
        'Lobster Two' => __( 'Lobster Two', 'software-agency' ),
        'Merriweather' => __( 'Merriweather', 'software-agency' ),
        'Monda' => __( 'Monda', 'software-agency' ),
        'Montserrat' => __( 'Montserrat', 'software-agency' ),
        'Muli' => __( 'Muli', 'software-agency' ),
        'Marck Script' => __( 'Marck Script', 'software-agency' ),
        'Noto Serif' => __( 'Noto Serif', 'software-agency' ),
        'Open Sans' => __( 'Open Sans', 'software-agency' ),
        'Overpass' => __( 'Overpass', 'software-agency' ),
        'Overpass Mono' => __( 'Overpass Mono', 'software-agency' ),
        'Oxygen' => __( 'Oxygen', 'software-agency' ),
        'Orbitron' => __( 'Orbitron', 'software-agency' ),
        'Patua One' => __( 'Patua One', 'software-agency' ),
        'Pacifico' => __( 'Pacifico', 'software-agency' ),
        'Padauk' => __( 'Padauk', 'software-agency' ),
        'Playball' => __( 'Playball', 'software-agency' ),
        'Playfair Display' => __( 'Playfair Display', 'software-agency' ),
        'PT Sans' => __( 'PT Sans', 'software-agency' ),
        'Philosopher' => __( 'Philosopher', 'software-agency' ),
        'Permanent Marker' => __( 'Permanent Marker', 'software-agency' ),
        'Poiret One' => __( 'Poiret One', 'software-agency' ),
        'Quicksand' => __( 'Quicksand', 'software-agency' ),
        'Quattrocento Sans' => __( 'Quattrocento Sans', 'software-agency' ),
        'Raleway' => __( 'Raleway', 'software-agency' ),
        'Rubik' => __( 'Rubik', 'software-agency' ),
        'Rokkitt' => __( 'Rokkitt', 'software-agency' ),
        'Russo One' => __( 'Russo One', 'software-agency' ),
        'Righteous' => __( 'Righteous', 'software-agency' ),
        'Slabo' => __( 'Slabo', 'software-agency' ),
        'Source Sans Pro' => __( 'Source Sans Pro', 'software-agency' ),
        'Shadows Into Light Two' => __( 'Shadows Into Light Two', 'software-agency'),
        'Shadows Into Light' => __( 'Shadows Into Light', 'software-agency' ),
        'Sacramento' => __( 'Sacramento', 'software-agency' ),
        'Shrikhand' => __( 'Shrikhand', 'software-agency' ),
        'Tangerine' => __( 'Tangerine', 'software-agency' ),
        'Ubuntu' => __( 'Ubuntu', 'software-agency' ),
        'VT323' => __( 'VT323', 'software-agency' ),
        'Varela Round' => __( 'Varela Round', 'software-agency' ),
        'Vampiro One' => __( 'Vampiro One', 'software-agency' ),
        'Vollkorn' => __( 'Vollkorn', 'software-agency' ),
        'Volkhov' => __( 'Volkhov', 'software-agency' ),
        'Yanone Kaffeesatz' => __( 'Yanone Kaffeesatz', 'software-agency' )
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
			'' => esc_html__( 'No Fonts weight', 'software-agency' ),
			'100' => esc_html__( 'Thin',       'software-agency' ),
			'300' => esc_html__( 'Light',      'software-agency' ),
			'400' => esc_html__( 'Normal',     'software-agency' ),
			'500' => esc_html__( 'Medium',     'software-agency' ),
			'700' => esc_html__( 'Bold',       'software-agency' ),
			'900' => esc_html__( 'Ultra Bold', 'software-agency' ),
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
			'' => esc_html__( 'No Fonts Style', 'software-agency' ),
			'normal'  => esc_html__( 'Normal', 'software-agency' ),
			'italic'  => esc_html__( 'Italic', 'software-agency' ),
			'oblique' => esc_html__( 'Oblique', 'software-agency' )
		);
	}
}
