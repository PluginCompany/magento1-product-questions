<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
/**
 * Class Plugincompany_Productquestions_Model_Questions
 */
class Plugincompany_Productquestions_Model_Questions extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("productquestions/questions");

    }

    /**
     * Get question collection by product ID
     *
     * @param $product_id
     * @return Plugincompany_Productquestions_Model_Mysql4_Questions_Collection
     */
    public function getQuestionsByProductId($product_id){
		$collection = $this->getCollection()
									->addFieldToFilter('product_id',$product_id)
									->addFieldToFilter('approved',0)
                                    ->addFieldToFilter('private',0)
        ;
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection->getSelect()->where("FIND_IN_SET($storeId,store_ids)");

		return $collection;
	}

    /**
     * Get question collection page by page number and product id
     *
     * @param int $page
     * @param $product_id
     * @return Plugincompany_Productquestions_Model_Mysql4_Questions_Collection
     */
    public function getQuestionsPage($page = 1,$product_id){
		$pagesize 	= Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_group/questionsperpage');
		$collection = $this->getQuestionsByProductId($product_id,$page);
		$sort = Mage::getStoreConfig('plugincompany_questions/plugincompany_questions_group/sorting');
		if(!$sort){$sort='upvotes';}
		$collection
			->addOrder($sort,'DESC');
		$collection->setPageSize($pagesize)->setCurPage($page);
		return $collection;
	}
    /**
     * Get question (process variables with template processor in frontend)
     *
     * @return string
     */
    public function getQuestion()
    {
        $question = $this->getData("question");
        if (!Mage::app()->getStore()->isAdmin()) {
            $helper = Mage::helper("cms");
            $processor = $helper->getBlockTemplateProcessor();
            $question = $processor->filter($question);
        }
        return $question;
    }
    /**
     * Get question answer (process variables with template processor in frontend)
     *
     * @return string
     */
    public function getAnswer()
    {
        $answer = $this->getData("answer");
        if (!Mage::app()->getStore()->isAdmin()) {
            $helper = Mage::helper("cms");
            $processor = $helper->getBlockTemplateProcessor();
            $answer = $processor->filter($answer);
        }
        return $answer;
    }
}
	 