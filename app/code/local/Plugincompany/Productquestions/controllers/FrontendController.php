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
class Plugincompany_Productquestions_FrontendController
extends Mage_Core_Controller_Front_Action{

    /**
     * Submits user question
     */
    public function submitquestionAction(){
        if (!$this->getRequest()->isPost()){
            return false;
        }
        try{
            $params = Mage::app()->getRequest()->getParams();
            $validationErrors = array();
            //strip html tags
            $reCaptchaRes = false;
            if (isset($params['g-recaptcha-response'])) {
                $reCaptchaRes = $params['g-recaptcha-response'];
                unset($params['g-recaptcha-response']);
            }
            if(!$this->isCaptchaOk($reCaptchaRes)){
                $response = '<ul class="messages" id="questionmsg" style="display:none;"><li class="error-msg"><ul><li><span>' . $this->__("Captcha Error - Please check captcha") . '</span></li></ul></li></ul><script type="text/javascript">$("questionmsg").appear();$("questionFade").appear();</script>';
                return $this->getResponse()->setBody($response);
            }

            foreach($params as $k => $v){
                $v = htmlspecialchars(strip_tags($v));
                if ($v == ""){
                    $validationErrors[] = $this->__("%s is empty", uc_words(substr($k, strpos($k, "_") + 1)));
                }
                $params[$k] = $v;
            }
            if (!empty($validationErrors)){
                $response = '<ul class="messages" id="questionmsg" style="display:none;"><li class="error-msg"><ul><li><span>' . implode("<br>", $validationErrors) . '</span></li></ul></li></ul><script type="text/javascript">$("questionmsg").appear();</script>';
                return $this->getResponse()->setBody($response);
            }
            $currentDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
            $storeId = Mage::app()->getStore()->getId();
            $customer_id = Mage::getSingleton('customer/session')->getCustomerId();

                //load question model and set data
                $question = Mage::getModel('productquestions/questions');
                $question->setData('question',$params['question_question']);
                $question->setData('name',$params['question_name']);
                $question->setData('product_id',$params['question_product_id']);
                $question->setData('email',$params['question_email']);
                $question->setData('date_asked',$currentDate);
                $question->setData('store_ids',$storeId);
                $question->setData('originalstoreid',$storeId);
                $question->setData('customer_id',$customer_id);
                if (isset($params['question_private'])) {
                    $question->setPrivate(1);
                }
                if(Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_group/autoapprove')){
                    $question->setData('approved',0);
                }
                //save question
                $question->save();

            $canSendMail    = Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_definemail/sendquestiontoclient');

            if ($canSendMail) {
                $product 		= Mage::getModel('catalog/product')->setStoreId($storeId)->load($question->getProductId());

                $customername	= $question->getName();
                $storename		= Mage::getModel('core/store')->load($storeId)->getName();
                $productName 	= $product->getName();
                $productSku 	= $product->getSku();
                $productUrl		= $product->getProductUrl();

                //define variables
                $variableArray 	= array('{customername}','{productname}','{storename}','{productsku}','{producturl}','{questiontext}');
                $replaceArray 	= array($customername,$productName,$storename,$productSku,$productUrl,$question->getQuestion());

                //replace variables in subject
                $subject 		= nl2br(str_replace($variableArray,$replaceArray,Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_definemail/subject')));

                //replace variables in fromname
                $fromname		= str_replace($variableArray,$replaceArray,Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_definemail/sendername'));

                $sender 		= Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_definemail/sender');
                if(!$sender)
                {
                    $sender 	= Mage::getStoreConfig('trans_email/ident_general/email');
                }

                if(!$fromname){
                    $fromname	= $storename;
                }
                $toEmail		= $question->getEmail();

                $body			= '{{template config_path="design/email/header"}}';
                $body			.= '{{inlinecss file="email-inline.css"}}';
                $body           .= '<table cellpadding="0" cellspacing="0" border="0"><td class="action-content">';
                $body           .= '<p>';
                $body 	 		.= nl2br(str_replace($variableArray,$replaceArray,Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_definemail/content')));
                $body           .= '</p>';
                $body           .= '</td></tr></table>';
                $body			.= '{{template config_path="design/email/footer"}}';

                $mail = Mage::getModel('productquestions/email');
                $mail->setToName($customername);
                $mail->setToEmail($toEmail);
                $mail->setBody($body);
                $mail->setSubject($subject);
                $mail->setFromEmail($sender);
                $mail->setFromName($fromname);
                $mail->setBcc(Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_definemail/sendquestionemail'));
                $mail->setType('html');
                $mail->send();
            }

            return $this->getResponse()->setBody(
                '<ul class="messages" id="questionmsg" style="display:none;"><li class="success-msg"><ul><li><span>' . $this->__('Your question has been received and will be answered shortly') . '</span></li></ul></li></ul><script type="text/javascript">$("questionmsg").appear();</script>'
            );

        }catch(Exception $e){
            //error message
            return $this->getResponse()->setBody(
                '<ul class="messages" id="questionmsg" style="display:none;"><li class="error-msg"><ul><li><span>' . $this->__('Something went wrong submitting the question. Please refresh the page and try again.') . '</span></li></ul></li></ul><script type="text/javascript">$("questionmsg").appear();</script>'
            );
        }
	 }

    /**
     * page html for AJAX loading
     * params page and productid
     */
    public function pageAction(){
		$page = Mage::app()->getRequest()->getParam('page');
		$productId = Mage::app()->getRequest()->getParam('productid');
		$collection = Mage::getModel('productquestions/questions')->getQuestionsPage($page,$productId);
		$response = '';
		foreach ($collection as $question){
            $response .= $this
                ->getLayout()
                ->createBlock('core/template')
                ->setTemplate('plugincompany/productquestions/questions/page.phtml')
                ->setQuestion($question)
                ->toHtml();
		}
		$this->getResponse()->setBody($response);
	 }

    public function voteAction()
    {
        $questionID = (int)$this->getRequest()->getParam('qid');
        $direction = $this->getRequest()->getParam('dir');
        $type = $this->getRequest()->getParam('type');

        if ($direction == 'up') {
            $attr = 'upvotes';
        }elseif ($direction == 'down') {
            $attr = 'downvotes';
        }

        if ($type == 'answer') {
            $attr = 'answer_' . $attr;
        }

        if ($attr && $questionID) {
            $question = Mage::getModel('productquestions/questions')->load($questionID);
            if (!$question) {
                return false;
            }
            $votes = $question->getData($attr);

            if (!$votes) {
                $votes = 0;
            }

            $votes++;
            $question->setData($attr, $votes)->save();
            $this->getResponse()->setBody($votes);
        }
    }

    public function isCaptchaOk($response)
    {
        if(!Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_group/enable_captcha')){
            return true;
        }
        $privateKey = Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_group/recaptcha_private_key');
        try{
            //validate using recaptcha api
            $fields  = 'secret=' . $privateKey;
            $fields .= '&response=' . $response;
            $fields .= '&remoteip=' . Mage::helper('core/http')->getRemoteAddr(true);
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_POST, 3);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);
            return $result->success;

        }catch(Exception $e){
            Mage::log($e->getMessage(),null,'product_questions.log',true);
            return false;
        }

    }


}
