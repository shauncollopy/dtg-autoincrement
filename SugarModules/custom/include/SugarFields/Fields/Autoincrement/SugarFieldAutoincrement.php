<?php
/*********************************************************************************
 * Custom SugarField that enables autoincrement fields in SugarCRM CE.
 *
 * This class takes care of saving the auto increment field using the settings defined
 * in ModuleBuilder, Studio or the custom package vardefs.
 *
 * When this module is installed creating an autoincrement field is a simple as
 * selecting Autoincrement as the data type when you add the field using module builder
 * or studio.
 ********************************************************************************/

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');
require_once('data/SugarBean.php');

class SugarFieldAutoincrement extends SugarFieldBase {

	/**
	 * Override the SugarFieldBase::save() function to implement the logic to get the next autoincrement value
	 * and format the saved value based on the attributes defined for the field.
	 *
	 * @param SugarBean bean - the bean performing the save
	 * @param array params - an array of paramester relevant to the save, most likely will be $_REQUEST
	 * @param string field - the name of the field
	 */
	public function save(&$bean, $params, $field, $properties, $prefix = '') {
			
		global $timedate;

		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() function called.");

		$format = !empty($properties["format"])?$properties["format"]: "{TESTCO}{-}{y}{-}{0000}{-}{ACCOUNT}";
		// E.g. {TESTCO}{-}{y}{-}{0000}{-}{ACCOUNT}
		$start_number = $properties["start_number"];

		$table_name = $bean->getTableName();
		$last_code = "";

		/**
		 * If the field is a custom field then the field value will be saved in the bean->custom_table
		 */
		if ( $this->isCustomField($bean,$field) ) {
			$table_name = $bean->getTableName() ."_cstm";
			$GLOBALS['log']->debug("SugarFieldAutoincrement::save() field Is a custom field, so table_name".$table_name);

		}
		// Create an array of the auto number/code parts using the specified format
		preg_match_all("!\{(.*?)\}!", $format, $matches);

		// If there are no matches, or matches[1] is not set, then we need to set a default format
		// otherwise we will get errors. This should not happen as the validation should
		// ensure a valid format before we get to this point

		if ( ( !isset( $matches ) ) || ( !isset( $matches[1] ) ) ) {
			$format = "{INVALID_FORMAT}{-}{y}{-}{0000}{-}{SUFFIX}";
			preg_match_all("!\{(.*?)\}!", $format, $matches);
		}

		$props = $matches[1];

		// If props is empty or there are not 7 parts then similar to above we need to set a default format.
		// This should not happen as the validation should ensure a valid format before we get to this point

		if ( ( !isset($props) ) || ( count($props) !=7 ) ) {
			$format = "{INVALID_FORMAT}{-}{y}{-}{0000}{}{}";
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
		$datestr = "";

		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() table_name=".$table_name);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() format=".$format);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() prefix=". $prefix);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() seperator_01=". $seperator_01);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() dateformat=". $dateformat);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() seperator_02=". $seperator_02);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() number_format=". $number_format);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() number_len=". $number_len);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() padded_number=". $padded_number);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() seperator_03=". $seperator_03);
		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() suffix=". $suffix);

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

		// pattern to detect valid auto number exists, if there are existing records and the format does not match then the numbering will start from
		// the value defined as start-Number

		$pattern = "/^" .

		$pattern = !empty($prefix) ? $prefix: "";
		$pattern .= !empty($seperator_01) ? $seperator_01: "";
		$pattern .= ($addDate) ? "([0-9]{".$date_len."})": "";
		$pattern .= !empty($seperator_02) ? $seperator_02: "";
		$pattern .= "([0-9]{".$number_len."})";
		$pattern .= !empty($seperator_03) ? $seperator_03: "";
		$pattern .= !empty($suffix) ? $suffix: "";
		$pattern .= "$/";

		$GLOBALS['log']->debug("SugarFieldAutoincrement::save() regular expression pattern=". $pattern);
		$initializeCode = false;

		if ( empty ( $bean->$field ) ) {
			/**
			 * Only set the value on the first time the record is saved, or if there is not value set
			 */
			$db =  DBManagerFactory::getInstance();
			$query = "select " . $field . " from " . $table_name .
					 " where (" . $field . " <> '' or " . $field . " is not null) order by right(" . $field. ", 4) desc limit 1";
			try {
				$result = $db->query($query, true, 'Error selecting most recent ' . $field . ' autoincrement value');
				if ($row=$db->fetchByAssoc($result)) {
					
					$last_code = $row[$field];
					if ( empty($last_code) ) {
						$initializeCode = true;
					}
					$GLOBALS['log']->debug("SugarFieldAutoincrement::save() query succesful, last_code=".$last_code);
				} else {
					// There were no values in the db for this field, so  create a new autoincrement code value based on the start_number
					$GLOBALS['log']->debug("SugarFieldAutoincrement::save() query executed successlly but there were no matching codes found.");
					$initializeCode = true;
				}
			} catch (Exception $e) {
				$error = $e->getMessage();
				$initalizeCode = true;
			}

			$GLOBALS['log']->debug("SugarFieldAutoincrement::save() Last Code: " . $last_code);
				
			if ( $initializeCode ) {
				$next_code = self::createAutoIncrementVal($prefix, $seperator_01, $datestr, $seperator_02, $padded_number, $seperator_03, $suffix);
				$GLOBALS['log']->info("SugarFieldAutoincrement::save() No autoincrement val found, so will set initial value, next_code".$next_code);
			} else {

				preg_match($pattern, $last_code, $ai_matches);

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
					$GLOBALS['log']->info("SugarFieldAutoincrement::save() The format has changed so will use the start_number val");
				}

				$next_ai_val_int = intval($last_ai_val)+1;
				$next_ai_val = str_pad(strval($next_ai_val_int),$number_len,"0",STR_PAD_LEFT);

				$next_code = self::createAutoIncrementVal($prefix, $seperator_01, $datestr, $seperator_02, $next_ai_val, $seperator_03, $suffix);
				
				
			}
			$GLOBALS['log']->debug("SugarFieldAutoincrement::save() Next Code: " . $next_code);

			$bean->$field=$next_code;
		}

	}


	/**
	 * Function:		isCustomField:
	 * Description:		Determine if the field is a custom field
	 *
	 * @param SugarBean $bean
	 * @param string $field
	 * @return boolean True if the field is a custom field, false if not
	 */
	public function isCustomField(&$bean,$field) {

		if ( $bean->hasCustomFields() ) {
			$field_source = $GLOBALS['dictionary'][$bean->object_name]['fields'][$field]['source'];
			$GLOBALS['log']->debug("SugarAutoincrement::isCustomField(), field=".$field.",field_source=".$field_source);

			if ( $field_source == "custom_fields") {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
		return false;

	}

	/**
	 * Generates a formated auto increment value using the arguments specified
	 * @param string $prefix
	 * @param string $seperator_01
	 * @param string $datestr
	 * @param string $seperator_02
	 * @param string $padded_number
	 * @param string $seperator_03
	 * @param string $suffix
	 * @return string val the formatted autoincrement value
	 */
	protected function createAutoIncrementVal($prefix,$seperator_01,$datestr,$seperator_02,$padded_number,$seperator_03,$suffix) {
		$val = "";
		$val = !empty($prefix) ? $prefix: "";
		$val .= !empty($seperator_01) ? $seperator_01: "";
		$val .= !empty($datestr) ? $datestr: "";
		$val .= !empty($seperator_02) ? $seperator_02: "";
		$val .= !empty($padded_number) ? $padded_number: "";
		$val .= !empty($seperator_03) ? $seperator_03: "";
		$val .= !empty($suffix) ? $suffix: "";
		return $val;
	}
}
?>