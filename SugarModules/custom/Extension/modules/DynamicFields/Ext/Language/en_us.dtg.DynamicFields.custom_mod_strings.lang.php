<?php
    
/**
 *  The additional DyanamicField module mod_strings for the Autoincrement Field
 */
$mod_strings['LBL_AUTOINCREMENT_FORMAT'] = 'Format';
$mod_strings['LBL_AUTOINCREMENT_FORMAT_HELP'] = 'Please ensure the format is set correctly. Below is an outline of the format attributes:
		<ul>
		<li><b>Prefix:</b> Displayed at the beginning of the value.  E.g. TESTCO. Optional.</li>
		<li><b>Seperator 01:</b> Seperator displayed betwee the prefix and the date values. E.g. -. Optional.</li>
		<li><b>Date:</b> Adds current date info to the value, as per the date format specified. E.g. y would add current two digit year.  Optional.</li>
		<li><b>Seperator 02:</b> Seperator displayed between the date and autoincrement number. E.g. -. Optional.</li>
		<li><b>Autoincrement Number:</b> The number that will be auto incremented each time a new record is created.  The number of zeros specified will set the length of the number. E.g. 0000 will create and number that is 4 digits in length. Required (** Do not leave this attribute empty).</li>
		<li><b>Seperator 03:</b> Seperator displayed between the autoincrement number and the suffix. E.g. -. Optional.</li>
		<li><b>Suffix:</b> Displayed at the end of the value.  E.g. -ACCOUNT. Optional.</li>
		<li><b>Example:</b> Based on the above examples the format would be {TESTCO}{-}{y}{-}{0000}{-}{ACCOUNT} which would create a value TESTCO-12-0001-ACCOUNT</li>
		</ul>';
$mod_strings['LBL_AUTOINCREMENT_START_NUMBER'] = 'Starting Number';
$mod_strings['LBL_AUTOINCREMENT_START_NUMBER_HELP'] = 'The initial autoincrement number value.  E.g. 0001.If you change the format you should update this value to the last number to ensure the numbering is not reset.';

?>
