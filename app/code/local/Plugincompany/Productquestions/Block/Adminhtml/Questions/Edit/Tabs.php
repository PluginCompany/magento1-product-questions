<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Block_Adminhtml_Questions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("questions_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("productquestions")->__("Answer Question"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
                    "label" => Mage::helper("productquestions")->__("Question Details"),
                    "title" => Mage::helper("productquestions")->__("Question Details"),
                    "content" => $this->getLayout()->createBlock("productquestions/adminhtml_questions_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
