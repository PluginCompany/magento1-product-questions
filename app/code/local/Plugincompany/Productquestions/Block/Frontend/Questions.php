<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Block_Frontend_Questions extends Mage_Core_Block_Template {

    private $totalQuestionsCount;

    public function getQuestions($page = 1){
		$productId = Mage::registry('current_product')->getEntityId();
		$collection = Mage::getModel('productquestions/questions')->getQuestionsPage($page,$productId);
		return $collection;
	}
	
	public function getPageCount(){
		$productId = Mage::registry('current_product')->getEntityId();
		$collection = Mage::getModel('productquestions/questions')->getQuestionsPage(1,$productId);
		$pageCount = $collection->getLastPageNumber();
		return $pageCount;
	}

    public function getTotalQuestionsCount()
    {
        if(!isset($this->totalQuestionsCount)){
            $this->initTotalQuestionsCount();
        }
        return $this->totalQuestionsCount;
	}

	private function initTotalQuestionsCount()
    {
        $productId = Mage::registry('current_product')->getEntityId();
        $this->totalQuestionsCount = Mage::getModel('productquestions/questions')
            ->getQuestionsByProductId($productId)
            ->count()
            ;
        return $this;
    }

    public function getEmailAddr(){
        if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            return Mage::getSingleton('customer/session')->getCustomer()->getData('email');
        } else {return "";}
    }

    public function getUserName(){
        if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            return Mage::getSingleton('customer/session')->getCustomer()->getName();
        } else {return "";}
    }

    public function getReCaptchaKey()
    {
        return Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_group/recaptcha_key');
    }

    public function isCaptchaEnabled()
    {
        return Mage::getStoreConfigFlag('plugincompany_questions/plugincompany_questions_group/enable_captcha');
    }

    public function allowPrivateQuestions()
    {
        return Mage::getStoreConfigFlag('plugincompany_questions/plugincompany_questions_group/allow_private');
    }

}
