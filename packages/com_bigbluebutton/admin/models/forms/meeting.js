/**
 * @package    Joomla.Component.Builder
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <https://www.hoicoimasti.com>
 * @github     Joomla Component Builder <https://github.com/vdm-io/Joomla-Component-Builder>
 * @copyright  Copyright (C) 2019 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

// Some Global Values
jform_vvvvvvvvvv_required = false;
jform_vvvvvvwvvw_required = false;

jQuery(document).ready(function(){var record_vvvvvvv=jQuery("#jform_record").val();vvvvvvv(record_vvvvvvv);var branding_vvvvvvw=jQuery("#jform_branding input[type='radio']:checked").val();vvvvvvw(branding_vvvvvvw)});function vvvvvvv(record_vvvvvvv){if(isSet(record_vvvvvvv)&&record_vvvvvvv.constructor!==Array){var temp_vvvvvvv=record_vvvvvvv;var record_vvvvvvv=[];record_vvvvvvv.push(temp_vvvvvvv)}
else if(!isSet(record_vvvvvvv)){var record_vvvvvvv=[]}
var record=record_vvvvvvv.some(record_vvvvvvv_SomeFunc);if(record){jQuery('#jform_duration').closest('.control-group').show();if(jform_vvvvvvvvvv_required){updateFieldRequired('duration',0);jQuery('#jform_duration').prop('required','required');jQuery('#jform_duration').attr('aria-required',!0);jQuery('#jform_duration').addClass('required');jform_vvvvvvvvvv_required=!1}}
else{jQuery('#jform_duration').closest('.control-group').hide();if(!jform_vvvvvvvvvv_required){updateFieldRequired('duration',1);jQuery('#jform_duration').removeAttr('required');jQuery('#jform_duration').removeAttr('aria-required');jQuery('#jform_duration').removeClass('required');jform_vvvvvvvvvv_required=!0}}}
function record_vvvvvvv_SomeFunc(record_vvvvvvv){if(record_vvvvvvv==1){return !0}
return !1}
function vvvvvvw(branding_vvvvvvw){if(branding_vvvvvvw==1){jQuery('#jform_copyright').closest('.control-group').show();if(jform_vvvvvvwvvw_required){updateFieldRequired('copyright',0);jQuery('#jform_copyright').prop('required','required');jQuery('#jform_copyright').attr('aria-required',!0);jQuery('#jform_copyright').addClass('required');jform_vvvvvvwvvw_required=!1}
jQuery('#jform_logo').closest('.control-group').show()}
else{jQuery('#jform_copyright').closest('.control-group').hide();if(!jform_vvvvvvwvvw_required){updateFieldRequired('copyright',1);jQuery('#jform_copyright').removeAttr('required');jQuery('#jform_copyright').removeAttr('aria-required');jQuery('#jform_copyright').removeClass('required');jform_vvvvvvwvvw_required=!0}
jQuery('#jform_logo').closest('.control-group').hide()}}
function updateFieldRequired(name,status){var not_required=jQuery('#jform_not_required').val();if(status==1){if(isSet(not_required)&&not_required!=0){not_required=not_required+','+name}
else{not_required=','+name}}
else{if(isSet(not_required)&&not_required!=0){not_required=not_required.replace(','+name,'')}}
jQuery('#jform_not_required').val(not_required)}
function isSet(val){if((val!=undefined)&&(val!=null)&&0!==val.length){return !0}
return !1} 
