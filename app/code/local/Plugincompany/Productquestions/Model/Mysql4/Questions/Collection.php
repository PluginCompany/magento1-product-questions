<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
    class Plugincompany_Productquestions_Model_Mysql4_Questions_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {

		public function _construct(){
			$this->_init("productquestions/questions");
		}
    }
	 