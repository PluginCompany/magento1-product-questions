<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */

class Plugincompany_Productquestions_Block_Adminhtml_Customer_Edit_Tab_Grid
    extends Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid
{

    public function __construct(){
        parent::__construct();
        $this->setId("questionsGridCAccount");
        $this->setUseAjax(true);
        return $this;
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->removeColumn('question_id');
        $this->removeColumn('private');
        $this->removeColumn('notified');
        return $this;

    }

    protected function _initCollection(){
        $collection = parent::_initCollection();
        $collection->addFieldToFilter('customer_id',$this->getCustomerId());
        return $collection;
    }


    public function getCustomerId(){
        return $this->getRequest()->getParam('id');
    }

    public function getGridUrl()
    {
        return $this->getUrl('adminhtml/productquestions_customertab/grid',array('id'=>$this->getCustomerId()));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("adminhtml/productquestions_questions/edit", array("id" => $row->getId(),'customer_id'=>$this->getCustomerId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->getMassactionBlock()->setFormFieldName('question_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $url = $this->getUrl('adminhtml/productquestions_questions/massRemove',array('customer_id'=>$this->getCustomerId(),'form_key'=>$this->getFormKey()));
        $this->getMassactionBlock()->addItem('remove_questions', array(
            'label'=> Mage::helper('productquestions')->__('Remove Questions'),
            'url'  => $url,
            'confirm' => Mage::helper('productquestions')->__('Are you sure?')
        ));
        return $this;
    }
}