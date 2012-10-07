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

class Netz98_Admin_Model_Session extends Mage_Admin_Model_Session
{
    /**
     * Try to login user in admin
     *
     * @param  string $username
     * @param  string $password
     * @param  string $request
     * @return Mage_Admin_Model_User|null
     */
    public function login($username, $password, $request = null)
    {
        if (empty($username) || empty($password)) {
            return;
        }

        try {
            /* @var $user Mage_Admin_Model_User */
            $user = Mage::getModel('admin/user');
            $user->login($username, $password);
            if ($user->getId()) {
                if (Mage::getSingleton('adminhtml/url')->useSecretKey()) {
                    Mage::getSingleton('adminhtml/url')->renewSecretUrls();
                }
                $session = Mage::getSingleton('admin/session');
                $session->setIsFirstVisit(true);
                $session->setUser($user);
                $session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());
                if ($request) {
                    header('Location: ' . $request);
                    exit;
                }
            }
            else {
                Mage::throwException(Mage::helper('adminhtml')->__('Invalid Username or Password.'));
            }
        }
        catch (Mage_Core_Exception $e) {
			die($e->getMessage());
        }

        return $user;
    }
}