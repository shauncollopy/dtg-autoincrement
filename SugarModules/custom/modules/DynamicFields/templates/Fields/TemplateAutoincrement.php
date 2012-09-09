<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/DynamicFields/templates/Fields/TemplateText.php');

/**
 * Class:		TemplateAutoincrement		
 * Description:	Custom Field Template for an Autoincrement Field
 * Author: 		Shaun Collopy
 *
 */
class TemplateAutoincrement extends TemplateText {
	
	var $supports_unified_search = true;
	var $type='autoincrement';
	
	/**
	 * TemplateAutoincrement Constructor: Map the ext attribute fields to the relevant autoincrement properties
	 *
	 * @returns field_type
	 */
	function __construct()
	{
		$this->vardef_map['ext1'] = 'format';
		$this->vardef_map['format'] = 'ext1';
		$this->vardef_map['ext2'] = 'start_number';
		$this->vardef_map['start_number'] = 'ext2';
		
	}


	/**
	 * Function:		get_field_def
	 * Description:		Get the field definition attributes that are required for the Autoincrement Field
	 *
	 * @return			Field Definition
	 */
	function get_field_def(){
		$def = parent::get_field_def();
		$def['dbType'] = 'varchar';
		$def['len']= 80;
        $def['format'] = !empty($this->format) ? $this->format : $this->ext1;
        $def['start_number'] = !empty($this->start_number) ? $this->start_number : $this->ext2;
		return $def;	
	} 
	
	
}


?>
