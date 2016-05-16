<?php
include('simple_html_dom.php');

function ExtractFormByID($contents,$formname)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form[id="'.$formname.'"] input, form[id="'.$formname.'"] select') as $element) 
	{
		$form_field[$element->name] = $element->value;
	}
	return $form_field;
}

function ExtractFormByName($contents,$formname)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form[name="'.$formname.'"] input, form[name="'.$formname.'"] select') as $element) 
	{
		$form_field[$element->name] = $element->value;
	}
	return $form_field;
}

function ExtractFormHided($contents,$formname)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form[name="'.$formname.'"] input[type="hidden"]') as $element) 
	{
		$form_field[$element->name] = $element->value;
	}
	return $form_field;
}

function ExtractJavaScriptToGetURL($contents,$formname)
{
	$html = str_get_html($contents);
	$form_field = array();
	foreach($html->find('form[id="'.$formname.'"] input') as $element) 
	{
		$onclick_explore1 = explode('SubjectDetailToAdd.aspx', $element);
		$onclick_explore2 = explode(',', $onclick_explore1[1]);
		$form_field[$element->name] = "SubjectDetailToAdd.aspx" . $onclick_explore2[0];
		$form_field[$element->name] = str_replace("&amp;","&",$form_field[$element->name]);
		$form_field[$element->name] = str_replace("&quot","",$form_field[$element->name]);
		$form_field[$element->name] = str_replace(";","",$form_field[$element->name]);
	}
	return $form_field;
}

function SelectSubjectSection($urlsubject,$section)
{
	$extect1 = explode('&aSubjectOfferID=', $urlsubject);
	$extect2 = explode(';', $extect1[1]);
	return $extect2[0].$section;
}

function GetDataFromURL($url,$parameter)
{
	$COOKIEFILE = 'sis-cookies.txt';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.6 (KHTML, like Gecko) Chrome/16.0.897.0 Safari/535.6");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $COOKIEFILE);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $COOKIEFILE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
	$data = curl_exec($ch);
	return $data;
}

function getTimeNow()
{
	// Set Date-Time Zone
	date_default_timezone_set('Asia/Bangkok');
	return date('Y-m-d H:i:s');
}

function extractSecInfo($contents,$section)
{
	$dom_selector = (integer)$section * 4 + ((integer)$section - 1);
	$html = str_get_html($contents);
	$returnarr = array();
	$temp_element1 = $html->find('table[id=ctl00_ctl00_mainContent_PageContent_UcSectionOfferDetail1_gvSectionDetail]')[0];
	
	$returnarr['section'] = trim($temp_element1->find('td')[$dom_selector-4]->plaintext);
	$returnarr['credit'] = trim($temp_element1->find('td')[$dom_selector-3]->plaintext);
	$returnarr['enrolled'] = trim($temp_element1->find('td')[$dom_selector-2]->plaintext);
	$returnarr['fullenroll'] = trim($temp_element1->find('td')[$dom_selector-1]->plaintext);
	$returnarr['reqstudent'] = trim($temp_element1->find('td')[$dom_selector-0]->plaintext);
	print_r($returnarr);
}
?>

