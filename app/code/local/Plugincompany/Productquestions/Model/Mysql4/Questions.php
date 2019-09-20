<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Model_Mysql4_Questions extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("productquestions/questions", "question_id");
    }
}