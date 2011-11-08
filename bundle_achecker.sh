#! /bin/csh -f
#########################################################################
# AChecker bundle script                                                #
# ./bundle [VERSION] to specify an optional version number              #
# Author: Cindy Qi Li - ATRC, Apr 2009                                  #
# Modified from ATutor bundle script                                    #
#########################################################################

set now = `date +"%Y_%m_%d"`
set achecker_dir = "AChecker_$now"
set bundle = "AChecker"
#set svndir = "http://svn.atutor.ca/repos/achecker/trunk/"
#set svnexec = "svn"
set gitdir = "git://github.com/atutor/AChecker.git"
set gitexec = "git"

echo "\033[1mAChecker Bundle Script [for CVS 1.3.1+] \033[0m"
echo "--------------------"

if ($#argv > 0) then
	set extension = "-$argv[1]"
else 
	echo "No argument given. Run \033[1m./achecker_bundle.sh [VERSION]\033[0m to specify bundle version."
	set extension = ""
endif

if ($#argv == "2") then
	set ignore_mode = true
else
	set ignore_mode = false
endif

echo "Using $achecker_dir as temp bundle directory."
echo "Using $bundle$extension.tar.gz as bundle name."
#sleep 1

if (-e $achecker_dir) then
	echo -n "Dir $achecker_dir exists. Overwrite? (y/q) "

	set ans = $<
	switch ($ans)
	    case q: 
		echo "$achecker_dir not touched. Exiting."
	       exit
	    case y:
		echo "Removing old $achecker_dir"
		rm -r $achecker_dir
	endsw
endif
#sleep 1

echo "Exporting from SVN/ to $achecker_dir"
mkdir $achecker_dir
#$svnexec --force export $svndir $achecker_dir/AChecker
$gitexec clone $gitdir
mv 'AChecker' $achecker_dir/AChecker
#sleep 1

echo "Dumping language_text"
rm $achecker_dir/AChecker/install/db/language_text.sql
#echo "DROP TABLE `language_text`;" > $achecker_dir/AChecker/install/db/language_text.sql
#wget --output-document=- http://atutor.ca/achecker/translate/dump_achecker_lang.php >> $achecker_dir/AChecker/install/db/language_text.sql
wget --output-document=- http://atutor.ca/achecker/translate/dump_achecker_lang.php > $achecker_dir/AChecker/install/db/language_text.sql

#sleep 1

echo "Removing $achecker_dir/AChecker/include/config.inc.php"
rm -f $achecker_dir/AChecker/include/config.inc.php
echo -n "<?php /* This file is a placeholder. Do not delete. Use the automated installer. */ ?>" > $achecker_dir/AChecker/include/config.inc.php
#sleep 1

echo "Removing $achecker_dir/AChecker/bundle_achecker.sh"
rm -f $achecker_dir/AChecker/bundle_achecker.sh

echo "Disabling AC_DEVEL if enabled."
sed "s/define('AC_DEVEL', 1);/define('AC_DEVEL', 0);/" $achecker_dir/AChecker/include/vitals.inc.php > $achecker_dir/vitals.inc.php
rm $achecker_dir/AChecker/include/vitals.inc.php
mv $achecker_dir/vitals.inc.php $achecker_dir/AChecker/include/vitals.inc.php
#sleep 1
rm -Rf $achecker_dir/AChecker/.git

set date = `date`
echo -n "<?php "'$svn_data = '"'" >> $achecker_dir/AChecker/svn.php
echo $date >> $achecker_dir/AChecker/svn.php
#$svnexec log  -q -r HEAD http://svn.atutor.ca/repos/achecker/trunk/  >> $achecker_dir/AChecker/svn.php
echo -n "';?>" >> $achecker_dir/AChecker/svn.php

echo "Targz'ing $bundle${extension}.tar.gz $achecker_dir/AChecker/"
#sleep 1

if (-f "$bundle${extension}.tar.gz") then
	echo -n "Bundle $bundle$extension.tar.gz exists. Overwrite? (y/n/q) "

	set ans = $<

	switch ($ans)
	    case q:
		echo "$bundle$extension.tar.gz not touched."
		exit
	    case y:
		echo "Removing old $bundle$extension.tar.gz"
		set final_name = "$bundle$extension.tar.gz"
		rm -r "$bundle$extension.tar.gz"
		breaksw
	    case n: 
		set time = `date +"%k_%M_%S"`
		set extension = "${extension}-${time}"
		echo "Saving as $bundle$extension.tar.gz instead."
		set final_name = "$bundle$extension.tar.gz"
		breaksw
	endsw
else
	set final_name = "$bundle$extension.tar.gz"
endif	

echo "Creating \033[1m$final_name\033[0m"
cd $achecker_dir
tar -zcf $final_name AChecker/
mv $final_name ..
cd ..
#sleep 1

if ($ignore_mode == true) then
	set ans = "y"
else 
	echo -n "Remove temp $achecker_dir directory? (y/n) "
	set ans = $<
endif

if ($ans == "y") then
	echo "Removing temp $achecker_dir directory"
	rm -r $achecker_dir
endif

#echo ">> Did you update check_achecker_version.php ? >>"

echo "Bundle complete. Enjoy. Exiting."


exit 1