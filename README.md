## Open Source Bookmark Manager

### Local installation

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
sudo usermod -aG docker ${USER}
su - ${USER}
sudo usermod -aG docker ${USER}
```

#### Build and run Docker

```
docker-compose build
docker-compose up -d
docker-compose exec php /bin/bash
```

#### Stop Docker

```
docker-compose down
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

Add new hosts to your `hosts` File:
```
127.0.0.1 bookmarks.local
127.0.0.1 admin.bookmarks.local
127.0.0.1 api.bookmarks.local
```

#### Dev links

```
Frontend: http://bookmarks.local:8025
Backend: http://admin.bookmarks.local:8025
API enpoint: http://api.bookmarks.local:8025
API docs: http://api.bookmarks.local:8025/v1/docs
```

#### Note for PHPStorm:

Mark the file `"/vendor/yiisoft/yii2/Yii.php"` as plain text (right-click "Mark as Plain Text").

#### Install all dependencies via NPM

```
npm install
```

### Build CSS/JavaScript

```
npm run dev
```

or

```
npm run frontend-dev
npm run backend-dev
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
