<?php 
/*
 * @author Jacek Materna
 *
 *	One Savant variable: $item which is the processed ouput message content according to lang spec.
 */
 
 global $_base_href;
 
// header
?>
<div id="error">
	<h4><?php echo _AC('the_follow_errors_occurred'); ?></h4>
	<?php if (is_array($item)) : ?>
		<ul>
		<?php foreach($item as $e) : ?>
			<li><?php echo $e; ?></li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>