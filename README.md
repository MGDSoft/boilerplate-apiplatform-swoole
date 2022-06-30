## Boilerplate php 8.1 / symfony / fast development / hexagonal architecture (mix) and swoole 🚀

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

There are some troubles with using "hot reload" with swoole and symfony and I prefere use a normal php server. If you look
`docker-compose.yml` you will see the entrypoint is overridden.

### Api Platform

It works as always with some util things like if you want to create a custom controller you can use some utils things
like serializer and validation. You can check `src/User/Infrastructure/Controller/CustomPostUserController.php` and there
you will see requestProcessor->process. It can extract input data and send it within a bus.

### Folder structure

Hexagonal architecture... ejem ejem... I know there are places where it's not strict hexagonal architecture, but it makes development faster and for this purpose 
I think is better. For example, my main goal is a separate "Application" layer than the others, "ApiPlatform" saves a 
lot of time building a site. I don't want to create a lot of layers that create noise and make me waste time.

As you can see, all related to the user is inside his folder even Tests. I think it’s really handy and all your code is 
near to modify.

All Interfaces are auto declared as service referring to `Infrastructure/Doctrine/Repository/UserRepositoryDoctrine.php`.
It's configured in `config/services/services_repositories.php`

All doctrine custom repositories extend of ServiceEntityRepository, you only must write new functionality.

### Make files

All commands are very descriptive but you can check it in Makefile.

```sh
make
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

Every pull request is welcome.