<?php
class PeriodsSwitcher extends \Elementor\Widget_Base {

	public function get_name() {
		return 'hello_world_widget_2';
	}

	public function get_title() {
		return esc_html__( 'Hello World 2', 'elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'hello', 'world' ];
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'elementor-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Hello world', 'elementor-addon' ),
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hello-world' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab End

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		<style>
			.periods-container {
				background: white;
				height: 45px;
				width: 330px;
				border-radius: 3px;
				display: flex;
				padding-top: 5px;
				padding-bottom: 5px;
			}
			.active-period {
				background: #dbdbdb !important;
				box-shadow: 0px 0px 3px black;
			}
			.active-period:hover {
				cursor: default !important;
			}
			.period {
				width: inherit;
				display: flex;
				justify-content: center;
				margin-left: 5px;
				margin-right: 5px;
				border: 1px solid #e3e3e3;
				background-color: white;
				border-radius: 3px;
			}
			.period:hover {
				background: #ebe7e7;
				cursor: pointer;
			}

			/* .day {
				width: inherit;
				display: flex;
				justify-content: center;
			}
			.month {
				width: inherit;
				display: flex;
				justify-content: center;
			}
			.year {
				width: inherit;
				display: flex;
				justify-content: center;
			} */
		</style>
		<div class="periods-container">
			<div class="period day active-period">
				<p>Day</p>
			</div>
			<div class="period month">
				<p>Month</p>
			</div>
			<div class="period year">
				<p>Year</p>
			</div>
		</div>


		<script type="text/javascript">
			(function($) {
				'use strict';
				window.dailyChartsToUpdate = []
				window.monthlyChartsToUpdate = []
				window.yearlyChartsToUpdate = []

				function updateCharts(chartsToUpdate = []) {
					chartsToUpdate.forEach(updateChart => {
						updateChart()
					});
				}

				$('.day').click(function() {
					$(this).addClass('active-period')
					$('.month').removeClass('active-period')
					$('.year').removeClass('active-period')
					updateCharts(window.dailyChartsToUpdate)
				})

				$('.month').click(function() {
					$(this).addClass('active-period')
					$('.day').removeClass('active-period')
					$('.year').removeClass('active-period')

					updateCharts(window.monthlyChartsToUpdate)
				})

				$('.year').click(function() {
					$(this).addClass('active-period')
					$('.month').removeClass('active-period')
					$('.day').removeClass('active-period')

					updateCharts(window.yearlyChartsToUpdate)
				})

			}).apply(this, [jQuery]);

		</script>

		<?php
	}
}
