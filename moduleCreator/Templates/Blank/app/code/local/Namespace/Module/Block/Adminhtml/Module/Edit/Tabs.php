<?php

class <Namespace>_<Module>_Block_Adminhtml_<Module>_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('<module>_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('<module>')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('<module>')->__('Item Information'),
          'title'     => Mage::helper('<module>')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('<module>/adminhtml_<module>_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}