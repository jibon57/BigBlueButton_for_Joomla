 <?php
/**
* @version		$Id:meeting.php  1 2015-03-05 16:31:34Z Jibon $
* @package		Bigbluebutton
* @subpackage 	Tables
* @copyright	Copyright (C) 2015, Jibon Lawrence Costa. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Jimtawl TableMeeting class
*
* @package		Bigbluebutton
* @subpackage	Tables
*/
class TableMeeting extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	public function __construct(& $db) 
	{
		parent::__construct('#__bbb_meetings', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	public function bind($array, $ignore = '')
	{ 
		
		return parent::bind($array, $ignore);		
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function check()
	{



		/** check for valid name */
		/**
		if (trim($this->meetingName) == '') {
			$this->setError(JText::_('Your Meeting must contain a meetingName.')); 
			return false;
		}
		**/		

		return true;
	}
}
 