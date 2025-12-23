<?php

namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.1.0 
 */
class Iteck_Best_Seller extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'iteck-best-seller';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Iteck Best Seller', 'iteck_plg');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['iteck-menu-elements'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Best Seller Settings', 'iteck_plg'),
            ]
        );

        $this->add_control(
			'name',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Name', 'iteck_plg' ),
				'default' => esc_html__( 'Mark', 'iteck_plg' ),
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Link','iteck_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'wwww.link.com',
			]
		); 

        $this->add_control(
			'text',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Text', 'iteck_plg' ),
				'default' => esc_html__( 'Rise:', 'iteck_plg' ),
			]
		);
		
		$this->add_control(
			'amount',
			[
				'label' => __( 'Amount','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '12,000,00', 'iteck_plg' ),
			]
		); 

        $this->add_control(
			'currency_symbol',
			[
				'label' => __( 'Currency Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'iteck_plg' ),
					'&#36;' => '&#36; ' . _x( 'Dollar', 'iteck_plg' ),
					'&#128;' => '&#128; ' . _x( 'Euro', 'iteck_plg' ),
					'&#3647;' => '&#3647; ' . _x( 'Baht', 'iteck_plg' ),
					'&#8355;' => '&#8355; ' . _x( 'Franc', 'iteck_plg' ),
					'&fnof;' => '&fnof; ' . _x( 'Guilder', 'iteck_plg' ),
					'kr' => 'kr ' . _x( 'Krona', 'iteck_plg' ),
					'&#8356;' => '&#8356; ' . _x( 'Lira', 'iteck_plg' ),
					'&#8359' => '&#8359 ' . _x( 'Peseta', 'iteck_plg' ),
					'&#8369;' => '&#8369; ' . _x( 'Peso', 'iteck_plg' ),
					'&#163;' => '&#163; ' . _x( 'Pound Sterling', 'iteck_plg' ),
					'R$' => 'R$ ' . _x( 'Real', 'iteck_plg' ),
					'&#8381;' => '&#8381; ' . _x( 'Ruble', 'iteck_plg' ),
					'&#8360;' => '&#8360; ' . _x( 'Rupee', 'iteck_plg' ),
					'&#8377;' => '&#8377; ' . _x( 'Rupee (Indian)', 'iteck_plg' ),
					'&#8362;' => '&#8362; ' . _x( 'Shekel', 'iteck_plg' ),
					'&#165;' => '&#165; ' . _x( 'Yen/Yuan', 'iteck_plg' ),
					'&#8361;' => '&#8361; ' . _x( 'Won', 'iteck_plg' ),
					'custom' => __( 'Custom', 'iteck_plg' ),
				],
				'default' => '&#36;',
			]
		);

        $this->add_control(
			'currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Seller Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings();

        if($settings['currency_symbol'] != 'custom'):

            $symbol =  $settings['currency_symbol'];

        else:

            $symbol =  $settings['currency_symbol_custom'];

        endif;

?>

        <div class="iteck-best-seller">
            <a href="<?php echo esc_url($settings['link']['url']) ?>" class="seller-card" <?php if ( $settings['link']['is_external'] ) {echo'target="_blank"';} ?>>
                <div class="img me-3">
                    <img src="<?php echo esc_url($settings['image']['url']) ?>" alt="">
                </div>
                <div class="info">
                    <h5><?php echo wp_kses_post($settings['name']) ?></h5>
                    <p><?php echo wp_kses_post($settings['text']) ?><span class="ms-1"> <?php echo wp_kses_post($symbol); ?> </span> <span class="counter"><?php echo wp_kses_post($settings['amount']) ?></span> </p>
                </div>
            </a>
        </div>

<?php }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function content_template()
    {
    }
}
