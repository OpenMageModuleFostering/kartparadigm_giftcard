<?php
class Kartparadigm_Giftcard_Block_Adminhtml_Groupsend_Edit_Form extends Mage_Adminhtml_Block_Widget_Form

{
    protected function _prepareForm()
    {
        Mage::log('Prepare Form');
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/send', array(
                'id' => $this->getRequest()->getParam('id')
            )) ,
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $form->setUseContainer(true);
        $this->setForm($form);
        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData();
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        }
        elseif (Mage::registry('registry_data')) $data = Mage::registry('registry_data')->getData();
        $fieldset = $form->addFieldset('registry_form', array(
            'legend' => Mage::helper('kartparadigm_giftcard')->__('Gift Card information')
        ));
        $fieldset->addField('giftcard_name', 'text', array(
            'label' => Mage::helper('kartparadigm_giftcard')->__('Giftcard Name') ,
            'class' => 'required-entry',
            'required' => true,
            'name' => 'giftcard_name',
        ));
        $fieldset->addField('giftcard_val', 'text', array(
            'label' => Mage::helper('kartparadigm_giftcard')->__('Giftcard Amount') ,
            'class' => 'required-entry',
            'required' => true,
            'name' => 'giftcard_val',
        ));
        $customerGroupModel = new Mage_Customer_Model_Group();
        $customerGroups = array();
        $allCustomerGroups = $customerGroupModel->getCollection()->addFieldToFilter('customer_group_id', array(
            'nin' => array(
                0
            )
        ))->toOptionHash();
        $fieldset->addField('customer_groups', 'select', array(
            'name' => 'customer_groups',
            'label' => Mage::helper('kartparadigm_giftcard')->__('Customer Group') ,
            'class' => 'required-entry',
            'required' => true,
            'options' => $allCustomerGroups,
        ));
        $fieldset->addField('giftcard_status', 'select', array(
            'name' => 'giftcard_status',
            'label' => Mage::helper('kartparadigm_giftcard')->__('Giftcard Status') ,
            'class' => 'required-entry',
            'required' => true,
            'options' => array(
                0 => Mage::helper('kartparadigm_giftcard')->__('Inactive') ,
                1 => Mage::helper('kartparadigm_giftcard')->__('Active') ,
            ) ,
        ));
        $fieldset->addField('store_id', 'select', array( //can use multiselect
            'name' => 'store_id[]',
            'label' => Mage::helper('cms')->__('Store View') ,
            'title' => Mage::helper('cms')->__('Store View') ,
            'required' => true,
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true) ,
        ));
        $fieldset->addField('giftcard_currency', 'select', array(
            'name' => 'giftcard_currency',
            'label' => Mage::helper('kartparadigm_giftcard')->__('Currency') ,
            'class' => 'required-entry',
            'required' => true,
            'values' => Mage::getSingleton('adminhtml/system_config_source_currency')->toOptionArray(false) ,
            'value' => Mage::app()->getStore()->getDefaultCurrency()->getCurrencyCode() ,
        ));
        // date field
        $fieldset->addField('giftcard_msg', 'textarea', array(
		'label'=> Mage::helper('kartparadigm_giftcard')->__('Giftcard Message'),
		'class'=> 'required-entry',
		'required' => true,
		'name'=> 'giftcard_msg',
		));


        $arr = array(
            'name' => 'expiry_date',
            'label' => Mage::helper('kartparadigm_giftcard')->__('Expiry Date') ,
            'required' => true,
            'image' => $this->getSkinUrl('images/grid-cal.gif') ,
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) ,
        );
        if (version_compare(Mage::getVersion() , '1.1.8', '>=')) {
            $arr['input_format'] = Varien_Date::DATE_INTERNAL_FORMAT;
        }
        $expireAt = $fieldset->addField('expiry_date', 'date', $arr);
        $form->setValues($data);
        return parent::_prepareForm();
    }
}
