<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Class Plugincompany_Productquestions_FrontendController
 */
class Plugincompany_Productquestions_IndexController
extends Mage_Core_Controller_Front_Action{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function preDispatch()
    {
        parent::preDispatch();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }
}
