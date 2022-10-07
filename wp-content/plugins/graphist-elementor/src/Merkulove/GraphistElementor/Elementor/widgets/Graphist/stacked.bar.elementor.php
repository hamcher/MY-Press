<?php /** @noinspection PhpUndefinedClassInspection */
/**
 * Graphist for Elementor
 * Creates modern and stylish Elementor widgets to display awesome charts and graphs.
 * Exclusively on https://1.envato.market/graphist-elementor
 *
 * @encoding        UTF-8
 * @version         1.2.1
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

namespace Merkulove\GraphistElementor;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

use Exception;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Merkulove\GraphistElementor\Unity\Plugin as UnityPlugin;

/** @noinspection PhpUnused */
/**
 * Line Chart - Custom Elementor Widget
 * @method start_controls_section( string $string, array $array )
 **/
class stacked_bar_elementor extends Widget_Base {

    /**
     * Use this to sort widgets.
     * A smaller value means earlier initialization of the widget.
     * Can take negative values.
     * Default widgets and widgets from 3rd party developers have 0 $mdp_order
     **/
    public $mdp_order = 1;

    /**
     * Widget base constructor.
     * Initializing the widget base class.
     *
     * @access public
     * @throws Exception If arguments are missing when initializing a full widget instance.
     * @param array      $data Widget data. Default is an empty array.
     * @param array|null $args Optional. Widget default arguments. Default is null.
     *
     * @return void
     **/
    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );

        /** Register styles */
        wp_register_style(
        'mdp-graphist-elementor-admin',
            UnityPlugin::get_url() . 'css/elementor-admin' . UnityPlugin::get_suffix() . '.css',
                [], UnityPlugin::get_version()
        );

        wp_register_style(
        'mdp-graphist-chartist',
            UnityPlugin::get_url() . 'css/chartist' . UnityPlugin::get_suffix() . '.css',
                [],
                UnityPlugin::get_version()
        );
        wp_register_style(
        'mdp-graphist',
            UnityPlugin::get_url() . 'css/graphist' . UnityPlugin::get_suffix() . '.css',
                [],
                UnityPlugin::get_version()
        );

        /** Register widget scripts */
        wp_register_script(
        'mdp-graphist-chartist',
            UnityPlugin::get_url() . 'js/chartist' . UnityPlugin::get_suffix() . '.js',
                [],
                UnityPlugin::get_version(),
        false
        );

        wp_register_script(
            'mdp-graphist-stacked-bar',
            UnityPlugin::get_url() . 'js/stacked.bar' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            false
        );

        wp_register_script(
            'mdp-graphist-stacked-bar-popup',
            UnityPlugin::get_url() . 'js/stacked.bar.popup' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            false
        );

        wp_register_script(
        'mdp-graphist',
            UnityPlugin::get_url() . 'js/graphist' . UnityPlugin::get_suffix() . '.js',
                [],
                UnityPlugin::get_version(),
        false
        );

    }

    /**
     * Return a widget name.
     *
     * @return string
     **/
    public function get_name() {
        return 'mdp-graphist-stacked-bar';
    }

    /**
     * Return the widget title that will be displayed as the widget label.
     *
     * @return string
     **/
    public function get_title() {
        return esc_html__( 'Stacked Bar Chart', 'graphist-elementor' );
    }

    /**
     * Set the widget icon.
     *
     * @return string
     */
    public function get_icon() {
        return 'mdp-stacked-bar-elementor-widget-icon';
    }

    /**
     * Set the category of the widget.
     *
     * @return array with category names
     **/
    public function get_categories() {
        return [ 'graphist-category' ];
    }

    /**
     * Get widget keywords. Retrieve the list of keywords the widget belongs to.
     *
     * @access public
     *
     * @return array Widget keywords.
     **/
    public function get_keywords() {
        return [ 'Merkulove', 'chart', 'stacked', 'bar', 'graphist', 'chartist' ];
    }

    /**
     * Get style dependencies.
     * Retrieve the list of style dependencies the widget requires.
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     **/
    public function get_style_depends() {
        return [ 'mdp-graphist', 'mdp-graphist-chartist', 'mdp-graphist-elementor-admin' ];
    }

	/**
	 * Get script dependencies.
	 * Retrieve the list of script dependencies the element requires.
	 *
	 * @access public
     *
	 * @return array Element scripts dependencies.
	 **/
	public function get_script_depends() {
		return [
		    'mdp-graphist',
            'mdp-graphist-stacked-bar',
            'mdp-graphist-stacked-bar-popup',
            'mdp-graphist-chartist'
        ];
    }

    /**
     * Default dynamic data for Stacked Bar Chart.
     *
     * @return false|string
     */
    function stacked_bar_chart_shortcode(){

        $mas= [
            'labels'  => ['Jan', 'Feb', 'Mar', 'Apr', 'HZ'],
            'color'  => ['red', 'black', 'blue', 'gray', 'white'],
            'bar_width' => 30,
            'series' => [
                [12, 9, 7, 8, 5],
                [2, 1, 3.5, 7, 3],
                [1, 3, 4, 5, 6],
                [3, 5, 6, 7, 8]
            ]
        ];

        return json_encode($mas);

    }

    /**
     * Add the widget controls.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return void with category names
     **/
    protected function _register_controls() {

        /** Content tab. */
        $this->start_controls_section(
                'section_content',
                       [ 'label' => esc_html__( 'Content', 'graphist-elementor' ) ]
        );

        /** Displayed in a pop-up window */
        $this->add_control(
            'show_popup',
            [
                'label' => esc_html__( 'Displayed in a pop-up window', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Title display condition. */
        $this->add_control(
            'show_title',
            [
                'label' => esc_html__( 'Show title', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        /** Title text. */
        $this->add_control(
            'chart_title',
            [
                'label'                 => esc_html__( 'Title', 'graphist-elementor' ),
                'label_block'           => true,
                'type'                  => Controls_Manager::TEXT,
                'dynamic'               => ['active' => true],
                'placeholder'           => esc_html__( 'Stacked Bar Chart', 'graphist-elementor' ),
                'default'               => esc_html__( 'Stacked Bar Chart', 'graphist-elementor' ),
                'condition'             => ['show_title' => 'yes']
            ]
        );

        /** Description display condition. */
        $this->add_control(
            'show_description',
            [
                'label' => esc_html__( 'Show description', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        /** Description text. */
        $this->add_control(
            'chart_description',
            [
                'label'                 => esc_html__( 'Description', 'graphist-elementor' ),
                'label_block'           => true,
                'type'                  => Controls_Manager::TEXTAREA,
                'dynamic'               => ['active' => true],
                'placeholder'           => esc_html__( 'You can also set your bar chart to stack the series bars on top of each other easily.', 'graphist-elementor' ),
                'default'               => esc_html__( 'You can also set your bar chart to stack the series bars on top of each other easily.', 'graphist-elementor' ),
                'condition'             => ['show_description' => 'yes']
            ]
        );

        /** Show description after title. */
        $this->add_control(
            'after_title',
            [
                'label' => esc_html__( 'Show description after title', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'graphist-elementor' ),
                'label_off' => esc_html__( 'No', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => ['show_description' => 'yes']
            ]
        );

        /** Divider. */
        $this->add_control(
            'value_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        /** Data type. */
        $this->add_control(
            'data_type',
            [
                'label' => esc_html__( 'Data type', 'graphist-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'statistical',
                'options' => [
                    'statistical'  => esc_html__( 'Statistical', 'graphist-elementor' ),
                    'dynamic' => esc_html__( 'Dynamic', 'graphist-elementor' ),
                ],
            ]
        );

        /** Dynamic Data. */
        $this->add_control(
            'dynamic_data_shortcode',
            [
                'label'                 => esc_html__( 'Dynamic Data', 'graphist-elementor' ),
                'label_block'           => true,
                'type'                  => Controls_Manager::TEXTAREA,
                'dynamic'               => ['active' => true],
                'description'           => esc_html__( 'Default shortcode [stacked_bar_chart]', 'graphist-elementor' ),
                'placeholder'           => esc_html__( 'Enter your shortcode', 'graphist-elementor' ),
                'condition'             => ['data_type' => 'dynamic']
            ]
        );

        /** Number of bars per series. */
        $this->add_control(
            'number_lines',
            [
                'label' => esc_html__( 'Number of bars per series', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 2,
                'condition' => ['data_type' => 'statistical']
            ]
        );

        /** Dynamically add fields. */
        $repeater = new Repeater();

        /** Label text. */
        $repeater->add_control(
            'label_text',
            [
                'label' => esc_html__( 'Label text', 'graphist-elementor' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        /** Chart label list. */
        $this->add_control(
            'graph_label_list',
            [
                'label' => esc_html__( 'Chart label list', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['label_text' => 'Jan'],
                    ['label_text' => 'Feb'],
                    ['label_text' => 'Mar']
                ],
                'title_field' => 'Label text : {{{label_text}}}',
                'condition' => ['data_type' => 'statistical']
            ]
        );

        /** Chart settings heading. */
        $this->add_control(
            'chart_options',
            [
                'label' => esc_html__( 'Chart settings', 'graphist-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /** Show grid X. */
        $this->add_control(
            'show_grid_x',
            [
                'label' => esc_html__( 'Show grid X', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Show label X. */
        $this->add_control(
            'show_label_x',
            [
                'label' => esc_html__( 'Show label X', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Show grid Y. */
        $this->add_control(
            'show_grid_y',
            [
                'label' => esc_html__( 'Show grid Y', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Show label Y. */
        $this->add_control(
            'show_label_y',
            [
                'label' => esc_html__( 'Show label Y', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Bar width. */
        $this->add_control(
            'bar_width',
            [
                'label' => esc_html__( 'Bar width', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'default' => 10,
            ]
        );

        /** Chart conventions. */
        $this->add_control(
            'dots_graph_legend',
            [
                'label' => esc_html__( 'Chart conventions', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        /** Text graph conventions. */
        $this->add_control(
            'legend_title',
            [
                'label' => esc_html__( 'Title graph conventions', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Chart conventions', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Title graph conventions', 'graphist-elementor' ),
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Show animation. */
        $this->add_control(
            'show_animation',
            [
                'label' => esc_html__( 'Show animation', 'graphist-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphist-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphist-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Speed animation. */
        $this->add_control(
            'speed_animation',
            [
                'label' => esc_html__( 'Speed animation', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 500,
                'condition' => ['show_animation' => 'yes']
            ]
        );

        /** End section content. */
        $this->end_controls_section();

        /** Section Item 1. */
        $this->start_controls_section( 'section_item_1', [
            'label' => esc_html__( 'Item 1', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_1',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 1', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 1', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_1',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#3F51B5'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_1 = new Repeater();

        /** Value field. */
        $repeater_item_1->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_1',
            [
                'label' => esc_html__( 'Bar chart value', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_1->get_controls(),
                'default' => [
                    ['value_chart' => 100],
                    ['value_chart' => 150],
                    ['value_chart' => 200],
                ],
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_1',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 1. */
        $this->end_controls_section();

        /** Section Item 2. */
        $this->start_controls_section( 'section_item_2', [
            'label' => esc_html__( 'Item 2', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(2, 3, 4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_2',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 2', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 2', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_2',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#FFEB3B'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_2 = new Repeater();

        /** Value field. */
        $repeater_item_2->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_2',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_2->get_controls(),
                'default' => [
                    ['value_chart' => 100],
                    ['value_chart' => 200],
                    ['value_chart' => 300],
                ],
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_2',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 2. */
        $this->end_controls_section();

        /** Section Item 3. */
        $this->start_controls_section( 'section_item_3', [
            'label' => esc_html__( 'Item 3', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(3, 4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_3',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 3', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 3', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_3',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#607D8B'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_3 = new Repeater();

        /** Value field. */
        $repeater_item_3->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_3',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_3->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_3',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 3. */
        $this->end_controls_section();

        /** Section Item 4. */
        $this->start_controls_section( 'section_item_4', [
            'label' => esc_html__( 'Item 4', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_4',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 4', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 4', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_4',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#673AB7'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_4 = new Repeater();

        /** Value field. */
        $repeater_item_4->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_4',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_4->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_4',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 4. */
        $this->end_controls_section();

        /** Section Item 5. */
        $this->start_controls_section( 'section_item_5', [
            'label' => esc_html__( 'Item 5', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_5',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 5', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 5', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_5',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#9E9E9E'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_5 = new Repeater();

        /** Value field. */
        $repeater_item_5->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_5',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_3->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_5',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 5. */
        $this->end_controls_section();

        /** Section Item 6. */
        $this->start_controls_section( 'section_item_6', [
            'label' => esc_html__( 'Item 6', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_6',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 6', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 6', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_6',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#FF9800'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_6 = new Repeater();

        /** Value field. */
        $repeater_item_6->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_6',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_3->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_6',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 6. */
        $this->end_controls_section();

        /** Section Item 7. */
        $this->start_controls_section( 'section_item_7', [
            'label' => esc_html__( 'Item 7', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_7',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 7', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 7', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_7',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#F44336'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_7 = new Repeater();

        /** Value field. */
        $repeater_item_7->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_7',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_7->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_7',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 7. */
        $this->end_controls_section();

        /** Section Item 8. */
        $this->start_controls_section( 'section_item_8', [
            'label' => esc_html__( 'Item 8', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_8',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 8', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 8', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_8',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#795548'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_8 = new Repeater();

        /** Value field. */
        $repeater_item_8->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_8',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_8->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_8',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 8. */
        $this->end_controls_section();

        /** Section Item 9. */
        $this->start_controls_section( 'section_item_9', [
            'label' => esc_html__( 'Item 9', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_9',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 9', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 9', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_9',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#03A9F4'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_9 = new Repeater();

        /** Value field. */
        $repeater_item_9->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_9',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_9->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_9',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 9. */
        $this->end_controls_section();

        /** Section Item 10. */
        $this->start_controls_section( 'section_item_10', [
            'label' => esc_html__( 'Item 10', 'graphist-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => 10
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_10',
            [
                'label' => esc_html__( 'Item name', 'graphist-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 10', 'graphist-elementor' ),
                'placeholder' => esc_html__( 'Item name 10', 'graphist-elementor' ),
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_10',
            [
                'label' => esc_html__( 'Line color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#4CAF50'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_10 = new Repeater();

        /** Value field. */
        $repeater_item_10->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphist-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 100,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_10',
            [
                'label' => esc_html__( 'Points on the chart', 'graphist-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_10->get_controls(),
                'title_field' => '{{{value_chart}}}',
            ]
        );

        /** Helper text. */
        $this->add_control(
            'info_text_10',
            [
                'label' => esc_html__( 'Helper text', 'graphist-elementor' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'The number of bars in the chart must match.', 'graphist-elementor' ),
            ]
        );

        /** End section Item 10. */
        $this->end_controls_section();

        /** Style tab. */
        $this->start_controls_section(
                'style_section',
                       [ 'label' => esc_html__( 'Style Section', 'plugin-name' ),
                         'tab' => Controls_Manager::TAB_STYLE, ]
        );

        /** Header settings. */
        $this->add_control(
            'title_options',
            [
                'label' => esc_html__( 'Header', 'graphist-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'graphist-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-title',
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header shadow. */
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'label' => esc_html__( 'Shadow', 'graphist-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-shadow-title',
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header color. */
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#3d3d3d',
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header alignment. */
        $this->add_responsive_control(
            'title_text_align',
            [
                'label' => esc_html__( 'Alignment', 'graphist-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-header-align' => 'text-align: {{title_text_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Indentation for the header. */
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'graphist-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-header-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['show_title' => 'yes']
            ]
        );

        $this->add_control(
            'divider_title',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Description settings. */
        $this->add_control(
            'description_options',
            [
                'label' => esc_html__( 'Description', 'graphist-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Description typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'graphist-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-description',
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Description text color. */
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#595959',
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Description text alignment. */
        $this->add_responsive_control(
            'description_text_align',
            [
                'label' => esc_html__( 'Alignment', 'graphist-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justify', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'Justify',
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-description-align' => 'text-align: {{description_text_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Indentation for the description. */
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'graphist-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-description-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition'  => ['show_description' => 'yes']
            ]
        );

        $this->add_control(
            'divider_description',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Chart legend header settings. */
        $this->add_control(
            'graph_title_options',
            [
                'label' => esc_html__( 'Conventions', 'graphist-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'legend_typography',
                'label' => esc_html__( 'Typography', 'graphist-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-legend',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend shadow. */
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'legend_shadow',
                'label' => esc_html__( 'Shadow', 'graphist-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-shadow-legend',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart conventions text color. */
        $this->add_control(
            'conventions_title_color',
            [
                'label' => esc_html__( 'Color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#595959',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Description text alignment. */
        $this->add_responsive_control(
            'conventions_title_align',
            [
                'label' => esc_html__( 'Alignment', 'graphist-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-conventions-align' => 'text-align: {{conventions_title_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Indentation for the graph conventions. */
        $this->add_responsive_control(
            'conventions_title_margin',
            [
                'label' => esc_html__( 'Margin', 'graphist-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-conventions-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        $this->add_control(
            'divider_conventions',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend list style. */
        $this->add_control(
            'graph_list_options',
            [
                'label' => esc_html__( 'List conventions', 'graphist-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend list typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'graph_list__typography',
                'label' => esc_html__( 'Typography', 'graphist-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-list',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Legend list color. */
        $this->add_control(
            'conventions_list_color',
            [
                'label' => esc_html__( 'Color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#595959',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** List style alignment. */
        $this->add_responsive_control(
            'conventions_style_align',
            [
                'label' => esc_html__( 'Alignment', 'graphist-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphist-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-conventions-list-align' => 'text-align: {{conventions_style_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** List style margin. */
        $this->add_responsive_control(
            'conventions_list_margin',
            [
                'label' => esc_html__( 'Margin', 'graphist-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-stacked-conventions-list-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        $this->add_control(
            'divider_legend',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend list style. */
        $this->add_control(
            'graph_labels_heading',
            [
                'label' => esc_html__( 'Labels', 'graphist-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        /** Chart legend list typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'graph_labels_typo',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ct-labels span',
            ]
        );

        /** Legend list color. */
        $this->add_control(
            'graph_labels_color',
            [
                'label' => esc_html__( 'Color', 'graphist-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ct-labels span' => 'color: {{VALUE}}',
                ],
            ]
        );

        /** End section style. */
        $this->end_controls_section();

    }

    /**
     * We create a multidimensional array which stores all the lines of the chart.
     *
     * @param $settings
     *
     * @return array
     */
    public function get_arrayLines( $settings ) {

        /** The number of lines that will need to be plotted. */
        $number_lines = $settings['number_lines'];

        /** In the array we save points of one line. */
        $line = array();

        /** The array stores all the lines that need to be plotted. */
        $lines = array();

        for ( $i = 1; $i <= $number_lines; $i++ ) {

            /** Create an array with points on which the graph line is built. */
            foreach ( $settings["value_list_item_$i"] as $val ) { $line[] = $val['value_chart']; }

            /** Save a new line to the array. */
            $lines[] = $line;

            /** After each cycle, we clear the array. */
            unset($line);
        }

        return $lines;

    }

    /**
     * We return the list of signatures.
     *
     * @param $settings
     *
     * @return array
     */
    public function get_arrayLabels( $settings ) {

        /** We get an array with graph data. */
        $valueList = $settings['graph_label_list'];

        /** Create an array to store the results. */
        $res = array();

        /** We write the labels of the graph into the array. */
        foreach ($valueList as $val){ $res[] = $val['label_text']; }

        return $res;

    }

    /**
     * Chart Fill Color.
     *
     * @param $settings - dynamic data transmitted by shortcode.
     * @param $globalClass - A unique class for the task of element styles.
     *
     * @return string
     */
    public function get_barColor( $settings, $globalClass ) {

        /** The number of lines that will need to be plotted. */
        $number_lines = $settings['number_lines'];

        /** We create an array in which we will store the alphabet. */
        $Alphabet = array();

        /** We create a string variable where we will store styles. */
        $res = '';

        /** We generate the alphabet and save it in an array. */
        for ( $i = ord('a'), $iMax = ord( 'z' ); $i <= $iMax; $i++ ){ $Alphabet[] = chr( $i ); }

        /** We generate styles for charts class and save them in a string. */
        $c = -1;
        for ( $i = 1; $i <= $number_lines; $i++ ) {
            $c++;
            $res .= sprintf(
         '.%s > svg > g > .ct-series-%s > .ct-bar'.
                '{ stroke: %s !important; stroke-width: %spx !important; }',
                esc_attr($globalClass),
                esc_attr($Alphabet[$c]),
                esc_attr($settings["line_color_$i"]),
                esc_attr($settings['bar_width'])
            );
        }

        return $res;

    }

    /**
     *  Set the color for the bars. Dynamic data.
     *
     * @param $settings - dynamic data transmitted by shortcode.
     * @param $globalClass - A unique class for the task of element styles.
     *
     * @return string
     */
    public function get_dynamic_barColor( $settings, $globalClass ) {

        $number_lines = 0;

        /** The number of lines that will need to be plotted. */
        foreach ($settings as $key => $val){ if( $key === 'series'){ $number_lines = count ($val); } }

        /** We create an array in which we will store the alphabet. */
        $Alphabet = array();

        /** We create a string variable where we will store styles. */
        $res = '';

        /** We generate the alphabet and save it in an array. */
        for ( $i = ord('a'), $iMax = ord( 'z' ); $i <= $iMax; $i++ ){ $Alphabet[] = chr( $i ); }

        /** We generate styles for charts class and save them in a string. */
        $c = -1;
        for ( $i = 1; $i <= $number_lines; $i++ ) {
            $c++;
            $res .= sprintf(
            '.%s > svg > g > .ct-series-%s > .ct-bar'.
                    '{ stroke: %s !important; stroke-width: %spx !important; }',
                    esc_attr($globalClass),
                    esc_attr($Alphabet[$c]),
                    esc_attr($settings["color"][$i]),
                    esc_attr($settings['bar_width'])
            );
        }

        return $res;

    }

    /**
     * Display labels for the data transferred dynamically.
     *
     * @param $settings - dynamic data transmitted by shortcode.
     * @param $elSettings - Widget settings.
     *
     * @return string
     */
    public function get_dynamic_graphConventions( $settings, $elSettings) {

        $number_lines = 0;

        /** The number of lines that will need to be plotted. */
        foreach ($settings as $key => $val){
            if( $key === 'series'){ $number_lines = count ($val); }
        }

        /** The array stores all the lines that need to be plotted. */
        $lines_html = '<ul class="mdp-typography-list mdp-stacked-conventions-list-align '.
                      'mdp-stacked-conventions-list-margin" style="color: %s;">';
        $lines = sprintf( $lines_html, esc_attr($elSettings['conventions_list_color']) );

        for ( $i = 0; $i <= $number_lines; $i++ ) {
            $lines .= sprintf(
            '<li><span class="mdp-item-color" style="background-color: %s;"></span>%s</li>',
                    esc_attr($settings["color"][$i]),
                    esc_html($settings["labels"][$i])
            );
        }

        $lines .= '</ul>';

        return $lines;

    }

    /**
     * @param $settings - Static data for the widget.
     *
     * @return string
     */
    public function get_graphConventions( $settings ) {

        /** The number of lines that will need to be plotted. */
        $number_lines = $settings['number_lines'];

        /** The array stores all the lines that need to be plotted. */
        $lines_html = '<ul class="mdp-typography-list mdp-stacked-conventions-list-align '.
                      'mdp-stacked-conventions-list-margin" style="color: %s;">';
        $lines = sprintf( $lines_html, esc_attr($settings['conventions_list_color']) );

        for ( $i = 1; $i <= $number_lines; $i++ ) {
            $lines .= sprintf(
            '<li><span class="mdp-item-color" style="background-color: %s;"></span>%s</li>',
                    esc_attr($settings["line_color_$i"]),
                    esc_html($settings["item_name_$i"])
            );
        }

        $lines .= '</ul>';

        return $lines;

    }

    /**
     *  Returns an array with dynamic data for building lines.
     *
     * @param $dynamic - dynamic data transmitted by shortcode.
     *
     * @return array
     */
    public function get_items_dynamic( $dynamic ) {

        $Items = [];

        if( is_array($dynamic) ){
            foreach( $dynamic as $key => $val ) { if ( $key === 'series' ) { $Items = $val; } }
        }

        return $Items;

    }

    /**
     *  We return an array with signatures for points on the chart.
     *
     * @param $dynamic - dynamic data transmitted by shortcode.
     *
     * @return array
     */
    public function get_labels_dynamic( $dynamic ) {

        $Labels = [];

        if( is_array($dynamic) ){
            foreach($dynamic as $key => $val ) { if ( $key === 'labels' ) { $Labels = $val; } }
        }

        return $Labels;

    }

    /**
     * @param $settings
     */
    public function get_app_title( $settings ){
        if( $settings['show_title'] === 'yes' ){
            $title_html = '<h3 class="mdp-typography-title mdp-shadow-title mdp-stacked-header-align '.
                          'mdp-stacked-header-margin" style="color: %s;">%s</h3>';
            echo sprintf( $title_html, esc_attr($settings['title_color']), esc_html($settings['chart_title']) );
        }
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_app_description( $settings ){
        $description_html = '<p class="mdp-typography-description mdp-stacked-description-align '.
                            'mdp-stacked-description-margin" style="color: %s;">%s</p>';
        return sprintf(
            $description_html,
            esc_attr($settings['description_color']),
            esc_html($settings['chart_description']) );
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_app_title_legend( $settings ){
        $title_legend_html = '<h4 class="mdp-typography-legend mdp-shadow-legend mdp-stacked-conventions-align '.
                             'mdp-stacked-conventions-margin" style="color: %s;">%s</h4>';
        return sprintf(
            $title_legend_html,
            esc_attr($settings['conventions_title_color']),
            esc_html($settings['legend_title']) );
    }

    /**
     * @param $settings
     * @param $dynamic
     */
    public function get_app_conventions( $settings, $dynamic ){
        if( $settings['dots_graph_legend'] === 'yes' ){

            echo sprintf(
                '<div class="mdp-graph-conventions">%s %s</div>',
                $this->get_app_title_legend( $settings ),
                wp_kses_post( $this->get_graphConventions( $settings ) )
            );
            if ( $settings["data_type"] === 'dynamic' ) {
                echo sprintf(
                    '<div class="mdp-graph-conventions">%s %s</div>',
                    $this->get_app_title_legend( $settings ),
                    wp_kses_post( $this->get_dynamic_graphConventions( $dynamic, $settings ) )
                );
            }

        }
    }

    /**
     * Render Frontend Output. Generate the final HTML on the frontend.
     *
     * @since 1.0.0
     * @access protected
     **/
    protected function render() {

        /** We get all the values from the admin panel. */
        $settings = $this->get_settings_for_display();

        /** Dynamic data transmitted by shortcode in json format. */
        $dynamic_json = $settings["dynamic_data_shortcode"];

        /** Convert json data to an array. */
        $dynamic = json_decode( $dynamic_json, true );

        /** We will smell an array of graph values. */
        $Items = $this->get_arrayLines( $settings );
        $Labels = $this->get_arrayLabels( $settings );
        if( $settings["data_type"] === 'dynamic' ) {
            $Items = $this->get_items_dynamic( $dynamic );
            $Labels = $this->get_labels_dynamic( $dynamic );
        }

        /** We write the section style class to a variable. */
        $globalClass = sprintf( 'mdp-%s', $this->get_id() );

        /** We translate the data from the array with a list of values into a string. */
        $point = '';
        foreach ($Items as $Item){
            if ( $Item !== null ) {
                $point .= '[';
                    foreach ( $Item as $val ) { $point .= $val . ', '; }
                $point .= '], ';
            }
        }

        /** Convert the array of chart labels to a string. */
        $separatedLabels = implode(", ", $Labels );

        echo sprintf('<style>%s</style>', $this->get_barColor( $settings, $globalClass ) );
        if( $settings["data_type"] === 'dynamic' ){
            echo sprintf('<style>%s</style>', $this->get_dynamic_barColor( $dynamic, $globalClass ) );
        }

        $this->get_app_title( $settings );

        if( $settings['show_description'] === 'yes' &&
            $settings['after_title'] === 'yes' ){ echo $this->get_app_description( $settings ); }

        echo sprintf(
            '<div id="%s" class="ct-golden-section mdp-%s"></div>',
                    esc_attr($globalClass),
                    esc_attr($this->get_id()) );

        $this->get_app_conventions( $settings, $dynamic );

        if( $settings['show_description'] === 'yes' &&
            $settings['after_title'] === '' ){ echo $this->get_app_description( $settings ); }

        $script_default = "<script>ready( () => { ".
            "window.StackedBar( '%s', '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', %s ); ".
            "});</script>";

        $script_popup = "<script>ready( () => { ".
            "window.StackedBarPopup( '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', %s ); ".
            "});</script>";

        if( !is_admin() ) {
            $script_default = "<script>window.addEventListener( 'DOMContentLoaded', (event) => { ready( () => { ".
                "window.StackedBar( '%s', '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', %s ); ".
                "}); });</script>";

            $script_popup = "<script>window.addEventListener( 'DOMContentLoaded', (event) => { ready( () => { ".
                "window.StackedBarPopup( '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', %s ); ".
                "}); });</script>";
        }

        echo sprintf(
            $script_default,
            esc_attr($this->get_id()),
            esc_attr($separatedLabels),
            esc_attr($globalClass),
            esc_attr($point),
            esc_attr($settings['show_grid_x']),
            esc_attr($settings['show_label_x']),
            esc_attr($settings['show_grid_y']),
            esc_attr($settings['show_label_y']),
            esc_attr($settings['show_animation']),
            esc_attr($settings['speed_animation'])
        );

        if( $settings['show_popup'] === 'yes' ){

            echo sprintf(
                $script_popup,
                esc_attr($separatedLabels),
                esc_attr($globalClass),
                esc_attr($point),
                esc_attr($settings['show_grid_x']),
                esc_attr($settings['show_label_x']),
                esc_attr($settings['show_grid_y']),
                esc_attr($settings['show_label_y']),
                esc_attr($settings['show_animation']),
                esc_attr($settings['speed_animation'])
            );

        }

    }

    /**
     * Return link for documentation
     * Used to add stuff after widget
     *
     * @access public
     *
     * @return string
     **/
    public function get_custom_help_url() {
        return 'https://docs.merkulov.design/category/graphist';
    }

}
