<?php

class Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("questionsGrid");
				$this->setDefaultSort("question_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

        protected function _initCollection(){
            $collection = Mage::getModel("productquestions/questions")->getCollection();
            $collection->addExpressionFieldToSelect("answered","IF(main_table.date_answered IS NULL,1,2)",array());
            return $collection;
        }

		protected function _prepareCollection()
		{
				$this->setCollection($this->_initCollection());
				return parent::_prepareCollection();
		}

		protected function _prepareColumns()
		{
				$this->addColumn("question_id", array(
				"header" => Mage::helper("productquestions")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "question_id",
				));
				
				$this->addColumn("product_id", array(
				"header" => Mage::helper("productquestions")->__("Product ID"),
				"index" => "product_id",
				"width" => "50px",
				"type" => "number",
				"align" =>"right",
				));
				
				$this->addColumn("name", array(
				"header" => Mage::helper("productquestions")->__("Name"),
				"index" => "name",
				"type" => "text",
				));
				
				$this->addColumn("email", array(
				"header" => Mage::helper("productquestions")->__("E-mail"),
				"index" => "email",
				"type" => "text",
				));
				
				$this->addColumn("question", array(
				"header" => Mage::helper("productquestions")->__("Question"),
				"index" => "question",
				"type" => "text",
				));
				
				$this->addColumn("answered_by", array(
				"header" => Mage::helper("productquestions")->__("Answered by"),
				"index" => "answered_by",
				"type" => "text",
				));

				$this->addColumn("answer", array(
				"header" => Mage::helper("productquestions")->__("Answer"),
				"index" => "answer",
				"type" => "text",
				));
                
				$this->addColumn('question_date', array(
					'header'    => Mage::helper('productquestions')->__('Question date'),
					'index'     => 'date_asked',
					'type'      => 'datetime',
					"width" => "160px",
				));
				
				$this->addColumn('answer_date', array(
					'header'    => Mage::helper('productquestions')->__('Answer date'),
					'index'     => 'date_answered',
					'type'      => 'datetime',
					"width" => "160px",
				));

                $this->addColumn('answered', array(
                    'header'    => Mage::helper('productquestions')->__('Answered'),
                    'index'     => 'answered',
                    "width"     => "50px",
                    'type'      => 'options',
                    'options'=>Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid::getOptionArray17(),
                    'filter_condition_callback' => array($this,'_answeredFilter')
                ));

				$this->addColumn('approved', array(
				'header' => Mage::helper('productquestions')->__('Approved'),
				'index' => 'approved',
				"width" => "50px",
				'type' => 'options',
				'options'=>Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid::getOptionArray15(),
				));
				
				$this->addColumn('notified', array(
				'header' => Mage::helper('productquestions')->__('Notified'),
				'index' => 'is_notified',
				"width" => "50px",
				'type' => 'options',
				'options'=>Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid::getOptionArray16(),
				));

            $this->addColumn('private', array(
                'header' => Mage::helper('productquestions')->__('Private'),
                'index' => 'private',
                "width" => "50px",
                'type' => 'options',
                'options'=> array(
                    0 => 'No',
                    1 => 'Yes'
                ),
            ));

			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('question_id');
			$this->getMassactionBlock()->setFormFieldName('question_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_questions', array(
					 'label'=> Mage::helper('productquestions')->__('Remove Questions'),
					 'url'  => $this->getUrl('*/*/massRemove'),
					 'confirm' => Mage::helper('productquestions')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray15()
		{
            $data_array=array(); 
			$data_array[0]='Yes';
			$data_array[1]='No';
            return($data_array);
		}
		static public function getValueArray15()
		{
            $data_array=array();
			foreach(Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid::getOptionArray15() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray16()
		{
            $data_array=array(); 
			$data_array[1]='Yes';
			$data_array[0]='No';
            return($data_array);
		}
		static public function getValueArray16()
		{
            $data_array=array();
			foreach(Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid::getOptionArray16() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}

    static public function getOptionArray17()
    {
        $data_array=array();
        $data_array[2]='Yes';
        $data_array[1]='No';
        return($data_array);
    }

        protected function _answeredFilter($collection, $column)
        {
            if (!$value = $column->getFilter()->getValue()) {
                return $this;
            }

            if($value == 2){
                $collection
                    ->addFieldToFilter('date_answered', array('notnull' => true));
            }else{
                $collection
                    ->addFieldToFilter('date_answered', array('null' => true));
            }

            return $this;
        }
		

}