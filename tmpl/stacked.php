<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="custom<?php echo $moduleclass_sfx; ?> <?php echo $modclass; ?> ">
<?php if($response->error){ ?>
	<div class="alert alert-danger">
		Error: <?php echo $response->error->message; ?>
	</div>
<?php }else{ ?>
	<?php foreach( $response->data as $item ){ ?>
	<?php 
	//Clean up the caption text and add links
	$caption = nl2br($item->caption);
	$caption = preg_replace('/\./', 'X!X!', $caption);
	$caption = preg_replace('/(\S)X!X!/', '$1.', $caption);
	$caption = str_replace('X!X!','',$caption);
	$caption = preg_replace('/(?:^|\B)#(?![0-9_]+\b)([a-zA-Z0-9_]{1,30})(?:\b|\r)/', '<a class="hashtag" href="https://www.instagram.com/explore/tags/$1" target="_blank" rel="nofollow">$0</a>', $caption);
	$caption = preg_replace('/(?:^|\B)@(?![0-9_]+\b)([a-zA-Z0-9_]{1,30})(?:\b|\r)/', '<a class="hashtag" href="https://www.instagram.com/$1" target="_blank" rel="nofollow">$0</a>', $caption);
	?>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="row no-gutter">
					<?php if(isset($item->thumbnail_url)){ //thumbnail_url is only set for videos.?>
					<div class="col-xs-12" id="image_<?php echo $item->id; ?>">
						<video controls
							src="<?php echo $item->media_url; ?>"
							poster="<?php echo $item->thumbnail_url; ?>"
							>
							Vous devez peut-être <a href="<?php echo $item->permalink; ?>" target="_blank">naviguer vers Instagram pour visionner cette vidéo</a>.
						</video>
					<?php }else{ ?>
					<div class="col-xs-12">
						<a href="<?php echo $item->permalink; ?>" target="_blank" rel="nofollow">
							<img id="image_<?php echo $item->id; ?>" src="<?php echo $item->media_url; ?>" />
						</a>
					<?php } ?>
					</div>
					<div id="panel_<?php echo $item->id; ?>" class="col-xs-12">
						<?php if($params->get('show_heading') || $params->get('show_date')){ ?>
						<div class="panel-heading">
							<h3 class="panel-title">
							<?php if($params->get('show_heading')){ ?>
								<span aria-hidden="true" class="fa fa-instagram fa-2x">&nbsp;</span><a href="https://www.instagram.com/<?php echo $item->username; ?>" target="_blank" rel="nofollow"><?php echo $item->username; ?></a>
							<?php } ?>
							<?php if($params->get('show_date')){ ?>
							<small <?php if($params->get('show_heading')){ ?>class="pull-right"<?php } ?>><a href="<?php echo $item->permalink; ?>" target="_blank" rel="nofollow"><?php
							$date = new JDate($item->timestamp);
							echo strtolower(JFactory::getDate($date)->format('d F')); ?>
							</a></small>
							<?php } ?>
							</h3>
						</div>
						<?php } ?>
						<div class="panel-body">
							<p><?php echo $caption; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php } ?>
	<?php } ?>
</div>
<style>
.<?php echo $modclass; ?> .panel-body{
	padding-left: 20px;
	padding-right: 20px;
}
.<?php echo $modclass; ?> .panel-title a{
	line-height: 30px;
	vertical-align: super;
	text-decoration: none;
}
.<?php echo $modclass; ?> .panel-title a:hover,.<?php echo $modclass; ?> .panel-title a:focus{
	text-decoration: underline;
}
.<?php echo $modclass; ?> small a{text-decoration: none; color: inherit;}
a.hashtag{text-decoration: none;}
.<?php echo $modclass; ?> small a:hover,.<?php echo $modclass; ?> small a:focus,a.hashtag:hover,a.hashtag:focus{text-decoration: underline;}
.<?php echo $modclass; ?> img{
	height: 100%;
	width: 100%;
	object-fit: cover;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
}
.<?php echo $modclass; ?> video{
	width: 100%;
	height: 100%;
	background-color: #000000;
}
</style>