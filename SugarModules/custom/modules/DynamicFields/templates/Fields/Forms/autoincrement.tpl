 {include
file="modules/DynamicFields/templates/Fields/Forms/coreTop.tpl"}

       

<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields"
	label="COLUMN_TITLE_MAX_SIZE"}:</td>
	<td>{if $hideLevel < 5} <input type='text' name='len'
		value='{$vardef.len|default:255}'
		onchange="forceRange(this,1,255);changeMaxLength(document.getElementById('default'),this.value);">
	{literal} <script>
		function forceRange(field, min, max){
			field.value = parseInt(field.value);
			if(field.value == 'NaN')field.value = max;
			if(field.value > max) field.value = max;
			if(field.value < min) field.value = min;
		}
		function changeMaxLength(field, length){
			field.maxLength = parseInt(length);
			field.value = field.value.substr(0, field.maxLength);
		}

		function showToolTip(ttId) {
			tte = document.getElementById(ttId);
			ttStyle = tte.style;
			ttStyle.visibility = "visible";
		}

		function hideToolTip(ttId) {
			tte = document.getElementById(ttId);
			ttStyle = tte.style;
			ttStyle.visibility = "hidden";
		}
		function validateFormat(fieldId) {
			field = document.forms['popup_form'][fieldId];
			

			var found = [],  // an array to collect the strings that are found
				value = field.value,         
		    	rxp = /{(.*?)}/g,
		   		curMatch;

			while( curMatch = rxp.exec( value ) ) {
		 	   found.push( curMatch[1] );
			}

			if ( found.length != 7 ) {
				alert('Invalid format.  Please make sure the format is correct. For any attributes you do not want in your auto code, do not remove the curly braces, just remove the text in the braces I.e {}.\nE.g. {PREFIX}{}{y}{-}{0000}{}{} = PREFIX12-0001');
			}
			console.log( found.length );  
			console.log( found );  
			
		}
		</script> {/literal} {else} <input type='hidden' name='len'
		value='{$vardef.len}'>{$vardef.len} {/if}</td>
</tr>


<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields"
	label="LBL_AUTOINCREMENT_FORMAT"}:</td>
	<td>
	<div id="formatToolTip" style="visibility: hidden" class="tool_tip">{sugar_translate module=DynamicFields label=LBL_AUTOINCREMENT_FORMAT_HELP}</div>
 	<div id="startNumberToolTip" style="visibility: hidden" class="tool_tip">{sugar_translate module=DynamicFields label=LBL_AUTOINCREMENT_START_NUMBER_HELP}</div>
 
	<input id="ext1" type='text' size="45" name='ext1' value='{$AUTOINCREMENT_FORMAT}' OnChange="validateFormat('ext1');"> 
		
		<style>
		{literal}
		.tool_tip {
		 	font: 12px Arial,Helvetica,sans-serif; 
			border:solid 1px #666666; 
			width:400px; 
			padding:5px;
		    position:absolute; 
			z-index:1000;
		    visibility:hidden;
		    float: top;
			color:#333333; 
			top: 260px;
		    left: 450px; 
			background-color:#ffffcc;
		} 
		{/literal}
		</style>
		<a href="#" onmouseout="hideToolTip('formatToolTip')" onmouseover="showToolTip('formatToolTip')" onclick="return false">{sugar_getimage alt=$mod_strings.LBL_HELP name="helpInline" ext=".gif"}</a>

		</td>
</tr>

<tr>
	<td class='mbLBL'>{sugar_translate module="DynamicFields"
	label="LBL_AUTOINCREMENT_START_NUMBER"}:</td>
	<td><input type='text' name='ext2'
		value='{$AUTOINCREMENT_START_NUMBER}'> 
		<a href="#" onmouseout="hideToolTip('startNumberToolTip')" onmouseover="showToolTip('startNumberToolTip')" onclick="return false">{sugar_getimage alt=$mod_strings.LBL_HELP name="helpInline" ext=".gif"}</a>

		</td></td>
</tr>

{include
file="modules/DynamicFields/templates/Fields/Forms/coreBottom.tpl"}
