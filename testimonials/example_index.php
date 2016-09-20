<?
echo "<html><head>\n";
echo "<base href=\"http://www.afourthdimension.com\">";
echo "<link rel=\"STYLESHEET\" type=\"text/css\" href=". $w . "menus.css>\n";
echo"<title>A fourth Dimension.com</title>";
echo "</head>\n";
echo "<body>\n";
echo "<div id=ItyBitHead>". date("D, F dS, Y h:ia")."</div>\n";
echo "<div id=Banner><h1 id=Banner>4thDimension.com</h1></div>\n";

// db information 
$MenuString = "Western New York Motorcycle Events Calendar";
$admin_email="webmaster@afourthdimension.com"; // change to your valid email 
$username="afourthd_afourth";
$password="scootre";
$database="afourthd_events";

mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query ="Select RecordId, Event_Title, DATE_FORMAT(Event_Date_Start,'%M %D, %Y') AS Start_Date,
DATE_FORMAT(Event_Date_End,'%M %D, %Y') AS End_Date,Event_Time_Start,Event_Time_End,
Event_Location,Event_Address,Event_City,Event_State,Event_Zipcode,Event_Host,
Event_Price,Event_Description,Event_Contact_Name,Event_Contact_Phone,Event_Contact_Email,
Event_Contact_URL,Event_Events,Event_WWW_URL,Event_Submit_Email_Address from events
Where Event_Date_Start between Now()-INTERVAL 1 DAY and Now()+interval 120 day Order by Event_Date_Start ASC Limit 0, 30";

$result=mysql_query($query);
$num=mysql_numrows($result);
mysql_close();

$i=0;


// display header table 
echo "\n\n<table width=500 class=menutable align=center cellpadding=6><tr>";
echo "<td class=lastcell-goldbar><b>Hardtalesmag.com Events Calendar</b> &nbsp;&nbsp;<a id=advert href=\"events/event_form.php\">";
echo "<img title=\"Add a New Event\" src=\"events/b_newentry.png\" border=\"0\">";
echo "Add Your Event</a></td></tr><tr><td>";


while ($i < $num) {

// begin showing events 

$Event_RecordID=mysql_result($result,$i,"RecordID");
$Event_Title=mysql_result($result,$i,"Event_Title");
$Event_Date_Start=mysql_result($result,$i,"Start_Date");
$Event_Date_End=mysql_result($result,$i,"End_Date");
$Event_Time_Start=mysql_result($result,$i,"Event_Time_Start");
$Event_Time_End=mysql_result($result,$i,"Event_Time_End");
$Event_Location=mysql_result($result,$i,"Event_Location");
$Event_Address = mysql_result($result,$i,"Event_Address");
$Event_City = mysql_result($result,$i,"Event_City");
$Event_State = mysql_result($result,$i,"Event_State");
$Event_Zipcode = mysql_result($result,$i,"Event_Zipcode");
$Event_Host = mysql_result($result,$i,"Event_Host");
$Event_Price = mysql_result($result,$i,"Event_Price");
$Event_Description = mysql_result($result,$i,"Event_Description");
$Event_Contact_Name = mysql_result($result,$i,"Event_Contact_Name");
$Event_Contact_Phone = mysql_result($result,$i,"Event_Contact_Phone");
$Event_Contact_Email = mysql_result($result,$i,"Event_Contact_Email");
$Event_Contact_URL = mysql_result($result,$i,"Event_Contact_URL");
$Event_Events = mysql_result($result,$i,"Event_Events");
$Event_WWW_URL = mysql_result($result,$i,"Event_WWW_URL");
$Event_Submit_Email_Address = mysql_result($result,$i,"Event_Submit_Email_Address");

echo "\n\n<!--  Start the Table Data -->\n\n";
echo "\n\n\n<table class=events cellspacing=0 cellpadding=4>\n";
echo "\n<tr><th class=event-list>";
echo "<a href='events/admin/tempform.php?RecordID=".$Event_RecordID."'>*</a>";
echo "Event Title</th><td class=events><b>$Event_Title</b></td></tr>";
echo "\n<tr><th class=event-list>Hosted by:</th><td class=events>$Event_Host</td></tr>";
echo "\n<tr><th class=event-list>Start Date</th><td class=events>";
echo $Event_Date_Start." at ".$Event_Time_Start;
echo "</td></tr>\n";


// Is there a End Date ? if so, display it
if ($Event_Date_End){
        echo "\n<tr><th class=event-list>End Date</th><td class=events>".$Event_Date_End."</td></tr>";
        }

echo "\n<tr><th class=event-list>Location</th><td class=events>$Event_Location</td></tr>";



// check for numeric digits in the beginning of the string, 
// if so, we know there's a street address, on match echo the url to mapquest.

if (ereg ("^[0-9]+.*", $Event_Address)) {

// Build me an army worthy of Mordor..

$strURLToMapQuest="http://www.mapquest.com/maps/map.adp?country=USA&address=".$Event_Address
."&state=".$Event_State."&city=".$Event_City."&county=".$Event_County."&zipcode=".$Event_Zipcode
."&countryId=250&zoom=6";

	echo "\n<tr><th class=event-list>Address</th><td class=events>$Event_Address
	<A class=TinyURL title='See a map of this location!' target='_Top' href='$strURLToMapQuest'>* MapQUEST.com *</a></td></tr>";
}else{
		//No street address, just display the text
        echo "\n<tr><th class=event-list>Address</th><td class=events>$Event_Address</td></tr>";

        }

echo "\n<tr><th class=event-list>City, State, Zip</th><td class=events>$Event_City, $Event_State $Event_Zipcode</td></tr>";
echo "<tr valign=top><th class=event-list>Description</th><td class=events style=\"text-align:justify;\">$Event_Description</td></tr>";
printf ("<tr valign=top><th class=event-list>Price</th><td class=events style=\"text-align:justify;\">%s</td></tr>", $Event_Price);
echo "\n<tr><th class=event-list>Contact Name</th><td class=events>$Event_Contact_Name</td></tr>";

if($Event_Contact_Phone){echo "\n<tr><th class=event-list>Contact Phone</th><td class=events>$Event_Contact_Phone</td></tr>";}
if ($Event_Contact_Email){echo "\n<tr><th class=event-list>Contact Email</th><td class=events><a href='mailto:$Event_Contact_Email'>$Event_Contact_Email</a></td></tr>";}
if ($Event_Contact){echo "\n<tr><th class=event-list>Contact URL</th><td class=events><a href='$Event_Contact_URL'>HhtmlEncode('$Event_Contact_URL')</a></td></tr>";}
if ($Event_Events){echo "\n<tr><th  class=event-list>Events</th><td class=events>$Event_Events</td></tr>";}
if ($Event_Contact_URL){echo "\n<tr><th  class=event-list>Event URL</th><td class=events><a target=\"_Top\" href='". $Event_Contact_URL . "'>".urldecode($Event_Contact_URL)."</a></td></tr>";}
if ($Event_WWW_URL){echo "\n<tr><th class=event-list>Host URL</th><td class=events><a target=\"_Top\" href='". $Event_WWW_URL . "'>".urldecode($Event_WWW_URL)."</a></td></tr>";}

echo "</table><p>";

//Next record please...
$i++;

}
echo "</td></tr></table>";

// Kissed the girls and made them cry.. 
// echo "<Div class=TinyText>HardTalesMag.com.</div>";

?>

	
<div class=bannerDiv>
<?php
    if (@include(getenv('DOCUMENT_ROOT').'/adsvr/phpadsnew.inc.php')) {
        if (!isset($phpAds_context)) $phpAds_context = array();
        $phpAds_raw = view_raw ('zone:1', 0, '', '', '0', $phpAds_context);
        echo $phpAds_raw['html'];
    }
?>

</div>

</body>

</html>



