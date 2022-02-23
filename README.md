# Simple ToDo MVC PHP app (For test)

## How to use

```bash
git clone git@github.com:fnematov/simple-todo.git
```

### Update configs from config/index.php file (Only postgresql supports)

```php
'driver' => 'pgsql', //supports only postgresql
'host' => 'localhost',
'port' => 5432,
'dbname' => 'db_name',
'username' => 'your_db_username',
'password' => 'your_db_password'
```

### Create database and default tables

```sql
CREATE DATABASE todo;
```
```sql
CREATE SEQUENCE todos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;
CREATE TABLE "public"."todos"
(
    "id"      integer DEFAULT nextval('todos_id_seq') NOT NULL,
    "name"    character varying,
    "email"   character varying,
    "content" text,
    "status"  boolean DEFAULT false,
    CONSTRAINT "todos_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;
CREATE TABLE "public"."users"
(
    "id"       integer DEFAULT nextval('users_id_seq') NOT NULL,
    "username" character varying(32)                   NOT NULL,
    "password" character varying(255)                  NOT NULL,
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
) WITH (oids = false);
```

### Insert test data

```sql
INSERT INTO "users" ("username", "password")
VALUES ('admin', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3');
```

### Nginx config

```nginx
server {
    listen 2536;
    server_name todo.loc www.todo.loc;
    charset utf-8;
    access_log  /var/log/nginx/todoapi.access.log;
    error_log  /var/log/nginx/todoapi.error.log;

    root   /var/www/html/simple-todo/public;

    location / {
        index  index.php index.html index.htm;
        try_files $uri /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9001;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    }
}
```

### Restart nginx
```bash
nginx -s reload
```

### And go to

[http://localhost:2536](http://localhost:2536)