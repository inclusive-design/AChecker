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

$handbook_pages = array(
               'register.php',
               'login.php',
               'password_reminder.php',
               'checker/index.php' =>   array(
                                        'checker/suggestion.php'
                                        ),
               'guideline/index.php' => array(
                                        'guideline/create_edit_guideline.php',
                                        'guideline/view_guideline.php',
                                        ),
               'check/index.php' => array(
                                        'check/check_create_edit.php',
                                        'check/check_function_edit.php',
                                        'check/html_tag_list.php'
                                        ),
               'user/index.php' =>      array(
                                        'user/user_create_edit.php',
                                        'user/user_password.php',
                                        'user/user_group.php',
                                        'user/user_group_create_edit.php'   
                                        ),
               'language/index.php' =>  array(
                                        'language/language_add_edit.php'
                                        ),
               'translation/index.php' => array(),
               'profile/index.php' =>   array(
                                        'profile/change_password.php',
                                        'profile/change_email.php'
                                        ),
               'updater/index.php' => array('updater/patch_create.php')
);

?>
