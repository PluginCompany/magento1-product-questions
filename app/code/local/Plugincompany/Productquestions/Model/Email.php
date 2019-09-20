<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Model_Email extends Mage_Core_Model_Email {

    public function send()
    {
        if (Mage::getStoreConfigFlag('system/smtp/disable')) {
            return $this;
        }


        $mail = Mage::getModel('core/email_template');

        //send to customer
        $mail
            ->setTemplateText($this->getBody())
            ->setTemplateSubject($this->getSubject())
            ->setSenderName($this->getFromName())
            ->setSenderEmail($this->getFromEmail())
            ->setReplyTo($this->getFromEmail())
        ;
        $mail->send($this->getToEmail(),$this->getToName());

        //send copy to admin
        if($this->getBcc()){
            $mail
                ->setTemplateText($this->getBody())
                ->setTemplateSubject($this->getSubject())
                ->setSenderName($this->getToName())
                ->setSenderEmail($this->getFromEmail())
                ->setReplyTo($this->getToEmail())
            ;
            $mail->send($this->getBcc(),$this->getFromName());
        }

        return $this;
    }

}