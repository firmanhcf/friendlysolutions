<?php 

	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="data.csv"');

	require_once 'libraries/simple_html_dom.php';

	$html = new simple_html_dom();
	$html->load_file('files/wo_for_parse.html');

	$elmIDArr = [
		'wo_number',
		'po_number',
		'scheduled_date',
		'customer',
		'trade',
		'nte',
		'store_id',
		'location_address',
		'location_phone'
	];

	$i = 1;
	foreach ($elmIDArr as $e) {
		if($e == 'location_address'){
			$e = "street,city,state,zipcode";
		}
		else if($e == 'location_phone'){
			$e = 'phone_number';
		}

		echo $e.($i<count($elmIDArr)?",":"");
		$i++;
	}

	echo "\n";

	$i = 1;
	foreach ($elmIDArr as $e) {
		$street = "";
		$city = "";
		$state = "";
		$zipCode = "";
		$eObj = $html->find('#'.$e, 0);
		$txt = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $eObj->plaintext);
		$txt = trim($txt);

		if($e == 'scheduled_date'){
			$txt = DateTime::createFromFormat('F j, Y h:i A', $txt)->format('Y-m-d H:i');
		}
		else if($e == 'nte'){
			$txt = str_replace("$", "", $txt);
			$txt = str_replace(",", "", $txt);
			$txt = (float)$txt;
		}
		else if($e == 'location_address'){
			$txtArr = explode(' ', $txt);
			$street = (isset($txtArr[0])?$txtArr[0]." ":"").(isset($txtArr[1])?$txtArr[1]." ":"").(isset($txtArr[2])?$txtArr[2]:"");
			$city = (isset($txtArr[3])?$txtArr[3]:"");
			$state = (isset($txtArr[4])?$txtArr[4]:"");
			$zipCode = (isset($txtArr[5])?$txtArr[5]:"");

			$txt = $street.",".$city.",".$state.",".$zipCode;
		}
		else if($e == 'location_phone'){
			$txt = str_replace("-", "", $txt);
			$txt = str_replace(" ", "", $txt);
		}

		echo $txt.($i<count($elmIDArr)?",":"");
		$i++;

	}
	 
?>