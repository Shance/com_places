<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_places
 *
 * @copyright   Copyright (C) 2015 Saity74 LLC, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/towns.php';

/**
 * Featured content controller class.
 *
 * @since  1.6
 */
class PlacesControllerFeatured extends PlacesControllerTowns
{
	/**
	 * Removes an item.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$ids  = $this->input->get('cid', array(), 'array');

		// Access checks.
		foreach ($ids as $i => $id)
		{
			if (!$user->authorise('core.delete', 'com_places.town.' . (int) $id))
			{
				// Prune items that you can't delete.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
			}
		}

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if (!$model->featured($ids, 0))
			{
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_places&view=featured');
	}

	/**
	 * Method to publish a list of towns.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function publish()
	{
		parent::publish();

		$this->setRedirect('index.php?option=com_places&view=featured');
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Feature', $prefix = 'PlacesModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}