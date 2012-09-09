<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/**
 * Implement get_body function to correctly populate the template for the ModuleBuilder/Studio
 * Add field page.
 * 
 * @param Sugar_Smarty 	$ss
 * @param array 		$vardef
 *
 */
function get_body(&$ss, $vardef){
    global $app_list_strings, $mod_strings;
	$vars = $ss->get_template_vars();
	$fields = $vars['module']->mbvardefs->vardefs['fields'];
	$fieldOptions = array();
	foreach($fields as $id=>$def) {
		$fieldOptions[$id] = $def['name'];
	}
	$ss->assign('fieldOpts', $fieldOptions);
	
    $format = !empty($vardef['format']) ? $vardef['format'] : '{TESTCO}{-}{y}{-}{0000}{-}{ACCOUNT}';
    $start_number = !empty($vardef['start_number']) ? $vardef['start_number'] : '0001';
    
    $ss->assign('AUTOINCREMENT_FORMAT', $format);
    $ss->assign('AUTOINCREMENT_START_NUMBER', $start_number);
   
    return $ss->fetch('custom/modules/DynamicFields/templates/Fields/Forms/autoincrement.tpl');
 }

?>
