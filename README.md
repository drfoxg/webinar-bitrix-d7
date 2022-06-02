# Api вебинаров на Bitrix D7(Main 21.400, PHP 7.4)
Тестовое задание
## Установка
Приложение тестировалось под Docker.
Хостом для Docker служил Fedora 34 Server Edition или Ubuntu 20.04 через WSL под Windows 10.  

Подготовка к установке Docker for Windows:  
[Windows Subsystem for Linux Installation Guide for Windows 10](https://docs.microsoft.com/en-us/windows/wsl/install-win10).  
[Docker for Windows](https://desktop.docker.com/win/stable/amd64/Docker%20Desktop%20Installer.exe) должен быть установлен и интегрирован с WSL 2 [Ubuntu 20.04 MS Store](https://www.microsoft.com/en-us/p/ubuntu-2004-lts/9n6svws3rx71?activetab=pivot:overviewtab).

.wslconfig следует разместить в домашнем каталоге пользователя Windows и скорректировать доступный объем памяти для WSL, пример конфига на 2 GB, возможно нужна перезагрузка Windows:  
```
[wsl2]  
memory=2GB  
swap=2Gb  
localhostForwarding=true
```

Конфиг виртуального хоста для веб-сервера Apache Docker берет из отдельного файла при сборке(docker build), а именно из webinar.conf.  
В нем указан хост webinar.test, который следует прописать в hosts, например: `127.0.0.1 webinar.test`  

Следует указать правильные пути к каталогу www и mysql-data в файле (см. раздел volumes):  
`/webinar-bitrix-d7/docker-latest/docker-compose.yml`  

Там же следует указать пароль пользователя `root` для `mysql` в переменной `MYSQL_ROOT_PASSWORD`.  
  
Инструкцию по установке docker под Linux я приводить тут не буду. Считаю лишним.

## Запуск
Переходим в /webinar-bitrix-d7/docker-latest/  
`sudo docker-compose up -d`

## Сборка приложения

Добавляем и даем права пользователю:  
```
CREATE USER 'user'@'%' IDENTIFIED BY 'password';
ALTER USER 'user'@'%' IDENTIFIED WITH mysql_native_password BY 'password';
```
  
Открываем консоль сервера:  
`sudo docker exec -it appw-webinar_1 bash`
  
```
cd /var/www/webinar-bitrix-d7/local
composer install
```
  
Будут скачаны `vendors`  
  
Возможно нужно поправить права доступа:  
```
sudo chown -R 1000:33 /var/www/webinar/
sudo chmod -R 775 /var/www/webinar/
```
  
USERID 1000 - это как правило единственный и первый пользователь, т.к. в Ubuntu и Fedora нумерация пользователей начинается с 1000.  
GROUPID 33 - это группа www-data пользователя Apache.   

## Работа приложения
POST запрос с json в body на /api/v1/, ответ в HTML  
AJAX запрос с x-www-form-urlencoded на /bitrix/services/main/ajax.php?c=drfoxg:webinar&action=webinar&mode=class, ответ в JSON  
Пример запроса на jquery находится по адресу /api/test/index.php  

Для тестирования через POSTMAN:  

Пример AJAX-запроса  
1. Параметры:
 - c = drfoxg:webinar
 - action = webinar
 - mode = class
2. Заголовки:
 - Accept = `*.*`
 - X-Requested-With = XMLHttpRequest
3. Тело:
 - theme[0] = 1
 - theme[1] = 2
 - month[0] = 5
 - month[1] = 6

Пример POST-запроса  
1. Параметры:
2. Заголовки:
 - Accept = `*.*`
3. Тело:
```json
{
    "theme": [1,2],
    "month": [5,6]
}
```

## Остановка
`sudo docker-compose down`
