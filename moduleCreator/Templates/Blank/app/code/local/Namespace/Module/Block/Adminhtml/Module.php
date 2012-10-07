<?php
class <Namespace>_<Module>_Block_Adminhtml_<Module> extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_<module>';
    $this->_blockGroup = '<module>';
    $this->_headerText = Mage::helper('<module>')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('<module>')->__('Add Item');
    parent::__construct();
  }
}