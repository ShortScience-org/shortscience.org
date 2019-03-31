<style>

.demo-container {
	box-sizing: border-box;
	padding: 20px 15px 15px 15px;
	margin: 0px auto 0px auto;
	border: 1px solid #ddd;
	background: #fff;
	background: linear-gradient(#f6f6f6 0, #fff 50px);
	background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
	box-shadow: 0 3px 10px rgba(0,0,0,0.15);
	-o-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-ms-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-moz-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-webkit-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
</style>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="res/js/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="res/js/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="res/js/flot/jquery.flot.time.js"></script>
	<script type="text/javascript">

	$(function() {
		drawPlot();
	});



	window.onresize = function(event) {

		drawPlot();
	};

	
<?php
// dbconn(false);
// $startdate = date('Y-m-d H:i:s',time()-(11*86400));
// $enddate = date('Y-m-d H:i:s',time()-(1*86400));
// $sql = "SELECT UNIX_TIMESTAMP(date) as date, size, count FROM `torrents_daily_stats` WHERE date >= '$startdate' AND date < '$enddate'";
// $res = mysql_query($sql) or die("Cannot Load Statistics".logerror("Error loading stats",mysql_error()));

//print_r($views);

date_default_timezone_set('UTC');
$startdate = strtotime(date('Y-m-d',time()-($previousdays*86400)))*1000;

$enddate = strtotime(date('Y-m-d',time()-(-1*86400)))*1000;

// print $startdate."\n";
// print $enddate."\n";

$withcounts = array();

for ($view = $startdate; $view < $enddate; $view+=86400000) {
	$withcounts[$view] = 0;
}

//print_r($views);

for ($view = 0; $view < count($views); $view++) {
	$withcounts[$views[$view]['date']*1000] = $views[$view]['count'];
}

//print_r($withcounts);

echo "var s = [";


for ($view = $startdate; $view < $enddate; $view+=86400000) {
	print("[".$view.",".$withcounts[$view]."],");
}


// for ($view = 0; $view <= count($views[$view]); $view++) {
// 	//	print_r($views[$view]);
	
// 	print("[".($views[$view]['date']*1000).",".($views[$view]['count'])."],");

// } 
echo "];\n";
?>

function drawPlot(){
	
		$.plot("#placeholder", 
			[
				{ data:s, label:"Number of views", lines:{show:true}, points:{show:true}}
		    ], {
			xaxis: {
				mode: "time",
				<?php if ($previousdays > 7){?>
				tickSize: [1,"month"],
				timeformat: "%b"
				<?php }else{?>
				tickSize: [1,"day"],
				timeformat: "%b%d"
				<?php }?>
			},
	        yaxes: [{
	        	min:0,
	            axisLabel: "Number of views",
	            axisLabelUseCanvas: true,
	            axisLabelFontSizePixels: 12,
	            axisLabelFontFamily: 'Verdana, Arial',
	            axisLabelPadding: 3,
	        }],
	        legend: {
	            noColumns: 0,
	            labelBoxBorderColor: "#000000",
	            position: "nw"
	        },
		});
}

	</script>

	<div style="font: 18px/1.5em 'proxima-nova', Helvetica, Arial, sans-serif;">
	<center><small>Last <?=$previousdays?> days</small></center>
		<div class="demo-container" style="width:100%;height:220px">
			<div id="placeholder" class="" style="	width: 100%; height: 100%;font-size: 14px;"></div>
		</div>
	</div>
