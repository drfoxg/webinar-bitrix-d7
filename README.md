# Форма обратной связи на Laravel + Vue
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

Конфиг виртуального хоста для веб-сервера Apache Docker берет из отдельного файла при сборке(docker build), а именно из laravel8.conf.  
В нем указан хост laravel8.test, который следует прописать в hosts, например: `127.0.0.1 laravel8.test`  

Следует указать правильные пути к каталогу www и mysql-data в файле (см. раздел volumes):  
`/feedbackform/docker-latest/docker-compose.yml`  

Там же следует указать пароль пользователя `root` для `mysql` в переменной `MYSQL_ROOT_PASSWORD`.  
  
Инструкцию по установке docker под Linux я приводить тут не буду. Считаю лишним.

## Запуск
Переходим в /webinar/docker-latest/  
`sudo docker-compose up -d`

## Сборка приложения

Добавляем и даем права пользователю:  
```
CREATE USER 'user'@'%' IDENTIFIED BY 'password';
ALTER USER 'user'@'%' IDENTIFIED WITH mysql_native_password BY 'password';
```
  
Импортируем через phpMyAdmin docker-laravel8/sql-backups/countries.sql, это справочник телефонных кодов.  
  
Открываем консоль сервера:  
`sudo docker exec -it docker-latest_laravel8_1 bash`
  
```
cd /var/www/laravel8
composer install
npm install && npm run dev
```
  
Будут скачаны `vendors` и `node_modules`, скомпилирована `app.js`  
  
Возможно нужно поправить права доступа:  
```
sudo chown -R 1000:33 /var/www/laravel8/bootstrap/cache/
sudo chmod -R 775 /var/www/laravel8/bootstrap/cache/
sudo chown -R 1000:33 /var/www/laravel8/storage/
sudo chmod -R 775 /var/www/laravel8/storage/
```
  
USERID 1000 - это как правило единственный и первый пользователь, т.к. в Ubuntu и Fedora нумерация пользователей начинается с 1000.  
GROUPID 33 - это группа www-data пользователя Apache.  
  
Создаем таблицы в базе:  
`php artisan migrate`  

## Работа приложения
Переходим по адресу `<имя домена>/`.  
Заполняем форму.  
Нажимаем кнопку **Отправить**.  
Зеленое уведомление сообщает об успехе, красное об ошибке.  
Результаты добавления в базу и файл можно увидеть по адресу `<имя домена>/messages`.  

## Остановка
`sudo docker-compose down`
