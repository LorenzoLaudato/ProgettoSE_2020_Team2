# SE2020Diem_Team2
#READ ME
I file sorgenti per la realizzazione della web application è organizzato in diverse cartelle, a seconda dell’utilità del formato. La cartella src presenta al suo interno le sottocartelle:
    -controller
    -model 
    -resources
    -sql
    -views
    -css
    -js
-------------
La cartella controller contiene i seguenti file:
-DataBaseConnection.php: classe di funzioni per la connessione al database. In questo file è sono presenti le credenziali da inserire per accedere al proprio        database;
-Cartella planner, contenente i file functions_pageX.php: insieme di funzioni di front-end utilizzate dalla pageX relative all’interfaccia del planner;
-Cartella general, contenente general_functions.php: insieme di funzioni di utility;
-Cartella systemAdministrator contenente il file sa_functions.php: insieme di funzioni di front-end utilizzate nelle pagine relative all’interfaccia del System Administrator;
-Service.php: classe che implementa l’interfaccia UserInterface contenente tutti i servizi offerti dall’app;
-ServiceFactory.php: classe “istanziatrice” che crea un oggetto della classe Service;
-UserInterface.php: interfaccia che contiene tutti i prototipi dei metodi che sono implementati nella classe Service;
-------------
La cartella model contiene i seguenti file:

      -MS_Activity.php: classe che raffigura il micro-servizio dedicato alle attività. Sono presenti i metodi contenenti richieste al database come ottenere le            informazioni dell’attività, l’aggiornamento delle note, del tempo stimato di intervento etc;
      -MS_maintainer.php: classe dedicata al micro-servizio per la gestione dei manutentori. I metodi effettuano query per richiedere informazioni come il nome,            l’email del manutentore, la disponibilità per svolgere un’attività, cancellare o aggiungere competenze di un particolare manutentore etc;
      -MS_Skills.php: classe per il micro-servizio riguardante le competenze. Mediante le funzioni proposte è possibile effettuare delle query al db per aggiornare        le competenze, inserirne di nuove oppure richiedere il conteggio delle skills richieste da una particolare attività e quelle effettivamente possedute dal            manutentore selezionato;
      -MS_Users.php: classe che raffigura il micro-servizio dedicato alla gestione degli utenti. I metodi proposti consentono di interfacciarsi al db per effettuare        l’inserimento/cancellazione di nuovi utenti, la verifica delle credenziali, l’aggiornamento di proprietà di un particolare utente etc;
-------------

Nella cartella views sono presenti 3 cartelle che separano le pagine dedicate al planner da quelle dedicate al System Administrator, la cartella comune general è dedicata alle operazioni di autenticazione.

	La cartella general contiene i seguenti file:
			- login.html: è la schermata di benvenuto dell’app e permette l’autenticazione agli utenti che hanno già effettuato precedentemente la registrazione. 
			- registrati.php: è il file concepito per la registrazione di un nuovo utente;
			- Una volta inserite le credenziali, il sistema ne verifica la correttezza e permette di raggiungere la pagina login-manager.php: tramite un apposito link è           possibile accedere ai contenuti riservati specifici per il ruolo inserito. 
      -Durante la navigazione dell’app è sempre possibile, tramite un apposito bottone, effettuare il logout: nel file logout.php è stata concepita una schermata di        arrivederci. 


	La cartella planner contiene i seguenti file:

			- page1.php: pagina principale dell’interfaccia planner che mostra la tabella delle attività di manutenzione a seconda della settimana selezionata                     dall’utente;
			- page2.php: pagina di visualizzazione dei dettagli relativi all’attività di manutenzione che deve essere svolta (descrizione dell’intervento, note, 		               competenze necessarie, file SMP);
			- page3.php: pagina di visualizzazione dei giorni disponibili dei manutentori;
			- page4.php: pagina per la selezione delle fasce orarie in cui il manutentore selezionato è disponibile per svolgere l’attività;
			- page2ewo.php: se in page1.php il planner seleziona un’attività EWO (quindi non pianificata) viene trasferito in questa pagina in cui deve inserire il tempo         stimato di intervento, la descrizione dell’attività e selezionare le skills richieste per svolgerla. 
			- page3ewo.php: pagina di selezione della fascia oraria in cui il manutentore dovrà svolgere l’attività.

	La cartella system administrator contiene i seguenti file:

      -configuration.php: una volta che il SA si è autenticato, questa pagina consente di visualizzare il menù principale in cui il SA può selezionare                      un’operazione;
      -viewMaintainers.php: pagina in cui il SA visualizza i manutentori e può decidere di effettuare delle modifiche premendo il bottone SELECT;
      -maintainerSkills.php: pagina che consente al SA di eliminare una skill del manutentore selezionato oppure decidere di assegnare una nuova competenza premendo        il bottone “ASSIGN A NEW SKILL”;
      -addSkills.php: pagina di visualizzazione di tutte le skills presenti nel database. Il SA, mediante le checkbox, seleziona nuove competenze al 	manutentore          precedentemente selezionato e conferma premendo il bottone “ADD”;
      -viewSkills.php: il SA gestisce le skills e può modificare premendo sul bottone “EDIT” e inserire una nuova competenza.
      -editSkill.php: pagina che consente di modificare la descrizione di una competenza;
      -viewProcedure.php: pagina che associa ad ogni manutentore l’attività che gli è stata assegnata in base alle sue competenze e disponibilità. In tal modo il SA        prende visione dell’organizzazione delle attività;
      -viewUsers.php: pagina di visualizzazione di tutti gli utenti del sistema. Il SA può aggiungere o eliminare un nuovo utente tramite appositi bottoni oppure          decidere di modificare le proprietà di un utente premendo il bottone “SELECT”;
      -editUsers.php: pagina che consente di modificare il ruolo e l’username dell’utente. 


-------------

In css è presente il file styles.css che rappresenta il foglio di stile dell’applicazione.

-------------

La cartella js contiene i seguenti file:

- functionJavaScript.js: contiene le funzioni comuni a tutte le pagine,dedicate alla dinamicità di esse;
- page3.js:contiene le funzioni dedicate alla dinamicità di pagina tre, tra cui la logica implementata per le diverse colorazioni delle celle della tabella dipendenti dalla percentuale di disponibilità del maintainer ;
- page3ewo.js: il file definisce il comportamento della pagina 3 relativa ad un’attività EWO;
- page4.js: il file definisce le funzioni dedicate alla dinamicità della pagina 4, tra cui la funzione dedicata all’invio di e-mail ;
- system.js: contiene le funzioni per alla dinamicità della pagina dedicata alsystem administrator ;

-------------

In resource sono presenti le immagini e le icone illustrate dall’app e i file pdf delle Standard Maintenance Procedure.

-------------

Nella cartella sql è presente il file db_maintenance_activity.sql che contiene il database e il popolamento delle relative tabelle.
-------------

Oltre alla cartella src è presente la cartella test dedicata alle classi di test:

- DatabaseConnection_TEST.php: classe di test dedicata al database;
- Service_TEST.php: classe di test relativa alla classe Service. 

