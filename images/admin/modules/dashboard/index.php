<?php
if(!defined('CMS_ADMIN')) {
	die();
}

include("page_header.php");

$usernotactive = array();
$useractive = array();
$userall = array();
$datelist = array();
for($i=30; $i>=1; $i--)
{
	$date = date("d-m-Y");
	$date = strtotime(date("d-m-Y", strtotime($date)) . " -".$i." days");
	$date = date("Y-m-d",$date);
	$result = $db->sql_query("SELECT COUNT(*) AS total FROM {$prefix}_user WHERE DATEDIFF(registrationTime,'$date')=1");
	if ($db->sql_numrows() > 0) {
		while (list($total) = $db->sql_fetchrow($result)) {
			$userall[]=$total;
		}
	}
	$result = $db->sql_query("SELECT COUNT(*) AS total FROM {$prefix}_user WHERE DATEDIFF(registrationTime,'$date')=1 AND activationCode IS NULL");
	if ($db->sql_numrows() > 0) {
		while (list($total) = $db->sql_fetchrow($result)) {
			$useractive[]=$total;
		}
	}
	$datelist[] = $date;
}
?>
<div class="Menu_dashboard fl">
<?php include('Menu_left.php')?>
</div>
<div class="Content_dashboard fl">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Tháng', 'Thành viên đăng ký', 'Đã kích hoạt', 'Chưa kích hoạt'],
		  <?php
			for($i=1;$i<30;$i++)
			{
				$usernotactive[$i] = $userall[$i]- $useractive[$i];
				if($i==29)
				{
					echo "['$datelist[$i]', $userall[$i], $useractive[$i], $usernotactive[$i] ]";
				}
				else{
					echo "['$datelist[$i]', $userall[$i], $useractive[$i], $usernotactive[$i] ],";
				}
				$total_userall= $total_userall+$userall[$i];
				$total_useractive= $total_useractive+$useractive[$i];
				$total_usernotactive= $total_usernotactive+$usernotactive[$i];
			}
		  ?>
        ]);

        var options = {
          title: 'Thống kê thành viên đăng ký'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 900px; height: 300px;"></div>
	<div>Tổng thành viên đã kích hoạt: <?php echo $total_useractive?></div>
	<div>Tổng thành viên chưa kích hoạt: <?php echo $total_usernotactive?></div>
	<div>Tổng thành viên: <?php echo $total_userall?></div>
	 <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Thành viên chưa kích hoạt', <?php echo $total_usernotactive?>],
          ['Thành viên đã kích hoạt', <?php echo $total_useractive?>]
        ]);

        // Set chart options
        var options = {'title':'Thành viên đăng ký',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }
    </script>
	 <div id="chart_div2"></div>
</div>
	
<?php
include_once("page_footer.php");
?>