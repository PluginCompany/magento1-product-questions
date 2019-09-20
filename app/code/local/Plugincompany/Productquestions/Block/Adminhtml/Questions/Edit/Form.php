<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Block_Adminhtml_Questions_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{
				$form = new Varien_Data_Form(array(
				"id" => "edit_form",
				"action" => $this->getUrl("*/*/save",
                    array(
                        "id" => $this->getRequest()->getParam("id"),
                        'customer_id'=>$this->getRequest()->getParam('customer_id')
                    )),
				"method" => "post",
				"enctype" =>"multipart/form-data",
				)
				);
				$form->setUseContainer(true);
				$this->setForm($form);
				return parent::_prepareForm();
		}
}
