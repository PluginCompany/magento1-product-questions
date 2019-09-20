<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */

/**
 * Class Plugincompany_Productquestions_Block_Adminhtml_Questions_Edit_Tab_Form
 */
class Plugincompany_Productquestions_Block_Adminhtml_Questions_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * init form fields
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("productquestions_form", array("legend"=>Mage::helper("productquestions")->__("Question Details")));

        $fieldset->addField("name", "text", array(
            "label" => Mage::helper("productquestions")->__("Customer name"),
            "name" => "name",
        ));

        $fieldset->addField("email", "text", array(
            "label" => Mage::helper("productquestions")->__("Customer e-mail address"),
            "name" => "email",
        ));

        $fieldset->addField("question", "editor", array(
            "label" => Mage::helper("productquestions")->__("Question"),
            "name" => "question",
            'style'     => 'height:15em',
            'config'    => $this->getWysiwygConfig(),
            'wysiwyg'   => true,
        ));

        $fieldset->addField("answer", "editor", array(
            "label" => Mage::helper("productquestions")->__("Answer"),
            "name" => "answer",
            'style'     => 'height:15em',
            'config'    => $this->getWysiwygConfig(),
            'wysiwyg'   => true,
        ));

        $fieldset->addField("answered_by", "text", array(
            "label" => Mage::helper("productquestions")->__("Answered by"),
            "name" => "answered_by",
            "class" => "required-entry",
            "required" => true,
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
        );

        $fieldset->addField('date_asked', 'text', array(
            'label'        => Mage::helper('productquestions')->__('Question submitted on'),
            'name'         => 'date_asked',
            'time' => true,
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $dateFormatIso,
            'disabled'		=> 'disabled'
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
        );

        $fieldset->addField('date_answered', 'text', array(
            'label'        => Mage::helper('productquestions')->__('Question answered on'),
            'name'         => 'date_answered',
            'time' => true,
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'format'       => $dateFormatIso,
            'disabled'		=> 'disabled'
        ));

        $fieldset->addField('approved', 'select', array(
            'label'     => Mage::helper('productquestions')->__('Approved'),
            'values'   => Plugincompany_Productquestions_Block_Adminhtml_Questions_Grid::getValueArray15(),
            'name' => 'approved',
        ));

        $fieldset->addField('private', 'select', array(
            'label'     => Mage::helper('productquestions')->__('Private question'),
            'name' => 'private',
            'disabled' => true,
            'options' => array(
                0 => 'No',
                1 => 'Yes'
            )

        ));

        $field = $fieldset->addField('store_ids', 'multiselect', array(
            'name'     => 'store_ids[]',
            'label'     => Mage::helper('catalogrule')->__('Visible in stores'),
            'title'     => Mage::helper('catalogrule')->__('Visible in stores'),
            'required' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
        ));



        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);



        $values = array();
        if (Mage::getSingleton("adminhtml/session")->getQuestionsData())
        {
            $values = Mage::getSingleton("adminhtml/session")->getQuestionsData();
            Mage::getSingleton("adminhtml/session")->setQuestionsData(null);
        }
        elseif(Mage::registry("questions_data")) {
            $values = Mage::registry("questions_data")->getData();
        }
        if(empty($values['answered_by'])) {
            $values['answered_by'] = Mage::getStoreConfig('plugincompany_questions/answer_settings/default_name');
        }

        $form->setValues($values);
        return parent::_prepareForm();
    }

    private function getWysiwygConfig()
    {
        $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $config = new Varien_Object($config->getData());
        $config->addData(array(
            'add_variables' => false,
            'add_widgets' => false,
            'hidden' => false,
            'enabled' => true,
            'plugins' => array(),
            'widget_plugin_src' => false
        ));
        return $config;
    }
}
