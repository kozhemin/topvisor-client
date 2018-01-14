Api2 Клиент TopVisor
=============
A PHP TopVisor client library

Установка
------------

Используйте [composer](http://getcomposer.org/download/) для установки.

Выполните

```
composer require --prefer-dist kozhemin/topvisor-client:@dev
```

либо добавтьте

```
"kozhemin/topvisor-client": "@dev"
```

в `composer.json` файл.


Пример использования
-----

Данная библиотека реализует взаимодействие с API версии 2  [TopVisor](https://topvisor.ru/api/)


Подключение
-----

```php
$topVisorToken = "000000000";   //Ваш Token
$topUserId = 00000;             //Ваш Id пользователя
$connection = new Connection($topVisorToken, $topUserId);

```
Работа с проектами
-----
[https://topvisor.ru/api/v2-services/projects_2/](https://topvisor.ru/api/v2-services/projects_2/)

```php

//Example: Получить список проектов
//@link https://topvisor.ru/api/v2/basic-params/
$params = [];
foreach ($connection->getProjects($params) as $currentProject) {
    /** @var Project $currentProject */
    echo '<pre>';
    print_r($currentProject->name);
    echo '</pre>';
}

//Example: Получить конкретный проект
$projectID = 00000;
$project = $connection->getProject($projectID, $params);
 
 
//Example: Добавить ключевую фразу в проект
$project->addKeyword("keyword", $groupId)
 

//Example: Получить ключевые фразы проекта
$project->getKeywords($params = []);
 

//Example: Получить папки проекта
$project->getFolders($params = []);
 

//Example: Добавить папрку в проект
$project->addFolder($name);
 

//Example: Получить группы проекта
$project->getGroups($params = []);
 

//Example: Добавить группу в проект
$project->addGroup($name, $params = []);
 

//Example: Получить ключевые фразы по проекту
$params = ['limit'=>5];
$project->getKeywords($params, $params);
 

```

Ключевые фразы
-----
[https://topvisor.ru/api/v2-services/keywords_2/](https://topvisor.ru/api/v2-services/keywords_2/)

```php

//Example: Получить конкретную ключевую фразу
$keyWord = $connection->getKeyword($projectID, $keywordID, $params);
 

//Example: Установить целевую страницу фразы
$keyWord->setTarget('url',  $params);
 

//Example: Установить тег
$keyWord->setTag($tagsId, $action = 'add', $params = []);
 

//Example: Переместить фразу в группу
$keyWord->move($groupId, $params = []);
 

//Example: Получить папку фразы
$keyWord->getFolder();
 

//Example: Изменить фразу
$keyWord->rename('New Name');
 

//Example: Удалить фразу
$keyWord->remove();
 

//Example: Восстановить фразу
$keyWord->unRemove();
 

//Example: Получить проект к которому принадлежит фраза
$keyWord->getProject($params = []);
 

//Example: Получить группу к которой принадлежит фраза
$keyWord->getGroup($params = []);
 

```

Папки
-----
[https://topvisor.ru/api/v2-services/keywords_2/folders/](https://topvisor.ru/api/v2-services/keywords_2/folders/)

```php
//Example: Добавить новую папку
$connection->addFolder($projectID, 'New Folder');
 

//Example: Получить все папки проекта
$connection->getFolders($projectId, $params = []);
 

//Example: Получить конкретную папку
$folder = $connection->getFolder($projectId, $folderId, $params = []);
 

//Example: Изменить папку
$folder->rename('Rename - Folder');
 

//Example: Переместить папку
$folder->move($params = []);
 

//Example: Удалить папку
$folder->remove();
 

//Example: Восстановить папку
$folder->unRemove();
 

//Example: Получить проект которому принадлежит папка
$folder->getProject($params = []);
 

```

Группы
-----
[https://topvisor.ru/api/v2-services/keywords_2/groups/](https://topvisor.ru/api/v2-services/keywords_2/groups/)

```php
//Example: Получить группы
$connection->getGroups($projectId, $params = []);
 

//Example: Получить конкретную группу
$group = $connection->getGroup($projectId, $groupId, $params = []);
 

//Example: Изменить группу
$group->rename($name);
 

//Example: Включить/Выключить группу
$group->on(1); //or 0
 

//Example: Переместить группу
$group->move($toGroupId, $params = []);
 

//Example: Удалить группу
$group->remove();
 

//Example: Восстановить группу
$group->unRemove();
 

//Example: Получить проект группы
$group->getProject();
 

//Example: Получить папку
$group->getFolder();
 

```

Позиции
-----
[https://topvisor.ru/api/v2-services/positions_2/](https://topvisor.ru/api/v2-services/positions_2/)
```php
//Example: Запуск проверки позиций
$connection->positionsCheck($projectId, $params = []);
 

//Example: Получить стоимость проверки позиций
$connection->positionsCheckPrice($projectId, $params = []);
 

//Example: Получить историю проверки позиций
$connection->positionsHistory($projectId, $regionsIndexes, $dateStart, $dateEnd, $params = []);
 

//Example: Получить данные сводки по выбранному проекту за две даты.
$connection->positionsSummary($projectId, $regionIndex, $dateStart, $dateEnd, $params = []);
 

```