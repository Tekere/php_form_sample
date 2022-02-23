<?php
session_start();

require 'validation.php';

header('X-FRAME-OPTIONS:DENY');


// 連想配列
// if (!empty($_POST)) {
//     echo '<pre>';
//     var_dump($_POST) ;
//     echo '</pre>';
// }

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


// 入力、確認、完了

// フォームのステートを定数化
const PAGE_FLAG_INPUT = 0;
const PAGE_FLAG_CONFIRM = 1;
const PAGE_FLAG_COMPLETE = 2;

$pageFlag = PAGE_FLAG_INPUT;
$errors = validation($_POST);

if (!empty($_POST['btn_confirm']) && empty($errors)) {
    $pageFlag = PAGE_FLAG_CONFIRM;
}
if (!empty($_POST['btn_submit'])) {
    $pageFlag = PAGE_FLAG_COMPLETE;
}


?>

<!DOCTYPE html>
<meta charset="utf-8">

<head>
  <style>
    .errors{
      color: red;
    }
  </style>
</head>

<body>


  <?php if ($pageFlag === PAGE_FLAG_CONFIRM) : ?>
  <?php if ($_POST['csrf'] === $_SESSION['csrfToken']) :?>
  <form method="POST" action="">
    氏名
    <?php echo h($_POST['your_name']) ;?>
    <br>
メールアドレス
    <?php echo h($_POST['email']) ;?>
    <br>
ホームページ
    <?php echo h($_POST['url']) ;?>
    <br>
性別
    <?php
  if ($_POST['gender'] === '0') {
      echo '男性';
  }
  if ($_POST['gender'] === '1') {
      echo '女性';
  }
?>
    <br>
年齢
    <?php
  if ($_POST['age'] === '1') {
      echo '〜19歳' ;
  }
  if ($_POST['age'] === '2') {
      echo '20歳〜29歳' ;
  }
  if ($_POST['age'] === '3') {
      echo '30歳〜39歳' ;
  }
  if ($_POST['age'] === '4') {
      echo '40歳〜49歳' ;
  }
  if ($_POST['age'] === '5') {
      echo '50歳〜59歳' ;
  }
  if ($_POST['age'] === '6') {
      echo '60歳〜' ;
  }
?>

    <br>
お問い合わせ内容
    <?php echo h($_POST['contact']) ;?>
    <br>

    <input type="submit" name="back" value="戻る">
    <input type="submit" name="btn_submit" value="送信する">
    <input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']) ;?>">
    <input type="hidden" name="email" value="<?php echo h($_POST['email']) ;?>">
    <input type="hidden" name="url" value="<?php echo h($_POST['url']) ;?>">
    <input type="hidden" name="gender" value="<?php echo h($_POST['gender']) ;?>">
    <input type="hidden" name="age" value="<?php echo h($_POST['age']) ;?>">
    <input type="hidden" name="contact" value="<?php echo h($_POST['contact']) ;?>">
    <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']) ;?>">
  </form>

  <?php endif; ?>

  <?php endif; ?>

  <?php if ($pageFlag === PAGE_FLAG_COMPLETE) : ?>
  <?php if ($_POST['csrf'] === $_SESSION['csrfToken']) :?>

  <!-- DB接続 保存 -->
  <?php
  require 'mainte/insert.php';

  insertContact($_POST)
  ?>
  送信が完了しました。

  <?php unset($_SESSION['csrfToken']); ?>
  <?php endif; ?>
  <?php endif; ?>


  <?php if ($pageFlag === PAGE_FLAG_INPUT) : ?>
  <?php
if (!isset($_SESSION['csrfToken'])) {
      $csrfToken = bin2hex(random_bytes(32));
      $_SESSION['csrfToken'] = $csrfToken;
  }
$token = $_SESSION['csrfToken'];
?>

  <?php if (!empty($errors) && !empty($_POST['btn_confirm'])) : ?>
  <?php echo '<ul class="errors">' ;?>
  <?php
  foreach ($errors as $error) {
      echo '<li>' . $error . '</li>' ;
  }
?>
  <?php echo '</ul>' ; ?>

  <?php endif ;?>

  <form method="POST" action="">
    氏名
    <input type="text" name="your_name" value="<?php if (!empty($_POST['your_name'])) {
    echo h($_POST['your_name']) ;
} ?>">
    <br>
メールアドレス
    <input type="email" name="email" value="<?php if (!empty($_POST['email'])) {
    echo h($_POST['email']) ;
} ?>">
    <br>
ホームページ
    <input type="url" name="url" value="<?php if (!empty($_POST['url'])) {
    echo h($_POST['url']) ;
} ?>">
    <br>
性別
    <input type="radio" name="gender" value="0"
<?php if (isset($_POST['gender']) && $_POST['gender'] === '0') {
    echo 'checked';
} ?>>男性
    <input type="radio" name="gender" value="1"
<?php if (isset($_POST['gender']) && $_POST['gender'] === '1') {
    echo 'checked';
} ?>>女性
    <br>
年齢
    <select name="age">
      <option value="">選択してください</option>
      <option value="1">〜19歳</option>
      <option value="2">20歳〜29歳</option>
      <option value="3">30歳〜39歳</option>
      <option value="4">40歳〜49歳</option>
      <option value="5">50歳〜59歳</option>
      <option value="6">60歳〜</option>
    </select>
    <br>
お問い合わせ内容
    <textarea name="contact">
<?php if (!empty($_POST['contact'])) {
    echo h($_POST['contact']) ;
} ?>
</textarea>
    <br>
    <input type="checkbox" name="caution" value="1">注意事項にチェックする
    <br>

    <input type="submit" name="btn_confirm" value="確認する">
    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
  </form>
  <?php endif; ?>

</body>

</html>