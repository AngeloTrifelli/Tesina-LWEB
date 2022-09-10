<?php
// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER INSERIRE DATI NEI FILE XML


//Funzione che crea un nuovo utente all'interno del file Clienti.xml
function nuovoCliente(){
    session_start();

    $xmlStringClienti = "";

    foreach(file("../XML/Clienti.xml") as $node){

        $xmlStringClienti .= trim($node);

    }
            
            $docClienti = new DOMDocument();
            $docClienti->loadXML($xmlStringClienti);
            $docClienti->formatOutput = true;

                $anno = mb_substr($_SESSION['dataNascita'], 0 , 4);
                $mese = mb_substr($_SESSION['dataNascita'], 5 , 2);
                $giorno = mb_substr($_SESSION['dataNascita'], 8 , 2);

                $currentDate= date('Y-m-d');

                $nuovoCliente = $docClienti->createElement("cliente");
                $nuovoCliente->setAttribute("codFisc", $_SESSION['codFisc']);
                $listaClienti = $docClienti->documentElement;
                $listaClienti->appendChild($nuovoCliente);

                $nuovoNome = $docClienti->createElement("nome", $_SESSION['nome']);
                $nuovoCliente->appendChild($nuovoNome);

                $nuovoCognome = $docClienti->createElement("cognome", $_SESSION['cognome']);
                $nuovoCliente->appendChild($nuovoCognome);

                $nuovaDataNascita = $docClienti->createElement("dataDiNascita", $anno."-".$mese."-".$giorno);
                $nuovoCliente->appendChild($nuovaDataNascita);

                $nuovoIndirizzo = $docClienti->createElement("indirizzo", $_SESSION['indirizzo']);
                $nuovoCliente->appendChild($nuovoIndirizzo);

                $nuovoTelefono = $docClienti->createElement("telefono", $_SESSION['telefono']);
                $nuovoCliente->appendChild($nuovoTelefono);

                $nuovaEmail = $docClienti->createElement("email", $_SESSION['email']);
                $nuovoCliente->appendChild($nuovaEmail);

                $nuovoNumCarta = $docClienti->createElement("numeroCarta", $_SESSION['numeroCarta']);
                $nuovoCliente->appendChild($nuovoNumCarta);

                $nuoveCredenziali = $docClienti->createElement("credenziali");
                $nuovoCliente->appendChild($nuoveCredenziali);

                $nuovoUsername = $docClienti->createElement("username", $_POST['username']);
                $nuoveCredenziali->appendChild($nuovoUsername);

                $nuovaPassword = $docClienti->createElement("password", md5($_POST['password']));
                $nuoveCredenziali->appendChild($nuovaPassword);

                $nuoviCrediti = $docClienti->createElement("crediti", 0);
                $nuovoCliente->appendChild($nuoviCrediti);

                $nuovaDataAssegnazioneCrediti = $docClienti->createElement("dataAssegnazioneCrediti", $currentDate);
                $nuovoCliente->appendChild($nuovaDataAssegnazioneCrediti);

                $nuovaSommaGiudizi= $docClienti->createElement("sommaGiudizi", 0);
                $nuovoCliente->appendChild($nuovaSommaGiudizi);

                $docClienti->save("../XML/Clienti.xml");

}
?>