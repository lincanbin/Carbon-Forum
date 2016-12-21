<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script type="text/javascript">
$(document).ready(function(){
	$("#statistics").easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion           
		width: 'auto', //auto or any custom width
		fit: true,   // 100% fits in a container
		closed: false, // Close the panels on start, the options 'accordion' and 'tabs' keep them closed in there respective view types
		activate: function() {}  // Callback function, gets called if tab is switched
	});
});
</script>
<div id="statistics" class="tab-container">
	<ul class='resp-tabs-list'>
		<li><?php echo $Lang['Topics_Statistics']; ?></li>
		<li><?php echo $Lang['Posts_Statistics']; ?></li>
		<li><?php echo $Lang['Users_Statistics']; ?></li>
		<li><?php echo $Lang['Tags_Statistics']; ?></li>
	</ul>
	<div class="resp-tabs-container">

		<div>
			<div id="TopicsStatistics" style="width:940px;height:560px"></div>
		</div>
		<div>
			<div id="PostsStatistics" style="width:940px;height:560px"></div>
		</div>
		<div>
			<div id="UsersStatistics" style="width:940px;height:560px"></div>
		</div>
		<div>
			<div id="TagsStatistics" style="width:940px;height:680px"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
//预置数据
var StatisticsData = <?php echo $DataJsonString; ?>;

loadScript("<?php echo $Config['WebsitePath']; ?>/static/echarts/echarts.js?version=<?php echo CARBON_FORUM_VERSION; ?>",function() {
	//准备好数据
	var TotalTopicsStatisticsData = [];
	var DaysTopicsStatisticsData = [];

	var TotalPostsStatisticsData = [];
	var DaysPostsStatisticsData = [];

	var TotalUsersStatisticsData = [];
	var DaysUsersStatisticsData = [];

	for(var i in StatisticsData){
		var CurStatisticsDate = StatisticsData[i][0].split('-');
		var CurStatisticsDateObject=new Date();
		CurStatisticsDateObject.setFullYear(
			CurStatisticsDate[0], 
			CurStatisticsDate[1]-1, 
			CurStatisticsDate[2]
		);
		TotalTopicsStatisticsData.push(
			[
				CurStatisticsDateObject, 
				StatisticsData[i][1],
				1
			]
		);
		DaysTopicsStatisticsData.push(
			[
				CurStatisticsDateObject, 
				StatisticsData[i][4],
				1
			]
		);

		TotalPostsStatisticsData.push(
			[
				CurStatisticsDateObject, 
				StatisticsData[i][2],
				1
			]
		);
		DaysPostsStatisticsData.push(
			[
				CurStatisticsDateObject, 
				StatisticsData[i][5],
				1
			]
		);

		TotalUsersStatisticsData.push(
			[
				CurStatisticsDateObject, 
				StatisticsData[i][3],
				1
			]
		);
		DaysUsersStatisticsData.push(
			[
				CurStatisticsDateObject, 
				StatisticsData[i][6],
				1
			]
		);
	};
	// 基于准备好的dom，初始化前三个图表
	option = {
		title : {
			text : '    <?php echo $Config['SiteName'].'    '.$Lang['Topics_Statistics']; ?>'
		},
		color : ['#c23531', '#314656', '#61a0a8', '#dd8668', '#91c7ae', 
			'#6e7074', '#61a0a8', '#bda29a', '#44525d', '#c4ccd3'],
		tooltip : {
			trigger: 'item',
			formatter : function (params) {
				var date = new Date(params.value[0]);
				data = date.getFullYear() + '-'
					   + (date.getMonth() + 1) + '-'
					   + date.getDate()
				return data + '<br/>'
					   + params.value[1]
			}
		},
		toolbox: {
			show : true,
			feature : {
				mark : {show: true},
				dataView : {show: true, readOnly: false},
				restore : {show: true},
				saveAsImage : {show: true}
			}
		},
		dataZoom: {
			show: true,
			start : TotalTopicsStatisticsData.length <= 45 ? 0 : (100 * (TotalTopicsStatisticsData.length - 45) / TotalTopicsStatisticsData.length)
		},
		legend : {
			data : [
					'<?php echo $Lang['TotalTopics_Statistics']; ?>',
					'<?php echo $Lang['DaysTopics_Statistics']; ?>'
				]
		},
		grid: {
			y2: 80
		},
		xAxis : [
			{
				type : 'time',
				splitNumber:7
			}
		],
		yAxis : [
			{
				name : '<?php echo $Lang['TotalTopics_Statistics']; ?>',
				scale : true,
				type : 'value'
			},
			{
				name : '<?php echo $Lang['DaysTopics_Statistics']; ?>',
				scale : false,
				type : 'value'
			}
		],
		series : [
			{
				name: '<?php echo $Lang['TotalTopics_Statistics']; ?>',
				type: 'line',
				smooth:true,
				itemStyle: {normal: {areaStyle: {type: 'default'}}},
				showAllSymbol: true,
				data: TotalTopicsStatisticsData
			},
			{
				name: '<?php echo $Lang['DaysTopics_Statistics']; ?>',
				type: 'line',
				yAxisIndex: 1,
				smooth:true,
				itemStyle: {normal: {areaStyle: {type: 'default'}}},
				showAllSymbol: true,
				data: DaysTopicsStatisticsData
			}
		]
	};

	// 为echarts对象加载数据 
	var TopicsStatistics = echarts.init(document.getElementById('TopicsStatistics'));
	option.color.sort(function(){return Math.random()>0.5?-1:1;});
	TopicsStatistics.setOption(option);

	option.title.text = '    <?php echo $Config['SiteName'].'    '.$Lang['Posts_Statistics']; ?>';
	option.legend.data = [
		'<?php echo $Lang['TotalPosts_Statistics']; ?>',
		'<?php echo $Lang['DaysPosts_Statistics']; ?>'
	];
	option.yAxis[0].name = '<?php echo $Lang['TotalPosts_Statistics']; ?>';
	option.yAxis[1].name = '<?php echo $Lang['DaysPosts_Statistics']; ?>';
	option.series[0].name = '<?php echo $Lang['TotalPosts_Statistics']; ?>';
	option.series[0].data = TotalPostsStatisticsData;
	option.series[1].name = '<?php echo $Lang['DaysPosts_Statistics']; ?>';
	option.series[1].data = DaysPostsStatisticsData;
	var PostsStatistics = echarts.init(document.getElementById('PostsStatistics'));
	option.color.sort(function(){return Math.random()>0.5?-1:1;});
	PostsStatistics.setOption(option);

	
	option.title.text = '    <?php echo $Config['SiteName'].'    '.$Lang['Users_Statistics']; ?>';
	option.legend.data = [
		'<?php echo $Lang['TotalUsers_Statistics']; ?>',
		'<?php echo $Lang['DaysUsers_Statistics']; ?>'
	];
	option.yAxis[0].name = '<?php echo $Lang['TotalUsers_Statistics']; ?>';
	option.yAxis[1].name = '<?php echo $Lang['DaysUsers_Statistics']; ?>';
	option.series[0].name = '<?php echo $Lang['TotalUsers_Statistics']; ?>';
	option.series[0].data = TotalUsersStatisticsData;
	option.series[1].name = '<?php echo $Lang['DaysUsers_Statistics']; ?>';
	option.series[1].data = DaysUsersStatisticsData;
	var UsersStatistics = echarts.init(document.getElementById('UsersStatistics'));
	option.color.sort(function(){return Math.random()>0.5?-1:1;});
	UsersStatistics.setOption(option);

	//话题的treemap
	function colorMappingChange(value) {
		var levelOption = getLevelOption(value);
		chart.setOption({
			series: [{
				levels: levelOption
			}]
		});
	}

	var formatUtil = echarts.format;

	function getLevelOption() {
		return [
			{
				itemStyle: {
					normal: {
						borderWidth: 0,
						gapWidth: 5
					}
				}
			},
			{
				itemStyle: {
					normal: {
						gapWidth: 1
					}
				}
			},
			{
				colorSaturation: [0.35, 0.5],
				itemStyle: {
					normal: {
						gapWidth: 1,
						borderColorSaturation: 0.6
					}
				}
			}
		];
	}

	option = {
		title : {
			text : '    <?php echo $Config['SiteName'].'    '.$Lang['Tags_Statistics']; ?>'
		},

		tooltip: {
			formatter: function (info) {
				return [
					'<div class="tooltip-title">' + formatUtil.encodeHTML(info.name) + '</div>',
					'<?php echo $Lang['Topics_Number']; ?>: ' + info.value,
				].join('');
			}
		},

		series: [
			{
				name:'    <?php echo $Config['SiteName'].'    '.$Lang['Tags_Statistics']; ?>',
				type:'treemap',
				visibleMin: 100,
				label: {
					show: true,
					formatter: '{b}'
				},
				itemStyle: {
					normal: {
						borderColor: '#fff'
					}
				},
				levels: getLevelOption(),
				data: <?php echo $TagsDataJsonString; ?>
			}
		],

		toolbox: {
			show : true,
			feature : {
				mark : {show: true},
				dataView : {show: true, readOnly: false},
				restore : {show: true},
				saveAsImage : {show: true}
			}
		}
	};
	var TagsStatistics = echarts.init(document.getElementById('TagsStatistics'));
	TagsStatistics.setOption(option);
});
</script>
<!-- main-content end -->