## ProtonDuck - Open Source Bookmark Manager

Backend/API for [Bookmarks-Frontend](https://github.com/protonduck/bookmarks-frontend).

## Local installation

#### Build and run Docker

- `docker-compose build` - Build Docker
- `docker-compose up -d` - Start Docker
- `docker-compose exec php /bin/bash` - SSH Login to Docker
- `docker-compose down` - Stop Docker

#### Add new hosts to your `hosts` File:

```
127.0.0.1 admin.bookmarks.local
127.0.0.1 api.bookmarks.local
```

#### Development links

```
Backend: http://admin.bookmarks.local:8025
API enpoint: http://api.bookmarks.local:8025
API docs: http://api.bookmarks.local:8025/v1/docs
```

#### Install all dependencies via Composer

```
composer install
```

#### Initialize Environment

```
php init --env=Development
```

#### Apply migrations

Setup Database with Host: localhost, Post: 3386, Username: user and Password: pass

```
php yii migrate
```

#### Note for PHPStorm:

Mark the file `"/vendor/yiisoft/yii2/Yii.php"` as plain text (right-click "Mark as Plain Text").

### Console commands

- `php yii user/create` - register new User

### Testing

```bash
# Once run 'build'
vendor/bin/codecept build

# Apply migrations for _test db
php yii_test migrate

# Run all tests
vendor/bin/codecept run
vendor/bin/codecept run -- -c <app_name:api|backend> <type:api|unit|acceptance|functional> <className>::<methodName>

# Run specific environment tests (also possible: backend, common)
vendor/bin/codecept run -- -c api

# Run all tests within one test class
vendor/bin/codecept run -- -c api api UserCest

# Run one specified test (useful when writing new test)
vendor/bin/codecept run -- -c api api UserCest::checkRegister
```

## Postman collection

You can import the Postman collection for the `bookmarks.postman_collection.json` file.

## License

This project is licensed under the AGPL-3.0 License. See the LICENSE file for details.

## Contributing

1. Fork it
2. Create your feature branch (git checkout -b my-new-feature)
3. Make your changes
4. Commit your changes (git commit -am 'Added some feature')
5. Push to the branch (git push origin my-new-feature)
6. Create new Pull Request

## Donations

If this project help you, you can give me a cup of coffee :)

- Bitcoin: bc1qj4sjfkfmdv7c7dy8z84t2zyp63zj3ykvp8q4lw
- Ethereum: 0xfa840eae321A5641E778B25dC05Ae1EEA53AA1E6
- Dogecoin: DDLxksdYeBZ4y6YUZ9UjQ5S93xce9EEU7c
