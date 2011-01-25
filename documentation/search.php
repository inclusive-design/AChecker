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
// $Id$

define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'handbook_pages.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="<?php if ($missing_lang) { echo DEFAULT_LANGUAGE_CODE; } else { echo $req_lang; } ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo _AC('achecker_documentation'); ?></title>
	<base target="body" />
<style>
body { font-family: Verdana,Arial,sans-serif; font-size: x-small; margin: 0px; padding: 0px; background: #fafafa; margin-left: -5px; }
ul, ol { list-style: none; padding-left: 0px; margin-left: -15px; }
li { margin-left: 19pt; padding-top: 2px; }
a { background-repeat: no-repeat; background-position: 0px 1px; padding-left: 12px; text-decoration: none; }
a.tree { background-image: url('../images/folder.gif'); }
a.leaf { background-image: url('../images/paper.gif'); }
a:link, a:visited { color: #006699; }
a:hover { color: #66AECC; }
</style>
</head>
<body>
<?php
echo '<a href="frame_toc.php" target="_self">';
echo _AC('back_to_contents');
echo '</a>';

if ($_GET['query']) {
	$_GET['query'] = str_replace(',', ' ', $_GET['query']);
	$_GET['query'] = str_replace('"', '', $_GET['query']);

	if (strlen($_GET['query']) > 3) {
		$_GET['query'] = strtolower($_GET['query']);

		$search_terms = explode(' ', $_GET['query']);

		$results = array();

		$languageTextDAO = new LanguageTextDAO();
		
		$final_match_rows = array();
		foreach ($search_terms as $term)
		{
			$match_rows = $languageTextDAO->getHelpByMatchingText($term, $_SESSION['lang']);

			if (is_array($match_rows)) $final_match_rows = array_merge($final_match_rows, $match_rows);
		}

		if (is_array($final_match_rows)) 
		{
			foreach ($final_match_rows as $match) 
			{
				if (is_array($result)) 
					$all_match_terms = array_keys($result);
				else 
					$all_match_terms = array(); 

				if (!in_array($match['term'], $all_match_terms))
				{ 
					$count = 0;
	
					$contents = strtolower($match['text']);
	
					foreach ($search_terms as $term) 
					{
						$term = trim($term);
						if ($term) {
							$count += substr_count($contents, $term);
						}
					}
	
					if ($count) 
					{
						$results[$match['term']] = $count;
					}
				}
			}
		}

		// replace term in match array with script name
		if ($results) 
		{
			arsort($results);
			
			echo '<ol>';
			foreach ($results as $term => $count) 
			{
				foreach ($_pages as $this_page => $page_def)
				{
					if (strcmp($page_def['guide'], $term) == 0)
						echo '<li><a href="frame_content.php?p='.$this_page.'" class="leaf" target="body">'._AC($page_def['title_var']).'</a></li>';
				}

			}
			echo '</ol>';
		} else {
			echo '<p style="padding: 8px;">';
			echo _AC('no_results_found');
			echo '</p>';
		}
	} else {
		echo '<p style="padding: 8px;">';
		echo _AC('search_term_longer_3_chars');
		echo '</p>';
	}
}
?>
</body>
</html>