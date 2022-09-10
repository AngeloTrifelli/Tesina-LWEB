<?php
// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER ESTRARRE DATI DA I FILE XML

// Funzione per verificare se il cliente che ha effettuato il login ha un soggiorno attivo al momento (cioè con pagamento approvato)

function getSoggiornoAttivo($codFiscCliente){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathCamere = new DOMXPath($doc);

    $idPrenotazione = $xpathCamere->query("/listaCamere/Camera/listaPrenotazioni/prenotazione[codFisc='$codFiscCliente' and statoSoggiorno='Approvato']/idPrenotazione");
    if($idPrenotazione->length == 1){
        return $idPrenotazione->item(0)->textContent;
    }
    else{
        return "null";      //Questo significa che non è stato trovato nessun soggiorno attivo
    }
}

//Funzione per verificare se il cliente con codice fiscale passato come parametro è presente nel file XML

function getCodFisc($codFiscCliente){
    $trovato="False";
    $xmlStringClienti = "";
            foreach(file("../XML/Clienti.xml") as $node){
                $xmlStringClienti .= trim($node);
            }

            $docClienti = new DOMDocument();
            $docClienti->loadXML($xmlStringClienti);

            $listaClienti = $docClienti->documentElement->childNodes;
            $i = 0;
            while($i < $listaClienti->length && $trovato == "False"){         
                $cliente = $listaClienti->item($i);
                $codFiscale = $cliente->getAttribute("codFisc");
                if($codFiscale == $codFiscCliente){
                    $duplicato = "True";
                }
                else{
                    $i++;
                }
            }
        return $trovato;
}

//Funzione per verificare se il cliente con l'username passato come parametro è presente nel file XML

function getUsername($usernameCliente){
    $trovato="False";
    $xmlStringClienti = "";

    foreach(file("../XML/Clienti.xml") as $node){

        $xmlStringClienti .= trim($node);

    }
            
            $docClienti = new DOMDocument();
            $docClienti->loadXML($xmlStringClienti);

            $listaClienti = $docClienti->documentElement->childNodes;
            $i = 0;
            while($i < $listaClienti->length && $trovato == "False"){          //Controllo se un utente con tale username è già registrato
                $cliente = $listaClienti->item($i);
                $elemCredenziali= $cliente->getElementsByTagName("credenziali")->item(0);
                $testoUsername=$elemCredenziali->firstChild->textContent;
                if($testoUsername == $usernameCliente){
                    $trovato = "True";
                }
                else{
                    $i++;
                }
            }

            return $trovato;

}







?>
