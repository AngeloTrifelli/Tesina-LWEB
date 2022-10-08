<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniModificaPHP.php');
    require_once('funzioniPHP.php');
    session_start();

    if(isset($_SESSION['loginType'])){
        if($_SESSION['loginType'] == "Cliente"){
            header('Location: areaUtente.php');
            exit();
        }
    }
    else{
        header('Location: login.php');
        exit();
    }

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA']) ){
        if(isset($_POST['ANNULLA'])){
            header('Location: areaUtente.php');
            exit();
        }
        else{
            if($_POST['oldUsername'] != "" && $_POST['oldPassword'] != ""  && ($_POST['newUsername'] != "" || $_POST['newPassword'] != "" ) ){
                if($_SESSION['loginType'] == "Concierge"){
                    $trovato = eseguiLoginStaff($_POST['oldUsername'], md5($_POST['oldPassword']), "Concierge");
                    if($trovato == "True"){
                        if($_POST['newUsername'] != "" ){
                            $duplicato = checkUsernameDuplicatoStaff($_POST['username'] , "Concierge" );
                        }                    
                        else{
                            $duplicato = "False";
                        }
    
                        if($duplicato == "False"){
                            if($_POST['newPassword'] != ""){
                                modificaCredenzialiStaff($_POST['oldUsername'] , $_POST['newUsername'] , md5($_POST['newPassword']) , "Concierge");
                            }            
                            else{
                                modificaCredenzialiStaff($_POST['oldUsername'] , $_POST['newUsername'] , "" , "Concierge");
                            }                
                            header('Location: areaUtente.php');
                            exit();
                        }
                        else{
                            $erroreDuplicato = "True";
                        }
                    }
                    else{
                        $erroreLogin = "True";
                    }                    
                }
                else{
                    $trovato = eseguiLoginStaff($_POST['oldUsername'], md5($_POST['oldPassword']), "Admin");
                    if($trovato == "True"){                        
                        if($_POST['newUsername'] != "" ){
                            $duplicato = checkUsernameDuplicatoStaff($_POST['newUsername'] , "Admin" );
                        }                    
                        else{
                            $duplicato = "False";
                        }                        
    
                        if($duplicato == "False"){
                            if($_POST['newPassword'] != ""){
                                modificaCredenzialiStaff($_POST['oldUsername'] , $_POST['newUsername'] , md5($_POST['newPassword']) , "Admin");
                            }            
                            else{
                                modificaCredenzialiStaff($_POST['oldUsername'] , $_POST['newUsername'] , "" , "Admin");
                            }                            
                            header('Location: areaUtente.php');
                            exit();
                        }
                        else{
                            $erroreDuplicato = "True";
                        }
                    }
                    else{
                        $erroreLogin = "True";
                    }      
                }
            }
        }
    }



    echo '<?xml version="1.0" encoding="UTF-8?>'
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Modifica dati</title>

    <style>
        <?php include "../CSS/modificaCredenzialiStaff.css" ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
</head>

<body>
    <div class="containerCentrale">
    <h1>MODIFICA CREDENZIALI:</h1>
        <div class="zonaDati">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <div class="zonaInput">                    
                <?php
                    if(isset($_POST['oldUsername'])){
                        echo "<input type=\"text\" class=\"textInput\" style=\"margin-right: 4%;\" placeholder=\"Inserisci il vecchio username\" name=\"oldUsername\" value=\"{$_POST['oldUsername']}\"    /> ";
                    }
                    else{
                        echo "<input type=\"text\" class=\"textInput\" style=\"margin-right: 4%;\" placeholder=\"Inserisci il vecchio username\" name=\"oldUsername\" /> ";
                    }

                    if(isset($_POST['CONFERMA']) && $_POST['oldUsername'] == "" ){
                        echo "<p class=\"errorLabel\">Inserire il vecchio username!</p>";
                    }
                ?>
                    <div class="containerPassword">
                        <input class="textInput" id="oldPassword" autocomplete="current-password" type="password" name="oldPassword" placeholder="Inserisci la vecchia password" />
                        <i class="far fa-eye occhio" id="toggleOldPassword"></i>
                    </div>      
                    
                <?php
                    if(isset($_POST['CONFERMA']) && $_POST['oldPassword'] == "" ){
                        echo "<p class=\"errorLabel\">Inserire la vecchia password!</p>";
                    }
                
                    echo '<br />';

                    if(isset($_POST['newUsername'])){
                        echo "<input type=\"text\" class=\"textInput\" style=\"margin-right: 4%;\" placeholder=\"Inserisci il nuovo username\" name=\"newUsername\" value=\"{$_POST['newUsername']}\"    /> ";
                    }
                    else{
                        echo "<input type=\"text\" class=\"textInput\" style=\"margin-right: 4%;\" placeholder=\"Inserisci il nuovo username\" name=\"newUsername\" /> ";
                    }

                    if(isset($_POST['CONFERMA']) && isset($erroreDuplicato)){
                        echo "<p class=\"errorLabel\">Il nuovo username scelto è già in uso!</p>";
                    }
                ?>                   
                                                    
                    <div class="containerPassword">
                        <input class="textInput" type="password" id="newPassword" autocomplete="current-password" name="newPassword" placeholder="Inserisci la nuova password" />  
                        <i class="far fa-eye occhio" id="toggleNewPassword"></i> 
                    </div>            
                                    
                </div>

                <?php
                    if(isset($_POST['CONFERMA'])  && isset($erroreLogin)){
                        echo "<p class=\"errorLabel\">Le vecchie credenziali inserite sono errate!</p>";
                    }

                    if(isset($_POST['CONFERMA']) && $_POST['newUsername'] == "" && $_POST['newPassword'] == "" ){
                        echo "<p class=\"errorLabel\">Inserire almeno una nuova credenziale!</p>";
                    }
                ?>
                <div class="zonaBottoni">
                    <input type="submit" class="button" value="ANNULLA" name="ANNULLA" /> 
                    <input type="submit" class="button" value="CONFERMA" name="CONFERMA" />                                          
                </div>
                <p><strong>N.B: Lasciare vuoti i campi delle nuove credenziali che non si vuole modificare</strong></p>

            </form>
        </div>
    </div>
    
        <script>
                const toggleOldPassword = document.querySelector('#toggleOldPassword');
                const oldPassword = document.querySelector('#oldPassword');

                const toggleNewPassword = document.querySelector('#toggleNewPassword');
                const newPassword = document.querySelector('#newPassword');

                toggleOldPassword.addEventListener('click', function (e) {            
                const type = oldPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                oldPassword.setAttribute('type', type);            
                this.classList.toggle('fa-eye-slash');
                });

                toggleNewPassword.addEventListener('click', function (e) {            
                const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                newPassword.setAttribute('type', type);            
                this.classList.toggle('fa-eye-slash');
                });
        </script>
    
    


    </body>
</html>