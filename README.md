## Boilerplate php 8.1 / symfony / fast development / hexagonal architecture (mix) and swoole 🚀


![Symfony Sersion](https://img.shields.io/badge/Symfony-6.1-purple.svg?style=flat-square&logo=symfony)
![php Version](https://img.shields.io/badge/php-8.1-blueviolet)
![Licence](https://img.shields.io/badge/Licence-MIT-brightgreen)
![Test](https://github.com/MGDSoft/boilerplate-apiplatform-swoole/workflows/CI/badge.svg?branch=master)


First of all, this repo is experimental and doesn't have enough time to use in a production environment 
(especially related to swoole). But as you can see all configuration related with swoole is in config/swoole/swoole.php. 
This project uses the symfony runtime runtime/swoole package.


- [Installation](#installation)
- [Swoole](#swoole)
- [Api Platform](#api-platform)
- [Folder structure](#folder-structure)
- [Make files](#make-files)
- [Git Pre-commit](#git-pre-commit)
- [Tests](#tests)
- [Todo](#todo)

### Installation 

Requirements
 - Docker
 - Make

To start server

```sh
make app-run-fresh
```

### Swoole

All related with swoole is in config/swoole/swoole.php like other runtimes of symfony can accept different options
([to see more options go to swoole doc](https://openswoole.com/docs/modules/swoole-server/configuration))

The performance of Swoole is AMAZING to get responses between 5-15ms.

There are some troubles with using "hot reload" with swoole and symfony and I prefere use a normal php server for dev development.
If you look `docker-compose.yml` you will see the entrypoint is overridden.

### Api Platform

It works as always with some utils things, for example if you want to create a custom controller you can use 
serializer or validation like ApiPlatform do. You can check `src/User/Infrastructure/Controller/CustomPostUserController.php` and there
you will see requestProcessor->process. It can extract input data and send it within a messenger bus.

### Folder structure

Hexagonal architecture... ejem ejem... I know there are places where it's not strict hexagonal architecture, but it makes development faster and for this purpose 
I think is better. For example, my main goal is a separate "Application" layer than the others, "ApiPlatform" saves a 
lot of time building a site. I don't want to create a lot of layers that create noise and make me waste time.

As you can see, all related to the user is inside his folder even Tests. I think it’s really handy and all your code is 
near to modify.

All Interfaces are auto declared as service referring to his correct service 
`Infrastructure/Doctrine/Repository/UserRepositoryDoctrine.php`. It's configured in `config/services/services_repositories.php`

All doctrine custom repositories extend of `ServiceEntityRepository`, you only should focus in write new functionality related to your business.

### Make files

All commands are very descriptive but you can check it in Makefile.

```sh
# To see all commands 
make help
```

### Git Pre-commit

It execute [phpstan](https://phpstan.org), [php-cs-fixer](https://cs.symfony.com) and all tests before committing.

### Tests

Folder `Test` lives near where things that are testing, it’s the most logical. For example if you are testing `User` 
test files should be in `User/Test`


I use [coduo/php-matcher](https://github.com/coduo/php-matcher) to compare api responses but only check certain things inside the response. With this great library you can check this json string

```json
[
  {
    "title": "big object",
    "desc": "big object"
  },
  {
    "title": "big object 2",
    "desc": "big object 2"
  }
]
``` 

Matches with

```json
[
  {
    "title": "big object",
    "@*@": "@*@"
  },
  "@...@"
]
```

Why do this? because with this methodology you can create new fields and your current tests will pass like always checking
only the important things.

### Todo
 
- Create a command to create skeleton of new section
- Hot reload for Swoole dev mode 

The purpose of this repo is to create a seed to work fast with this stack, if you know some better way to improve speed 
or quality of it I’d love to hear from you.

by the way `php8` and `swoole` are amazing ❤️. 