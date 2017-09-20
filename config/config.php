<?php
/**
  * файл, который хранит имя сайта, массив языков и ролей
  * сохраняет настройки по умолчанию для подключения к БД (host, user, password, db_name)
  * по дефолту: роутер, язык, контроллер, экшен
  * содержит соль для паролей
 */
Config::set('site_name','vesti_ua');

#массив с языками
Config::set(
    'languages', array (
        'ru',
        'uk',
        'eng'
    )
);

# массив ролей
Config::set(
    'routes', array (
        'default'   => '',
        'admin'     => 'admin_',
        'user'      => 'user_',
        'moderator' => 'moderator_',
    )
);

// Default sets for all site
Config::set('default_router'    ,'default');
Config::set('default_language'  ,'ru');
// Config::set('default_controller','news'); TO DONE change default controller
Config::set('default_controller','home');
Config::set('default_action'    ,'index'); 

// DataBase config
Config::set('db.host'    ,'localhost');
Config::set('db.user'    ,'root');
Config::set('db.password','');
Config::set('db.name'    ,'vesti_ua');

// slat makes the password more complex
Config::set('salt', 'dfnk346jg4jg36gj4h5g6kg5kjg3');
