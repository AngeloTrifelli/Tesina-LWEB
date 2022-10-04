<?php
   require_once('funzioniGetPHP.php');
   require_once('funzioniPHP.php');

   session_start();

   if(isset($_POST['bottonePremuto'])){
    individuaBottonePagamentoPremuto();
   }



   if(isset($_SESSION['loginType'])){
       if($_SESSION['loginType']=="Admin"){

            $tabellaSoggiorni=getSoggiorni();
            $soggiorniApprovati=$tabellaSoggiorni[0];
            $soggiorniSospesi=$tabellaSoggiorni[1];
            $soggiorniRifiutati=$tabellaSoggiorni[2];
           
       }else{
           header('Location: areaUtente.php');
       }
       
   }else{
       header('Location: intro.php');
   }

  


    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Pagamenti clienti</title>

        <style>
            <?php include "../CSS/pagamentiClienti.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>

    <body>
        <div class="top">
            <div class="topLeft">
                <a href="./areaUtente.php">TORNA NELL'AREA UTENTE</a>    
            </div>
            <h1 class="alignCenter">LISTA STATI SOGGIORNO CLIENTI</h1>
            <div style="width: 18.5%;"></div>
        </div>

        <?php
             if(count($soggiorniSospesi)>0){
                echo "<h3 class=\"titoloImportante alignCenter\">SOGGIORNI SOSPESI:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter\">NON CI SONO SOGGIORNI SOSPESI AL MOMENTO</h3>";
                echo "<span class=\"trePuntini\">...</span>";
                }
            for($i=0;$i<count($soggiorniSospesi);$i++){
                $soggiorno=$soggiorniSospesi[$i];
                $cliente=getDatiCliente($soggiorno['codFisc']);
                $numeroCamera=substr($soggiorno['idPrenotazione'],0, 4);
        ?>

        <div class="mainContainer">
        
            <table class="prenotazione alignCenter" align="center">
                <tr>
                <td>
                    <strong>Codice Fiscale Cliente:</strong><br />
                            <?php echo $soggiorno['codFisc'];?>
                                            
                    </td>
                    <td>
                        <strong>Carta di credito:</strong><br />
                        <?php echo $cliente['numeroCarta'];?>
                    </td>
                    <td>
                        <strong>Numero Camera:</strong><br />
                        <?php echo $numeroCamera?>
                    </td>
                    
                    <td>
                        <strong>Stato soggiorno:</strong><br />
                       
                            <span class="waiting"><?php echo $soggiorno['statoSoggiorno'];?></span>
                       
                    </td>
                    <td>
                        <strong>Inizio:</strong><br />
                        
                        <?php
                                            $stringaData = $soggiorno['dataArrivo'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                    </td>
                    <td>
                        <strong>Fine:</strong><br />
                        <?php
                                            $stringaData = $soggiorno['dataPartenza'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                    </td>
                    <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
                    <td>

                        <input type="submit" class="buttonApprova" name="<?php echo $soggiorno['idPrenotazione'];?>" value="Approva" />
                        <input type="hidden" name="bottonePremuto"/>

                    </td>
                    <td>
                    
                        <input type="submit" class="button" name="<?php echo $soggiorno['idPrenotazione'];?>" value="Rifiuta" />

                    </td>
                    </form>
                </tr>
            </table>

        </div>

        <?php 
            }
            if(count($soggiorniApprovati)>0){
                echo "<h3 class=\"titoloImportante alignCenter\">SOGGIORNI APPROVATI:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter\">NON CI SONO SOGGIORNI APPROVATI AL MOMENTO</h3>";
                echo "<span class=\"trePuntini\">...</span>";
                }
            for($i=0;$i<count($soggiorniApprovati);$i++){
                $soggiorno=$soggiorniApprovati[$i];
                $cliente=getDatiCliente($soggiorno['codFisc']);
                $numeroCamera=substr($soggiorno['idPrenotazione'],0, 4);
        ?>
        
        <div class="mainContainer">
        
        <table class="prenotazione alignCenter" align="center">
            <tr>
            <td>
                <strong>Codice Fiscale Cliente:</strong><br />
                        <?php echo $soggiorno['codFisc'];?>
                                        
                </td>
                <td>
                    <strong>Carta di credito:</strong><br />
                    <?php echo $cliente['numeroCarta'];?>
                </td>
                <td>
                    <strong>Numero Camera:</strong><br />
                    <?php echo $numeroCamera?>
                </td>
                
                <td>
                    <strong>Stato soggiorno:</strong><br />
                   
                        <span class="success"><?php echo $soggiorno['statoSoggiorno'];?></span>
                   
                </td>
                <td>
                    <strong>Inizio:</strong><br />
                    
                    <?php
                                            $stringaData = $soggiorno['dataArrivo'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                </td>
                <td>
                    <strong>Fine:</strong><br />
                    <?php
                                            $stringaData = $soggiorno['dataPartenza'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                </td>
            </tr>
        </table>
    </div>
        <?php 
            }
            if(count($soggiorniRifiutati)>0){
                echo "<h3 class=\"titoloImportante alignCenter\">SOGGIORNI RIFIUTATI:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter\">NON CI SONO SOGGIORNI RIFIUTATI AL MOMENTO</h3>";
                echo "<span class=\"trePuntini\">...</span>";
                }
            for($i=0;$i<count($soggiorniRifiutati);$i++){
                $soggiorno=$soggiorniRifiutati[$i];
                $cliente=getDatiCliente($soggiorno['codFisc']);
                $numeroCamera=substr($soggiorno['idPrenotazione'],0, 4);
        ?>


<div class="mainContainer">
        
        <table class="prenotazione alignCenter" align="center">
            <tr>
            <td>
                <strong>Codice Fiscale Cliente:</strong><br />
                        <?php echo $soggiorno['codFisc'];?>
                                        
                </td>
                <td>
                    <strong>Carta di credito:</strong><br />
                    <?php echo $cliente['numeroCarta'];?>
                </td>
                <td>
                    <strong>Numero Camera:</strong><br />
                    <?php echo $numeroCamera?>
                </td>
                
                <td>
                    <strong>Stato soggiorno:</strong><br />
                   
                        <span class="insuccess"><?php echo $soggiorno['statoSoggiorno'];?></span>
                   
                </td>
                <td>
                    <strong>Inizio:</strong><br />
                    
                    <?php
                                            $stringaData = $soggiorno['dataArrivo'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                </td>
                <td>
                    <strong>Fine:</strong><br />
                    <?php
                                            $stringaData = $soggiorno['dataPartenza'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                </td>
            </tr>
        </table>
    </div>
    
            <?php 
            }
            ?>
    </body>
</html>