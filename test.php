<?php

$format = "{MGM-ACCOUNT}{-}{Ymd}{-}{00000000}{}{}";
$start_number = "0008";

$table_name = "accounts";
$last_code = "";

// Create an array of the auto number/code parts using the specified format
preg_match_all("!\{(.*?)\}!", $format, $matches);

// If there are no matches, or matches[1] is not set, then we need to set a default format
// otherwise we will get errors. This should not happen as the validation should
// ensure a valid format before we get to this point

if ( ( !isset( $matches ) ) || ( !isset( $matches[1] ) ) ) {
	$format = "{INVALID_FORMAT}{-}{Y}{-}{0000}{}{}";
	preg_match_all("!\{(.*?)\}!", $format, $matches);
}

$props = $matches[1];

// If props is empty or there are not 7 parts then similar to above we need to set a default format.
// This should not happen as the validation should ensure a valid format before we get to this point

if ( ( !isset($props) ) || ( count($props) !=7 ) ) {
	$format = "{INVALID_FORMAT}{-}{Y}{-}{0000}{}{}";
	preg_match_all("!\{(.*?)\}!", $format, $matches);
	$props = $matches[1];
}



$prefix =  			isset( $props[0] ) ? $props[0]: "";
$seperator_01 = 	isset( $props[1] ) ? $props[1]: "";
$dateformat = 		isset( $props[2] ) ? $props[2]: "";
$seperator_02 = 	isset( $props[3] ) ? $props[3]: "";
// if the number format is not set then default to 0000 as it is required for the field to function
$number_format = 	isset( $props[4] ) ? $props[4]: "0000";
$seperator_03 = 	isset( $props[5] ) ? $props[5]: "";
$suffix = 			isset( $props[6] ) ? $props[6]: "";
$number_len 	= 	strlen($number_format);
$padded_number = 	str_pad($start_number, $number_len, "0", STR_PAD_LEFT);


echo("SugarFieldAutoincrement::save() table_name=".$table_name."\n");
echo("SugarFieldAutoincrement::save() format=".$format."\n");
echo("SugarFieldAutoincrement::save() prefix=". $prefix."\n");
echo("SugarFieldAutoincrement::save() seperator_01=". $seperator_01."\n");
echo("SugarFieldAutoincrement::save() dateformat=". $dateformat."\n");
echo("SugarFieldAutoincrement::save() seperator_02=". $seperator_02."\n");
echo("SugarFieldAutoincrement::save() number_format=". $number_format."\n");
echo("SugarFieldAutoincrement::save() number_len=". $number_len."\n");
echo("SugarFieldAutoincrement::save() seperator_03=". $seperator_03."\n");
echo("SugarFieldAutoincrement::save() suffix=". $suffix."\n");

if ( ! empty($dateformat ) ) {
	// The code format can not handle any seperators in the date format, so even if they are set strip then out
	$dateformat = preg_replace("/[^a-zA-Z\s]/", "", $dateformat);
	echo("SugarFieldAutoincrement::save() validated date format=". $dateformat."\n");

	$tmpdate = new DateTime();
	$datestr = $tmpdate->format($dateformat);
	$date_len = strlen($datestr);
	$addDate = true;

} else {
	$addDate = false;
}

$last_code = "";
$last_code = !empty($prefix) ? $prefix: "";
$last_code .= !empty($seperator_01) ? $seperator_01: "";
$last_code .= !empty($datestr) ? $datestr: "";
$last_code .= !empty($seperator_02) ? $seperator_02: "";
$last_code .= !empty($padded_number) ? $padded_number: "";
$last_code .= !empty($seperator_03) ? $seperator_03: "";
$last_code .= !empty($suffix) ? $suffix: "";

// pattern to detect valid auto number exists, designed to detect non padded numbering too.

$pattern = "/^" .

$pattern = !empty($prefix) ? $prefix: "";
$pattern .= !empty($seperator_01) ? $seperator_01: "";
$pattern .= ($addDate) ? "([0-9]{".$date_len."})": "";
$pattern .= !empty($seperator_02) ? $seperator_02: "";
$pattern .= "([0-9]{".$number_len."})";
$pattern .= !empty($seperator_03) ? $seperator_03: "";
$pattern .= !empty($suffix) ? $suffix: "";
$pattern .= "$/";

echo ("pattern=". $pattern. "\n");
echo ("last_code=". $last_code. "\n");

// Now lets extract the last auto increment val from the last code

preg_match($pattern, $last_code, $ai_matches);

print_r($ai_matches);

// If date is one of the format components, then the match will be at index 2

if ( isset($ai_matches) ) {
	if ( $addDate ) {
		$last_ai_val = $ai_matches[2];
	} else {
		$last_ai_val = $ai_matches[1];
	}
} else {
	// should not happen but if it does then default last_auto_increment_val to the start_number
	$last_ai_val = $start_number;
}

echo("last_ai_val=".$last_ai_val."\n");

$next_ai_val_int = intval($last_ai_val)+1;

echo("next_ai_val_int=".$next_ai_val_int."\n");

$next_ai_val = str_pad(strval($next_ai_val_int),$number_len,"0",STR_PAD_LEFT);

echo("next_ai_val=".$next_ai_val."\n");

$next_code = "";
$next_code = !empty($prefix) ? $prefix: "";
$next_code .= !empty($seperator_01) ? $seperator_01: "";
$next_code .= !empty($datestr) ? $datestr: "";
$next_code .= !empty($seperator_02) ? $seperator_02: "";
$next_code .= !empty($next_ai_val) ? $next_ai_val: "";
$next_code .= !empty($seperator_03) ? $seperator_03: "";
$next_code .= !empty($suffix) ? $suffix: "";

echo ("last_code=". $last_code. "\n");
echo ("next_code=". $next_code. "\n");

?>

