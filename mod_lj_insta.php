<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$modclass = 'insta' . $module->id;

if($params->get('api_key')){
	$url = 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,timestamp,username,thumbnail_url&access_token=' . $params->get('api_key') . '&limit=' . $params->get('limit');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	$response = json_decode($response);
	if($response === NULL){$response->error->message = "Invalid response.";}
}else{
	$response->error->message = "Please provide the Instagram User Token in the module configuration";
}

require JModuleHelper::getLayoutPath('mod_lj_insta', $params->get('layout', 'default'));