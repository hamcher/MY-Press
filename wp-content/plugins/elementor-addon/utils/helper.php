<?php

use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography as Scheme_Typography;


function graphina_basic_controls($this_ele, $type = 'chart_id')
{

    $this_ele->start_controls_section(
        'iq_' . $type . '_section_1',
        [
            'label' => esc_html__('Basic Setting', 'graphina-charts-for-elementor')
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_card_show',
        [
            'label' => esc_html__('Card', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-charts-for-elementor'),
            'label_off' => esc_html__('Show', 'graphina-charts-for-elementor'),
            'default' => 'yes',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_card_heading_show',
        [
            'label' => esc_html__('Heading', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-charts-for-elementor'),
            'label_off' => esc_html__('No', 'graphina-charts-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes',
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_heading',
        [
            'label' => esc_html__('Card Heading', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'My Example Heading',
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes',
                'iq_' . $type . '_chart_card_show' => 'yes',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_card_desc_show',
        [
            'label' => esc_html__('Description', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-charts-for-elementor'),
            'label_off' => esc_html__('No', 'graphina-charts-for-elementor'),
            'default' => 'yes',
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes',
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_content',
        [
            'label' => 'Card Description',
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'My Other Example Heading',
            'condition' => [
                'iq_' . $type . '_is_card_desc_show' => 'yes',
                'iq_' . $type . '_chart_card_show' => 'yes',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->end_controls_section();
};

// function graphina_chart_data_enter_options($type = '', $chartType = '', $first = false)
// {
//     $options = [];
//     $type = !empty($type) ? $type : 'base';
//     switch ($type) {
//         case 'base':
//             $options = [
//                 'manual' => esc_html__('Manual', 'graphina-charts-for-elementor'),
//                 'dynamic' => esc_html__('Dynamic', 'graphina-charts-for-elementor')
//             ];

//             if (get_option('graphina_firebase_addons') === '1') {
//                 $options['firebase'] = esc_html__('Firebase', 'graphina-charts-for-elementor');
//             }
//             if(graphinaForminatorAddonActive()){
//                 if(in_array($chartType,['line', 'column', 'area', 'pie', 'donut', 'radial', 'radar', 'polar','data_table_lite','distributed_column','scatter','mixed','brush', 'pie_google','donut_google','line_google','area_google',
//                     'column_google','bar_google','gauge_google','geo_google','org_google'])){
//                     $options['forminator'] = esc_html__('Forminator Addon', 'graphina-charts-for-elementor');
//                 }
//             }
//             break;
//         case 'dynamic':
//             $options = [
//                 'csv' => esc_html__('CSV', 'graphina-charts-for-elementor'),
//                 'remote-csv' => esc_html__('Remote CSV', 'graphina-charts-for-elementor'),
//                 'google-sheet' => esc_html__('Google Sheet', 'graphina-charts-for-elementor'),
//                 'api' => esc_html__('API', 'graphina-charts-for-elementor'),
//             ];
//             $sql_builder_for = ['line', 'column', 'area', 'pie', 'donut', 'radial', 'radar', 'polar','data_table_lite','distributed_column','scatter','mixed','brush', 'pie_google','donut_google','line_google','area_google','column_google','bar_google','gauge_google','geo_google','org_google','gantt_google'];
//             if (in_array($chartType, $sql_builder_for)) {
//                 $options['sql-builder'] = esc_html__('SQL Builder', 'graphina-charts-for-elementor');
//             }
//             // if(isGraphinaPro()){
//             //     $options['filter'] = esc_html__('Data From Filter', 'graphina-charts-for-elementor');
//             // }
//             break;
//     }
//     if ($first) {
//         return (count($options) > 0) ? array_keys($options)[0] : [];
//     }
//     return $options;
// }




function graphina_chart_data_option_controls($this_ele, $type = 'chart_id', $defaultCount = 0, $showNegative = false)
{
    $this_ele->start_controls_section(
        'iq_' . $type . '_section_5_2',
        [
            'label' => esc_html__('Chart Data Options', 'graphina-charts-for-elementor')
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_is_pro',
        [
            'label' => esc_html__('Is Pro', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::HIDDEN,
            'default' =>  'true', // false
        ]
    );
    
    if(in_array($type, [ 'demo'])) {
        $this_ele->add_control(
			'iq_' . $type . '_chart_data_option',
			[
				'label' => esc_html__( 'Type', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'manual',
			]
		);
    }else{
        $this_ele->add_control(
            'iq_' . $type . '_chart_data_option',
            [
                'label' => esc_html__('Type', 'graphina-charts-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => graphina_chart_data_enter_options('base', $type, true),
                'options' => graphina_chart_data_enter_options('base', $type)
            ]
        );
    }
    $seriesTest = 'Elements';
    if(!in_array($type, ['geo_google'])){
        $this_ele->add_control(
            'iq_' . $type . '_chart_data_series_count',
            [
                'label' => esc_html__('Data ' . $seriesTest, 'graphina-charts-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => $defaultCount !== 0 ? $defaultCount : (in_array($type, ['pie', 'polar', 'donut', 'radial', 'bubble','pie_google','donut_google','org_google']) ? 5 : 1),
                'min' => 1,
                'max' => $type === 'gantt_google' ? 1 : graphina_default_setting('max_series_value'),
            ]
        );
    }

    if ($showNegative && (!in_array($type, ['pie_google','donut_google','gauge_google','geo_google','org_google','gantt_google']))) {
        $this_ele->add_control(
            'iq_' . $type . '_can_chart_negative_values',
            [
                'label' => esc_html__('Default Negative Value', 'graphina-charts-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'graphina-charts-for-elementor'),
                'label_off' => esc_html__('No', 'graphina-charts-for-elementor'),
                'description' => esc_html__("Show default chart with some negative values", 'graphina-charts-for-elementor'),
                'default' => false,
                'condition' => [
                    'iq_' . $type . '_chart_data_option' => 'manual'
                ]
            ]
        );
    }

    if (!in_array($type, ['nested_column','brush','area_google', 'pie_google','line_google', 'bar_google','column_google','donut_google','gauge_google','geo_google','org_google','gantt_google'])) {
        $this_ele->add_control(
            'iq_' . $type . '_can_chart_reload_ajax',
            [
                'label' => esc_html__('Reload Ajax', 'graphina-charts-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('True', 'graphina-charts-for-elementor'),
                'label_off' => esc_html__('False', 'graphina-charts-for-elementor'),
                'default' => false,
                'condition' => [
                    'iq_' . $type . '_chart_data_option!' => ['manual'],
                ]
            ]
        );
    }

    $this_ele->add_control(
        'iq_' . $type . '_interval_data_refresh',
        [
            'label' => __('Set Interval(sec)', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 5,
            'step' => 5,
            'default' => 15,
            'condition' => [
                'iq_' . $type . '_can_chart_reload_ajax' => 'yes',
                'iq_' . $type . '_chart_data_option!' => ['manual'],
            ]
        ]
    );

    $this_ele->end_controls_section();

    if(in_array($type,['line', 'column', 'area', 'pie', 'donut', 'radial', 'radar', 'polar','data_table_lite','distributed_column','scatter','mixed','brush', 'pie_google','donut_google','line_google','area_google',
        'column_google','bar_google','gauge_google','geo_google','org_google','gantt_google'])){
        do_action('graphina_forminator_addon_control_section', $this_ele, $type);
    }

    do_action('graphina_addons_control_section', $this_ele, $type);

    $this_ele->start_controls_section(
        'iq_' . $type . '_section_5_2_1',
        [
            'label' => esc_html__('Dynamic Data Options', 'graphina-charts-for-elementor'),
            'condition' => [
                'iq_' . $type . '_chart_data_option' => ['dynamic']
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_dynamic_data_option',
        [
            'label' => esc_html__('Type', 'graphina-charts-for-elementor'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => graphina_chart_data_enter_options('dynamic', $type, true),
            'options' => graphina_chart_data_enter_options('dynamic', $type)
        ]
    );

    if (isGraphinaPro()) {
        graphina_pro_get_dynamic_options($this_ele, $type);
    }

    if (!isGraphinaPro()) {

        $this_ele->add_control(
            'iq_' . $type . 'get_pro',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => graphina_get_teaser_template([
                    'title' => esc_html__('Get New Exciting Features', 'graphina-charts-for-elementor'),
                    'messages' => ['Get Graphina Pro for above exciting features and more.'],
                    'link' => 'https://codecanyon.net/item/graphinapro-elementor-dynamic-charts-datatable/28654061'
                ]),
            ]
        );
    }

    $this_ele->end_controls_section();

    if(!in_array($type,['mixed','brush','nested_column','area_google', 'pie_google','line_google', 'bar_google','column_google','donut_google','gauge_google','geo_google','org_google'])){
        graphina_charts_filter_settings($this_ele,$type);
    }
    if (isGraphinaPro()) {
        graphina_restriction_content_options($this_ele, $type);
    }

}
