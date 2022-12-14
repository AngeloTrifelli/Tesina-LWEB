<?php
    require_once('funzioniModificaPHP.php');
    require_once('funzioniGetPHP.php');
    require_once('funzioniInsertPHP.php');



// Funzione per controllare se le credenziali inserite dal concierge o dall'admin in login.php sono corrette

function eseguiLoginStaff($username , $password , $tipoLogin){
    $xmlString = "";
    if($tipoLogin == "Concierge"){
        foreach(file("../XML/Concierge.xml") as $node){
            $xmlString .= trim($node);
        }
    }
    else{
        foreach(file("../XML/Amministratori.xml") as $node){
            $xmlString .= trim($node);
        }
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    
    $listaStaff = $doc->documentElement->childNodes;
    $trovato = "False";
    $i = 0;
    while ($i < $listaStaff->length && $trovato == "False"){
        $staff = $listaStaff->item($i);
        $testoUsername = $staff->firstChild->textContent;
        $testoPassword = $staff->lastChild->textContent;
        if($testoUsername == $username && $testoPassword == $password){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }
    return $trovato;
}


// Funzione per controllare se le credenziali inserite dall'utente sono corrette in login.php (login cliente)

function eseguiLoginCliente ($username , $password){
    $xmlString = "";
    foreach(file("../XML/Clienti.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaClienti = $doc->documentElement->childNodes;
    $trovato = "False";
    $i = 0;

    while($i < $listaClienti->length && $trovato == "False"){
        $cliente = $listaClienti->item($i);
        $elemCredenziali = $cliente->getElementsByTagName("credenziali")->item(0);

        $testoUsername = $elemCredenziali->firstChild->textContent;
        $testoPassword = $elemCredenziali->lastChild->textContent;
        
        if($testoUsername == $username && $testoPassword == $password){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    if($trovato == "True"){
        $codFisc = $cliente->getAttribute("codFisc");
        $date = date('Y-m-d');
        $dataAssegnazioneCrediti = $cliente->getElementsByTagName("dataAssegnazioneCrediti")->item(0)->textContent;
        if($date != $dataAssegnazioneCrediti){
            modificaDataCreditiGiornalieri($codFisc);
        }
        return $codFisc;
    }
    else{
        return "null";          //Questo significa che l'username e la password inserita sono errate (non corrispondono a nessun utente registrato)
    }
}




// Funzione per capire che dato si vuole modificare in modificaDatiUtente.php

function individuaDatoDaModificare(){
    if(isset($_POST['nome']) || isset($_POST['cognome']) || isset($_POST['codFisc']) || isset($_POST['dataDiNascita']) || isset($_POST['indirizzo']) || isset($_POST['telefono']) || isset($_POST['email']) || isset($_POST['numeroCarta']) || isset($_POST['username']) || isset($_POST['password'])){
        if(!isset($_POST['nome'])){
            if(!isset($_POST['cognome'])){
                if(!isset($_POST['codFisc'])){
                    if(!isset($_POST['dataDiNascita'])){
                        if(!isset($_POST['indirizzo'])){
                            if(!isset($_POST['telefono'])){
                                if(!isset($_POST['email'])){
                                    if(!isset($_POST['numeroCarta'])){
                                        if(!isset($_POST['username'])){
                                            return "password";
                                        }
                                        return "username";
                                    }
                                    return "numeroCarta";
                                }
                                return "email";
                            }
                            return "telefono";
                        }
                        return "indirizzo";
                    }
                    return "dataDiNascita";
                }
                return "codFisc";
            }
            return "cognome";
        }
        return "nome";
    }
    else{
        return "null";       //Questo significa che ho aperto la pagina modificaDatiUtente.php senza esser passato prima per datiPersonali.php
    }
}


// Funzione per capire che bottone ha premuto l'utente in visualizzaDisponibilita.php

function individuaBottoneCamereDisponibili(){
    $listaIdCamere = getIdCamere();

    $i = 0;
    $trovato = "False";

    while($i < $listaIdCamere->length && $trovato == "False"){
        $idCamera = $listaIdCamere->item($i)->textContent;
        if(isset($_POST[$idCamera])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idCamera;
}



// Funzione per capire che bottone ha premuto l'utente in attivita.php e in listaPrenotazioniAttivita.php

function individuaBottoneidAttivita(){

    $listaIdAttivita = getIdAttivita();

    $i = 0;
    $trovato = "False";

    while($i < $listaIdAttivita->length && $trovato == "False"){
        $idAttivita = $listaIdAttivita->item($i)->textContent;
        if(isset($_POST[$idAttivita])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idAttivita; 
}

// Funzione per capire che bottone ha premuto l'utente in attivita.php e in listaPrenotazioniAttivita.php

function individuaBottoneidPrenotazioneAttivita(){

    $xmlStringAttivita= "";

    foreach(file("../XML/Attivita.xml") as $node){

       $xmlStringAttivita .= trim($node);

   }

    $docAttivita = new DOMDocument();
    $docAttivita->loadXML($xmlStringAttivita);    

    $listaAttivita = $docAttivita->documentElement->childNodes;
    $i=0;

    $trovato="False";
    while($i<$listaAttivita->length && $trovato=="False"){
        $attivita=$listaAttivita->item($i);
        $listaPrenotazione = $attivita->getElementsByTagName("prenotazione");

        $j=0;
        while($j < count($listaPrenotazione) && $trovato=="False"){
            $prenotazione = $listaPrenotazione->item($j);
            $idPrenotazione= $prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;    
            if(isset($_POST[$idPrenotazione])){
                $trovato = "True";
            }
            else{
                $j++;
            }
        
        }
    $i++;
    }
    
    return($idPrenotazione);

    
}

// Funzione per capire che bottone ha premuto l'utente in visualizzaClienti.php

function individuaBottoneCodFiscUtenteSelezionato(){

    $listaCodFiscClienti=getCodFiscClienti();

    $i=0;
    $trovato="False";

    while($i < $listaCodFiscClienti->length && $trovato == "False"){
        $codFisc = $listaCodFiscClienti->item($i)->textContent;
        if(isset($_POST[$codFisc])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $codFisc;
}

//Funzione che restitusce true se il cliente ?? presente in struttura al momento del run,altrimenti restituisce false
function presenzaClienteInStruttura($codFisc){

    $dataAttuale=date('Y-m-d');

    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaCamere = $doc->documentElement->childNodes;

    for($i=0 ; $i < $listaCamere->length ; $i++ ){
        $camera = $listaCamere->item($i);

        $listaPrenotazioni = $camera->getElementsByTagName("prenotazione");
        $j = 0;
        while($j < $listaPrenotazioni->length){
            $prenotazione = $listaPrenotazioni->item($j);
            $codiceFiscale= $prenotazione->getElementsByTagName("codFisc")->item(0)->textContent;
            $statoSoggiorno = $prenotazione->getElementsByTagName("statoSoggiorno")->item(0)->textContent;

            if($statoSoggiorno != "Pagamento rifiutato" && $statoSoggiorno != "Terminato"  && $codiceFiscale == $codFisc){
            $dataInizioPrenotazione = $prenotazione->getElementsByTagName("dataArrivo")->item(0)->textContent;
            $dataFinePrenotazione = $prenotazione->getElementsByTagName("dataPartenza")->item(0)->textContent;
                if(($dataInizioPrenotazione<=$dataAttuale)&&($dataFinePrenotazione>=$dataAttuale)){
                    return("True");
                }
            }
            $j++;
        }
        
    }
    return("False");
}

// Funzione per capire che bottone ha premuto l'admin in pagamentiClienti.php
function individuaBottonePagamentoPremuto(){

    $tabellaSoggiorni=getSoggiorni();
    $listaSoggiorniSospesi=$tabellaSoggiorni[1];

    $i=0;
    $trovato="False";

    while($i < count($listaSoggiorniSospesi) && $trovato == "False"){

        $soggiorno= $listaSoggiorniSospesi[$i];
        $idPrenotazione= $soggiorno['idPrenotazione'];
        if(isset($_POST[$idPrenotazione])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }
    if($_POST[$idPrenotazione]=="Approva"){
        modificaStatoSoggiorno($idPrenotazione,"Approvato");
    
    }else{
        modificaStatoSoggiorno($idPrenotazione,"Pagamento rifiutato");

    }

}

// Funzione per capire che bottone ha premuto l'admin in categorie.php

function individuaBottoneCategoriaPremuto(){

    $tabellaCategorie=getCategorie();
    $listaCategorieAttivate=$tabellaCategorie[0];
    $listaCategorieDisattivate=$tabellaCategorie[1];



    $i=0;
    $trovato="False";

    while($i < count($listaCategorieAttivate) && $trovato == "False"){
        $categoria= $listaCategorieAttivate[$i];
        $nome= $categoria['nome'];
        if(isset($_POST[$nome])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }
    $j=0;
    while($j<count($listaCategorieDisattivate) && $trovato == "False"){
        $categoria=$listaCategorieDisattivate[$j];
        $nome=$categoria['nome'];
        if(isset($_POST[$nome])){
            $trovato = "True";
        }
        else{
            $j++;
        }

    }


    if($_POST[$nome]=="Attiva"){
        modificaStatoCategoria($nome,"Attiva");
    
    }else{
        modificaStatoCategoria($nome,"Disabilitata");
    }

}

//funzione per capire che attivit?? il cliente ha deciso di eliminare

function individuaBottoneAttivitaDaEliminare(){
   

    $xmlStringAttivita= "";

    foreach(file("../XML/Attivita.xml") as $node){

       $xmlStringAttivita .= trim($node);

   }

   $docAttivita = new DOMDocument();
   $docAttivita->loadXML($xmlStringAttivita);
   $docAttivita->formatOutput = true;

   $listaAttivita = $docAttivita->documentElement->childNodes;
   $i=0;
   $trovato="False";
   while($i<$listaAttivita->length && $trovato=="False"){

    $attivita=$listaAttivita->item($i);

   $listaPrenotazioni=$attivita->getElementsByTagName("listaPrenotazioni")->item(0);
   $listaPrenotazione = $attivita->getElementsByTagName("prenotazione");
   $j = 0;
   while($j < count($listaPrenotazione) && $trovato=="False"){

       $prenotazione = $listaPrenotazione->item($j);
       $idPrenotazione= $prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
       
       if(isset($_POST[$idPrenotazione])){
        $trovato = "True";
        $listaPrenotazioni->removeChild($prenotazione);
    }
    $j++;
   }
   $i++;
}

$docAttivita->save("../XML/Attivita.xml");
    
}




// Funzione per effettuare la sottrazione tra due orari

function differenzaOrari($orarioIniziale , $orarioDaSottrarre){

    $diff = $orarioDaSottrarre->diff($orarioIniziale);
    $nuoviMinuti = $diff->i;
    $nuovaOra = $diff->h;
    if(strlen($nuoviMinuti) == 1){
        $nuoviMinuti = "0".$nuoviMinuti;
    }

    if(strlen($nuovaOra) == 1){
        $nuovaOra = "0".$nuovaOra;
    }

    $nuovaOraFine = $nuovaOra.":".$nuoviMinuti.":00";
    return $nuovaOraFine;
}


// Funzione per cercare un tavolo disponibile all'interno di prenotaTavolo.php

function cercaTavoloDisponibile($dataPrenotazioneScelta, $locazioneScelta , $pasto , $oraPrenotazioneScelta , $codFiscCliente){
    $xmlString = "";

    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlString .= trim($node);
    } 

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaTavoli = $doc->documentElement->childNodes;
    
    $orariRistorante = getOrariRistorante();

    $i=0;
    $trovato = "False";

    while($i < $listaTavoli->length && $trovato == "False"){
        $tavolo = $listaTavoli->item($i);

        $locazioneTavolo = $tavolo->getElementsByTagName("locazione")->item(0)->textContent;

        if($locazioneTavolo == $locazioneScelta ){
            $listaPrenotazioni = $tavolo->getElementsByTagName("prenotazione");
            $j=0;
            $disponibile = "True";

            while($j < $listaPrenotazioni->length && $disponibile == "True"){
                $prenotazione = $listaPrenotazioni->item($j);

                $dataPrenotazione = $prenotazione->getElementsByTagName("data")->item(0)->textContent;
                if($dataPrenotazione == $dataPrenotazioneScelta){
                    $oraPrenotazione = $prenotazione->getElementsByTagName("ora")->item(0)->textContent;
                    if($pasto == "Pranzo"){
                        if($oraPrenotazione >= $orariRistorante['aperturaPranzo'] && $oraPrenotazione <= $orariRistorante['chiusuraPranzo']){
                            $disponibile = "False";
                        }
                        else{
                            $j++;
                        }
                    }
                    else{
                        if($oraPrenotazione >= $orariRistorante['aperturaCena'] && $oraPrenotazione <= $orariRistorante['chiusuraCena']){
                            $disponibile = "False";
                        }
                        else{
                            $j++;
                        } 
                    }

                }
                else{
                    $j++;
                }
            }

            if($disponibile == "True"){
                $trovato = "True";
            }
            else{
                $i++;
            }

        }
        else{            
            $i++;
        }
    }

    if($trovato == "True"){
        $numTavolo = $tavolo->getAttribute("numero");        
        inserisciPrenotazioneTavolo($numTavolo ,$codFiscCliente ,$dataPrenotazioneScelta , $oraPrenotazioneScelta);
        return "success";
    }
    else{        
        return "insuccess";             // Questo significa che non ho trovato un tavolo disponibile 
    }

}

// Funzione per verificare se un determinato tavolo ?? disponibile 

function verificaDisponibilitaTavolo($numeroTavolo, $idPrenotazioneEsistente , $dataScelta , $pasto){    
    $xmlStringTavoli = "";
    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlStringTavoli .=trim($node);
    }
    $docTavoli = new DOMDocument();
    $docTavoli->loadXML($xmlStringTavoli);    

    $xpathTavoli = new DOMXPath($docTavoli);

    $tavolo = $xpathTavoli->query("/listaTavoli/tavolo[@numero='$numeroTavolo']");
    $tavolo = $tavolo->item(0);

    $orariRistorante = getOrariRistorante();

    $listaPrenotazioni = $tavolo->getElementsByTagName("prenotazione");      
    $i=0;
    $disponibile = "True";    

    while($i < $listaPrenotazioni->length && $disponibile == "True"){
        $prenotazione = $listaPrenotazioni->item($i);  
        $idPrenotazione = $prenotazione->getElementsByTagName("idPrenotazione")->item(0)->textContent;
        
        if($idPrenotazione != $idPrenotazioneEsistente){
            $dataPrenotazione = $prenotazione->getElementsByTagName("data")->item(0)->textContent;
            if($dataPrenotazione == $dataScelta){            
                $oraPrenotazione = $prenotazione->getElementsByTagName("ora")->item(0)->textContent;
                if($pasto == "Pranzo"){
                    if($oraPrenotazione >= $orariRistorante['aperturaPranzo'] && $oraPrenotazione <= $orariRistorante['chiusuraPranzo']){
                        $disponibile = "False";
                    }
                    else{
                        $i++;
                    }
                }
                else{
                    if($oraPrenotazione >= $orariRistorante['aperturaCena'] && $oraPrenotazione <= $orariRistorante['chiusuraCena']){
                        $disponibile = "False";
                    }
                    else{
                        $i++;
                    } 
                }

            }
            else{
                $i++;
            }
        }
        else{
            $i++;
        }        
    }

    return $disponibile;
}




// Funzione per capire quali portate sono state scelte in listaPortate.php

function individuaPortateSelezionate(){
    $portate = getPortate();
    $antipasti = $portate[0];
    $primiPiatti = $portate[1];
    $secondiPiatti = $portate[2];
    $dolci = $portate[3];

    $tabellaPortateScelte = array();

    for($i=0 ; $i < 4 ; $i++){
        $tipoPortata = $portate[$i];
        
        for($j=0 ; $j < count($tipoPortata) ; $j++){
            $portata = $tipoPortata[$j];
            $nomePortata = str_replace(" ", "", $portata['descrizione']);

            if(isset($_POST[$nomePortata])){
                $prezzoPortata = $portata['prezzo'];
                $quantitaScelta = $_POST[$nomePortata."-quantita"];
                
                $temp = array(
                    "descrizione"=>$portata['descrizione'],
                    "prezzo"=>$prezzoPortata,
                    "quantita"=>$quantitaScelta
                );

                array_push($tabellaPortateScelte , $temp);
            }
        }
    }

    if(count($tabellaPortateScelte) >= 1){
        return $tabellaPortateScelte;
    }
    else{
        return "null";  // Questo significa che l'utente non ha selezionato nessuna checkbox
    }

}


// Funzione per capire l'id della prenotazione associata al bottone premuto in visualizzaPrenotazioniRistorante.php

function individuaBottonePrenotazioniRistorante(){
    $listaIdPrenotazioniTavolo = getIdPrenotazioniTavolo();

    $i=0;
    $trovato="False";

    while($i < $listaIdPrenotazioniTavolo->length && $trovato == "False"){        
        $idPrenotazioneTavolo = $listaIdPrenotazioniTavolo->item($i)->textContent;
        
        if(isset($_POST[$idPrenotazioneTavolo])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    if($trovato != "True"){
        $listaIdPrenotazioniSC = getIdPrenotazioniSC();

        $i=0;
        
        while($i < $listaIdPrenotazioniSC->length && $trovato == "False"){
            $idPrenotazioneSC = $listaIdPrenotazioniSC->item($i)->textContent;
            if(isset($_POST[$idPrenotazioneSC])){
                $trovato = "True";
            }
            else{
                $i++;
            }
        }

        return $idPrenotazioneSC;
    }
    else{
        return $idPrenotazioneTavolo;
    }

}

function individuaBottoneDescrizionePortata(){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathRistorante = new DOMXPath($doc);
    $listaPortate = $xpathRistorante->query("/ristoranti/ristorante/menu/portata");
    $i=0;
    $trovato="False";
    while($i< $listaPortate->length && $trovato=="False"){
        $portata = $listaPortate->item($i);
        $descrizione = $portata->getElementsByTagName("descrizione")->item(0)->textContent;

        $nomePortataNonSeparato = str_replace(" ", "", $descrizione);

            if(isset($_POST[$nomePortataNonSeparato])){
                $trovato = "True";
            }
        
        $i++;
    }
    $nomePortata=array();
    $nomePortata['nomeSeparato']=$descrizione;
    $nomePortata['nomeNonSeparato']=$nomePortataNonSeparato;
    return $nomePortata;
}

//Funzione che controlla se il nome della portata ?? gi?? presente nel menu

function checkNomePortata($nomePortata){
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathRistorante = new DOMXPath($doc);
    $listaPortate = $xpathRistorante->query("/ristoranti/ristorante/menu/portata");
    $i=0;
    while($i< $listaPortate->length){
        $portata = $listaPortate->item($i);
        $descrizione = $portata->getElementsByTagName("descrizione")->item(0)->textContent;
            if($descrizione==$nomePortata){
                return "True";
                exit();
            }
        $i++;
    }
    return "False";
}

//Funzione che restituisce true se il tempo in cui ?? chiamata rientra tra gli orari di update della concierge del menu, altrimenti restituisce false

function confermaOraUpdateMenu(){
    $oraCorrente=time();
    $xmlString = "";
    foreach(file("../XML/Ristorante.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $xpathRistorante = new DOMXPath($doc);
    $oraInizioUpdate = $xpathRistorante->query("/ristoranti/ristorante/oraInizioUpdate");
    $oraInizioUpdate=$oraInizioUpdate->item(0)->textContent;

    $oraFineUpdate = $xpathRistorante->query("/ristoranti/ristorante/oraFineUpdate");
    $oraFineUpdate=$oraFineUpdate->item(0)->textContent;
    if($oraCorrente>=strtotime($oraInizioUpdate) && $oraCorrente<=strtotime($oraFineUpdate)){
        return "True";
    }else{
        return "False";
    }

}

// Funzione per individuare l'id della domanda associato al bottone premuto in domande.php

function individuaBottoneDomanda(){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString);

    $listaDomande = $docDomande->documentElement->childNodes;

    $trovato = "False";
    $i=0;

    while ($i < $listaDomande->length && $trovato == "False"){
        $domanda = $listaDomande->item($i);
        $idDomanda = $domanda->getAttribute("id");

        if(isset($_POST[$idDomanda])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idDomanda;
}


// Funzione per individuare la risposta selezionata dal concierge o dall'admin in risposteDomanda.php quando si vuole elevare una domanda a faq

function individuaBottoneRispostaSelezionata($idDomanda){
    $xmlString = "";
    foreach(file("../XML/Domande.xml") as $node){
        $xmlString .=trim($node);
    }
    $docDomande = new DOMDocument();
    $docDomande->loadXML($xmlString);

    $xpathDomande = new DOMXPath($docDomande);

    $domanda = $xpathDomande->query("/listaDomande/domanda[@id='$idDomanda']");
    $domanda = $domanda->item(0);

    $listaRisposte = $domanda->getElementsByTagName("risposta");

    $trovato = "False";
    $i=0;
    
    while($i < $listaRisposte->length && $trovato == "False"){
        $risposta = $listaRisposte->item($i);
        $idRisposta = $risposta->firstChild->textContent;

        if(isset($_POST[$idRisposta])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idRisposta;    
}


//Funzione per verificare se gli orari inseriti dall'admin per fare l'update del menu non contrastino quelli del pranzo e della cena

function checkOrariRistorante($oraInizioUpdate,$oraFineUpdate){
    $orariRistoranti=getOrariRistorante();
    $oraAperturaPranzo=$orariRistoranti['aperturaPranzo'];
    $oraChiusuraPranzo=$orariRistoranti['chiusuraPranzo'];
    $oraAperturaCena=$orariRistoranti['aperturaCena'];
    $oraChiusuraCena=$orariRistoranti['chiusuraCena'];
    if(($oraInizioUpdate<=$oraAperturaPranzo && $oraFineUpdate<=$oraAperturaPranzo) || ($oraInizioUpdate>=$oraChiusuraPranzo && $oraFineUpdate>=$oraChiusuraPranzo)){
        if(($oraInizioUpdate<=$oraAperturaCena && $oraFineUpdate<=$oraAperturaCena) || ($oraInizioUpdate>=$oraChiusuraCena && $oraFineUpdate>=$oraChiusuraCena)){
            return "False";
        }else{
            return "True";
        }
    }else{
        return "True";
    }
}

//Funzione per riconoscere che faq ha selezionato la concierge/l'admin in faq.php

function individuaBottoneIdFaq(){
    $xmlString = "";
    foreach(file("../XML/FAQs.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaFaqs = $doc->documentElement->childNodes;

    $trovato = "False";
    $i=0;

    while ($i < $listaFaqs->length && $trovato == "False"){
        $faq = $listaFaqs->item($i);
        $idFaq = $faq->getAttribute("id");

        if(isset($_POST[$idFaq])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idFaq;

}


// Funzione per individuare il bottone premuto in recensioni.php

function individuaBottoneRecensione(){
    $xmlString = "";
    foreach(file("../XML/recensioni.xml") as $node){
        $xmlString .= trim($node);
    }
    $docRecensioni = new DOMDocument();
    $docRecensioni->loadXML($xmlString);

    $listaRecensioni = $docRecensioni->documentElement->childNodes;

    $i=0;
    $trovato = "False";

    while($i < $listaRecensioni->length && $trovato == "False"){
        $recensione = $listaRecensioni->item($i);
        $idRecensione = $recensione->getAttribute("id");

        if(isset($_POST[$idRecensione])){
            $trovato = "True";
        }
        else{
            $i++;
        }
    }

    return $idRecensione;
}





//Funzione per individuare il bottone premuto in risposteRecensione.php

function individuaBottoneRisposteRecensione($idRecensione){
    $xmlString = "";
    foreach(file("../XML/Commenti.xml") as $node){
        $xmlString .= trim($node);
    }

    $docCommenti = new DOMDocument();
    $docCommenti->loadXML($xmlString);
    
    $xpathCommenti = new DOMXPath($docCommenti);

    $listaCommenti = $xpathCommenti->query("/listaCommenti/commento[idRecensione='$idRecensione']");

    $i=0;
    $trovato = "False";
    $idOggettoScelto = "";

    while($i < $listaCommenti->length && $trovato == "False"){
        $commento = $listaCommenti->item($i);
        $idCommento = $commento->getAttribute("id");

        if(isset($_POST[$idCommento])){
            $trovato = "True";
            $idOggettoScelto = $idCommento;
        }
        else{
            $listaRisposteCommento = $commento->getElementsByTagName("risposta");
            $j=0;
            
            while($j < $listaRisposteCommento->length && $trovato == "False"){
                $risposta = $listaRisposteCommento->item($j);
                $idRisposta = $risposta->firstChild->textContent;

                if(isset($_POST[$idRisposta])){
                    $trovato = "True";
                    $idOggettoScelto = $idRisposta;
                }
                else{
                    $j++;
                }
            }

            if($trovato == "False"){
                $i++;
            }
        }
    }

    return $idOggettoScelto;


}



//Funzione per verificare se un dato username esiste gi?? all'interno dei file Concierge.xml oppure Amministratori.xml

function checkUsernameDuplicatoStaff($username , $tipoStaff){
    $xmlString = "";

    if($tipoStaff == "Concierge"){
        foreach(file("../XML/Concierge.xml") as $node){
            $xmlString .= trim($node);
        }
    }
    else{
        foreach(file("../XML/Amministratori.xml") as $node){
            $xmlString .= trim($node);
        }
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $listaStaff = $doc->documentElement->childNodes;

    $i=0;
    $duplicato = "False";

    while($i < $listaStaff->length && $duplicato == "False"){
        $staff = $listaStaff->item($i);
        $usernameStaff = $staff->firstChild->textContent;

        if($usernameStaff == $username){
            $duplicato = "True";
        }
        else{
            $i++;
        }
    }

    return $duplicato;
}






?>

