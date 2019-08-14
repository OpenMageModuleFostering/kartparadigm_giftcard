<?php

class Kartparadigm_Giftcard_Model_Quote extends Mage_Sales_Model_Quote
{
    public function isVirtual()
    {
    }

    public function merge(Mage_Sales_Model_Quote $quote)
    {
        parent::merge($quote);
        if ($quote->getGiftcardCode()) {
            $this->setGiftcardCode($quote->getGiftcardCode());
        }
        return $this;
    }
}
