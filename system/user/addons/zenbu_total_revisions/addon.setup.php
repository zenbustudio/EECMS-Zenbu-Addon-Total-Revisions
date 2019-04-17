<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// require_once __DIR__.'/vendor/autoload.php';
if(file_exists(__DIR__.'/language/english/zenbu_total_revisions_lang.php'))
{
	include_once __DIR__.'/language/english/zenbu_total_revisions_lang.php';
}

$config['name']           = isset($lang['module_name']) ? $lang['module_name'] : 'Zenbu Total Revisions';
$config['version']        = '0.0.1';
$config['description']    = isset($lang['module_description']) ? $lang['module_description'] : '';
$config['author']         = 'Nicolas Bottari - Zenbu Studio';
$config['author_url']     = 'https://zenbustudio.com/software/zenbu';
$config['docs_url']       = 'https://zenbustudio.com/software/docs/zenbu';
$config['namespace']      = 'Zenbustudio\ZenbuTotalRevisions';
$config['settings_exist'] = FALSE;

if( ! defined('ZENBU_VER') )
{
	define('ZENBU_VER', $config['version']);
	define('ZENBU_NAME', $config['name']);
	define('ZENBU_DESCRIPTION', $config['description']);
	define('ZENBU_SETTINGS_EXIST', $config['settings_exist']);
}

return $config;