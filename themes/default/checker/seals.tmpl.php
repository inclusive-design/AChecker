<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2011                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id: checker_results.tmpl.php 501 2011-02-25 17:43:05Z greg $

// display seals
if (is_array($this->seals))
{
?>
<h3><?php echo _AC('valid_icons');?></h3>
<p><?php echo _AC('valid_icons_text');?></p>
<?php 
	$user_link_url = '';
	
	if (isset($this->user_link_id))
		$user_link_url = '&amp;id='.$this->user_link_id;
	
	foreach ($this->seals as $seal)
	{
?>
	<img class="inline-badge" src="<?php echo SEAL_ICON_FOLDER . $seal['seal_icon_name'];?>"
    alt="<?php echo $seal['title']; ?>" height="32" width="102"/>
    <pre class="badgeSnippet">
  &lt;p&gt;
    &lt;a href="<?php echo AC_BASE_HREF; ?>checker/index.php?uri=referer&amp;gid=<?php echo $seal['guideline'].$user_link_url;?>"&gt;
      &lt;img src="<?php echo AC_BASE_HREF.SEAL_ICON_FOLDER . $seal['seal_icon_name'];?>" alt="<?php echo $seal['title']; ?>" height="32" width="102" /&gt;
    &lt;/a&gt;
  &lt;/p&gt;
	</pre>

<?php 
	} // end of foreach (display seals)
} // end of if (display seals)
?>
