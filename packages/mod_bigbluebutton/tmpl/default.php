<?php 
/**
 * @version 1.2
 * @package    joomla
 * @subpackage Bigbluebutton
 * @author	   	Jibon Lawrence Costa
 *  @copyright  	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
 *  @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');	
if ($params->get("jquery") == "1") {
	if (version_compare(JVERSION,'3','<')) {
		JFactory::getDocument()->addScript(JURI::base().'administrator/components/com_bigbluebutton/assets/jquery-1.11.2.min.js');
	}else {
		JHtml::_('jquery.framework');
	}
}	    
?>
<script type="text/javascript">
jQuery("document").ready(function (){
	jQuery("#meetingjoin").click(function(){
		var meetingID = jQuery('#meeting #meetingID').val();
		var username= jQuery('#meeting #username').val();
		var password= jQuery('#meeting #password').val();
		var url = '<?php echo Juri::base(); ?>index.php?option=com_bigbluebutton&task=getMeeting&meetingID='+ meetingID +'&username='+ username +'&password='+ password;
		jQuery("#status").html("<p style='color: red'>Checking infomation......</p>");
		jQuery.getJSON(url, function(data){
			jQuery.each(data, function(i, rep){					
				if (rep.status == "yes") {
					jQuery("#status").html("<p style='color: red'>Please wait redirecting......</p>");
					window.location = rep.url;
					
				}
				else {
					jQuery("#status").html("<p style='color: red'>"+rep.message+"</p>");
				}			
			});
		});	
	});
});
</script>
<div id="status"></div>
<div class="bigbluebutton<?php echo $params->get( 'moduleclass_sfx' ) ?>" id="meeting">
		  <div class="form-group">
		    <label for="Meeting"><?php echo JText::_('MEETING') ?></label>
		    	<select name="id" id="meetingID">
				<?php
					foreach ($result as $data) {
						echo "<option value=".$data['meetingId'].">".$data['meetingName']."</option>";
					}
				?>
			</select>
		  </div>
		  <div class="form-group">
		    <label for="Name"><?php echo JText::_('USERNAME') ?></label>
		    <input type="text" id="username" name="username" size="10" class="form-control" placeholder="<?php echo JText::_('USERNAME') ?>">
		  </div>
		  <div class="form-group">
		    <label for="Password"><?php echo JText::_('PASSWORD') ?></label>
			<input type="password" name="password" id="password" size="10" class="form-control" placeholder="<?php echo JText::_('PASSWORD') ?>">
		  </div>
		  <button type="submit" class="btn btn-default" id="meetingjoin"><?php echo JText::_('JOIN') ?></button>

</div>	
