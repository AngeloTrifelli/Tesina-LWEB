<?php
// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER ESTRARRE DATI DA I FILE XML

    require_once("funzioniModificaPHP.php");

// Funzione per ottenere tutti i soggiorni passati di un cliente in datiPersonali.php

function getSoggiorniPassati($codFiscCliente){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathCamere = new DOMXPath($doc);

    $soggiorniPassati = $xpathCamere->query("/listaCamere/Camera/listaPrenotazioni/prenotazione[codFisc= '$codFiscCliente' and (statoSoggiorno='Pagamento rifiutato' or statoSoggiorno='Terminato')]");
    if($soggiorniPassati->length >= 1){
        $tabellaSoggiorni = array();

        for($i=0 ; $i < $soggiorniPassati->length ; $i++){
            $soggiorno = $soggiorniPassati->item($i);

            $idPrenotazione = $soggiorno->getElementsByTagName("idPrenotazione")->item(0)->textContent;

            $pieces = explode("-",$idPrenotazione);
            $idCamera = $pieces[0];
            $camera = $xpathCamere->query("/listaCamere/Camera[@numero='$idCamera']");
            $camera = $camera->item(0);

            $numeroCamera = substr($idCamera, 1);
            $tipoCamera = $camera->getElementsByTagName("tipo")->item(0)->textContent; 
            $statoSoggiorno = $soggiorno->getElementsByTagName("statoSoggiorno")->item(0)->textContent;
            $dataArrivo = $soggiorno->getElementsByTagName("dataArrivo")->item(0)->textContent;
            $dataPartenza = $soggiorno->getElementsByTagName("dataPartenza")->item(0)->textContent;

            $temp = array(
                "idPrenotazione"=>$idPrenotazione,
                "numeroCamera"=>$numeroCamera,
                "tipoCamera"=>$tipoCamera,
                "statoSoggiorno"=>$statoSoggiorno,
                "dataArrivo"=>$dataArrivo,
                "dataPartenza"=>$dataPartenza 
            );

            array_push($tabellaSoggiorni , $temp);
        }
        array_multisort(array_column($tabellaSoggiorni, 'dataArrivo') , SORT_DESC , $tabellaSoggiorni );        //Ordino i soggiorni in ordine descrescente rispetto alla data di arrivo
        return $tabellaSoggiorni;
    }
    else{
        return $soggiorniPassati;
    }
}





// Funzione per verificare se il cliente che ha effettuato il login ha un soggiorno attivo al momento (cioè con pagamento approvato)

function getSoggiornoAttivo($codFiscCliente){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathCamere = new DOMXPath($doc);

    $prenotazione= $xpathCamere->query("/listaCamere/Camera/listaPrenotazioni/prenotazione[codFisc='$codFiscCliente' and (statoSoggiorno='Approvato' or statoSoggiorno='Pagamento sospeso')]");
   
    if($prenotazione->length == 1){
        $prenotazione = $prenotazione->item(0);
        $dataPartenza = $prenotazione->getElementsByTagName("dataPartenza")->item(0)->textContent;
        $idPrenotazione = $prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
        $statoPrenotazione = $prenotazione->getElementsByTagName("statoSoggiorno")->item(0)->textContent;

        if($statoPrenotazione == "Approvato"){
            $todayDate = date('Y-m-d');

            if($todayDate >= $dataPartenza){
                modificaStatoSoggiorno($idPrenotazione , "Terminato");      
                return "null";
            }
        }

        $pieces = explode("-",$idPrenotazione);
        $numeroCamera = $pieces[0];

        $camera = $xpathCamere->query("/listaCamere/Camera[@numero='$numeroCamera']");
        $camera = $camera->item(0);
        
        $arrayDati['idPrenotazione'] = $idPrenotazione;
        $arrayDati['numeroCamera'] = substr($numeroCamera, 1);
        $arrayDati['tipoCamera'] = $camera->getElementsByTagName("tipo")->item(0)->textContent;
        $arrayDati['statoSoggiorno'] = $statoPrenotazione;
        $arrayDati['dataArrivo'] = $prenotazione->getElementsByTagName("dataArrivo")->item(0)->textContent;
        $arrayDati['dataPartenza'] = $dataPartenza;
        
        return $arrayDati;
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
            $trovato = "True";
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


//Funzione per recuperare TUTTI i dati di uno specifico cliente

function getDatiCliente ($codFiscCliente){
    $xmlString = "";

    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathClienti = new DOMXPath($doc);

    $cliente = $xpathClienti->query("/listaClienti/cliente[@codFisc = '$codFiscCliente']");
    $cliente = $cliente->item(0);

    $arrayDati['nome'] = $cliente->getElementsByTagName("nome")->item(0)->textContent;
    $arrayDati['cognome'] = $cliente->getElementsByTagName("cognome")->item(0)->textContent;

    $stringaData = $cliente->getElementsByTagName("dataDiNascita")->item(0)->textContent;
    $giorno = substr($stringaData, 8,2);       
    $mese = substr($stringaData,5,2 );
    $anno = substr($stringaData,0,4 );

    $stringaData = $giorno."-".$mese."-".$anno;
    $arrayDati['dataDiNascita'] = $stringaData;

    $arrayDati['codFisc'] = $codFiscCliente;
    $arrayDati['indirizzo'] = $cliente->getElementsByTagName("indirizzo")->item(0)->textContent;
    $arrayDati['telefono'] = $cliente->getElementsByTagName("telefono")->item(0)->textContent;
    $arrayDati['email'] = $cliente->getElementsByTagName("email")->item(0)->textContent;
    $arrayDati['numeroCarta'] = $cliente->getElementsByTagName("numeroCarta")->item(0)->textContent;
    $arrayDati['username'] = $cliente->getElementsByTagName("username")->item(0)->textContent;
    $arrayDati['crediti'] = $cliente->getElementsByTagName("crediti")->item(0)->textContent;
    $arrayDati['sommaGiudizi'] = $cliente->getElementsByTagName("sommaGiudizi")->item(0)->textContent;
    
    return $arrayDati;
}



// Funzione per ottenere le camere disponibili per la prenotazione e visualizzarle in visualizzaDisponibilita.php
// (in base alle date inserite dall'utente in prenotaOra.php)

function getCamereDisponibili ($dataArrivo , $dataPartenza){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $tabellaCamere = array();

    $listaCamere = $doc->documentElement->childNodes;
    for($i=0 ; $i < $listaCamere->length ; $i++ ){
        $camera = $listaCamere->item($i);

        $idCamera = $camera->getAttribute("numero");

        $listaPrenotazioni = $camera->getElementsByTagName("prenotazione");
        $disponibile = "True";
        $j = 0;

        while($j < $listaPrenotazioni->length && $disponibile == "True"){
            $prenotazione = $listaPrenotazioni->item($j);
            
            $dataInizioPrenotazione = $prenotazione->getElementsByTagName("dataArrivo")->item(0)->textContent;
            $dataFinePrenotazione = $prenotazione->getElementsByTagName("dataPartenza")->item(0)->textContent;
            $statoSoggiorno = $prenotazione->getElementsByTagName("statoSoggiorno")->item(0)->textContent;


            if($statoSoggiorno != "Pagamento rifiutato" && $statoSoggiorno != "Terminato"){
                if(($dataArrivo >= $dataInizioPrenotazione && $dataArrivo <= $dataFinePrenotazione) || ($dataPartenza >= $dataInizioPrenotazione && $dataPartenza <= $dataFinePrenotazione) || ($dataInizioPrenotazione >= $dataArrivo && $dataFinePrenotazione <= $dataPartenza)){
                    $disponibile = "False";
                }
                else{
                    $j++;
                }
            }
            else{
                $j++;
            }
        } 

        if($disponibile == "True"){
            $tipoCamera = $camera->getElementsByTagName("tipo")->item(0)->textContent;
            $prezzo = $camera->getElementsByTagName("prezzo")->item(0)->textContent;

            $temp = array(
                "idCamera"=>$idCamera,
                "tipoCamera"=>$tipoCamera,
                "prezzo"=>$prezzo
            );
            array_push($tabellaCamere , $temp);
        }
    }

    if(count($tabellaCamere) >= 1){
        array_multisort(array_column($tabellaCamere, 'prezzo') , SORT_ASC , $tabellaCamere);
    }
    
    return $tabellaCamere;
}



// Funzione per ottenere gli id di tutte le camere 

function getIdCamere (){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathCamere = new DOMXPath($doc);

    $listaID = $xpathCamere->query("//@numero");
    return $listaID;
}

// Funzione per ottere le informazioni di una camera a partire da un ID 

function getCamera($idCamera){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathCamere = new DOMXPath($doc);

    $camera = $xpathCamere->query("/listaCamere/Camera[@numero='$idCamera']");
    $camera = $camera->item(0);

    $arrayDati['tipoCamera'] = $camera->getElementsByTagName("tipo")->item(0)->textContent;
    $arrayDati['prezzo'] = $camera->getElementsByTagName("prezzo")->item(0)->textContent;
    
    return $arrayDati;
}

//Funzione per recuperare il numero delle attività presenti nel file Attivita.xml

function numAttivita(){
    $xmlStringAttivita= "";

    foreach(file("../XML/Attivita.xml") as $node){

        $xmlStringAttivita .= trim($node);

    }

    $docAttivita = new DOMDocument();
    $docAttivita->loadXML($xmlStringAttivita);

    $listaAttivita = $docAttivita->documentElement->childNodes;
    
    return ($listaAttivita->length);

}


//Funzione per recuperare TUTTI i dati di una specifica attività

function getDatiAttivita($idAttivita){

    $xmlStringAttivita= "";

    foreach(file("../XML/Attivita.xml") as $node){

        $xmlStringAttivita .= trim($node);

    }

    $docAttivita = new DOMDocument();
    $docAttivita->loadXML($xmlStringAttivita);

    $xpathAttivita = new DOMXPath($docAttivita);

    $attivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']");
    $attivita = $attivita->item(0);

    $arrayDati['nome'] = $attivita->getElementsByTagName("nome")->item(0)->textContent;
    $arrayDati['descrizione'] = $attivita->getElementsByTagName("descrizione")->item(0)->textContent;
    $arrayDati['linkImmagine'] = $attivita->getElementsByTagName("linkImmagine")->item(0)->textContent;
    $arrayDati['oraApertura'] = $attivita->getElementsByTagName("oraApertura")->item(0)->textContent;
    $arrayDati['oraChiusura'] = $attivita->getElementsByTagName("oraChiusura")->item(0)->textContent;
    $arrayDati['prezzoOrario'] = $attivita->getElementsByTagName("prezzoOrario")->item(0)->textContent;

    return $arrayDati;

}

function getIdAttivita(){
    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathAttivita = new DOMXPath($doc);

    $listaID = $xpathAttivita->query("//@id");
    return $listaID;
}




// Funzione per ottenere TUTTE le portate inserite nel menu del ristorante 

function getPortate(){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathRistorante = new DOMXPath($doc);

    $tabellaPortate = array();
    $antipasti = array();
    $primiPiatti = array();
    $secondiPiatti = array();
    $dolci = array();

    $listaPortate = $xpathRistorante->query("/ristoranti/ristorante/menu/portata");
    for($i=0 ; $i<$listaPortate->length ; $i++){
        $portata = $listaPortate->item($i);

        $tipologia = $portata->getElementsByTagName("tipologia")->item(0)->textContent;
        $descrizione = $portata->getElementsByTagName("descrizione")->item(0)->textContent;
        $prezzo = $portata->getElementsByTagName("prezzo")->item(0)->textContent;

        $temp = array(
            "tipologia"=>$tipologia,
            "descrizione"=>$descrizione,
            "prezzo"=>$prezzo
        );

        if($tipologia == "antipasto"){
            array_push($antipasti , $temp);
        }
        elseif($tipologia == "primo piatto"){
            array_push($primiPiatti , $temp);
        }elseif($tipologia == "secondo piatto"){
            array_push($secondiPiatti , $temp);
        }else{
            array_push($dolci , $temp);
        }
    }

    if(count($antipasti) >= 1){
        array_multisort(array_column($antipasti , "descrizione") , SORT_ASC , $antipasti);
    }
    if(count($primiPiatti) >= 1){
        array_multisort(array_column($primiPiatti , "descrizione") , SORT_ASC , $primiPiatti);
    }
    if(count($secondiPiatti) >= 1){
        array_multisort(array_column($secondiPiatti , "descrizione") , SORT_ASC , $secondiPiatti);
    }
    if(count($primiPiatti) >= 1){
        array_multisort(array_column($dolci , "descrizione") , SORT_ASC , $dolci);
    }
    
    array_push($tabellaPortate , $antipasti);
    array_push($tabellaPortate , $primiPiatti);
    array_push($tabellaPortate , $secondiPiatti);
    array_push($tabellaPortate , $dolci);

    return $tabellaPortate;
}


// Funzione per ottenere gli orari del ristorante 

function getOrariRistorante(){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $ristorante = $doc->documentElement->childNodes->item(0);

    $arrayDati['aperturaPranzo'] = $ristorante->getElementsByTagName("oraAperturaPranzo")->item(0)->textContent;
    $arrayDati['chiusuraPranzo'] = $ristorante->getElementsByTagName("oraChiusuraPranzo")->item(0)->textContent;
    $arrayDati['aperturaCena'] = $ristorante->getElementsByTagName("oraAperturaCena")->item(0)->textContent;
    $arrayDati['chiusuraCena'] = $ristorante->getElementsByTagName("oraChiusuraCena")->item(0)->textContent;

    return $arrayDati;
}


?>
