<?php
    require_once("funzioniModificaPHP.php");

// FILE CHE CONTIENE TUTTE E SOLO LE FUNZIONI PER INSERIRE DATI NEI FILE XML


//Funzione che crea un nuovo utente all'interno del file Clienti.xml
function inserisciNuovoCliente(){    
    $xmlStringClienti = "";

    foreach(file("../XML/Clienti.xml") as $node){
        $xmlStringClienti .= trim($node);
    }
            
    $docClienti = new DOMDocument();
    $docClienti->loadXML($xmlStringClienti);
    $docClienti->formatOutput = true;

    $anno = mb_substr($_POST['dataNascita'], 0 , 4);
    $mese = mb_substr($_POST['dataNascita'], 5 , 2);
    $giorno = mb_substr($_POST['dataNascita'], 8 , 2);

    $currentDate= date('Y-m-d');

    $nuovoCliente = $docClienti->createElement("cliente");
    $nuovoCliente->setAttribute("codFisc", $_POST['codFisc']);
    $listaClienti = $docClienti->documentElement;
    $listaClienti->appendChild($nuovoCliente);

    $nuovoNome = $docClienti->createElement("nome", $_POST['nome']);
    $nuovoCliente->appendChild($nuovoNome);

    $nuovoCognome = $docClienti->createElement("cognome", $_POST['cognome']);
    $nuovoCliente->appendChild($nuovoCognome);

    $nuovaDataNascita = $docClienti->createElement("dataDiNascita", $anno."-".$mese."-".$giorno);
    $nuovoCliente->appendChild($nuovaDataNascita);

    $nuovoIndirizzo = $docClienti->createElement("indirizzo", $_POST['indirizzo']);
    $nuovoCliente->appendChild($nuovoIndirizzo);

    $nuovoTelefono = $docClienti->createElement("telefono", $_POST['telefono']);
    $nuovoCliente->appendChild($nuovoTelefono);

    $nuovaEmail = $docClienti->createElement("email", $_POST['email']);
    $nuovoCliente->appendChild($nuovaEmail);

    $nuovoNumCarta = $docClienti->createElement("numeroCarta", $_POST['numeroCarta']);
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


//Funzione per inserire una nuova prenotazione di una camera 

function inserisciPrenotazioneCamera($idCamera ,$codFiscCliente , $creditiUsati, $dataArrivo , $dataPartenza){
    $xmlString = "";
    foreach(file("../XML/Camere.xml") as $node){
        $xmlString .=trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathCamere = new DOMXPath($doc);

    $camera = $xpathCamere->query("/listaCamere/Camera[@numero='$idCamera']");
    $camera = $camera->item(0);

    $listaPrenotazioni = $camera->getElementsByTagName("listaPrenotazioni")->item(0);
    $ultimaPrenotazione = $listaPrenotazioni->lastChild;
    if(is_null($ultimaPrenotazione)){
        $idNuovaPrenotazione = $idCamera."-PC1";
    }
    else{
        $idUltimaPrenotazione = $ultimaPrenotazione->getElementsByTagName('idPrenotazione')->item(0)->textContent;

        $pieces = explode("-",$idUltimaPrenotazione);

        $nuovoNumero = substr($pieces[1] , 2) + 1;
        $idNuovaPrenotazione = $idCamera."-PC".$nuovoNumero;    
    }
    

    $nuovaPrenotazione = $doc->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoID = $doc->createElement("idPrenotazione" , $idNuovaPrenotazione);
    $nuovaPrenotazione->appendChild($nuovoID);

    $nuovoCodFisc = $doc->createElement("codFisc" , $codFiscCliente);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovoStato = $doc->createElement("statoSoggiorno" , "Pagamento sospeso");
    $nuovaPrenotazione->appendChild($nuovoStato);

    $nuoviCrediti = $doc->createElement("creditiUsati" , $creditiUsati);
    $nuovaPrenotazione->appendChild($nuoviCrediti);

    $nuovaDataArrivo = $doc->createElement("dataArrivo" , $dataArrivo);
    $nuovaPrenotazione->appendChild($nuovaDataArrivo);

    $nuovaDataPartenza = $doc->createElement("dataPartenza" , $dataPartenza);
    $nuovaPrenotazione->appendChild($nuovaDataPartenza);


    modificaCreditiCliente($codFiscCliente, -$creditiUsati);    

    $doc->save("../XML/Camere.xml");
}

//Funzione  per aggiungere una prenotazione alle attività

function aggiungiPrenotazioneAttivita($idAttivita,$codFisc,$data,$oraInizio,$oraFine,$prezzoTotale,$creditiInseriti){


    $xmlStringAttivita= "";

    foreach(file("../XML/Attivita.xml") as $node){

        $xmlStringAttivita .= trim($node);

    }

    $docAttivita = new DOMDocument();
    $docAttivita->loadXML($xmlStringAttivita);
    $docAttivita->formatOutput = true;

    $xpathAttivita = new DOMXPath($docAttivita);

    $attivita = $xpathAttivita->query("/listaAttivita/attivita[@id = '$idAttivita']");
    $attivita = $attivita->item(0);

    $listaPrenotazioni=$attivita->getElementsByTagName("listaPrenotazioni")->item(0);
    $prenotazioni=$attivita->getElementsByTagName("prenotazione");
    $numPrenotazioni=count($prenotazioni);
    

    $nuovaPrenotazione = $docAttivita->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoIdPrenotazione = $docAttivita->createElement("idPrenotazione", $idAttivita."-PA".($numPrenotazioni+1));
    $nuovaPrenotazione->appendChild($nuovoIdPrenotazione);

    $nuovoCodFisc= $docAttivita->createElement("codFisc", $codFisc);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovaData= $docAttivita->createElement("data", $data);
    $nuovaPrenotazione->appendChild($nuovaData);

    $nuovaOraInizio= $docAttivita->createElement("oraInizio", $oraInizio.":00");
    $nuovaPrenotazione->appendChild($nuovaOraInizio);

    $nuovaOraFine= $docAttivita->createElement("oraFine", $oraFine.":00");
    $nuovaPrenotazione->appendChild($nuovaOraFine);

    $nuovoPrezzoTotale= $docAttivita->createElement("prezzoTotale", $prezzoTotale);
    $nuovaPrenotazione->appendChild($nuovoPrezzoTotale);

    modificaCreditiCliente($codFisc , -$creditiInseriti);
    $nuovoCreditiUsati= $docAttivita->createElement("creditiUsati", $creditiInseriti);
    $nuovaPrenotazione->appendChild($nuovoCreditiUsati);

    $docAttivita->save("../XML/Attivita.xml");

}

//Funzione che inserisce una nuova categoria nel file Categorie.xml

function aggiungiCategoriaNuova($categoriaDaAggiungere){

    $xmlStringCategoria= "";

    foreach(file("../XML/Categorie.xml") as $node){

        $xmlStringCategoria .= trim($node);

    }

    $docCategoria = new DOMDocument();
    $docCategoria->loadXML($xmlStringCategoria);
    $docCategoria->formatOutput = true;

    $nuovaCategoria = $docCategoria->createElement("categoria");
    $listaCategorie= $docCategoria->documentElement;
    $listaCategorie->appendChild($nuovaCategoria);

    $nuovoNome = $docCategoria->createElement("nome", $categoriaDaAggiungere['nome']);
    $nuovaCategoria->appendChild($nuovoNome);

    $nuovoStato = $docCategoria->createElement("stato", "Attiva");
    $nuovaCategoria->appendChild($nuovoStato);

    $nuovaListaAzioni = $docCategoria->createElement("listaAzioni");
    $nuovaCategoria->appendChild($nuovaListaAzioni);

    if($categoriaDaAggiungere['prenotazioneTavolo']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione tavolo");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['prenotazioneServizioInCamera']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione servizio in camera");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['attivita']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione attivita");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['prenotazioneSoggiorno']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Prenotazione soggiorno");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }
    if($categoriaDaAggiungere['registrazione']!=""){
        $nuovaAzioneUtente = $docCategoria->createElement("azioneUtente");
        $nuovaListaAzioni->appendChild($nuovaAzioneUtente);
        $nuovaNomeAzione = $docCategoria->createElement("nomeAzione", "Registrazione cliente");
        $nuovaAzioneUtente->appendChild($nuovaNomeAzione);
    }

    $nuovaListaUtenti = $docCategoria->createElement("listaUtenti");
    $nuovaCategoria->appendChild($nuovaListaUtenti);

    $docCategoria->save("../XML/Categorie.xml");

}



// Funzione per inserire una prenotazione ad un determinato tavolo

function inserisciPrenotazioneTavolo($numeroTavolo , $codFiscCliente , $data , $ora){
    $xmlString= "";

    foreach(file("../XML/Tavoli.xml") as $node){
        $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathTavoli = new DOMXPath($doc);

    $tavolo = $xpathTavoli->query("/listaTavoli/tavolo[@numero = '$numeroTavolo']");
    $tavolo = $tavolo->item(0);

    $listaPrenotazioni = $tavolo->getElementsByTagName("listaPrenotazioni")->item(0);
    $prenotazioni = $tavolo->getElementsByTagName("prenotazione");
    $numPrenotazioni = count($prenotazioni);

    $nuovaPrenotazione = $doc->createElement("prenotazione");
    $listaPrenotazioni->appendChild($nuovaPrenotazione);

    $nuovoIdPrenotazione = $doc->createElement("idPrenotazione" , $numeroTavolo."-PT".($numPrenotazioni + 1));
    $nuovaPrenotazione->appendChild($nuovoIdPrenotazione);

    $nuovoCodFisc = $doc->createElement("codFisc" , $codFiscCliente);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovaDataPrenotazione = $doc->createElement("data" , $data);
    $nuovaPrenotazione->appendChild($nuovaDataPrenotazione);

    $nuovaOraPrenotazione = $doc->createElement("ora" , $ora);
    $nuovaPrenotazione->appendChild($nuovaOraPrenotazione);

    $doc->save("../XML/Tavoli.xml");
}

// Funzione per inserire la prenotazione di un servizio in camera in dettagliPrenotazioneSC.php

function inserisciPrenotazioneServizioCamera($portateScelte , $codFiscCliente   ,$data , $ora , $richieste , $prezzoTotale , $creditiUsati){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node ){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $elemRadice = $doc->documentElement;
    $listaPrenotazioni = $elemRadice->childNodes;
    $numPrenotazioni = $listaPrenotazioni->length;

    $nuovaPrenotazione = $doc->createElement("prenotazione");
    $nuovaPrenotazione->setAttribute("id" , "PSC".($numPrenotazioni + 1) );
    $elemRadice->appendChild($nuovaPrenotazione);

    $nuovoCodFisc = $doc->createElement("codFisc" , $codFiscCliente);
    $nuovaPrenotazione->appendChild($nuovoCodFisc);

    $nuovaData = $doc->createElement("data" , $data);
    $nuovaPrenotazione->appendChild($nuovaData);

    $nuovaOra = $doc->createElement("ora" , $ora);
    $nuovaPrenotazione->appendChild($nuovaOra);

    $nuoveRichieste = $doc->createElement("richieste" , $richieste);
    $nuovaPrenotazione->appendChild($nuoveRichieste);

    $nuovoPrezzoTotale = $doc->createElement("prezzoTotale" , $prezzoTotale);
    $nuovaPrenotazione->appendChild($nuovoPrezzoTotale);

    modificaCreditiCliente($codFiscCliente , -$creditiUsati);

    $nuoviCreditiUsati = $doc->createElement("creditiUsati" , $creditiUsati);
    $nuovaPrenotazione->appendChild($nuoviCreditiUsati);

    $nuovaListaPortate = $doc->createElement("listaPortate");
    $nuovaPrenotazione->appendChild($nuovaListaPortate);

    for($i=0 ; $i < count($portateScelte) ; $i++){
        $portata = $portateScelte[$i];
        $nuovaPortata = $doc->createElement("portata");
        $nuovaListaPortate->appendChild($nuovaPortata);

        $nuovoNome = $doc->createElement("nome" , $portata['descrizione']);
        $nuovaPortata->appendChild($nuovoNome);

        $nuovoPrezzo = $doc->createElement("prezzo", $portata['prezzo']);
        $nuovaPortata->appendChild($nuovoPrezzo);

        $nuovaQuantita =$doc->createElement("quantita" , $portata['quantita']);
        $nuovaPortata->appendChild($nuovaQuantita);
    }

    $doc->save("../XML/ServizioCamera.xml");
}

// Funzione per inserire una portata all'interno di una prenotazione già esistente

function aggiungiPortataPrenotazioneSC($idPrenotazione ,$nomePortata , $quantitaSelezionata){
    $xmlString = "";
    foreach(file("../XML/ServizioCamera.xml") as $node ){
        $xmlString .= trim($node);
    }
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $doc->formatOutput = true;

    $xpathSC = new DOMXPath($doc);

    $prenotazioneSC = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']");
    $prenotazioneSC = $prenotazioneSC->item(0);
    
    $portata = $xpathSC->query("/listaPrenotazioni/prenotazione[@id='$idPrenotazione']/listaPortate/portata[nome='$nomePortata']");
    if($portata->length == 1){
        $portata = $portata->item(0);
        $prezzoPortata = $portata->getElementsByTagName("prezzo")->item(0)->textContent;
        $vecchiaQuantita = $portata->getElementsByTagName("quantita")->item(0);

        $nuovaQuantita = $vecchiaQuantita->textContent + $quantitaSelezionata;

        $vecchiaQuantita->nodeValue="";
        $vecchiaQuantita->appendChild($doc->createTextNode($nuovaQuantita));
    }
    else{
        $prezzoPortata = getPrezzoPortata($nomePortata);        
        $listaPortate = $prenotazioneSC ->getElementsByTagName("listaPortate")->item(0);

        $nuovaPortata = $doc->createElement("portata");
        $listaPortate->appendChild($nuovaPortata);

        $nuovoNome = $doc->createElement("nome", $nomePortata);
        $nuovaPortata->appendChild($nuovoNome);

        $nuovoPrezzo = $doc->createElement("prezzo", $prezzoPortata);
        $nuovaPortata->appendChild($nuovoPrezzo);

        $nuovaQuantita = $doc->createElement("quantita", $quantitaSelezionata);
        $nuovaPortata->appendChild($nuovaQuantita);            
    }

    $vecchioPrezzoTot = $prenotazioneSC->getElementsByTagName("prezzoTotale")->item(0);
    $nuovoPrezzoTot = ($vecchioPrezzoTot->textContent) + ($prezzoPortata * $quantitaSelezionata);

    $vecchioPrezzoTot->nodeValue="";
    $vecchioPrezzoTot->appendChild($doc->createTextNode($nuovoPrezzoTot));

    $doc->save("../XML/ServizioCamera.xml");
}




?>