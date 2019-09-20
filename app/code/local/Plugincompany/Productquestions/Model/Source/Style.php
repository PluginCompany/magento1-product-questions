<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Model_Source_Style {

    public function toOptionArray()
    {
        $options = array();
        foreach($this->toArray() as $k => $v){
            $options[] = array(
                'label' => $v,
                'value' => $k
            );
        }
        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
             1 => 'Modern',
             0 => 'Classic',
        );
    }
}