<?php
/**
 * @category   Netz98
 * @package    Netz98_ModuleCreator
 * @author	   Daniel Nitz <d.nitz@netz98.de>
 * @copyright  Copyright (c) 2008-2009 netz98 new media GmbH (http://www.netz98.de)
 * 			   Credits for blank files go to alistek, Barbanet (contributer), Somesid (contributer) from the community:
 * 			   http://www.magentocommerce.com/wiki/custom_module_with_custom_database_table
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * $Id$
 */

class Templates_Blank_Config extends Netz98_Templates_Config_Abstract
{
	protected $_name = 'Blank News Module';
	
	public function getFromFiles()
	{
		return array(
		    'Templates/'.$this->_vars['template'].'/app/etc/modules/Namespace_Module.xml',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Module.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/controllers/IndexController.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/etc/config.xml',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Model/Module.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Model/Mysql4/Module.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Model/Mysql4/Module/Collection.php',
			'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Model/Status.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/sql/module_setup/mysql4-install-0.1.0.php',
		    'Templates/'.$this->_vars['template'].'/app/design/frontend/interface/theme/layout/module.xml',
		    'Templates/'.$this->_vars['template'].'/app/design/frontend/interface/theme/template/module/module.phtml',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Adminhtml/Module.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Adminhtml/Module/Edit.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Adminhtml/Module/Grid.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Adminhtml/Module/Edit/Form.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Adminhtml/Module/Edit/Tabs.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Block/Adminhtml/Module/Edit/Tab/Form.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/controllers/Adminhtml/ModuleController.php',
		    'Templates/'.$this->_vars['template'].'/app/code/local/Namespace/Module/Helper/Data.php',
			'Templates/'.$this->_vars['template'].'/app/design/adminhtml/interface/theme/layout/module.xml',
		);
	}

	public function getToFiles()
	{
		return array(
		    'app/etc/modules/'.$this->_vars['capNamespace'].'_'.$this->_vars['capModule'].'.xml',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/'.$this->_vars['capModule'].'.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/controllers/IndexController.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/etc/config.xml',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Model/'.$this->_vars['capModule'].'.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Model/Mysql4/'.$this->_vars['capModule'].'.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Model/Mysql4/'.$this->_vars['capModule'].'/Collection.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Model/Status.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/sql/'.$this->_vars['lowModule'].'_setup/mysql4-install-0.1.0.php',
		    'app/design/frontend/'.$this->_vars['interface'].'/'.$this->_vars['theme'].'/layout/'.$this->_vars['lowModule'].'.xml',
		    'app/design/frontend/'.$this->_vars['interface'].'/'.$this->_vars['theme'].'/template/'.$this->_vars['lowModule'].'/'.$this->_vars['lowModule'].'.phtml',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/Adminhtml/'.$this->_vars['capModule'].'.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/Adminhtml/'.$this->_vars['capModule'].'/Edit.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/Adminhtml/'.$this->_vars['capModule'].'/Grid.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/Adminhtml/'.$this->_vars['capModule'].'/Edit/Form.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/Adminhtml/'.$this->_vars['capModule'].'/Edit/Tabs.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Block/Adminhtml/'.$this->_vars['capModule'].'/Edit/Tab/Form.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/controllers/Adminhtml/'.$this->_vars['capModule'].'Controller.php',
		    'app/code/local/'.$this->_vars['capNamespace'].'/'.$this->_vars['capModule'].'/Helper/Data.php',
		    'app/design/adminhtml/'.$this->_vars['interface'].'/'.$this->_vars['theme'].'/layout/'.$this->_vars['lowModule'].'.xml'
		);
	}
}