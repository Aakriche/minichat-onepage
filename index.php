<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <form class="formulaire" method="Post" action="index.php">
        <label>Pseudo</label>
        <input type="text" name="pseudo">
        <label>Message</label>
        <textarea id="message" name="message"> </textarea>
        <button name="submit-message">Poster</button>
    </form>

    <?php
    if(isset($_POST["submit-message"])){
        $searchSend = new PDO('mysql: host=localhost;dbname=chat', 'root', '');
        $pseudo = $_POST['pseudo'];
        $message = $_POST["message"];

        if(empty($pseudo) || !preg_match("/^[a-zA-ZÀ-ú0-9_ ]*$/", $pseudo)){
            header("Location: index.php?error=invalidnom");
            exit();       
        }else if (empty($message) || !preg_match("/^^[a-zA-ZÀ-ú0-9_ ?!.,\/()+\'-=\*€\}\{#]*$/", $message)){
            header("Location: index.php?error=invalidmessage");
            exit();
        }else{
            $sql= "INSERT INTO messages(pseudo, message) VALUES('".$pseudo."','".$message."');";
            $res= $searchSend->query($sql);
            if(!$res){
                header("Location: index.php?error=sqlerror");
                exit(); 
            }else{
                header("Location: index.php");
                exit();
            
         
            }
    $res->closeCursor();  }
    }
   
    ?>
    <?php   if (isset($_GET['error'])){
                            if($_GET['error']=="invalidnom"){
                                echo'<p class="msg-erreur">Pseudo invalide</p>';
                            } else if($_GET['error']=="invalidmessage"){
                                echo'<p class="msg-erreur">Message invalide !</p>';
                            } else if($_GET['error']=="sqlerror"){
                                echo'<p class="msg-erreur">erreur sql</p>';
                            }
                        }
                ?>
                <a href="index.php">Rafraichir</a>
    <div class="chat">

    <?php
    $searchSend = new PDO('mysql: host=localhost;dbname=chat', 'root', '');
        $searchResp = $searchSend->query("SELECT DISTINCT pseudo, message, DATE_FORMAT(date_creation, '%d/%m/%Y %Hh%imin%ss') AS date FROM messages ORDER BY id DESC LIMIT 0, 10");
        while($donnees = $searchResp->fetch()) {
            echo '<div class="box">
            <div class="pseudoBox"><p>'
                    .ucfirst($donnees['date'])
                    .'</p></div><div class="pseudoBox"><p>'
                    .ucfirst($donnees['pseudo'])
                    .'</p></div><div class="messageBox"><p>'
                    .$donnees['message']
                    .'</p></div></div>';
        }

        $searchResp->closeCursor();
    ?>

    </div>
    
</body>
</html>