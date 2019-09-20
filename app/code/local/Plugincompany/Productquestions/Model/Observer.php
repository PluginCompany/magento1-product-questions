<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */

class Plugincompany_Productquestions_Model_Observer {

    public function addCustomerTab(Varien_Event_Observer $observer){
        $block = $observer->getEvent()->getBlock();
        if($block instanceof Mage_Adminhtml_Block_Customer_Edit_Tabs){
            $block->addTab('product_questions', array(
                'label'        => Mage::helper('productquestions')->__('Product Questions'),
                'title'        => Mage::helper('productquestions')->__('Product Questions'),
                'content'     => $block->getLayout()->createBlock('productquestions/adminhtml_customer_edit_tab_grid')->toHtml(),
                'after'     => 'tags'
            ));
        }
    }
}