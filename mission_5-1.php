<DOCTYPE html>
        
        <html lang="ja">
            
            <head >
                <meta charset="UTF-8">
                <title></title>
            </head>
            <form action=""method="post">
                <p>【新規投稿】</p>
                <input type="text" name="name" placeholder="名前">
                <br>
                <input type="text" name="comment" placeholder="コメント">
                <br>
                <input type="text" name="password1" placeholder="password">
                <br>
                <input type="submit" name="submit" value="送信">
                <br>
                <p>【コメント削除】</p>
                <input type="number" name="deleteno" placeholder="削除番号">
                <br>
                <input type="text" name="password2" placeholder="password">
                <input type="submit" name="submit" value="削除">
                <br>
                <p>【編集先呼び出し】</p>
                <input type="number" name="editnum" placeholder="編集番号">
                <br>
                <input type="text" name="password3" placeholder="password">
                <input type="submit" name="submit" value="編集">
            </form>

            <body>
               <?php
//                echo "接続開始"."<br>";
                $dsn = 'Server Name';
                $user = 'User Name';
                $password = 'Password';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//                echo "接続成功"."<br>";

                $sql = "CREATE TABLE IF NOT EXISTS mission_5_need_password"
                ." ("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "name char(32),"
                . "comment TEXT,"
                . "password TEXT,"
                . "date datetime"
                .");";
                $stmt = $pdo->query($sql);
//                echo "テーブル作成";
//新規投稿~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~               
    //form欄の空欄判定------------------------------------------------------------------------------------------------------------
                if(!empty($_POST["name"]) && !empty($_POST["comment"])&& !empty($_POST["password1"])){
    
    //変数の定義----------------------------------------
                    $post_name = $_POST["name"];
                    $post_com = $_POST["comment"];
                    $pass1=$_POST["password1"];
                    $date_create=date("Y-m-d H:i:s");
//                    echo $date_create;

    //データベースへの接続---------------------------------------------------------------------------------------------------------
//                    echo "接続開始";
                    $dsn = 'Server Name';
                    $user = 'User Name';
                    $password = 'Password';
                    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//                    echo "接続処理終了";
    
    //書き込み---------------------------------------------------------------------------------------------------------------------
                	$sql = $pdo -> prepare("INSERT INTO mission_5_need_password (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
	                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	                $name = $post_name;
                    $comment = $post_com;
                    $pass = $pass1;
                    $date = $date_create; 
                    $sql -> execute();
    
    //投稿内容確認------------------------------------------------------------------------------------------------------------------
                    $sql = 'SELECT * FROM mission_5_need_password WHERE name = :name';
                    $stmt = $pdo->prepare($sql);                  
                    $stmt->bindParam(':name', $name, PDO::PARAM_INT); 
                    $stmt->execute();                             
                    $results = $stmt->fetchAll(); 
    
                    foreach ($results as $row){
        
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
	                    echo "<hr>";

                    }

//                    echo $post_name;
                    echo "投稿完了";


                }

//削除(完全削除)~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /* 
    //formの空欄判定-------------------------------------------------------------------------
                if(!empty($_POST["deleteno"])){
    //変数の定義-----------------------------------------------------------------------------
                    $delnum = $_POST["deleteno"];
    //データベースへの接続-------------------------------------------------------------------
                $dsn = 'Server Name';
                $user = 'User Name';
                $password = 'Password';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //idの一致した行を削除----------------------------------------------------------------
                    $id = $delnum ; 
                    $sql = 'delete from mission_5 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    echo $delnum . "を削除したよ～～～";
                }
    */

//削除(削除済みに書き換え)~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     
    //formの空欄判定-------------------------------------------------------------------------
                if(!empty($_POST["deleteno"]) && !empty($_POST["password2"])){
    //変数の定義-----------------------------------------------------------------------------
                    $delnum = $_POST["deleteno"];
                    $DeleName = "";
                    $DeleCom = "削除したよ～～";
                    $Deledate = date("Y-m-d H:i:s");

    //データベースへの接続-------------------------------------------------------------------
                    $dsn = 'Server Name';
                    $user = 'User Name';
                    $password = 'Password';
                    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

                    $sql = 'SELECT * FROM mission_5_need_password WHERE id = :id';
                    $stmt = $pdo->prepare($sql);                  
                    $stmt->bindParam(':id', $delnum, PDO::PARAM_INT); 
                    $stmt->execute();                             
                    $results = $stmt->fetchAll(); 
    
                    foreach ($results as $row){
        
                        $delpass = $row['password'];
                    //    echo $row['id'].',';
                    //    echo $row['name'].',';
                    //    echo $row['comment'].',';
                    //    echo $row['date'].'<br>';
                    //    echo "<hr>";

                    }

                    if($delpass == $_POST["password2"]){

    //データベ－ス内容の書き換え------------------------------------------------------------------------------
                        $sql = 'UPDATE mission_5_need_password SET name=:name,comment=:comment,date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $DeleName, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $DeleCom, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $Deledate, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
                        $stmt->execute();
/*削除後のブラウザ表示が動作していない
    //投稿内容確認------------------------------------------------------------------------------------------------------------------
                        $sql = 'SELECT * FROM mission_5_need_password WHERE id = :id';
                        $stmt = $pdo->prepare($sql);                  
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                        $stmt->execute();                             
                        $results = $stmt->fetchAll(); 
        
                        foreach ($results as $row){
            
                            echo $row['id'].',';
                            echo $row['name'].',';
                            echo $row['comment'].',';
                            echo $row['date'].'<br>';
                            echo "<hr>";
    
                        }
*/    
                        echo "削除成功や！！！";

                    }else{
                        echo "password が違います";
                    }
                }elseif(!empty($_POST["deleteno"]) && empty($_POST["password2"])){
                    echo "passwordを入力してください！";
                }


//編集~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //formの空欄判定-----------------------------------------------------
                if(!empty($_POST["editnum"])){

    //変数の定義---------------------------------------------------------
                    $id = $_POST["editnum"];

    //データベースへの接続-------------------------------------------------------------------
                    $dsn = 'Server Name';
                    $user = 'User Name';
                    $password = 'Password';
                    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //データベースの中身を表示----------------------------------------------------------------


                    $sql = 'SELECT * FROM mission_5_need_password WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                    $stmt->execute();                             
                    $results = $stmt->fetchAll(); 
    
                    foreach ($results as $row){
                    
                        $editid = $row['id'];
                        $editname = $row['name'];
                        $editcom = $row['comment'];
                        $editpass = $row['password'];
                        echo $row['id'].',';
/*                      echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
	                    echo "<hr>";
*/
                    }
    //編集用フォームにテキストを表示---------------------------------------------------------------                
               ?>
            <?php if($editpass == $_POST["password3"]): ?>

            <form action=""method="post">
                <input type="number" name="editnumber" value=<?php echo $editid?>>
                <br>
                <input type="text" name="Editname" value=<?php echo $editname?>>
                <br>
                <input type="text" name="edit_text" value=<?php echo $editcom?>>
                <br>
<!--
                   <input type="text" name="password4" placeholder="password">
-->    
                <input type="submit" name="submit" value="編集内容送信">
            </form>
               
            <?php endif; ?>
            <?php   if($editpass != $_POST["password3"]){
                        echo "passwordが違います";
                    }
            ?>

<!--php開始

    //編集用フォームの空欄判定-----------------------------------------------------------------------------------------
-->
           <?php
            } 
                if(!empty($_POST["editnumber"]) && !empty($_POST["Editname"]) && !empty($_POST["edit_text"])){

                
                        $Editid = $_POST["editnumber"];
                        $EditName = $_POST["Editname"];
                        $EditCom = $_POST["edit_text"]; 
                        $editdate=date("Y-m-d H:i:s");

    //データベースへの接続-------------------------------------------------------------------
                        $dsn = 'Server Name';
                        $user = 'User Name';
                        $password = 'Password';
                        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
/*
                        $Editid = $_POST["editnumber"]
                        $EditName = $_POST["Editname"];
                        $EditCom = $_POST["edit_text"]; 
                        $editdate=date("Y-m-d H:i:s");
*/
    //データベ－ス内容の書き換え------------------------------------------------------------------------------
                        $sql = 'UPDATE mission_5_need_password SET name=:name,comment=:comment,date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $EditName, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $EditCom, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $editdate, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $Editid, PDO::PARAM_INT);
                        $stmt->execute();

                        echo "編集成功や！！！";

                }
                
               ?>
                
            </body>
            
        </html>