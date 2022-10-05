<?php
   require_once('funzioniInsertPHP.php');

   session_start();

if(isset($_SESSION['loginType'])){
    if($_SESSION['loginType']=="Admin"){

        if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: categorie.php');
            exit();
        }
        else{
            $categoriaMancante="False";
            $nessunaCheckSelezionata="False";
            if($_POST['categoria']==""){
                $categoriaMancante="True";
            }else{
                if(!(isset($_POST['prenotazioneTavolo'])) && !(isset($_POST['prenotazioneServizioInCamera'])) && !(isset($_POST['attivita'])) && !(isset($_POST['prenotazioneSoggiorno'])) && !(isset($_POST['registrazione']))){
                    $nessunaCheckSelezionata="True";
                }else{
                    $categoriaDaAggiungere=array();
                    $categoriaDaAggiungere['nome']=$_POST['categoria'];
                    if(isset($_POST['prenotazioneTavolo'])){
                        $categoriaDaAggiungere['prenotazioneTavolo']=$_POST['prenotazioneTavolo'];
                    }else{
                        $categoriaDaAggiungere['prenotazioneTavolo']="";
                    }
                    if(isset($_POST['prenotazioneServizioInCamera'])){
                        $categoriaDaAggiungere['prenotazioneServizioInCamera']=$_POST['prenotazioneServizioInCamera'];
                    }else{
                        $categoriaDaAggiungere['prenotazioneServizioInCamera']="";
                    }
                    if(isset($_POST['attivita'])){
                        $categoriaDaAggiungere['attivita']=$_POST['attivita'];
                    }else{
                        $categoriaDaAggiungere['attivita']="";
                    }
                    if(isset($_POST['prenotazioneSoggiorno'])){
                        $categoriaDaAggiungere['prenotazioneSoggiorno']=$_POST['prenotazioneSoggiorno'];
                    }else{
                        $categoriaDaAggiungere['prenotazioneSoggiorno']="";
                    }
                    if(isset($_POST['registrazione'])){
                        $categoriaDaAggiungere['registrazione']=$_POST['registrazione'];
                    }else{
                        $categoriaDaAggiungere['registrazione']="";
                    }
                    aggiungiCategoriaNuova($categoriaDaAggiungere);
                    header('Location: categorie.php');
                    exit();
                }
            }

        }

    }
    }else{
        header('Location: areaUtente.php');
        exit();
    }
}else{
    header('Location: intro.php');
    exit();
}

    echo '<?xml version="1.0" encoding="UTF-8?>'
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Aggiungi una categoria</title>

    <style>
        <?php include "../CSS/aggiungiCategoria.css" ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet" />  
</head>

    <body>
        <div class="containerCentrale">

            <h1>AGGIUNGI UNA CATEGORIA:</h1>

            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <div class="zonaDati">
                    <div class="divSx">

                        <input type="text" class="textInput" placeholder="Inserisci la categoria:" name="categoria"/>
                        <?php

                        if(isset($_POST['CONFERMA']) && $categoriaMancante=="True"){

                                            echo "
                                                <p class=\"errorLabel alignLeft\">Inserire la categoria!</p> 
                                            ";
                                        }
                                    ?>

                    </div>



                    <div class="divDx">    
                                    
                        <h2>Che tipo di azioni sono correlate?</h2>
                        <input type="checkbox"  name="prenotazioneTavolo" value="prenotazioneTavolo" />
                        <label for="prenotazioneTavolo">Prenotazione tavolo</label><br /> 
                        <input type="checkbox"  name="prenotazioneServizioInCamera" value="prenotazioneServizioInCamera"/>
                        <label for="prenotazioneServizioInCamera">Prenotazione servizio in camera</label><br />
                        <input type="checkbox"  name="attivita" value="attivita"/>
                        <label for="attivita">Prenotazione attivita</label><br />
                        <input type="checkbox"  name="prenotazioneSoggiorno" value="prenotazioneSoggiorno"/>
                        <label for="prenotazioneSoggiorno">Prenotazione di un soggiorno</label><br />
                        <input type="checkbox"  name="registrazione" value="registrazione"/>
                        <label for="registrazione">Registrazione</label>
                        <?php

                        if(isset($_POST['CONFERMA']) && $nessunaCheckSelezionata=="True"){

                            echo "
                                <p class=\"errorLabel\">Inserire almeno una azione!</p> 
                            ";
                            }
                            ?>

                    

                    </div>

                
                    
                </div>

                <div class="zonaBottoni">
                    <input type="submit" class="button" value="ANNULLA" name="ANNULLA" /> 
                    <input type="submit" class="button" value="CONFERMA" name="CONFERMA" />
                </div>

            </form>

        </div>

    </body>

</html>