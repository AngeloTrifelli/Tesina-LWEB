<?php   
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){
        individuaBottoneCategoriaPremuto();
       }


    if(isset($_SESSION['loginType'])){
        if($_SESSION['loginType']=="Admin"){

            $tabellaCategorie=getCategorie();
            $categorieAttivate=$tabellaCategorie[0];
            $categorieDisattivate=$tabellaCategorie[1];


        }else{
            header('Location: areaUtente.php');
            exit();
        }
        
    }else{
        header('Location: intro.php');
        exit();
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Categorie</title>

        <style>
            <?php include "../CSS/categorie.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
    </head>

    <body>
        <div class="top">
            <div class="topLeft">
                <a href="./areaUtente.php">TORNA NELL'AREA UTENTE</a>    
            </div>
            <h1 class="alignCenter">CATEGORIE DOMANDE</h1>
            <div style="width: 18.5%;"></div>
        </div>

        <?php
             if(count($categorieAttivate)>0){
                echo "<h3 class=\"titoloImportante alignCenter\">CATEGORIE ATTIVE:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter red\">NON CI SONO CATEGORIE ATTIVE AL MOMENTO</h3>";
                echo "<span class=\"trePuntini\">...</span>";
                }
            ?>
                <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
            <?php
            for($i=0;$i<count($categorieAttivate);$i++){
                $categoria=$categorieAttivate[$i];
        ?>

        <div class="mainContainer">
    
            <table class="categoria alignCenter" align="center">

                <tr>
                    <td>
                        <strong>Nome categoria:</strong><br />
                        <?php echo $categoria['nome'];?>
                    </td>
                    <td>

                        <input type="submit" class="buttonDisattiva" name="<?php echo $categoria['nome'];?>" value="Disattiva" />
                        <input type="hidden" name="bottonePremuto"/>

                    </td>
                </tr>


            </table>

        </div>

        <?php
            }
            if(count($categorieDisattivate)>0){
                echo "<h3 class=\"titoloImportante alignCenter\">CATEGORIE NON ATTIVE:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter green\">TUTTE LE CATEGORIE SONO ATTIVE AL MOMENTO</h3>";
                }
            for($i=0;$i<count($categorieDisattivate);$i++){
                $categoria=$categorieDisattivate[$i];
        ?>

        <div class="mainContainer">
    
            <table class="categoria alignCenter" align="center">

                <tr>
                    <td>
                        <strong>Nome categoria:</strong><br />
                        <?php echo $categoria['nome'];?>
                    </td>
                    <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
                    <td>

                        <input type="submit" class="buttonAttiva"  name="<?php echo $categoria['nome'];?>"  value="Attiva" />
                        <input type="hidden" name="bottonePremuto"/>

                    </td>
                    </form>
                </tr>

            </table>


        </div>

        <?php
            }
        ?>
        </form>
                <div>
                <h3 class="title alignCenter">AGGIUNGI CATEGORIA:</h3>
                </div>
                <div class="divText">
                <form action="./aggiungiCategoria.php"  method="post">
                    <input type="submit" class="continuaButton button" name="aggiungi" value="Aggiungi">
                </form>
                </div>

                <br />
                <br />
    </body>
</html>