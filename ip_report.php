<?php
include("db_connect.php");
//retrieve the appropriate visitor data
$view = $_GET["view"];
//set a default value for $view
if($view!="all" && $view!="record")
  $view = "all";
if($view == "all")
{
    //show all recent visitors
    $sql = "SELECT visitor_id, GROUP_CONCAT(DISTINCT ip_address) as ip_address_list,
        COUNT(DISTINCT ip_address) as ip_total, COUNT(visitor_id) as page_count,
        MIN(timestamp) as start_time, MAX(timestamp) as end_time FROM visitor_tracking GROUP BY visitor_id";
    $result = mysql_query($sql);
    if($result==false){
        $view = "error";
        $error = "Could not retrieve values";   
    }
} else {
    //show pages for a specific visitor
    $visitor_id = $_GET['id'];
    //rung $visitor_id through filter_var to check it's not an invalid
    //value, or a hack attempt
    if(!filter_var($visitor_id, FILTER_VALIDATE_INT, 0)){
        $error = "Invalid ID specified";   
        $view = "error";
    } else {
        $sql = "SELECT timestamp, page_name, query_string, ip_address FROM
          visitor_tracking WHERE visitor_id = '$visitor_id'";
        $result = mysql_query($sql);
    }    
}
function display_date($time){
    return date("F j, Y, g:i a", $time);   
}

?>
<html>
<head>
<title>IP Tracker Report Page</title>
<style>
html {font-family:tahoma,verdana,arial,sans serif;}
body {font-size:62.5%;}
table tr th{
    font-size:0.8em;
    background-color:#ddb;    
    padding:0.2em 0.6em 0.2em 0.6em;
}
table tr td{
    font-size:0.8em;
    background-color:#eec;
    margin:0.3em;
    padding:0.3em;
}
</style>
</head>
<body>
<h1>IP Tracker Report</h1>
<?php if($view=="all") {
    //display all of the results grouped by visitor
    if($row = mysql_fetch_array($result)){
    ?>
    <table>
      <tbody>
      <tr>
      <th>Id</th>
      <th>IP Address(es)</th>
      <th>Entry Time</th>
      <th>Exit Time</th>
      <th>Duration</th>
      <th>Pages visited</th>
      <th>Actions</th>
      </tr>
    <?php
      do{
        if($row["ip_total"] > 1)
            $ip_list = "Multiple addresses";
        else
            $ip_list = $row["ip_address_list"];
        $start_time = strtotime($row["start_time"]);
        $end_time = strtotime($row["end_time"]);
        $start = display_date($start_time);
        $end = display_date($end_time);
        $duration = $end_time - $start_time;
        if($duration >= 60) {
            $duration = number_format($duration/60, 1)." minutes";
        }
        else {
            $duration = $duration." seconds";   
        }
        echo "<tr>";
        echo "<td>{$row["visitor_id"]}</td>";
        echo "<td>$ip_list</td>";
        echo "<td>$start</td>";
        echo "<td>$end</td>";
        echo "<td>$duration</td>";
        echo "<td>{$row["page_count"]}</td>";
        echo "<td><a href='ip_report.php?view=record&id={$row["visitor_id"]}'>view</a></td>";
        echo "</tr>";
      } while ($row = mysql_fetch_array($result));
    ?>
      </tbody>
    </table>
    <?php } else { ?>
      <h3>No records in the table yet</h3>
    <?php } ?>
<?php } elseif($view=="record"){ ?>
  <h3>Showing records for Visitor <?php echo $visitor_id; ?></h3>
  <p><a href="ip_report.php">back</a></p>
  <?php
    //show all pages for a single visitor
    if($row = mysql_fetch_array($result)){
    ?>
    <table>
      <tbody>
      <tr>
      <th>Page viewed</th>
      <th>Query string</th>
      <th>Time of view</th>
      </tr>
    <?php
      do{
        if($row["ip_total"] > 1)
            $ip_list = "More than 1";
        else
            $ip_list = $row["ip_address_list"];
        $time = display_date(strtotime($row["timestamp"]));
        echo "<tr>";
        echo "<td>{$row["page_name"]}</td>";
        echo "<td>{$row["query_string"]}</td>";
        echo "<td>$time</td>";
        echo "</tr>";
      } while ($row = mysql_fetch_array($result));
    ?>
      </tbody>
    </table>
    <?php } else { ?>
      <h3>No records for this visitor</h3>  
    <?php
    }
} elseif($view=="error") { ?>
    <h3>There was an error</h3>
    <?php echo $error;
}
?>

</body>
</html>