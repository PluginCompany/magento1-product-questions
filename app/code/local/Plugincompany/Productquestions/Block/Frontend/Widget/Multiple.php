<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */

/**
 * Multiple product question widget block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Productquestions
 * @author      Milan Simek
 */
class Plugincompany_Productquestions_Block_Frontend_Widget_Multiple
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface {

    protected $_htmlTemplate = 'plugincompany/productquestions/widget/multiple.phtml';


    /**
     * Prepare widget
     */
    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $this->setTemplate($this->_htmlTemplate);
        return $this;
    }
}
