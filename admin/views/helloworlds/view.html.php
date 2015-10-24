<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorlds View
 *
 * @since  0.0.1
 */
class HelloWorldViewHelloWorlds extends JViewLegacy
{
   /**
    * Display the Hello World view
    *
    * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
    *
    * @return  void
    */
   function display($tpl = null)
   {
      // Get application
      $app = JFactory::getApplication();
      $context = "helloworld.list.admin.helloworld";

      // Get data from the model
      $this->items = $this->get('Items');
      $this->pagination = $this->get('Pagination');
      $this->state = $this->get('State');
      $this->filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'greeting', 'cmd');
      $this->filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
      $this->filterForm = $this->get('FilterForm');
      $this->activeFilters = $this->get('ActiveFilters');

      // What Access Permissions does this user have? What can (s)he do?
      $this->canDo = HelloWorldHelper::getActions();

      // Check for errors.
      if (count($errors = $this->get('Errors'))) {
         JError::raiseError(500, implode('<br />', $errors));

         return false;
      }


      // Set the submenu
      HelloWorldHelper::addSubmenu('helloworlds');
      $this->sidebar = JHtmlSidebar::render();

      // Set the toolbar and number of found items
      $this->addToolBar();

      // Display the template
      parent::display($tpl);

      // Set the document
      $this->setDocument();
   }

   /**
    * Add the page title and toolbar.
    *
    * @return  void
    *
    * @since   1.6
    */
   protected function addToolBar()
   {
      //You can find other classic backend actions
      // in the administrator/includes/toolbar.php file of your Joomla installation.

      $title = JText::_('COM_HELLOWORLD_MANAGER_HELLOWORLDS');

      if ($this->pagination->total) {
         $title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
      }

      JToolBarHelper::title($title, 'helloworld');

      if ($this->canDo->get('core.create'))
      {
         JToolBarHelper::addNew('helloworld.add', 'JTOOLBAR_NEW');
      }
      if ($this->canDo->get('core.edit'))
      {
         JToolBarHelper::editList('helloworld.edit', 'JTOOLBAR_EDIT');
      }
      //if ($this->canDo->get('core.edit.state'))
      {
         JToolbarHelper::publish('helloworlds.publish', 'JTOOLBAR_PUBLISH', true);
         JToolbarHelper::unpublish('helloworlds.unpublish', 'JTOOLBAR_UNPUBLISH', true);
         JToolbarHelper::archiveList('helloworlds.archive');
      }

      if ($this->state->get('filter.published') == -2 && $this->canDo->get('core.delete'))
      {
         JToolbarHelper::deleteList('', 'helloworlds.delete', 'JTOOLBAR_EMPTY_TRASH');
      } else//if ($this->canDo->get('core.edit.state'))
      {
         JToolbarHelper::trash('helloworlds.trash');
      }

      if ($this->canDo->get('core.admin'))
      {
         JToolBarHelper::divider();
         JToolBarHelper::preferences('com_helloworld');
      }
   }

   /**
    * Method to set up the document properties
    *
    * @return void
    */
   protected function setDocument()
   {
      $document = JFactory::getDocument();
      $document->setTitle(JText::_('COM_HELLOWORLD_ADMINISTRATION'));
   }
}