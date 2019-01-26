/**
 * @package    Joomla.Component.Builder
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <https://www.hoicoimasti.com>
 * @github     Joomla Component Builder <https://github.com/vdm-io/Joomla-Component-Builder>
 * @copyright  Copyright (C) 2019 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

jQuery(document).ready(function(){var timezone_vvvvvvx=jQuery("#jform_timezone input[type='radio']:checked").val();vvvvvvx(timezone_vvvvvvx);var emails_vvvvvvy=jQuery("#jform_emails").val();vvvvvvy(emails_vvvvvvy)});function vvvvvvx(timezone_vvvvvvx){if(timezone_vvvvvvx==0){jQuery('#jform_event_timezone').closest('.control-group').show()}
else{jQuery('#jform_event_timezone').closest('.control-group').hide()}}
function vvvvvvy(emails_vvvvvvy){if(emails_vvvvvvy.length>=5){jQuery('#jform_send_invitation_email').closest('.control-group').show()}
else{jQuery('#jform_send_invitation_email').closest('.control-group').hide()}}
function isSet(val){if((val!=undefined)&&(val!=null)&&0!==val.length){return !0}
return !1} 
