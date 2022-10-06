<?php
    require_once("funzioniGetPHP.php");
    require_once("funzioniPHP.php");
    require_once("funzioniDeletePHP.php");


session_start();

if(isset($_SESSION['loginType'])){
    if($_SESSION['loginType']=="Admin" || $_SESSION['loginType']=="Concierge"){
        $accessoStaff="True";
    }
}

if(isset($_POST['bottonePremuto'])){
    $idFaqSelezionata=individuaBottoneIdFaq();
    if($_POST[$idFaqSelezionata]=="MODIFICA FAQ"){
        $_SESSION['idFaq']=$idFaqSelezionata;
        header('Location: modificaFaq.php');
        exit();
    }else{
        if(isset($_COOKIE['Cancella'])){
            unset($_COOKIE['Cancella']);
            setcookie('Cancella', '', time() - 3600, '/');
            rimuoviFAQ($idFaqSelezionata) ;
            header('Location: faq.php');
            exit();
    }
}
}

$faqs=getFAQs();

    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>FAQ</title>

        <style type="text/css">
            <?php include "../CSS/faq.css" ?>
         </style>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  

        <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

      </head>

    <body>
    <?php
            if(isset($accessoStaff)){
                ?>
            <form action="./aggiungiFaq.php" method="post"  >
                <?php
                    }
                ?>
        <div class="top">
            <div class="topLeft">               
                <a href="./intro.php">TORNA ALLA HOME</a>  
            </div>   
            <?php 
                if(isset($accessoStaff)){
                    echo '
                    <div>
                    <h1>FAQ</h1>
                      
                    </div>
                    <div>
                        <input type="submit" class="fakeLink" name="AGGIUNGI" value="AGGIUNGI FAQ" />
                    </div>
                    ';
                }
                else{
                    echo '
                    <h1 class="alignCenter">FAQ</h1>
                    <div style="width: 18.5%;"></div>';
                }
            ?>
            
        </div>
        <?php
            if(isset($accessoStaff)){
        ?>
            </form>
        <?php
            }
        ?>                            
        </div>

        <section>
            <?php 
                if(count($faqs)==0){
            ?>
                <p class="alignCenter scrittaCentrale">Non ci sono FAQ al momento...</p>
            <?php
                }
                for($i=0;$i<count($faqs);$i++){
                    $faq=$faqs[$i];

            ?>
            <?php
                        if(isset($accessoStaff)){
                ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
                <?php
                    }
                ?>
            <div class="container">
                <div class="innerContainer">
                    <div class="innerContainer-item" id="<?php echo $faq['id'];?>">
                        <a class="innerContainer-link" href="<?php echo "#".$faq['id'];?>">
                            <div class="domanda">
                                <?php echo $faq['testoDomanda'];?>
                            </div>
                            <?php
                                if(isset($accessoStaff)){
                            ?>
                            <button type="submit" class="button" name="<?php echo $faq['id'];?>" value="MODIFICA FAQ">
                            MODIFICA FAQ
                            </button>
                            <button type="submit" class="button" name="<?php echo $faq['id'];?>" onClick="myEvent()">
                                RIMUOVI FAQ
                            </button>
                            <?php
                                }
                            ?>
                            <i class="icon ion-md-add"></i>
                            <i class="icon ion-md-remove"></i>
                        </a>                        

                        <div class="risposta">
                            <?php echo $faq['testoRisposta'];?>
                        </div>
                    </div>                    
                </div>
            </div>
            <?php
            if(isset($accessoStaff)){
        ?>
                <input type="hidden" name="bottonePremuto"/>
            </form>
        <?php
            }
        }       
        ?>       
        </section>
        
        <script>
            function myEvent(){
                var choice =confirm("Confermi di voler rimuovere la FAQ?");
                if(choice == true){
                    document.cookie = "Cancella" + "=" + "Cancella" + "" + "; path=/";  
                }
            }
        </script>                           
    </body>
</html> 
