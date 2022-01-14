## 環境準備

### docker用gitリポジトリのクローン
```git clone https://github.com/nunulk/learning-laravel-tdd-docker docker```

### アプリケーション用ディレクトリの作成
```mkdir app```

### Dockerコンテナの起動
```
cd docker
cp .env.example .env
docker-compose up -d 
```

### アプリケーションの初期化
```
docker exec -it learning-laravel-tdd_app_1 ash
composer create-project --prefer-dist "laravel/laravel=7.*" .
```

#### create-appでエラー
```Fatal error: Allowed memory size of ......```
メモリが足らないというエラーなので、以下の手順で一旦vendorの中身をすべて消してからメモリ上限をなくしてライブラリのインストールをやり直す

```
rm -rf vendor/
COMPOSER_MEMORY_LIMIT=-1 composer update
```

さらにgitがないというエラー

```
[RuntimeException]
  Failed to clone https://github.com/voku/portable-ascii.git, git was not found, check that it is installed and in
   your PATH env.

  sh: git: not found
```

alpine linuxのimageを使っている場合は以下のコマンドでgitをインストールする
```apk add git```

### app/.envファイルにDBの設定
元のDB関連の設定を消して、以下の内容を追加
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=learning_laravel_tdd
DB_USERNAME=root
DB_PASSWORD=root

DB_TESTING_HOST=db-testing
DB_TESTING_PORT=3306
DB_TESTING_DATABASE=learning_laravel_tdd_testing
DB_TESTING_USERNAME=root
DB_TESTING_PASSWORD=root
```

### Models ディレクトリの作成と User.php の移動とnamespace修正
```mkdir app/Models```

以下3ファイルのApp\UserをApp\Models\Userに修正する

app/Models/User.php
config/auth.php
database/factories/UserFactory.php

### アプリケーションの確認

```
php artisan migrate
php artisan migrate --database=mysql_testing
php artisan test
```

### migrateに失敗
MySQL8の場合、デフォルトの認証方式がcaching_sha2_passwordになっていてlaravelから接続できないのでmigrateに失敗する
```SQLSTATE[HY000] [2054] The server requested authentication method unknown to the client```

一旦てきとうなクライアントソフトでrootユーザでMySqlに接続し、以下の方法で認証方式を確認し、mysql_native_password方式に変更する

```
select user, plugin from mysql.user;
alter user 'ユーザ名' identified with mysql_native_password by 'パスワード';
```


