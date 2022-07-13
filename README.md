#Project aoyos - Starter Kit - Symfony

## Introduction

Hello fellow dev and welcome to the aoyos website project. 
aoyos is an agency that coordinates events and is an intermediary between customers and Djs.

First of all, before we begin, we will introduce the main points of our project. 

The website is divided into three main parts : 

-> A main website, which is mostly meant to introduce aoyos to the general public and has no particular feature aside from two forms : one for customers to submit their dj-ing requests, and one for djs to register.
-> Once the djs are registered, they have a their own subsidiary website that they can use to modify their profiles, see all the events and position themselves on the ones they’re interested in. They can also upload their own invoices for the admin to see. 
-> And finally, the admin part from which the admin can administrate all parts of their website.

Now that this is out of the way, here are the technical aspects: 

This project is based on a symfony website-skeleton project with some additional libraries (webpack, fixtures) and tools to validate code standards.

* GrumPHP, as pre-commit hook, will run 2 tools when `git commit` is run :

    * PHP_CodeSniffer to check PSR12
    * PHPStan focuses on finding errors in your code (without actually running it)
    * PHPmd will check if you follow PHP best practices

  If tests fail, the commit is canceled and a warning message is displayed to developper.

* Github Action as Continuous Integration will be run when a branch with active pull request is updated on github. It will run :

    * Tasks to check if vendor, .idea, env.local are not versionned,
    * PHP_CodeSniffer, PHPStan and PHPmd with same configuration as GrumPHP.

Now that you have a better understanding of how the project is built, let's get started. 

## Getting Started

### Prerequisites

1. Check that composer is installed
2. Check that yarn & node are installed

### Install

1. Clone this project
2. Duplicate the .env file and name it .env.local
3. To create you database, in .env.local, at the line "DATABASE_URL=" change the line just above to correspond to your needs.
4. To use the mailer, insert at the line MAILER_DSN your own mailer link (mailtrap for exemple). On the line MAILER_FROM_ADDRESS enter the e-mail you want to be used in your e-mails.
5. Run `composer install`
6. Run `yarn install`
7. Run `yarn encore dev` to build assets

### Working

1. Run `symfony server:start` to launch your local php web server
2. Run `yarn run dev --watch` to launch your local server for assets (or `yarn dev-server` do the same with Hot Module Reload activated)
3. To use the database, enter in your terminal symfony console doctrice:database:create (d:d:c).
4. If migrations exist, use symfony console doctrice:make:migration (d:m:m). 
5. If fixtures exist, use symfony console doctrice:fixtures:load (d:f:l).
6. To drop the database and start anew, use symfony console doctrice:database:drop --force (d:d:d --force).

### Testing

1. Run `php ./vendor/bin/phpcs` to launch PHP code sniffer
2. Run `php ./vendor/bin/phpstan analyse src --level max` to launch PHPStan
3. Run `php ./vendor/bin/phpmd src text phpmd.xml` to launch PHP Mess Detector
4. Run `./node_modules/.bin/eslint assets/js` to launch ESLint JS linter

### Windows Users

If you develop on Windows, you should edit you git configuration to change your end of line rules with this command:

`git config --global core.autocrlf true`

The `.editorconfig` file in root directory do this for you. You probably need `EditorConfig` extension if your IDE is VSCode.

### Run locally with Docker

1. Fill DATABASE_URL variable in .env.local file with
`DATABASE_URL="mysql://root:password@database:3306/<choose_a_db_name>"`
2. Install Docker Desktop an run the command:
```bash
docker-compose up -d
```
3. Wait a moment and visit http://localhost:8000


## Deployment

Some files are used to manage automatic deployments (using tools as Caprover, Docker and Github Action). Please do not modify them.

* [captain-definition](/captain-definition) Caprover entry point
* [Dockerfile](/Dockerfile) Web app configuration for Docker container
* [docker-entry.sh](/docker-entry.sh) shell instruction to execute when docker image is built
* [nginx.conf](/ginx.conf) Nginx server configuration
* [php.ini](/php.ini) Php configuration


## Built With

* [Symfony](https://github.com/symfony/symfony)
* [GrumPHP](https://github.com/phpro/grumphp)
* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHPStan](https://github.com/phpstan/phpstan)
* [PHPMD](http://phpmd.org)
* [ESLint](https://eslint.org/)
* [Sass-Lint](https://github.com/sasstools/sass-lint)



## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning


## Authors

Wild Code School trainers team
aoyos dev team

## License

MIT License

Copyright (c) 2019 aurelien@wildcodeschool.fr

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Acknowledgments

Thanks to our trainers, Sylvain and Thibault for accompagnying us throughout this project. 
Special thanks as well to our customers from aoyos, Gaëtan and Alice, for trusting us with this project. 
And thanks to the Wild Code School for providing us with the base for this project.