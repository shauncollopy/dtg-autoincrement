<?php

$manifest = array (
  0 => 
  array (
    'acceptable_sugar_versions' => 
    array (
      0 => '',
    ),
  ),
  1 => 
  array (
    'acceptable_sugar_flavors' => 
    array (
      0 => 'CE',
      1 => 'PRO',
      2 => 'ENT',
    ),
  ),
  'readme' => '
  
Update: 08 July 2012: Fixed various bugs and changed the field meta data options to enable more flexible formatting of the autoincrement val.
  		
DTG AUTO INCREMENT FIELD FOR SUGARCRM
Version 0.2 BETA, 08 July 2012

Copyright (C) 2012 Disruptive Technologies

Below is a very basic overview.  For more information refer to documentation.

This package will install the necessary extensions to add support for
auto increment fields with SugarCRM, specifically for Sugar CE where
auto increment fields are not available.  This simple extension will save you 
the time and effort required to implement a Custom Logic Hook for the simple task
of incrementing the value of a certain field each time a new record is created.

Once this module is installed the field type Autoincrement will be available,
in ModuleBuilder and Studio.  The Auto increment field value is stored as text.  

An Auto increment field has the following attributes:

Format: 	The format of the auto increment val.  This enables you to customize
			the format of the auto generated values.  More information is available in
			the user manual.  Make sure you maintain the default format parts if you 
			want to customize the format. I.e. do not remove any of the {ATTRIBUTE} parts,
			just insert an empty value for any attributes you do not want to include in the
			format.  E.g. 
				-	No suffix: {PREFIX}{-}{y}{-}{0000}{}{} 
				- 	No separators: {PREFIX{}{y}{}{0000}{}{SUFFIX}
				- 	No date: {PREFIX}{-}{}{}{0000}{-}{SUFFIX}
				- 	Custom separators: {PREFIX}{::}{y}{-}{0000}{+}{SUFFIX}
				-	Longer Number: {PREFIX}{-}{y}{-}{00000000}{-}{SUFFIX}
				-	Prefix and Number Only: {PREFIX}{-}{}{}{0000}{}{}
				- 	Number Only: {}{}{}{}{0000}{}{}

Start Number:	The Number to start from when you first add records.
				E.g. 000001
				
Examples:
				Format: {TESTCO}{-}{y}{-}{0000}{-}{ACCOUNT}
				Start Number: 0001
				Value: TESTCO-12-0001-ACCOUNT
				
				Format: {TESTCO}{-}{Y}{-}{0000}{}{}
				Start Number: 0001
				Value: TESTCO-2012-0001
				
				Format: {TESTCO}{}{y}{-}{0000}{}{}
				Start Number: 0001
				Value: TESTCO12-0001
				
				Format: {TESTCO}{}{}{-}{0000}{}{}
				Start Number: 0001
				Value: TESTCO-0001
				
				Format: {CASE}{-}{}{}{000000}{}{}
				Start Number: 000001
				Value: CASE-000001
				
You should not display auto increment fields on the Modules EditView layout,
as you would not normally want users to manually enter values for the auto increment
field.',
  'key' => 'dtg',
  'author' => 'Shaun Collopy',
  'description' => 'DTG Generic Autoincrement Field for SugarCRM',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'DTG SugarCRM Autoincrement Field v0.2 beta',
  'published_date' => '2012-07-08 17:05:46',
  'type' => 'module',
  'version' => '0.2 beta',
  'remove_tables' => 'prompt',
);


$installdefs = array (
  'id' => 'dtg',
  'copy' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/custom',
      'to' => 'custom',
    ),
    
  ),
);