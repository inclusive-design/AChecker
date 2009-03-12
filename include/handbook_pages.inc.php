<?php
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
                                        )
);

?>