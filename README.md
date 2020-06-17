## Менеджер закладок

### Локальная установка проекта 
```
docker-compose build
docker-compose up -d
docker-compose exec php /bin/bash

composer update
php init --env=Development
php yii migrate
```

Для удобства обновить свой локальный `hosts` файл и дописать туда:
```
127.0.0.1 bookmarks.local
127.0.0.1 admin.bookmarks.local
127.0.0.1 api.bookmarks.local
```

Frontend: http://bookmarks.local:8025<br>
Backend: http://admin.bookmarks.local:8025
API enpoint: http://api.bookmarks.local:8025
API docs: http://api.bookmarks.local:8025/v1/docs

Database:<br>
Host: localhost<br>
Post: 3386<br>
User: user<br>
Pass: pass

### Разное
Note for PhpStorm: mark the file `"/vendor/yiisoft/yii2/Yii.php"` as plain text (right-click "Mark as Plain Text")
