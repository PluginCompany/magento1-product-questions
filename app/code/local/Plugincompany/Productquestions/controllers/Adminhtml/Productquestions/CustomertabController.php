<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Class Plugincompany_Productquestions_Adminhtml_CustomertabController
 */
class Plugincompany_Productquestions_Adminhtml_Productquestions_CustomertabController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("productquestions/questions")->_addBreadcrumb(Mage::helper("adminhtml")->__("Questions  Manager"),Mage::helper("adminhtml")->__("Questions Manager"));
        return $this;
    }

    /**
     * Grid view
     */
    public function indexAction()
    {
        $this->_title($this->__("Productquestions"));
        $this->_title($this->__("Manager Questions"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function gridAction(){
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('productquestions/adminhtml_customer_edit_tab_grid')->toHtml()
        );
    }
    
    protected function _isAllowed()
    {
    	return Mage::getSingleton('admin/session')->isAllowed('catalog/productquestions');
    }


}