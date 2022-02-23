<?php
/*
* DBに保存する関数
*/
function insertContact($request) //$_POSTが渡ってくる($requestという名前で使う)
{
    //DB接続
    require 'db_connection.php';

    // 入力 DB保存

    //例用のサンプル配列 こんな感じで受け取ったデータを配列化する
    // ※ 自動設定されるid,created_at,updated_atは含めなくてよい
//     $params = [
    //   'your_name'=>'名前',
    //   'email'=>'test@test.com',
    //   'url'=> 'https://test.com',
    //   'gender'=>'1',
    //   'age'=>'2',
    //   'contact'=>'内容'
    // ];

    $params = [
  'your_name'=>$request['your_name'],
  'email'=>$request['email'],
  'url'=> $request['url'],
  'gender'=>$request['gender'],
  'age'=>$request['age'],
  'contact'=>$request['contact']
];



    // prepare用のinsert文を生成
    $columns = '';
    $values = '';
    $count = 0;
    foreach ($params as $key => $value) {
        if ($count > 0) {
            $columns .= ',';
            $values .= ',';
        }
  
        $columns .= $key;
        $values .= ':'.$key;
        $count++;
    }
    $sql = 'insert into contacts ('.$columns.')values('.$values.')' ;
    //↓これで各項目全てをプレースホルダとしたinsert文ができる
    //  "insert into contacts (id,your_name,email,url,gender,age,contact)values(:id,:your_name,:email,:url,:gender,:age,:contact)"

    // sqlを登録
    // プレースホルダの項目全てが、文字列のときは、bindValueせずに直接、prepareできる(連想配列をexecuteの引数に渡すこと)
    $stmt =  $pdo->prepare($sql);
    // 実行 連想配列を渡す
    $stmt->execute($params);
    // exit;
}

// // トランザクション開始
// $pdo->beginTransaction();
// try {
//     // プリペアードステートメント sqlの紐付け
//     $stmt = $pdo->prepare($sql);
//     // プレースホルダに値を設定 //第三引数は型の指定
//     $stmt->bindValue('id', 1, PDO::PARAM_INT);
//     //実行
//     $stmt->execute();
//     // トランザクション実行
//     $pdo->commit();
// } catch (PDOException $e) {
//     $pdo->rollBack();
// }
