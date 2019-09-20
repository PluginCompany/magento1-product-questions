<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */

/**
 * Class Plugincompany_Productquestions_Adminhtml_QuestionsController
 */
class Plugincompany_Productquestions_Adminhtml_Productquestions_QuestionsController extends Mage_Adminhtml_Controller_Action
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

    /**
     * Load question in editor
     */
    public function editAction()
    {
        $this->_title($this->__("Productquestions"));
        $this->_title($this->__("Questions"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("productquestions/questions")->load($id);
        if ($model->getId()) {
            Mage::register("questions_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("productquestions/questions");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Questions Manager"), Mage::helper("adminhtml")->__("Questions Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Questions Description"), Mage::helper("adminhtml")->__("Questions Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->_addContent($this->getLayout()->createBlock("productquestions/adminhtml_questions_edit"))->_addLeft($this->getLayout()->createBlock("productquestions/adminhtml_questions_edit_tabs"));
            $this->renderLayout();
        }
        else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("productquestions")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    /**
     * Saves product question but does not send notification
     */
    public function saveAction()
    {
        $post_data=$this->getRequest()->getPost();
        if ($post_data) {
            try {
                $model = Mage::getModel("productquestions/questions")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"));
                if(!$model->getData('date_answered')){
                    $currentDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
                    $model->setData('date_answered',$currentDate);
                }
                $store_ids=$post_data['store_ids'];
                $store_id = implode(',',$store_ids);
                $model->setStoreIds($store_id);

                $model->save();

                $id = $this->getRequest()->getParam("id");

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Question $id was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setQuestionsData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }

                if ($customerId = $this->getRequest()->getParam("customer_id")) {
                    $this->_redirect("adminhtml/customer/edit", array("id" => $customerId));
                    return;
                }

                $this->_redirect("*/*/");
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setQuestionsData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }

        }
        $this->_redirect("*/*/");
    }


    /**
     * Deletes product question by id
     */
    public function deleteAction()
    {
        if( $this->getRequest()->getParam("id") > 0 ) {
            try {
                $model = Mage::getModel("productquestions/questions");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));


                $this->_redirect("*/*/");
            }
            catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }


    /**
     * Deletes product questions by IDs
     */
    public function massRemoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('question_ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("productquestions/questions");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        }
        catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }

        if ($customerId = $this->getRequest()->getParam("customer_id")) {
            $this->_redirect("adminhtml/customer/edit", array("id" => $customerId));
            return;
        }

        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'questions.csv';
        $grid       = $this->getLayout()->createBlock('productquestions/adminhtml_questions_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'questions.xml';
        $grid       = $this->getLayout()->createBlock('productquestions/adminhtml_questions_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    /**
     * Notifies customer but does not update question
     */
    public function notifyCustomerAction()
    {
        $question_id 	= Mage::app()->getRequest()->getParam('id');
        $question 		= Mage::getModel('productquestions/questions')->load($question_id);

        try {
            //send e-mail
            $this->sendMail($question);
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send e-mail.');
            $this->_redirect('');
            return $this;
        }

        Mage::getSingleton('core/session')->addSuccess("Customer has been notified for question $question_id");
        $question->setIsNotified(1);
        $question->save();
        $this->_redirect('*/*');
    }

    /**
     * Saves answer and notifies customer
     */
    public function answerAndNotifyCustomerAction()
    {
        $postArr		= Mage::app()->getRequest()->getPost();
        $question_id 	= Mage::app()->getRequest()->getParam('id');
        $question 		= Mage::getModel('productquestions/questions')->load($question_id);
        foreach($postArr as $key=>$value){
            $question->setData($key,$value);
        }
        if(!$question->getData('date_answered')){
            $currentDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
            $question->setData('date_answered',$currentDate);
        }
        $store_ids=$postArr['store_ids'];
        $store_id = implode(',',$store_ids);
        $question->setData('store_ids',$store_id);

        $question->save();

        try {
            $this->sendMail($question);
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send e-mail.');
            $this->_redirect('');
            return $this;
        }

        Mage::getSingleton('core/session')->addSuccess("Question $question_id has been saved and customer is notified.");
        $question->setIsNotified(1);
        $question->save();
        $this->_redirect('*/*');
    }

    /**
     * Sends answer notification e-mail
     *
     * @param $question
     */
    public function sendMail($question){
        $product 		= Mage::getModel('catalog/product')->setStoreId($question->getOriginalstoreid())->load($question->getProductId());
        $moderator 		= $question->getAnsweredBy();
        $customername	= $question->getName();
        $answertext		= $question->getAnswer();
        $storename		= Mage::getModel('core/store')->load($question->getOriginalstoreid())->getName();
        $productName 	= $product->getName();
        $productSku 	= $product->getSku();
        $productUrl		= $product->getProductUrl();
        $variableArray 	= array('{moderator}','{customername}','{productname}','{answertext}','{storename}','{productsku}','{producturl}');
        $replaceArray 	= array($moderator,$customername,$productName,$answertext,$storename,$productSku,$productUrl);

        $sender 		= Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_defineanswermail/sender');
        if(!$sender){
            $sender 	= Mage::getStoreConfig('trans_email/ident_general/email');
        }
        $fromname		= str_replace($variableArray,$replaceArray,Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_defineanswermail/sendername'));
        if(!$fromname){
            $fromname	= $storename;
        }
        $subject 		= str_replace($variableArray,$replaceArray,Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_defineanswermail/subject'));

        $body			= '{{template config_path="design/email/header"}}';
        $body			.= '{{inlinecss file="email-inline.css"}}';
        $body           .= '<table cellpadding="0" cellspacing="0" border="0"><td class="action-content">';
        $body           .= '<p>';
        $body 	 		.= nl2br(str_replace($variableArray,$replaceArray,Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_defineanswermail/content')));
        $body           .= '</p>';
        $body           .= '</td></tr></table>';
        $body			.= '{{template config_path="design/email/footer"}}';

        $toEmail		= $question->getEmail();

        // Start store emulation process
        /** @var $appEmulation Mage_Core_Model_App_Emulation */
        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($question->getOriginalstoreid());

        $mail = Mage::getModel('productquestions/email');
        $mail->setToName($customername);
        $mail->setToEmail($toEmail);
        $mail->setBody($body);
        $mail->setSubject($subject);
        $mail->setFromEmail($sender);
        $mail->setFromName($fromname);
        $mail->setType('html');
        $mail->send();

        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/productquestions');
    }
}

