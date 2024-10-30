<?php
$options = get_option("widget_ComparePress_UK_Games_order_results_games_prices");
$headings = get_option("widget_ComparePress_UK_Games_order_headings_games_prices");


// if it is empty, set it up for the first time
if ($options[1] == "") {
	$options = array("1" => "name", "2" => "price", "3" => "postage", "4" => "total", "5" => "availability", "6" => "buy");
	update_option("widget_ComparePress_UK_Games_order_results", $options);
}
if ($headings["name"] == "") {	
	$headings = array("name" => "Retailer", "price" => "Price", "postage" => "Postage", "total" => "Total", "availability" => "Availability", "buy" => "Buy");
	update_option("widget_ComparePress_UK_Games_order_headings", $headings);
}

// check that all values are unique and less than 6
$check = array($_POST['m4e_order_name'], $_POST['m4e_order_price'], $_POST['m4e_order_postage'], $_POST['m4e_order_total'], $_POST['m4e_order_availability'], $_POST['m4e_order_buy']);
if (count(array_unique($check)) < 6) {
	$quit = true;
}

if ($_POST['submit'] != "") {
	if (($_POST['m4e_order_name'] > 0) && ($_POST['m4e_order_name'] <= 6 )) {
		
		$options[$_POST['m4e_order_name']] = "name";
		
		if ($_POST['m4e_headings_name'] != "") {
			$headings["name"] = $_POST['m4e_headings_name'];
		} else {
			$quit = true;
		}
		
	} else {
		$quit = true;
	}
	if (($_POST['m4e_order_price'] > 0) && ($_POST['m4e_order_price'] <= 6)) {
		$options[$_POST['m4e_order_price']] = "price";
		if ($_POST['m4e_headings_price'] != "") {
			$headings["price"] = $_POST['m4e_headings_price'];
		} else {
			$quit = true;
		}
	} else {
		$quit = true;
	}
	if (($_POST['m4e_order_postage'] > 0) && ($_POST['m4e_order_postage'] <= 6)) {
		$options[$_POST['m4e_order_postage']] = "postage";
		if ($_POST['m4e_headings_postage'] != "") {
			$headings["postage"] = $_POST['m4e_headings_postage'];
		} else {
			$quit = true;
		}
	} else {
		$quit = true;
	}
	if (($_POST['m4e_order_total'] > 0) && ($_POST['m4e_order_total'] <= 6)) {
		$options[$_POST['m4e_order_total']] = "total";
		if ($_POST['m4e_headings_total'] != "") {
			$headings["total"] = $_POST['m4e_headings_total'];
		} else {
			$quit = true;
		}
		
	} else {
		$quit = true;
	}
	if (($_POST['m4e_order_availability'] > 0) && ($_POST['m4e_order_availability'] <= 6)) {
		$options[$_POST['m4e_order_availability']] = "availability";
		if ($_POST['m4e_headings_availability'] != "") {
			$headings["availability"] = $_POST['m4e_headings_availability'];
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
	
	if (($_POST['m4e_pricingresults']>0) && ($_POST['m4e_pricingresults']!=$options["m4e_pricingresults"])) {
		$options["m4e_pricingresults"]=$_POST['m4e_pricingresults'];
		$quit=false;
	} else {
		$options["m4e_pricingresults"]=10;
		$quit=false;
	}
	
	if ($quit != true) {
		update_option("widget_ComparePress_UK_Games_order_results_games_prices", $options);
		update_option("widget_ComparePress_UK_Games_order_headings_games_prices", $headings);
		$message = "<DIV class=message>Data Updated</DIV>";
	}
}


echo "<STYLE> .message{width:80%; background-color:#ffffe0; border:1px solid #e6db55; color:black; padding:5px; margin:5px;}></STYLE>".$message."<FORM ACTION=\"\" name=form1 METHOD=POST>
		<table width=90% BORDER=1>
	  <tr>
	  	<td colspan=2><h1>Results Editor</h1></td>
	  </tr>
	  <tr>
	  	<td colspan=2>This page allows you alter the order and headings of the video game deal results</td>
	  </tr>
	  <tr>
	  	<td colspan=2>&nbsp;</td>
	  </tr>
	   <tr>
	  	<td><strong>Column Name</strong></td>
		<td><strong>Position</strong></td>
	  </tr>";
	  
	  foreach ($options as $key => $val) {
		if ($key!="m4e_pricingresults") {
			echo "<tr>
					<td><input type=text name=m4e_headings_".$val." value='".$headings[$val]."'></td>
					<td><input type=text name=m4e_order_".$val." SIZE=1 value='".$key."'></td>
				  </tr>";
	    }
	  }
	 
echo "</TABLE>
	  </FORM>
	  <FORM ACTION=\"\" name=form1 METHOD=POST>
	  <table width=90% BORDER=1>
	  <tr>
	  	<td colspan=2><h2>Further Instructions</h2></td>
	  </tr>
	  <tr>
	  	<td colspan=2>Change the numbers in the POSITION column (above) to reflect the order in which you wish to output the results columns.</td>
	  </tr>
	  <tr>
	  	<td colspan=2>Change the COLUMN NAMEs by editing the text in each field and then pressing SUBMIT</td>
	  </tr>
	  <tr>
		<td colspan=2><h2>Limit Pricing Results</h2></td>
	  </tr>
	  <tr>
		<td colspan=2>Number of pricing results to return: <input type=text name=m4e_pricingresults size=4 value='" . ($options["m4e_pricingresults"]>0?$options["m4e_pricingresults"]:10) . "'></td>
	  </tr>
	  <tr>
	  	<td colspan=2 style='text-align: right;'><input type=submit name=submit value=Submit></td>
	  </tr>
	  </TABLE>
	  </FORM>";
?>