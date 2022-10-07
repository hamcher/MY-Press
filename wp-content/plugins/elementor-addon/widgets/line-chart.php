<?php
declare(strict_types=1);

require_once(plugin_dir_path(__FILE__) . '../utils/helper.php');

class LineChart extends \Elementor\Widget_Base {

	public function get_name() {
		return 'Line chart widget';
	}

	public function get_title() {
		return 'Line chart';
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

	protected function render() {
		?>

			<div class="chart-card">
				<div class="">
					<h4 class="heading graphina-chart-heading" style="text-align: start; color: green;">Line chart title</h4>
					<p class="sub-heading graphina-chart-sub-heading" style="text-align: start; color: gray;">Line chart description</p>
				</div>

				<div class="chart-box">
					<div class="chart-texture" id='line_google_chart1'>
					</div>
				</div>

			</div>

			<script type="text/javascript">
				(function($) {
					'use strict';
					google.charts.load('current', {'packages':['corechart']});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart() {

						var chart = new google.visualization.LineChart(document.getElementById('line_google_chart1'));


						var options = {
							curveType: 'function',
							legend: { position: 'bottom' }
						};



						window.fetchDailyDataOfLineChart = function () {
							options.title = 'daily data'

							var data = google.visualization.arrayToDataTable([
								['Hours', 'Sales', 'Expenses'],
								['1AM',  10,      6],
								['4AM',  11,      15],
								['9AP',  6,       20],
								['1PM',  30,      5],
								['5PM',  30,      5]
							]);
							chart.draw(data, options);
						}
						window.dailyChartsToUpdate.push(window.fetchDailyDataOfLineChart)

						window.fetchMonthlyDataOfLineChart = function () {

							options.title = 'monthly data'

							var data = google.visualization.arrayToDataTable([
								['Month', 'Sales', 'Expenses'],
								['Janvier',  100,      40],
								['Mars',  110,      460],
								['Octobre',  60,       112],
								['Mai',  130,      54]
							]);
							chart.draw(data, options);
						}
						window.monthlyChartsToUpdate.push(window.fetchMonthlyDataOfLineChart)

						window.fetchYearlyDataOfLineChart = function () {

							options.title = 'yearly data'

							var data = google.visualization.arrayToDataTable([
								['Year', 'Sales', 'Expenses'],
								['2004',  1000,      400],
								['2005',  1170,      460],
								['2006',  660,       1120],
								['2007',  1030,      540]
							]);
							chart.draw(data, options);
						}
						window.yearlyChartsToUpdate.push(window.fetchYearlyDataOfLineChart)

						window.fetchDailyDataOfLineChart()

					}

					console.log('hellow')
				}).apply(this, [jQuery]);

			</script>

		<?php
	}
}

