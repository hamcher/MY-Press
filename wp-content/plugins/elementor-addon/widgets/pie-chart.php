<?php
declare(strict_types=1);

require_once(plugin_dir_path(__FILE__) . '../utils/helper.php');

class PieChart extends \Elementor\Widget_Base {

	public function get_name() {
		return 'Pie chart widget';
	}

	public function get_title() {
		return 'Pie Chart';
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_chart_type()
    {
        return 'pie_google';
    }

	protected function register_controls()
    {

		$type = $this->get_chart_type();

		graphina_basic_controls($this, $type);

		graphina_chart_data_option_controls($this, $type, 0, true);


	}

	public function get_keywords() {
		return [ 'hello', 'world' ];
	}

	protected function render() {
		?>

			<div class="chart-card">
				<div class="">
					<h4 class="heading graphina-chart-heading" style="text-align: start; color: green;">Pie chart title</h4>
					<p class="sub-heading graphina-chart-sub-heading" style="text-align: start; color: gray;">Pie chart description</p>
				</div>

				<div class="chart-box">
					<div class="chart-texture" id='pie_google_chart1'>
					</div>
				</div>

			</div>

			<script type="text/javascript">
				(function($) {
					'use strict';
					google.charts.load('current', {'packages':['corechart']});
					google.charts.setOnLoadCallback(drawChart);


					function drawChart() {

						var chart = new google.visualization.PieChart(document.getElementById('pie_google_chart1'));

						window.fetchDailyDataOfPieChart = function () {

							var options = {
								title: 'My Daily Activities'
							};

							$.ajax({
								method: "POST",
								url: 'https://staging.network.gway.enduser.myem.io/ng-api/query-public',
								headers: {
									"Content-Type": "application/json"
								},
								data: JSON.stringify({
									"interval": "4h",
									"range": {
									"from": "2022-07-19T01:00:00.000Z",
									"to": "2022-07-20T22:00:00.000Z"
									},
									"targets": [
									{
										"target": "__global__consumption_metrics",
										"type": "timeserie"
									}
									]
								})
							}).done(function (results) {

								// var data = new google.visualization.DataTable();
								// data.addColumn('date', 'time_stamp');
								// data.addColumn('number', 'ph');
								// data.addColumn('number', 'moist');

								// $.each(results, function (i, row) {
								// data.addRow([
								// 	new Date(row.time_stamp),
								// 	parseFloat(row.ph),
								// 	parseFloat(row.moist)
								// ]);

								var data = google.visualization.arrayToDataTable([
									['Task', 'Hours per Day'],
									['Work',     5],
									['Eat',      3],
									['Commute',  4],
									['Watch TV', 2],
									['Sleep',    9]
								]);
								chart.draw(data, options);
							});


						}

						window.dailyChartsToUpdate.push(window.fetchDailyDataOfPieChart)

						window.fetchMonthlyDataOfPieChart = function () {

							var options = {
								title: 'My Monthly Activities'
							};

							window.pieChartData = google.visualization.arrayToDataTable([
								['Task', 'Hours per Day'],
								['Work',     11],
								['Eat',      4],
								['Commute',  6],
								['Watch TV', 2],
								['Sleep',    1]
							]);
							chart.draw(window.pieChartData, options);
						}

						window.monthlyChartsToUpdate.push(window.fetchMonthlyDataOfPieChart)

						window.fetchYearlyDataOfPieChart = function () {

							var options = {
								title: 'My Yearly Activities'
							};

							window.pieChartData = google.visualization.arrayToDataTable([
								['Task', 'Hours per Day'],
								['Work',     6],
								['Eat',      5],
								['Commute',  1],
								['Watch TV', 8],
								['Sleep',    4]
							]);
							chart.draw(window.pieChartData, options);
						}
						window.yearlyChartsToUpdate.push(window.fetchYearlyDataOfPieChart)

						window.fetchDailyDataOfPieChart()

					}

					console.log('hellow')
				}).apply(this, [jQuery]);

			</script>

		<?php
	}
}

