<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>m5-1</title>
</head>
<body>
    <div style="color: #658CFF">
    <h2>掲示板</h2>
    </div>
    <?php 
        //データベースに接続
        $dsn = 'mysql:dbname=XXXDB;host=localhost';
        $user = 'XXXUSER';
        $password = 'XXXPASSWORD';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = "CREATE TABLE IF NOT EXISTS mission5_01"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "date CHAR(32),"
    . "name CHAR(32),"
    . "comment TEXT,"
    . "pass CHAR(32)"
    .");";
    $stmt = $pdo->query($sql);
    

        if(empty($_POST["enum"])){    //編集対番号の中に何も入力されてなかったら、35行目までの内容を実行
    ?>
        
        <form action = "" method = "post"> <!--新規投稿用フォーム-->
        <input type = "text" name= "name" value = "名前" ><br>
        <input type = "text" name = "str" value = "コメント"><br>
        <input type = "text" name = "pass" placeholder = "パスワード">
        <input type = "submit" name = "submit"><br>
    </form>
    <form action = "" method = "post"> <!--削除用フォーム-->
        <input type = "number" name = "dnum" placeholder = "削除対象番号"><br> 
        <input type = "text" name = "dpass" placeholder = "パスワード">
        <input type = "submit" name = "delete" value = "削除" >
    </form>
    
    <form action = "" method = "post"> <!--編集用フォーム-->
        <input type = "number" name = "enum" placeholder = "編集対象番号"><br>
        <input type = "text" name = "epass" placeholder = "パスワード">
        <input type = "submit" name = "edit" value = "編集">
    </form>
    <?php
        }
        if(!empty($_POST["str"]) && empty($_POST["dnum"]) && empty($_POST["enum2"]) && !empty($_POST["pass"]) && empty($_POST["dpass"])){
          $date = date("y/m/d h:i:s"); 
          $comment = $_POST["str"];
          $name = $_POST["name"];
          $pass = $_POST["pass"];   
          //＝データベースのテーブルにレコードを追加する
          $sql = "INSERT INTO mission5_01 (name, comment, pass, date) VALUES(:name, :comment, :pass, :date)";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
          $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
          $stmt->bindParam(':date', $date, PDO::PARAM_STR);
          $stmt->execute();
        }  
        if(!empty($_POST["dnum"]) && !empty($_POST["dpass"])){
            $dnum = $_POST["dnum"];
            $dpass = $_POST["dpass"];
            
        //削除番号と投稿番号が一致するとき削除される
            $sql = 'delete from mission5_01 where id=:dnum AND pass=:dpass' ;
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dnum', $dnum, PDO::PARAM_INT);
            $stmt->bindParam(':dpass', $dpass, PDO::PARAM_INT);
            $stmt->execute();
        } //条件式が当てはまったら、以上の流れで削除対象の投稿を削除する

        if(!empty($_POST["enum"]) && !empty($_POST["epass"])){
            //編集対象番号と編集用パスワードに何か入力されていたら88行目までの内容を実行する
            $enum = $_POST["enum"];
            $epass = $_POST["epass"];//編集番号指定フォームの内容をPHPで受け取る
            //データベースから$enumと$id、$epassと$passが一致するレコードを取り出し,フォームに表示する
            $sql = 'SELECT * FROM mission5_01 WHERE id=:enum AND pass=:epass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':enum', $enum, PDO::PARAM_INT);
            $stmt->bindParam(':epass',$epass, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
                if($row){//配列を1行ずつ変数に代入していく、全て代入されるまで{}の中を繰り返す
    ?>
                <form action = "" method = "post" >
                    <input type = "hidden" name = "enum2" value = <?php echo $row['id']; ?>><br>
                    <input type = "text" name = "name" value = <?php echo $row['name']; ?>><br>
                    <input type = "text" name = "str" value = <?php echo $row['comment']; ?>>
                    <input type = "submit" name = "submit"><br>
                </form>
                <form action = "" method = "post">
                    <input type = "number" name = "dnum" placeholder = "削除対象番号">
                    <input type = "submit" name = "delete" value = "削除" >
                </form>
                <form action = "" method = "post">
                    <input type = "number" name = "enum" placeholder = "編集対象番号">
                    <input type = "submit" name = "edit" value = "編集">
                </form>
    <?php
                }
        }
        if(!empty($_POST["enum2"])){//編集される投稿の番号が空欄じゃなかったら108行目までの内容を実行する
            $enum2 = $_POST["enum2"];
            $name2 = $_POST["name"];
            $comment2 = $_POST["str"];//編集用投稿フォームの内容をPHPで受けとる
            $date = date("y/m/d h:i:s");
            $sql = 'UPDATE mission5_01 SET name=:name2,comment=:comment2,date=:date WHERE id=:enum2';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name2', $name2, PDO::PARAM_STR);
            $stmt->bindParam(':comment2',$comment2, PDO::PARAM_STR);
            $stmt->bindParam(':enum2',$enum2, PDO::PARAM_STR);
            $stmt->bindParam(':date',$date, PDO::PARAM_STR);
            $stmt->execute();
                //$lineの投稿番号と編集される投稿の番号が同じ時だけ編集用投稿フォームに入ってる内容に書き換える
         
                    
        }//条件式が当てはまったら、以上の流れで編集対象の投稿を書き換える
    

          //データベースの内容を表示する
          $sql = 'SELECT * FROM mission5_01';
          $stmt = $pdo->query($sql);
          $results = $stmt->fetchAll();
          foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].'<br>';
            echo $row['date'].'<br>';
            echo "<hr>";
          }
        
    ?> 
    </body>
</html>