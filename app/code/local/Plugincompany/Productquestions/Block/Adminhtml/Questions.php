<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Block_Adminhtml_Questions extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_questions";
	$this->_blockGroup = "productquestions";
	$this->_headerText = Mage::helper("productquestions")->__("Manage Product Questions");
	$this->_addButtonLabel = Mage::helper("productquestions")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	}

}