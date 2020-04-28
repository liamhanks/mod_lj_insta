<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//$document = JFactory::getDocument();
//$document->addScript('/templates/ja_university_t3/js/recliner.min.js');
?>
<div class="ljinsta jumbotron <?php echo $moduleclass_sfx; ?> <?php echo $modclass; ?>" style="padding-top: 20px;">
	<div class="container">
	<?php if($response->error){ ?>
		<div class="alert alert-danger">
			Error: <?php echo $response->error->message; ?>
		</div>
	<?php }else{ ?>
		<script>
			function resizew(id){
				var widthofdiv = jQuery('#panel_' + id).width(); //set height to same as width (make squares)
				jQuery("#image_" + id).css("height",widthofdiv);
			}
		</script>
		<?php if($params->get('show_heading')){ ?>
		<h3><span aria-hidden="true" class="fa fa-instagram fa-2x">&nbsp;</span><a href="https://www.instagram.com/<?php echo $response->data[0]->username; ?>" target="_blank"><?php echo $response->data[0]->username; ?></a></h3>
		<?php } ?>
		<div class="row">
			<?php 
			$i = 1;
			foreach( $response->data as $item ){ ?>
			<?php 
			//Clean up the caption
			$caption = nl2br($item->caption);
			$caption = preg_replace('/\./', 'X!X!', $caption);
			$caption = preg_replace('/(\S)X!X!/', '$1.', $caption);
			$caption = str_replace('X!X!','',$caption);
			$break = 12 / $params->get('per_row');
			?>
				<div id="panel_<?php echo $item->id; ?>" class="col-xs-6 col-md-<?php echo $params->get('per_row'); ?>">
					<a href="<?php echo $item->permalink; ?>" target="_blank" rel="nofollow"><img id="image_<?php echo $item->id; ?>" src="images/photos.php?id=<?php echo $item->id; ?>&1=<?php echo $item->var1 ?>&2=<?php echo $item->var2 ?>&w=340" onload="resizew(<?php echo "'" . $item->id . "'"; ?>);" title="<?php echo strip_tags($caption); ?>" loading="lazy"/></a>
				</div>
			<?php if($i % $break === 0){ ?>
				<div class="clearfix hidden-xs"></div>
			<?php }
				if($i % 2 === 0){ ?>
				<div class="cleafix visible-xs"></div>
			<?php } ?>
			<?php 
			$i++;
			} ?>
		</div>
		<style>
			.<?php echo $modclass; ?> small a{text-decoration: none; color: inherit;}
			a.hashtag{text-decoration: none;}
			.<?php echo $modclass; ?> small a:hover,.<?php echo $modclass; ?> small a:focus,a.hashtag:hover,a.hashtag:focus{text-decoration: underline;}
			.<?php echo $modclass; ?> .panel-title a{
				line-height: 30px;
				vertical-align: super;
				text-decoration: none;
			}
			.<?php echo $modclass; ?> .panel-body{
				padding: 0 20px 20px 20px;
			}
			.<?php echo $modclass; ?> img{
				height: 100%;
				width: 100%;
				object-fit: cover;
				border-radius: 3px;
				margin-top: 20px;
			}
			.<?php echo $modclass; ?> img:hover,<?php echo $modclass; ?> img:focus{
				opacity: 0.6;
			}
		</style>
	<?php } ?>
	</div>
</div>
