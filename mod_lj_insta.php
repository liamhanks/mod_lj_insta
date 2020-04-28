<?php
/**
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$modclass = 'insta' . $module->id;


//Check the access token age and renew the token if it's more than 24 hours old.
$token_age = date('Y-m-d H:i:s',$params->get('token_time'));
$token_exp = strtotime($token_age . '+1 day');
$now = time();
if($now > $token_exp){
	$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $params->get('api_key');
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
	
	$params->set('api_key',$response->access_token);
	$params->set('token_time',$now);
	$table = JTable::getInstance('module');
	$table->load($module->id);
	$table->save(array('params' => json_encode($params)));
}

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

//Save a copy of each image to the image management system (allows us to resize the image on the server, and to keep a copy of the image
$new = 0;
foreach( $response->data as $key => $insta ){
	if(isset($insta->thumbnail_url)){$file = $insta->thumbnail_url;}else{$file = $insta->media_url;}
	if(!file_exists("/home/haliam/public_communications_html/images/Instagram/" . $insta->id . ".jpg")){
		copy($file, "/home/haliam/public_communications_html/images/Instagram/" . $insta->id . ".jpg");
		$new++;
	}
	$response->data[$key]->var1 = md5(filemtime("/home/haliam/public_communications_html/images/Instagram/" . $insta->id . ".jpg"));
	$response->data[$key]->var2 = md5(filesize("/home/haliam/public_communications_html/images/Instagram/" . $insta->id . ".jpg"));
}

if($new > 0){
	unlink("/home/haliam/public_communications_html/cache/beta-photolist-cache.json");
}

require JModuleHelper::getLayoutPath('mod_lj_insta', $params->get('layout', 'default'));
