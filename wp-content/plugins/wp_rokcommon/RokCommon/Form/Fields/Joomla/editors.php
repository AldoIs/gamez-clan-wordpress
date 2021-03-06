<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

RokCommon_Form_Helper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 * Provides a list of installed editors.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @see         RokCommon_Form_Field_Editor
 * @since       11.1
 * @deprecated  21.1 Use RokCommon_Form_Field_Plugins instead (with folder="editors")
 */
class RokCommon_Form_Field_Editors extends RokCommon_Form_Field_List
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Editors';

	/**
	 * Method to get the field options for the list of installed editors.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		JLog::add('RokCommon_Form_Field_Editors is deprecated. Use RokCommon_Form_Field_Plugins instead (with folder="editors").', JLog::WARNING, 'deprecated');

		// Get the database object and a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('element AS value, name AS text');
		$query->from('#__extensions');
		$query->where('folder = ' . $db->quote('editors'));
		$query->where('enabled = 1');
		$query->order('ordering, name');

		// Set the query and load the options.
		$db->setQuery($query);
		$options = $db->loadObjectList();
		$lang = JFactory::getLanguage();
		foreach ($options as $i => $option)
		{
			$lang->load('plg_editors_' . $option->value, JPATH_ADMINISTRATOR, null, false, false)
				|| $lang->load('plg_editors_' . $option->value, JPATH_PLUGINS . '/editors/' . $option->value, null, false, false)
				|| $lang->load('plg_editors_' . $option->value, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
				|| $lang->load('plg_editors_' . $option->value, JPATH_PLUGINS . '/editors/' . $option->value, $lang->getDefault(), false, false);
			$options[$i]->text = rc__($option->text);
		}

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
