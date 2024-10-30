<?php
$options = get_option("widget_ComparePress_order_results");
$headings = get_option("widget_ComparePress_order_headings");

// if it is empty, set it up for the first time
if ($options[1] == "") {
    $options = array("1" => "network", "2" => "minutes", "3" => "texts", "4" => "information", "5" => "costpermonth", "6" => "buy");
    update_option("widget_ComparePress_order_results", $options);
}
if ($headings["network"] == "") {
    $headings = array("network" => "Network", "minutes" => "Mins", "texts" => "Texts", "information" => "Deal Information", "costpermonth" => "Cost p/m", "buy" => "Buy");
    update_option("widget_ComparePress_order_headings", $headings);
} 

// check that all values are unique and less than 6
$check = array($_POST['m4e_order_network'], $_POST['m4e_order_minutes'], $_POST['m4e_order_texts'], $_POST['m4e_order_information'], $_POST['m4e_order_costpermonth'], $_POST['m4e_order_buy']);
if (count(array_unique($check)) < 6) {
    $quit = true;
}

if ($_POST['submit'] != "") {
    if (($_POST['m4e_order_network'] > 0) && ($_POST['m4e_order_network'] <= 6 )) {

        $options[$_POST['m4e_order_network']] = "network";

        if ($_POST['m4e_headings_network'] != "") {
            $headings["network"] = $_POST['m4e_headings_network'];
        } else {
            $quit = true;
        }
    } else {
        $quit = true;
    }
    if (($_POST['m4e_order_minutes'] > 0) && ($_POST['m4e_order_minutes'] <= 6)) {
        $options[$_POST['m4e_order_minutes']] = "minutes";
        if ($_POST['m4e_headings_minutes'] != "") {
            $headings["minutes"] = $_POST['m4e_headings_minutes'];
        } else {
            $quit = true;
        }
    } else {
        $quit = true;
    }
    if (($_POST['m4e_order_texts'] > 0) && ($_POST['m4e_order_texts'] <= 6)) {
        $options[$_POST['m4e_order_texts']] = "texts";
        if ($_POST['m4e_headings_texts'] != "") {
            $headings["texts"] = $_POST['m4e_headings_texts'];
        } else {
            $quit = true;
        }
    } else {
        $quit = true;
    }
    if (($_POST['m4e_order_information'] > 0) && ($_POST['m4e_order_information'] <= 6)) {
        $options[$_POST['m4e_order_information']] = "information";
        if ($_POST['m4e_headings_information'] != "") {
            $headings["information"] = $_POST['m4e_headings_information'];
        } else {
            $quit = true;
        }
    } else {
        $quit = true;
    }
    if (($_POST['m4e_order_costpermonth'] > 0) && ($_POST['m4e_order_costpermonth'] <= 6)) {
        $options[$_POST['m4e_order_costpermonth']] = "costpermonth";
        if ($_POST['m4e_headings_costpermonth'] != "") {
            $headings["costpermonth"] = $_POST['m4e_headings_costpermonth'];
        } else {
            $quit = true;
        }
    } else {
        $quit = true;
    }
    if (($_POST['m4e_order_buy'] > 0) && ($_POST['m4e_order_buy'] <= 6)) {
        $options[$_POST['m4e_order_buy']] = "buy";
        if ($_POST['m4e_headings_buy'] != "") {
            $headings["buy"] = $_POST['m4e_headings_buy'];
        } else {
            $quit = true;
        }
    } else {
        $quit = true;
    }

    if ($quit != true) {
        update_option("widget_ComparePress_order_results", $options);
        update_option("widget_ComparePress_order_headings", $headings);
        $message = "<DIV class=message>Data Updated</DIV>";
    }
}

echo "<STYLE> .message{width:80%; background-color:#ffffe0; border:1px solid #e6db55; color:black; padding:5px; margin:5px;}></STYLE>" . $message . "<FORM ACTION=\"\" name=form1 METHOD=POST>
		<table width=100%>
	  <tr>
	  	<td colspan=2><h1>Results Editor</h1></td>
	  </tr>
	  <tr>
	  	<td colspan=2>This page allows you alter the order and headings of the mobile phone deal results</td>
	  </tr>
	  <tr>
	  	<td colspan=2>&nbsp;</td>
	  </tr>
	   <tr>
	  	<td><strong>Column Name</strong></td>
		<td><strong>Position</strong></td>
	  </tr>";

foreach ($options as $key => $val) {
    echo "<tr>
				<td><input type=text name=m4e_headings_" . $val . " value='" . $headings[$val] . "'></td>
				<td><input type=text name=m4e_order_" . $val . " SIZE=1 value='" . $key . "'></td>
			  </tr>";
}

echo "<tr><td colspan=2><input type=submit name=submit value=Submit></td></tr></TABLE></FORM> <br />
	  <FORM ACTION=\"\" name=form1 METHOD=POST>
	  <table width=100%>
	  <tr>
	  	<td colspan=2><h2>Further Instructions</h2></td>
	  </tr>
	  <tr>
	  	<td colspan=2>Change the numbers in the POSITION column (above) to reflect the order in which you wish to output the results columns.</td>
	  </tr>
	  <tr>
	  	<td colspan=2>Change the COLUMN NAMEs by editing the text in each field and then pressing SUBMIT</td>
	  </tr>
	  </TABLE>
	  </FORM>";
?>