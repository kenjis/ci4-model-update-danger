# CodeIgniter 4 Model::update() is Dangerous

This sample code contains vulnerabilities.

> **Warning**
> **Do not use**. **Do not code like these**.

The vulnerabilities can be exploited to destroy data in the database.

## Setup

```console
$ git clone https://github.com/kenjis/ci4-model-update-danger.git
$ cd ci4-model-update-danger/
$ composer install
```

```console
$ php spark serve
```

Navigate to <http://localhost:8080/>. You will see links to News controllers.

You can add sample news articles with Seeder.

```console
$ php spark db:seed NewsSeeder
```
