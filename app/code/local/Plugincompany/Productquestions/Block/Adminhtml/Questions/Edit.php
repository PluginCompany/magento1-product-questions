<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Block_Adminhtml_Questions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				//print_r(get_class_methods($this));exit;
				$id = Mage::app()->getRequest()->getParam('id');
				//print_r(get_class_methods($this));exit;
				$this->_objectId = "question_id";
				$this->_blockGroup = "productquestions";
				$this->_controller = "adminhtml_questions";
				
				$this->_updateButton("save", "label", Mage::helper("productquestions")->__("Submit Answer"));
				$submit = 'Submit';
				if(Mage::getModel('productquestions/questions')->load($id)->getAnswer()){
					$submit = 'Update';
					$this->_updateButton("save", "label", Mage::helper("productquestions")->__("Update Answer"));
					$this->_addButton("notify", array(
						"label"     => Mage::helper("productquestions")->__("Notify Customer"),
						"onclick"   => "NotifyCustomer()",
						"class"     => "",
					), 0);
				
				}

				$this->_updateButton("delete", "label", Mage::helper("productquestions")->__("Delete Item"));

				
				$this->_addButton("answerandnotify", array(
					"label"     => Mage::helper("productquestions")->__("$submit Answer And Notify Customer"),
					"onclick"   => "answerAndNotifyCustomer()",
					"class"     => "save",
				), 10);


				$this->_formScripts[] = "
							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/njet/');
							}
							function NotifyCustomer(){
								$('edit_form').writeAttribute('action', '".$this->getUrl('adminhtml/productquestions_questions/notifyCustomer', array('id' => $id))."');
								editForm.submit($('edit_form').action);
							}
							function answerAndNotifyCustomer(){
								$('edit_form').writeAttribute('action', '".$this->getUrl('adminhtml/productquestions_questions/answerAndNotifyCustomer', array('id' => $id))."');
								editForm.submit($('edit_form').action);
							}
							
							
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("questions_data") && Mage::registry("questions_data")->getId() ){
					$productId = Mage::registry("questions_data")->getProductId();
					$productName = Mage::getModel('catalog/product')->load($productId)->getName();
				    return Mage::helper("productquestions")->__("Answer Question regarding $productName");

				} 
				else{

				     return Mage::helper("productquestions")->__("Add Item");

				}
		}
		public function getHeaderHtml(){
			$productId 		= Mage::registry("questions_data")->getProductId();
			$product 		= Mage::getModel('catalog/product')->load($productId);
			$productAdmin 	= Mage::getUrl('adminhtml/catalog_product/edit', array('id' => $productId));
			$productUrl		= Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).$product->getUrlPath();
			return parent::getHeaderHtml().'<a href="'.$productUrl.'" target="_blank" style="float:left;clear:left;margin-top:10px;margin-left:23px;">View product on front-end</a><a href="'.$productAdmin.'" target="_blank" style="float:left;margin-left:10px;margin-top:10px;">View product on back-end</a><style type="text/css">.form-buttons {margin-top:15px;</style>';
		
		}

    public function getBackUrl(){
        $customerId = $this->getRequest()->getParam('customer_id');
        if($customerId){
            return $this->getUrl('adminhtml/customer/edit',array('id'=>$customerId));
        }else{
			return $this->getUrl('*/*/index');
		}
    }
}