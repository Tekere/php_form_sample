<?php 

const DB_HOST = 'mysql:dbname=udemy_php;host=127.0.0.1;charset=utf8;';
const DB_USER = 'udemy_php';
const DB_PASSWORD = 'Password123#';



// DB接続時の例外処理
try {
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
    // ↑4つ目の引数は、任意のオプション どれも基本的には必須なので記載しよう
    
    echo '接続成功';
} catch (PDOexception $e) {
    echo '接続失敗' . $e->getMessage(). '\n';
    exit();
}
?>
