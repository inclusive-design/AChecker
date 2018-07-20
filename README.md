# AChecker

AChecker is an automated accessibility checker used to evaluate the accessibility of HTML pages, and help ensure they can be accessed by all individuals, including those with disabilities, using assistive technologies to navigate the Internet.

AChecker live site: http://achecker.ca

What sets AChecker apart from other automated accessibility checkers?

- Reviewers can interact with the system to make decisions on potential barriers that automated checkers can not determine with certainty.
- Choose from a range of accessibility standards to review conformance with various international accessibility requirements.
- Design custom accessibility guidelines tailored specifically to your organization
- View existing guidelines in AChecker to see exactly what it is reviewing.
- Design new accessibility checks and have them added to AChecker.

## Requirements

- PHP 7+
- MySQL 4.1.13+
- Composer

## Installation

- Clone this repository
- Be sure to have Composer [setup on your system/server](https://getcomposer.org/doc/00-intro.md)
- Run `composer install` or `composer update` to install dependencies
- Open a web browser to access the installation directory where AChecker was installed or cloned to
- Follow the instructions provided by the installer.

Note, if you are installing from a Git clone of AChecker, before following the instructions above, you will need to create an empty configuration file. In the AChecker directory, at the command prompt, issue the following command:

```
touch include/config.inc.php
```

Then follow the instructions above.

For more about using AChecker, see the instructional videos on [YouTube](http://www.youtube.com/watch?v=jtNyF7KuOk8).
