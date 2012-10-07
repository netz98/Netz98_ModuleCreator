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

$mageFilename = '../app/Mage.php';

if (!file_exists($mageFilename)) {
    echo $mageFilename." was not found";
    exit;
}

require_once $mageFilename;

Mage::setIsDeveloperMode(true);

ini_set('display_errors', 1);
date_default_timezone_set('Europe/Berlin');

umask(0);
Mage::app();

$session = new Netz98_Admin_Model_Session();
$session->start();

if (!empty($_POST)) {
	if(isset($_POST['form']) && $_POST['form'] == 'login') {
		$request = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$session->login($_POST['username'], $_POST['password'], $request);
	}
}
if(isset($_GET['logout']) && $_GET['logout'] == 'yes') {
	$session->clear();
}
if (!$session->isLoggedIn()) {
	print getHeader()
	    . getLoginBox()
	    . getFooter();
	exit;
}

$root = dirname(__FILE__) . DS;
$shop = null;
define('TEMPLATES_DIR', 'Templates');

//--------------------------------------------------------------

/**
 * Enter description here...
 *
 * @return string
 */
function getLoginBox()
{
	return '
		<div style="width:300px; padding:20px; margin:90px auto !important; background:#f6f6f6;">
			<form method="post" action="'.$_SERVER['PHP_SELF'].'">
			    <h2 class="page-head">Log In</h2>
			    <p><small>Please re-enter your Magento Adminstration Credentials.<br/>Only administrators with full permissions will be able to log in.</small></p>
			    <table class="form-list">
			        <tr><td class="label"><label for="username">Username:</label></td><td class="value"><input id="username" name="username" value=""/></td></tr>
			        <tr><td class="label"><label for="password">Password:</label></td><td class="value"><input type="password" id="password" name="password"/></td></tr>
			        <tr><td></td>
			            <td class="value"><button type="submit">Log In</button></td></tr>
			        </table>
			    <input type="hidden" name="form" value="login" />
			</form>
			</div>
	';
}

/**
 * Enter description here...
 *
 * @param string|array $from
 * @param string|array $to
 * @return boolean
 */
function copyBlankoFiles($from, $to, $shop = null)   
{
    global $root;
    
    if (!is_array($from)) {
        $from = array($from);
    }
    
    if (!is_array($to)) {
        $to = array($to);
    }
    
    if ($shop === null) {
        $shop = $root . 'new/';
        if (!is_dir($shop)) {
            mkdir($shop);
        }
    }
    
    if (count($from) !== count($to)) {
        throw new Exception('Count of from -> to files do not match.');
    }
    
    foreach ($to as $file) {
        $newPath = substr($file, 0, strrpos($file, '/'));
        createFolderPath($newPath, $shop);
    }

    for ($i = 0; $i < count($to); $i++) {
        if (copy($root.$from[$i], $shop.$to[$i]) === false) {
            throw new Exception('Could not copy blanko files.');
        }
    }
    return true;
}

/**
 * Enter description here...
 *
 * @param string|array $paths
 * @return bolean
 */
function createFolderPath($paths, $shop = null)
{
    global $root;
    
    if (!is_array($paths)) {
        $paths = array($paths);
    }

    if ($shop === null) {
        $shop = $root;
    }
    
    foreach ($paths as $path) {
        $folders = explode('/', $path);
        $current = '';
        
        foreach ($folders as $folder) {
            $fp = $current . DIRECTORY_SEPARATOR . $folder;
            if (!is_dir($shop.$fp)) {
                if (mkdir($shop.$fp) === false) {
                    throw new Exception('Could not create new path: '. $shop.$fp);
                }
            }
            $current = $fp;
        }
    }
    return true;
}

/**
 * Enter description here...
 *
 * @param array|string $files
 */
function insertCustomVars($files, $shop = null)
{
    global $root;
    
    if (!is_array($files)) {
        $files = array($files);
    }

    if ($shop === null) {
        $shop = $root . 'new' . DIRECTORY_SEPARATOR;
    }
    
    foreach ($files as $file) {
        $handle = fopen ($shop.$file, 'r+');
        $content = '';
        while (!feof($handle)) {
            $content .= fgets($handle);
        }
        fclose($handle);
        
        $type = strrchr($file, '.');
        switch ($type) {
            case '.xml':
                $content = replaceXml($content);
                break;
            case '.php':
            case '.phtml':
                $content = replacePhp($content);
                break;
            default:
                throw new Exception('Unknown file type found: '.$type);
        }
        $handle = fopen ($shop.$file, 'w');
        fputs($handle, $content);    
        fclose($handle);
    }
}

/**
 * Enter description here...
 *
 * @param string $content
 * @return string
 */
function replacePhp($content)
{
    global $vars;
    
    $search = array(
                    '/<Namespace>/',
                    '/<namespace>/',
                    '/<Module>/',
                    '/<module>/',
   					);
    
    $replace = array(
                    $vars['capNamespace'],
                    $vars['lowNamespace'],
                    $vars['capModule'],
                    $vars['lowModule'],
                    );
    
    return preg_replace($search, $replace, $content);
}

/**
 * Enter description here...
 *
 * @param string $content
 * @return string
 */
function replaceXml($content)
{
    global $vars;
    
    $search = array(
                    '/\[Namespace\]/',
                    '/\[namespace\]/',
                    '/\[Module\]/',
                    '/\[module\]/',
                    );
                    
    $replace = array(
                    $vars['capNamespace'],
                    $vars['lowNamespace'],
                    $vars['capModule'],
                    $vars['lowModule'],
                    );
    
    return preg_replace($search, $replace, $content);
}

/**
 * Enter description here...
 *
 * @param string $dir
 * @return boolean|string
 */
function checkShopRoot($dir)
{
    $dir = replaceDirSeparator($dir);
    if (substr($dir, strlen($dir) - 1, 1) !== DIRECTORY_SEPARATOR) {
        $dir .= DIRECTORY_SEPARATOR;
    }
    if (is_dir($dir . 'app')) {
        return $dir;
    }
    return false;
}

/**
 * Enter description here...
 *
 * @param string $dir
 * @return string
 */
function replaceDirSeparator($dir)
{
    $search = array('\\\\', '/');
    $dir = str_replace($search, DIRECTORY_SEPARATOR, $dir);
    
    return $dir;
}
/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @param unknown_type $module
 * @return boolean
 */
function uninstallModule($dir, $module, $files)
{
	foreach ($files as $file) {
		@unlink($dir . $file);
	}
    if (is_dir($dir.$module)) {
        $folder = rmRecurse($dir.$module);
        $sql = deleteSql($dir, $module);
        if ($folder and $sql) {
            return true;
        }
    }
    return false;
}

/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @return array
 */
function getMagentoDatabaseSettings($dir)
{
    $xml = simplexml_load_file($dir.'app/etc/local.xml', null, LIBXML_NOCDATA);
    
    $settings = array();
    $settings['dbUser'] = (string)$xml->global->resources->default_setup->connection->username;
    $settings['dbHost'] = (string)$xml->global->resources->default_setup->connection->host;
    $settings['dbPassword'] = (string)$xml->global->resources->default_setup->connection->password;
    $settings['dbName'] = (string)$xml->global->resources->default_setup->connection->dbname;
    
    return $settings;
}

/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @param unknown_type $module
 * @return boolean
 */
function deleteSql($dir, $module)
{
    $settings = getMagentoDatabaseSettings($dir);
    $connection = dbConnect($settings);

    $module = preg_replace('/\/$/', '', $module);
    $module = strtolower(substr(strrchr($module, '/'), 1));
    
    $tblPrefix = getTablePrefix($dir);
    
    $sql = "DELETE FROM ".$tblPrefix."core_resource WHERE code = '".$module."_setup'";
    $delete = mysql_query($sql);

    $sql = "DROP TABLE ".$tblPrefix.$module;
    $drop = mysql_query($sql); 
    
    dbDisconnect($connection);
    if ($delete and $drop) {
        return true;
    }
    return false;
}

/**
 * Enter description here...
 *
 * @return unknown
 */
function getTablePrefix($dir)
{
    $xml = simplexml_load_file($dir.'app/etc/local.xml', null, LIBXML_NOCDATA);
    $prefix = (string)$xml->global->resources->db->table_prefix;
    if ($prefix != '') {
        return $prefix.'.';
    }
    return $prefix;
}

/**
 * Enter description here...
 *
 * @param array $settings
 * @return boolean
 */
function dbConnect(array $settings)
{
    $connection = mysql_connect($settings['dbHost'], $settings['dbUser'], $settings['dbPassword']) or die
        ('Could not connect to host.');
    mysql_select_db($settings['dbName']) or die
        ('Database does not exsist.');
    
    return $connection;
}

/**
 * Enter description here...
 *
 * @param unknown_type $connection
 */
function dbDisconnect($connection)
{
    mysql_close($connection);
}

/**
 * http://de3.php.net/manual/de/function.rmdir.php
 * ornthalas at NOSPAM dot gmail dot com
 *
 * @param string $filepath
 * @return unknown
 */
function rmRecurse($filepath)
{
    if (is_dir($filepath) && !is_link($filepath)) {
        if ($dh = opendir($filepath)) {
            while (($sf = readdir($dh)) !== false) {
                if ($sf == '.' || $sf == '..') {
                    continue;
                }
                if (!rmRecurse($filepath.'/'.$sf)) {
                    throw new Exception($filepath.'/'.$sf.' could not be deleted.');
                }
            }
            closedir($dh);
        }
        return rmdir($filepath);
    }
    return unlink($filepath);
}

/**
 * Enter description here...
 *
 * @param string $folder
 * @return string
 */
function getAvailableTemplates($folder)
{
	$array = array();
	if ($handle = opendir($folder)) {
	    while (false !== ($file = readdir($handle))) {
	    	if(!is_dir($file) && $file !== '.' && $file !== '..' && $file !== '.DS_Store') {
	    		$class = $folder . '_' . $file . '_Config';
	    		$config = new $class;
	    		$array[$file] = $config->getName();
	    	}
	    }
	    closedir($handle);
	}
	return $array;
}

/**
 * Enter description here...
 *
 * @param string $folder
 * @return string
 */
function getAvailableTemplatesHTML($folder)
{
	$array = getAvailableTemplates($folder);
	$string = '';
	foreach ($array as $dir => $name) {
		$string .= '<option value="' . $dir . '">' . $name . '</option>';
	}
	return $string;
}

/**
 * Enter description here...
 *
 * @return string
 */
function getHeader()
{
	return '<html>
            <head>
            	<title>Module Creator</title>
            	<style type="text/css">
            		* {
            			font-family: Arial, Helvetica, Sans-Serif;
            			font-size: 10pt;
            		}
            		body {
            			background-color: #E5E5E5;
            		}
            		#main {
            			width:400px;margin:0px auto;
            			border: 1px solid #0072A6;
            			padding: 20px 30px 20px 30px;
            			background-color: white;
            		}
            		#message {
            			border:1px solid grey;
            			margin: 10px;
            			padding: 10px;
            		}
            		.description {
            			width: 170px;
            			float: left;
            		}
            		.element {
            			clear:both;
            			height:40px;
            		}
            		.annotation {
            			font-size: 8pt;
            			color: grey;
            		}
            		#submit {
            			height: 20px;
            			display:block;
            		}
            		#create {
            			float: left;
            			margin-left: 30px;
            		}
            		#uninstall {
            			float: right;
            			margin-right: 30px;
            		}
            		h1 {
            			font-size: 14pt;
            		}
            		#logout, a {
            			font-size:8pt;
            			color: grey;
            			position:relative;
						right:-183px;
						top:-13px;
            		}
            		.text {
            			width: 230px;
            		}
            		.file {
            			font-size:8pt;
            			color: grey;
            		}
            	</style>
            </head>
            <body>
            	<div id="main">';	
}

/**
 * Enter description here...
 *
 * @return string
 */
function getFooter()
{
	return '</div>
         	</body>
      </html>';
}

function clearCache()
{
	global $shop;

	$cacheDir = $shop . 'var/cache/';
	rmRecurse($cacheDir);
}
//--------------------------------------------------------------

$formNamespace 		= isset($_POST['namespace']) ? $_POST['namespace'] : '';
$formModule 		= isset($_POST['module']) ? $_POST['module'] : '';
$formMagentoRoot 	= isset($_POST['magento_root']) ? replaceDirSeparator($_POST['magento_root']) : substr($root, 0, -15);
$formInterface 		= isset($_POST['interface']) ? $_POST['interface'] : '';
$formTheme 			= isset($_POST['theme']) ? $_POST['theme'] : '';
$formTemplates 		= getAvailableTemplatesHTML(TEMPLATES_DIR);

$form = '       <h1>Magento Module Creator</h1>
				<span id="logout"><a href="?logout=yes">Logout</a></span>
                <form name="newmodule" method="POST" action="" />
                	<div class="element">
                		<div class="description">Skeleton Template:<br /><span class="annotation">(you could build your own)</span></div>
                		<select name="template" class="select">
                		' . $formTemplates . '
                		</select>
                	</div>
                	<div class="element">
                		<div class="description">Namespace:<br /><span class="annotation">(e.g. your Company Name)</span></div>
                		<input name="namespace" class="text" type="text" length="50" value="' . $formNamespace . '" />
                	</div>
                	<div id="module" class="element">
                		<div class="description">Module:<br /><span class="annotation">(e.g. Blog, News, Forum)</span></div>
                		<input name="module" class="text" type="text" length="50" value="' . $formModule . '" />
                	</div>
                	<div id="magento_root" class="element">
                		<div class="description">Magento Root Directory:<br /><span class="annotation">(auto detected)</span></div>
                		<input name="magento_root" class="text" type="text" length="255" value="' . $formMagentoRoot . '" />
                	</div>
                	<div id="interface" class="element">
                		<div class="description">Design:<br /><span class="annotation">(interface, default is \'default\')</span></div>
                		<input name="interface" class="text" type="text" length="100" value="' . $formInterface . '" />
                	</div>
                	<div id="theme" class="element">
                		<div class="description">Design:<br /><span class="annotation">(theme, default is \'default\')</span></div>
                		<input name="theme" class="text" type="text" length="100" value="' . $formTheme . '" />
                	</div>
                	<div id="submit">
                		<input type="submit" value="create" name="create" id="create" /> <input type="submit" value="uninstall" name="uninstall" id="uninstall" />
                	</div>
                </form>';

if(!empty($_POST)) {
    $namespace = $_POST['namespace'];
    $module = $_POST['module'];
    $interface = $_POST['interface'];
    $theme = $_POST['theme'];
    
    if ($interface == '') {
        $interface = 'default';
    }
    
    if ($theme == '') {
        $theme = 'default';
    }
    
    if ($_POST['magento_root'] != '') {
        if (checkShopRoot($_POST['magento_root']) !== false) {
            $shop = checkShopRoot($_POST['magento_root']);
        } else {
            throw new Exception('This is not a valid Magento install dir: ' . $_POST['magento_root']);
        }
    }
    
    $vars = array(
	    'template' 		=> $_POST['template'],
    	'capNamespace' 	=> ucfirst($namespace),
	    'lowNamespace' 	=> strtolower($namespace),
	    'capModule' 	=> ucfirst($module),
	    'lowModule' 	=> strtolower($module),
    	'interface'		=> $interface,
    	'theme'			=> $theme,
    );
    
   	$class = TEMPLATES_DIR . '_' . $_POST['template'] . '_Config';
    if (class_exists($class)) {
	    $config = new $class;
	    $config->setVars($vars);
    	$fromFiles = $config->getFromFiles();
    	$toFiles = $config->getToFiles();
    } else {
    	throw new Exception('No Config.php found for selected skeleton template: '.$template);
    }
                        
     if (isset($_POST['create'])) {
         if (!empty($module) && !empty($namespace)) {
         	clearCache();
            copyBlankoFiles($fromFiles, $toFiles, $shop);
            insertCustomVars($toFiles, $shop);
            
            $message = '<div id="message"><p><strong>New Module successfully created!</strong></p>
        		<p><strong>List of created files:</strong></p>';
                 foreach ($toFiles as $file) {
                     $message .= '<p class="file">' . $file . '</p>';
                 }
        		$message .= '</div>';
         } else {
             $message = '<div id="message"><p>Please fill out all required fields.</p></div>';
         }
     }
     if (isset($_POST['uninstall'])) {    
     	 $modulePath = 'app/code/local/'.$vars['capNamespace'].'/'.$vars['capModule'].'/';
         if (uninstallModule($shop, $modulePath, $toFiles) === true) {
         	clearCache();
            $message = '<div id="message"><p><strong>Module successfully uninstalled!</strong></p></div>';
         } else {
             $message = '<div id="message"><p><strong>Couldn\'t find module in Magento installation.</strong></p>
             			<p>After creating a module, you need to run Magento to install all new required tables
             			automatically. Also make sure you deactivate/refresh all Magento caches. Otherwise
             			no new modules will be recognized.</p></div>';
         }
     }
    
} else {
    $message = '<div id="message">To create a new module, insert Namespace and a Module name (e.g. Blog, Forum, etc.) as well as
    			your design above. If you want it to be installed right away into your Magento, enter your Magento install path.</div>';
}

/*
 * Output
 */
print getHeader()
    . $form
    . $message
    . getFooter();
