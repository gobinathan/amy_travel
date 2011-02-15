<?php
include("../../../config.php");
include("../_functions.php");

include_once( 'open-flash-chart.php' );
//srand((double)microtime()*1000000);

$bar_red = new bar_3d( 75, '#D54C78' );
$bar_red->key( 'Visits', 10 );

//
// create a 2nd set of bars:
//
$bar_blue = new bar_3d( 75, '#3334AD' );
$bar_blue->key( 'Hits', 10 );


$last10days=array();
// add bars:
for( $i=0; $i<10; $i++ ) {
//	$usr_dt = SlimStat::to_user_time( time() );  
//	$svr_dt = SlimStat::to_user_time( time() );  
//	$svr_dt_start = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ), date( "d", $usr_dt ) - $i, date( "Y", $usr_dt ) ) );
//	$svr_dt_end = SlimStat::to_server_time( mktime( 0, 0, 0, date( "n", $usr_dt ), date( "d", $usr_dt ) - $i+1, date( "Y", $usr_dt ) ) );
	$svr_dt_start=mktime(0, 0, 0, date("m")  , date("d") - $i, date("Y"));
	$svr_dt_end=mktime(0, 0, 0, date("m")  , date("d") - $i+1, date("Y"));	
	$hvu = SlimStat::get_hits_visits_uniques( $svr_dt_start, $svr_dt_end - 1 );
	$bar_red->data[] = $hvu["visits"];
	$bar_blue->data[] = $hvu["hits"];
	$last10days[]  = date("j", mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y"))).''.date("M", mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));
	if ($hvu["hits"]>$y_max) { $y_max=$hvu["hits"]; }
	if ($hvu["visits"]>$y_max) { $y_max=$hvu["visits"]; }
}


// create the graph object:
$g = new graph();
$g->title( 'last 10 days', '{font-size:20px; color: #FFFFFF; margin: 5px; background-color: #505050; padding:5px; padding-left: 20px; padding-right: 20px;}' );

//$g->set_data( $data_1 );
//$g->bar_3D( 75, '#D54C78', '2006', 10 );

//$g->set_data( $data_2 );
//$g->bar_3D( 75, '#3334AD', '2007', 10 );

$g->data_sets[] = $bar_red;
$g->data_sets[] = $bar_blue;

$g->set_x_axis_3d( 12 );
$g->x_axis_colour( '#909090', '#ADB5C7' );
$g->y_axis_colour( '#909090', '#ADB5C7' );
//$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
$g->set_x_labels( $last10days );
//$g->set_x_labels( array( 'January','February','March','April','May','June','July','August','September','October' ) );
//$g->set_y_max( 200 );
$g->set_y_max( $y_max );
$g->y_label_steps( 5 );
$g->set_y_legend( 'Ray Solutions', 12, '#736AFF' );
echo $g->render();
?>