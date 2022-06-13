# 1Setup 
```bash

cp .env.example .env

composer install
php artisan key:generate
php artisa migrate --seed
php artisan cache:clear


```

# 2file 

- [File design Table](https://drive.google.com/file/d/1f7VBNM6SbSI7PnnvsjTcGCAmN41N_us5/view?usp=sharing)
- [Template](https://drive.google.com/drive/folders/10yQqtDZ0WGzTkzNuqdvjZCW01A_GVbT4?usp=sharing)



# 3deploy Heroku

add file Procfile
```text    
web: vendor/bin/heroku-php-apache2 public/
```

## 3.1 set up

login heroku in 
https://www.heroku.com/
create account

create app 

## 3.2 
```bash 

heroku git:clone -a <project-name>
cd  <project-name>

git push heroku master
```
# 3.3 set update data base
in tab resource =>  Heroku Postgres => click add => show infomation of database

set update env.

```bash 
heroku run bash
cp .env.example .env

php artisan key:generate

# edit env 

heroku config:set $(cat .env | sed '/^$/d; /#[[:print:]]*$/d')


```


