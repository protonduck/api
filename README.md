## Open Source Bookmark Manager

## Local installation

#### Install Docker (Ubuntu-Linux)

```
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
sudo apt-get update
apt-cache policy docker-ce
sudo apt-get install -y docker-ce docker-ce-cli containerd.io
sudo systemctl status docker
```

#### Install Docker-Compose (Ubuntu-Linux)

```
sudo curl -L "https://github.com/docker/compose/releases/download/1.26.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
sudo usermod -aG docker <username>
su - <username>
```

#### Build and run Docker

- `docker-compose build` - Build Docker
- `docker-compose up -d` - Start Docker
- `docker-compose exec php /bin/bash` - SSH Login to Docker
- `docker-compose down` - Stop Docker

#### Add new hosts to your `hosts` File:

```
127.0.0.1 bookmarks.local
127.0.0.1 admin.bookmarks.local
127.0.0.1 api.bookmarks.local
```

#### Development links

```
Frontend: http://bookmarks.local:8025
Backend: http://admin.bookmarks.local:8025
API enpoint: http://api.bookmarks.local:8025
API docs: http://api.bookmarks.local:8025/v1/docs
```

#### Install all dependencies via Composer

```
composer install
```

#### Install all dependencies via NPM

```
npm install
```

#### Build CSS/JavaScript

- `npm run dev` - for development
- `npm run prod` - for production (minified)
- `npm run watch` - for active development

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
vendor/bin/codecept run -- -c <app_name:api|frontend|backend> <type:api|unit|acceptance|functional> <className>::<methodName>

# Run specific evnviroment tests (also possible: backend, frontend, common)
vendor/bin/codecept run -- -c api

# Run all tests within one test class
vendor/bin/codecept run -- -c api api UserCest

# Run one specified test (useful when writing new test)
vendor/bin/codecept run -- -c api api UserCest::checkRegister
```

## License

This project is licensed under the AGPL-3.0 License. See the LICENSE file for details.

## Contributing

1. Fork it
2. Create your feature branch (git checkout -b my-new-feature)
3. Make your changes
4. Commit your changes (git commit -am 'Added some feature')
5. Push to the branch (git push origin my-new-feature)
6. Create new Pull Request
