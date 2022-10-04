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

// Funzione per ottenere gli id di tutte le attivita 


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

// Funzione per ottenere i codici fiscali di tutti i clienti 

function getCodFiscClienti(){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathClienti = new DOMXPath($doc);
    $listaCodFisc= $xpathClienti->query("//@codFisc");
    return $listaCodFisc;
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

//Funzione che restituisce il numero di Clienti presenti e non in struttura(che hanno il soggiorno attivo)

function getNumClientiConSoggiornoAttivo(){

    $dataAttuale=date('Y-m-d');

    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaCamere = $doc->documentElement->childNodes;
    $numClientiPresenti = 0;
    $numClientiNonPresenti =0;
    for($i=0 ; $i < $listaCamere->length ; $i++ ){
        $camera = $listaCamere->item($i);

        $listaPrenotazioni = $camera->getElementsByTagName("prenotazione");
        $j = 0;
        while($j < $listaPrenotazioni->length){
            $prenotazione = $listaPrenotazioni->item($j);

            $statoSoggiorno = $prenotazione->getElementsByTagName("statoSoggiorno")->item(0)->textContent;

            if($statoSoggiorno != "Pagamento rifiutato" && $statoSoggiorno != "Terminato"){
            $dataInizioPrenotazione = $prenotazione->getElementsByTagName("dataArrivo")->item(0)->textContent;
            $dataFinePrenotazione = $prenotazione->getElementsByTagName("dataPartenza")->item(0)->textContent;
            
                if(($dataInizioPrenotazione<=$dataAttuale)&&($dataFinePrenotazione>=$dataAttuale)){
                    $numClientiPresenti+=1;
                }else{
                    $numClientiNonPresenti+=1;
                }
            }
            $j++;
        }
    }
    $numClienti=array();
    $numClienti['presenti']=$numClientiPresenti;
    $numClienti['nonPresenti']=$numClientiNonPresenti;

    return $numClienti;

}

//Funzione che restituisce il numero di clienti presenti nel file Clienti.xml

function getNumClientiTotali(){
    $xmlStringCliente= "";

    foreach(file("../XML/Clienti.xml") as $node){

        $xmlStringCliente .= trim($node);

    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlStringCliente);

    $listaClienti = $doc->documentElement->childNodes;
    
    return ($listaClienti->length);

}

//Funzione che restituisce l'iesimo cliente presente nel file Clienti.xml

function restituisciClienteIEsimo($i){
    $xmlStringCliente= "";

    foreach(file("../XML/Clienti.xml") as $node){

        $xmlStringCliente .= trim($node);

    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlStringCliente);

    $listaClienti = $doc->documentElement->childNodes;
    $cliente=$listaClienti->item($i);
    $codFisc=$cliente->getAttribute("codFisc");
    return(getDatiCliente($codFisc));

}

//Funzione che restituisce una tabella contente array composti da tutti i soggiorni distinti per Approvati,sospesi, rifiutati e terminati

function getSoggiorni(){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $tabellaSoggiorni = array();
    $soggiorniSospesi = array();
    $soggiorniApprovati= array();
    $soggiorniRifiutati = array();
    $soggiorniTerminati= array();

    $listaCamere = $doc->documentElement->childNodes;

    for($i=0 ; $i < $listaCamere->length ; $i++ ){
        $camera = $listaCamere->item($i);
        $listaPrenotazioni = $camera->getElementsByTagName("prenotazione");
        $j = 0;

        while($j < $listaPrenotazioni->length){

            $prenotazione = $listaPrenotazioni->item($j);
            $idPrenotazione = $prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
            $codFisc=$prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
            $statoSoggiorno = $prenotazione->getElementsByTagName("statoSoggiorno")->item(0)->textContent;
            $creditiUsati=$prenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;
            $dataArrivo=$prenotazione->getElementsByTagName("dataArrivo")->item(0)->textContent;
            $dataPartenza=$prenotazione->getElementsByTagName("dataPartenza")->item(0)->textContent;
            $temp = array(
                "idPrenotazione"=>$idPrenotazione,
                "codFisc"=>$codFisc,
                "statoSoggiorno"=>$statoSoggiorno,
                "creditiUsati"=>$creditiUsati,
                "dataArrivo"=>$dataArrivo,
                "dataPartenza"=>$dataPartenza
            );
            if($statoSoggiorno == "Approvato"){
                array_push($soggiorniApprovati , $temp);
            }elseif($statoSoggiorno=="Pagamento sospeso"){
                array_push($soggiorniSospesi , $temp);
            }elseif($statoSoggiorno=="Pagamento rifiutato"){
                array_push($soggiorniRifiutati , $temp);
            }else{
                array_push($soggiorniTerminati , $temp);
            }
                $j++;
        }
    }

    if(count($soggiorniApprovati) >= 1){
        array_multisort(array_column($soggiorniApprovati, 'dataArrivo') , SORT_DESC , $soggiorniApprovati);
    }
    if(count($soggiorniSospesi) >= 1){
        array_multisort(array_column($soggiorniSospesi, 'dataArrivo') , SORT_DESC , $soggiorniSospesi);
    }
    if(count($soggiorniRifiutati) >= 1){
        array_multisort(array_column($soggiorniRifiutati, 'dataArrivo') , SORT_DESC , $soggiorniRifiutati);
    }
    if(count($soggiorniTerminati) >= 1){
        array_multisort(array_column($soggiorniTerminati, 'dataArrivo') , SORT_DESC , $soggiorniTerminati);
    }

    array_push($tabellaSoggiorni , $soggiorniApprovati);
    array_push($tabellaSoggiorni , $soggiorniSospesi);
    array_push($tabellaSoggiorni , $soggiorniRifiutati);
    array_push($tabellaSoggiorni , $soggiorniTerminati);

    return $tabellaSoggiorni;
    
}

function getCategorie(){
    $xmlString = "";
    foreach(file("../XML/Categorie.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $tabellaCategorie = array();
    $categorieAttivate=array();
    $categorieDisattivate=array();

    $listaCategorie = $doc->documentElement->childNodes;
    
    for($i=0;$i<$listaCategorie->length;$i++){
        $categoria=$listaCategorie->item($i);
        $nome=$categoria->getElementsByTagName("nome")->item(0)->textContent;
        $stato=$categoria->getElementsByTagName("stato")->item(0)->textContent;

        $temp = array(
            "nome"=>$nome,
            "stato"=>$stato
        );
        
        if($stato=="Attiva"){
            array_push($categorieAttivate , $temp);
        }else{
            array_push($categorieDisattivate , $temp);
        }
    }
    
    if(count($categorieAttivate) >= 1){
        array_multisort(array_column($categorieAttivate, 'nome') , SORT_ASC , $categorieAttivate);
    }
    if(count($categorieDisattivate) >= 1){
        array_multisort(array_column($categorieDisattivate, 'nome') , SORT_ASC , $categorieDisattivate);
    }

    array_push($tabellaCategorie, $categorieAttivate);
    array_push($tabellaCategorie, $categorieDisattivate);

    return($tabellaCategorie);
}

// Funzione che restituisce le categorie associate ad un cliente (solo quelle attive)

function getCategorieCliente($codFiscCliente){
    $xmlString = "";
    foreach(file("../XML/Categorie.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaCategorie = $doc->documentElement->childNodes;

    for($i=0 ; $i< $listaCategorie->length ; $i++){
        $categoria = $listaCategorie->item($i);
        $statoCategoria = $categoria->getElementsByTagName("stato")->item(0)->textContent;

        if($statoCategoria == "Attiva"){        
            $utentiAssociati = $categoria->getElementsByTagName("utente");

            $presente = "False";
            $j=0;
            while($j < $utentiAssociati->length && $presente == "False"){
                $utente = $utentiAssociati->item($j);
                $codFisc = $utente->firstChild->textContent;
                if($codFisc == $codFiscCliente){
                    $presente = "True";
                }
                else{
                    $j++;
                }
            }

            if($presente == "True"){
                $nomeCategoria = $categoria->getElementsByTagName("nome")->item(0)->textContent;            
                $arrayDati[$nomeCategoria] = "Attiva";
            }
        }
    }

    return $arrayDati;
}



//Funzione che restituisce le  attivita presenti in Attivita.xml

function getAttivita(){

    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $arrayAttivita = array();

    $listaAttivita = $doc->documentElement->childNodes;

    for($i=0;$i<$listaAttivita->length;$i++){
        $attivita=$listaAttivita->item($i);
        $idAttivita=$attivita->getAttribute("id");
        $nome=$attivita->getElementsByTagName("nome")->item(0)->textContent;
        $descrizione=$attivita->getElementsByTagName("descrizione")->item(0)->textContent;
        $linkImmagine=$attivita->getElementsByTagName("linkImmagine")->item(0)->textContent;
        $oraApertura=$attivita->getElementsByTagName("oraApertura")->item(0)->textContent;
        $oraChiusura=$attivita->getElementsByTagName("oraChiusura")->item(0)->textContent;
        $prezzoOrario=$attivita->getElementsByTagName("prezzoOrario")->item(0)->textContent;

        $temp = array(
            "id"=>$idAttivita,
            "nome"=>$nome,
            "descrizione"=>$descrizione,
            "linkImmagine"=>$linkImmagine,
            "oraApertura"=>$oraApertura,
            "oraChiusura"=>$oraChiusura,
            "prezzoOrario"=>$prezzoOrario
        );

        array_push($arrayAttivita , $temp);

    }

    return($arrayAttivita);

}

//Funzione che restituisce le prenotazioni di attivita presenti in Attivita.xml

function getPrenotazioniAttivita($idAttivita){

    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathAttivita = new DOMXPath($doc);

    $attivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']");
    $attivita = $attivita->item(0);

    $arrayPrenotazioniAttivita=array();

    $listaPrenotazioni = $attivita->getElementsByTagName("prenotazione");
    $i=0;
    while($i< count($listaPrenotazioni)){
        $prenotazione=$listaPrenotazioni->item($i);
        $idPrenotazione=$prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
        $codFisc=$prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
        $data=$prenotazione->getElementsByTagName("data")->item(0)->textContent;
        $oraInizio=$prenotazione->getElementsByTagName("oraInizio")->item(0)->textContent;
        $oraFine=$prenotazione->getElementsByTagName("oraFine")->item(0)->textContent;
        $prezzoTotale=$prenotazione->getElementsByTagName("prezzoTotale")->item(0)->textContent;
        $creditiUsati=$prenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;

        $temp = array(
            "idPrenotazione"=>$idPrenotazione,
            "codFisc"=>$codFisc,
            "data"=>$data,
            "oraInizio"=>$oraInizio,
            "oraFine"=>$oraFine,
            "prezzoTotale"=>$prezzoTotale,
            "creditiUsati"=>$creditiUsati
        );

        array_push($arrayPrenotazioniAttivita , $temp);
        $i++;
    }


    return($arrayPrenotazioniAttivita);

}

//Funzione che restituisce la prenotazione di attivita presente in Attivita.xml con secifico idPrenotazione

function getPrenotazioneAttivita($idPrenotazione){

    $idAttivita=substr($idPrenotazione,0,2);
    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathAttivita = new DOMXPath($doc);

    $attivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']");
    $attivita = $attivita->item(0);

    $listaPrenotazioni = $attivita->getElementsByTagName("prenotazione");
    $i=0;
    $trovato="False";
    while($i< count($listaPrenotazioni) && $trovato=="False"){
        $prenotazioneEsaminata=$listaPrenotazioni->item($i);
        $idPrenotazioneEsaminato=$prenotazioneEsaminata->getElementsByTagName("idPrenotazione")->item(0)->textContent;
        if($idPrenotazioneEsaminato==$idPrenotazione){
        $codFisc=$prenotazioneEsaminata->getElementsByTagName("codFisc")->item(0)->textContent;
        $data=$prenotazioneEsaminata->getElementsByTagName("data")->item(0)->textContent;
        $oraInizio=$prenotazioneEsaminata->getElementsByTagName("oraInizio")->item(0)->textContent;
        $oraFine=$prenotazioneEsaminata->getElementsByTagName("oraFine")->item(0)->textContent;
        $prezzoTotale=$prenotazioneEsaminata->getElementsByTagName("prezzoTotale")->item(0)->textContent;
        $creditiUsati=$prenotazioneEsaminata->getElementsByTagName("creditiUsati")->item(0)->textContent;

        $prenotazione = array(
            "idPrenotazione"=>$idPrenotazione,
            "codFisc"=>$codFisc,
            "data"=>$data,
            "oraInizio"=>$oraInizio,
            "oraFine"=>$oraFine,
            "prezzoTotale"=>$prezzoTotale,
            "creditiUsati"=>$creditiUsati
        );
        $trovato="True";
    }
        $i++;
    }
    if($trovato=="True"){
        return $prenotazione;
    }else{
        return null;
    }

}

//Funzione che restituisce le prenotazioni di attivita presenti in Attivita.xml differenziandole tra attive o passate


function getPrenotazioniAttivitaAttive(){

    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    

    $tabellaPrenotazioniAttivita=array();
    $arrayPrenotazioniAttivitaAttive=array();
    $arrayPrenotazioniAttivitaPassate=array();
    $dataAttuale=date('Y-m-d');

    $listaAttivita = $doc->documentElement->childNodes;

    for($j=0;$j<$listaAttivita->length;$j++){
    $attivita=$listaAttivita->item($j);
    $nomeAttivita=$attivita->getElementsByTagName("nome")->item(0)->textContent;
    $listaPrenotazioni = $attivita->getElementsByTagName("prenotazione");
    $i=0;
    while($i< count($listaPrenotazioni)){
        $prenotazione=$listaPrenotazioni->item($i);
        $idPrenotazione=$prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
        $codFisc=$prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
        $data=$prenotazione->getElementsByTagName("data")->item(0)->textContent;
        $oraInizio=$prenotazione->getElementsByTagName("oraInizio")->item(0)->textContent;
        $oraFine=$prenotazione->getElementsByTagName("oraFine")->item(0)->textContent;
        $prezzoTotale=$prenotazione->getElementsByTagName("prezzoTotale")->item(0)->textContent;
        $creditiUsati=$prenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;

        $temp = array(
            "nome"=>$nomeAttivita,
            "idPrenotazione"=>$idPrenotazione,
            "codFisc"=>$codFisc,
            "data"=>$data,
            "oraInizio"=>$oraInizio,
            "oraFine"=>$oraFine,
            "prezzoTotale"=>$prezzoTotale,
            "creditiUsati"=>$creditiUsati
        );

        if($data>$dataAttuale){
        array_push($arrayPrenotazioniAttivitaAttive , $temp);
        }else{
        array_push($arrayPrenotazioniAttivitaPassate , $temp);
        }
        $i++;
    }
}

if(count($arrayPrenotazioniAttivitaAttive) >= 1){
    array_multisort(array_column($arrayPrenotazioniAttivitaAttive, 'data') , SORT_ASC , $arrayPrenotazioniAttivitaAttive);
}

if(count($arrayPrenotazioniAttivitaPassate) >= 1){
    array_multisort(array_column($arrayPrenotazioniAttivitaPassate, 'data') , SORT_ASC , $arrayPrenotazioniAttivitaPassate);
}
    array_push($tabellaPrenotazioniAttivita , $arrayPrenotazioniAttivitaAttive);
    array_push($tabellaPrenotazioniAttivita , $arrayPrenotazioniAttivitaPassate);

    return($tabellaPrenotazioniAttivita);

}

//Funzione che restituisce le attivita di un cliente in particolare

function getPrenotazioniAttivitaUtente($codFiscUtenteLoggato){

    $xmlString = "";
    foreach(file("../XML/Attivita.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $arrayPrenotazioniAttivitaUtente=array();
    $listaAttivita = $doc->documentElement->childNodes;
    for($i=0;$i<$listaAttivita->length;$i++){
    $attivita=$listaAttivita->item($i);

    $nomeAttivita=$attivita->getElementsByTagName("nome")->item(0)->textContent;

    $listaPrenotazioni = $attivita->getElementsByTagName("prenotazione");
    $j=0;
    while($j< count($listaPrenotazioni)){
        $prenotazione=$listaPrenotazioni->item($j);
        $idPrenotazione=$prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
        $codFisc=$prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
        $data=$prenotazione->getElementsByTagName("data")->item(0)->textContent;
        $oraInizio=$prenotazione->getElementsByTagName("oraInizio")->item(0)->textContent;
        $oraFine=$prenotazione->getElementsByTagName("oraFine")->item(0)->textContent;
        $prezzoTotale=$prenotazione->getElementsByTagName("prezzoTotale")->item(0)->textContent;
        $creditiUsati=$prenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;
        if($codFiscUtenteLoggato==$codFisc){
            $temp = array(
                "nome"=>$nomeAttivita,
                "idPrenotazione"=>$idPrenotazione,
                "codFisc"=>$codFisc,
                "data"=>$data,
                "oraInizio"=>$oraInizio,
                "oraFine"=>$oraFine,
                "prezzoTotale"=>$prezzoTotale,
                "creditiUsati"=>$creditiUsati
            );
            array_push($arrayPrenotazioniAttivitaUtente , $temp);
        }
            $j++;
        

            }
    }

    if(count($arrayPrenotazioniAttivitaUtente) >= 1){
        array_multisort(array_column($arrayPrenotazioniAttivitaUtente, 'data') , SORT_ASC , $arrayPrenotazioniAttivitaUtente);
    }

    return($arrayPrenotazioniAttivitaUtente);

}



// Funzione che restituisce tutti le prenotazioni del ristorante effettuate da un cliente oppure di tutti i clienti (se viene passato
// come parametro null)

function getPrenotazioniRistorante($codFiscCliente){
    $xmlStringTavoli = "";
    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlStringTavoli .=trim($node);
    }
    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlStringTavoli);
    
    $xmlStringSC = "";
    foreach(file("../XML/ServizioCamera.xml") as $node){
        $xmlStringSC .=trim($node);
    }
    $docServizioCamera = new DOMDocument();
    $docServizioCamera->loadXML($xmlStringSC);

    $xpathServizioCamera = new DOMXPath($docServizioCamera);

    $tabellaPrenotazioni = array();
    $prenotazioniAttive = array();
    $prenotazioniPassate = array();
    $todayDate = date("Y-m-d");

    $listaTavoli = $docTavoli->documentElement->childNodes;

    for($i=0 ; $i < $listaTavoli->length ; $i++){
        $tavolo = $listaTavoli->item($i);

        $listaPrenotazioni = $tavolo->getElementsByTagName("prenotazione");

        for($j=0 ; $j < $listaPrenotazioni->length ; $j++){
            $prenotazione = $listaPrenotazioni->item($j);

            $codFisc = $prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;            
            if($codFisc == $codFiscCliente || $codFiscCliente == "null"){
                $idPrenotazione = $prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
                $numeroTavolo = $tavolo->getAttribute("numero");
                $locazioneTavolo = $tavolo->firstChild->textContent;
                $dataPrenotazione = $prenotazione->getElementsByTagName("data")->item(0)->textContent;
                $oraPrenotazione = $prenotazione->getElementsByTagName("ora")->item(0)->textContent;   
                
                $temp = array(
                    "codFiscCliente"=>$codFisc,
                    "tipoPrenotazione"=>"Servizio al tavolo",
                    "idPrenotazione"=>$idPrenotazione,
                    "numeroTavolo"=>$numeroTavolo,
                    "locazioneTavolo"=>$locazioneTavolo,
                    "dataPrenotazione"=>$dataPrenotazione,
                    "oraPrenotazione"=>$oraPrenotazione
                );

                if($todayDate >= $dataPrenotazione){
                    array_push($prenotazioniPassate , $temp);
                }
                else{
                    array_push($prenotazioniAttive , $temp);
                }
            }            
        }
    }

    if($codFiscCliente != "null"){
        $listaPrenotazioniSC = $xpathServizioCamera->query("/listaPrenotazioni/prenotazione[codFisc='$codFiscCliente']");
    }
    else{
        $listaPrenotazioniSC = $xpathServizioCamera->query("/listaPrenotazioni/prenotazione");
    }
    
    for($i=0 ; $i < $listaPrenotazioniSC->length ; $i++){
        $prenotazione = $listaPrenotazioniSC->item($i);
        $idPrenotazione = $prenotazione->getAttribute("id");
        $codFisc = $prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
        $dataPrenotazione = $prenotazione->getElementsByTagName("data")->item(0)->textContent;
        $oraPrenotazione = $prenotazione->getElementsByTagName("ora")->item(0)->textContent;

        $temp = array(
            "codFiscCliente"=>$codFisc,
            "tipoPrenotazione"=>"Servizio in camera",
            "idPrenotazione"=>$idPrenotazione,            
            "dataPrenotazione"=>$dataPrenotazione,
            "oraPrenotazione"=>$oraPrenotazione
        );      
        
        if($todayDate >= $dataPrenotazione){
            array_push($prenotazioniPassate , $temp);
        }
        else{
            array_push($prenotazioniAttive , $temp);
        }        
    }

    if(count($prenotazioniAttive) > 1 ){
        array_multisort(array_column($prenotazioniAttive, 'dataPrenotazione') , SORT_ASC , $prenotazioniAttive);
    }

    if(count($prenotazioniPassate) > 1){
        array_multisort(array_column($prenotazioniPassate, 'dataPrenotazione') , SORT_DESC, $prenotazioniPassate);
    }
    
    array_push($tabellaPrenotazioni , $prenotazioniAttive);
    array_push($tabellaPrenotazioni , $prenotazioniPassate);
    
    return $tabellaPrenotazioni;
}







//Funzione che restituisce tutti gli id delle prenotazioni dei tavoli

function getIdPrenotazioniTavolo(){
    $xmlStringTavoli = "";
    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlStringTavoli .=trim($node);
    }
    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlStringTavoli);

    $xpathTavoli = new DOMXPath($docTavoli);

    $listaID = $xpathTavoli->query("/listaTavoli/tavolo/listaPrenotazioni/prenotazione/idPrenotazione");

    return $listaID;
}

// Funzione che restituisce tutti gli id delle prenotazioni del servizio in camera

function getIdPrenotazioniSC(){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathSC = new DOMXPath($doc);

    $listaID = $xpathSC->query("//@id");
    
    return $listaID;
}


// Funzione per restituire tutte le informazioni riguardo una particolare prenotazione del servizio in camera 

function getPrenotazioneServizioCamera($idPrenotazione){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $tabellaPortateScelte = array();

    $xpathSC = new DOMXPath($doc);

    $prenotazione = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']");
    $prenotazione = $prenotazione->item(0);

    $codFisc = $prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
    $arrayDati['codFiscCliente'] = $codFisc;  

    $dataPrenotazione = $prenotazione->getElementsByTagName("data")->item(0)->textContent;
    $arrayDati['dataPrenotazione'] = $dataPrenotazione;

    $oraPrenotazione = $prenotazione->getElementsByTagName("ora")->item(0)->textContent;
    $arrayDati['oraPrenotazione'] = $oraPrenotazione;

    $richieste = $prenotazione->getElementsByTagName("richieste")->item(0)->textContent;
    $arrayDati['richiesteAggiuntive'] = $richieste;
    
    $prezzo = $prenotazione->getElementsByTagName("prezzoTotale")->item(0)->textContent;
    $arrayDati['prezzoTotale'] = $prezzo;

    $creditiUsati = $prenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;
    $arrayDati['creditiUsati'] = $creditiUsati;

    $listaPortate = $prenotazione->getElementsByTagName("portata");

    for($i=0 ; $i < $listaPortate->length ; $i++){
        $portata = $listaPortate->item($i);

        $nomePortata = $portata->getElementsByTagName("nome")->item(0)->textContent;
        $prezzo = $portata->getElementsByTagName("prezzo")->item(0)->textContent;
        $quantita = $portata->getElementsByTagName("quantita")->item(0)->textContent;

        $temp = array(
            "descrizione"=>$nomePortata,
            "prezzo"=>$prezzo,
            "quantita"=>$quantita
        );

        array_push($tabellaPortateScelte , $temp);
    }

    $arrayDati['portateScelte'] = $tabellaPortateScelte;
    
    return $arrayDati;
}


//Funzione per ottenere tutti i dati di una particolare prenotazione al tavolo 

function getPrenotazioneTavolo($idPrenotazione){
    $xmlStringTavoli = "";
    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlStringTavoli .=trim($node);
    }
    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlStringTavoli);

    $xpathTavoli = new DOMXPath($docTavoli);

    $pieces = explode("-",$idPrenotazione);
    $numeroTavolo = $pieces[0];

    $tavolo = $xpathTavoli->query("/listaTavoli/tavolo[@numero='$numeroTavolo']");
    $tavolo = $tavolo->item(0);

    $prenotazioneTavolo = $xpathTavoli->query("/listaTavoli/tavolo[@numero='$numeroTavolo']/listaPrenotazioni/prenotazione[idPrenotazione='$idPrenotazione']");
    $prenotazioneTavolo = $prenotazioneTavolo->item(0);

    $arrayDati['numeroTavolo'] = $tavolo->getAttribute("numero");
    $arrayDati['locazioneTavolo'] = $tavolo->getElementsByTagName("locazione")->item(0)->textContent;
    $arrayDati['codFiscCliente'] = $prenotazioneTavolo->getElementsByTagName("codFisc")->item(0)->textContent;
    $arrayDati['dataPrenotazione'] = $prenotazioneTavolo->getElementsByTagName("data")->item(0)->textContent;
    $arrayDati['oraPrenotazione'] = $prenotazioneTavolo->getElementsByTagName("ora")->item(0)->textContent;
    
    return $arrayDati;
}

// Funzione per ottenere il prezzo di una determinata portata 

function getPrezzoPortata($nomePortata){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);   

    $xpathRistorante = new DOMXPath($doc);

    $prezzoPortata = $xpathRistorante->query("/ristoranti/ristorante/menu/portata[descrizione='$nomePortata']/prezzo");    
    $prezzoPortata = $prezzoPortata->item(0)->textContent;        

    return $prezzoPortata;
}


// Funzione per ottenere le domande in domande.php
// Se codFiscCliente è diverso da null allora verranno prese solo le domande di un particolare cliente 
// Se categoria è diverso da null allora verranno prese solo le domande di una particolare categoria
// Queste due cose possono essere combinate (solo le domande di un particolare cliente e di una particolare categoria)

function getDomande($codFiscCliente , $categoria){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString); 

    $xpathDomande = new DOMXPath($docDomande);

    if($codFiscCliente != "null" && $categoria != "null"  ){
        $listaDomande = $xpathDomande->query("/listaDomande/domanda[codFiscAutore='$codFiscCliente' and categoria='$categoria']");
    }
    else{
        if($categoria != "null"){
            $listaDomande = $xpathDomande->query("/listaDomande/domanda[categoria='$categoria']");
        }
        elseif($codFiscCliente != "null"){
            $listaDomande = $xpathDomande->query("/listaDomande/domanda[codFiscAutore='$codFiscCliente']");
        }   
        else{
            $listaDomande = $docDomande->documentElement->childNodes;
        }     
    }
    
    return $listaDomande;
}



// Funzione per ottenere tutti i dati di una particolare domanda

function getDatiDomanda($idDomanda){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString); 

    $xpathDomande = new DOMXPath($docDomande);

    $domanda = $xpathDomande->query("/listaDomande/domanda[@id='$idDomanda']");
    $domanda = $domanda->item(0);

    $arrayDati['nomeAutore'] = $domanda->getElementsByTagName("nomeAutore")->item(0)->textContent;
    $arrayDati['cognomeAutore'] = $domanda->getElementsByTagName("cognomeAutore")->item(0)->textContent;
    $arrayDati['codFiscAutore'] = $domanda->getElementsByTagName("codFiscAutore")->item(0)->textContent;
    $arrayDati['categoria'] = $domanda->getElementsByTagName("categoria")->item(0)->textContent;
    $arrayDati['testoDomanda'] = $domanda->getElementsByTagName("testoDomanda")->item(0)->textContent;
    $arrayDati['listaRisposte'] = $domanda->getElementsByTagName("risposta");

    return $arrayDati;
}


// Funzione per ottenere tutti i dati di una particolare risposta di una domanda 

function getDatiRisposta($idRisposta){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString); 

    $xpathDomande = new DOMXPath($docDomande);

    $pieces = explode("-", $idRisposta);
    $idDomanda = $pieces[0];

    $risposta = $xpathDomande->query("/listaDomande/domanda[@id='$idDomanda']/listaRisposte/risposta[idRisposta='$idRisposta']");
    $risposta = $risposta->item(0);


    $arrayDati['autore'] = $risposta->getElementsByTagName("autore")->item(0)->textContent;
    if($arrayDati['autore'] != "Staff"){
        $arrayDati['nomeAutoreRisposta'] = $risposta->getElementsByTagName("nomeAutoreRisposta")->item(0)->textContent;
        $arrayDati['cognomeAutoreRisposta'] = $risposta->getElementsByTagName("cognomeAutoreRisposta")->item(0)->textContent;
        $arrayDati['codFiscAutoreRisposta'] = $risposta->getElementsByTagName("codFiscAutoreRisposta")->item(0)->textContent;
    }
    $arrayDati['testoRisposta'] = $risposta->getElementsByTagName("testoRisposta")->item(0)->textContent;
    
    return $arrayDati;
}




?>
