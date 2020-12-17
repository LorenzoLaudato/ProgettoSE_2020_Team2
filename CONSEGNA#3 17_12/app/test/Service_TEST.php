<?php

use PHPUnit\Framework\TestCase;
include "../src/controller/ServiceFactory.php";
include "../src/controller/general/general_functions.php";

final class Service_TEST extends TestCase
{
    public function test_getActivities()
    {

        $micro = ServiceFactory::create();
        $this->assertFalse($micro->getActivities("")); //se l'utente non inserisce un input per weekNumber, la funzione restituisce false
        $activity3 = ['7', 'Hydraulic', 'Salerno - Molding', '45', 'Robot motor oil has burned out', 'It is possible to intevene only after 14:30','pdf7', 3, 'planned', ''];
        $ret = $micro->getActivities(3);
        $row = pg_fetch_row($ret);
        for ($i = 0; $i < count($activity3); $i++) {
            $this->assertEquals($activity3[$i], $row[$i]);
        }
    }

    public function test_get_skillsNeeded()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->getSkillsNeeded(5);
        //devo testare se in $ret ci sono le due skills: 1) Electrical Maintenance, 2) xyz- type robot knowledge
        $row = pg_fetch_row($ret);
        $this->assertEquals('Electrical Maintenance', $row[0]);
        $row = pg_fetch_row($ret);
        $this->assertEquals('xyz- type robot knowledge', $row[0]);

    }

    public function test_infoActivity()
    {
        /*
        code = 1 , tipo = Mechanical, area=Fisciano - Molding, tempo intervento = 120,
        descrizione = Replacement of robot 23 welding cables, note = The plant is closed from 00/00/20 to 00/00/20. On the remaining days, it is possible to intevene only after 16:00,
        pdf_directory = pdf1, nsettimana=1
        */
        $micro = ServiceFactory::create();
        $ret = $micro->infoActivity(1);

        $infoactivity1 = ['1', 'Mechanical', 'Fisciano - Molding', '120', 'Replacement of robot 23 welding cables', 'The plant is closed from 00/00/20 to 00/00/20. On the remaining days, it is possible to intevene only after 15:00', 'pdf1', 1];
        //devo testare se in $ret c'è quello inserito nell'array

        $row = pg_fetch_row($ret);
        for ($i = 0; $i < count($infoactivity1); $i++) {
            $this->assertEquals($infoactivity1[$i], $row[$i]);
        }

        /*

         - FAIL-
         $infoactivity2 = ['3', 'Mechanical', 'Fisciano - Molding', '120', 'Replacement of robot 23 welding cables', 'The plant is closed from 00/00/20 to 00/00/20. On the remaining days, it is possible to intevene only after 16:00', 'pdf1', 1];
         for ($i=0; $i<count($infoactivity2); $i++){
            $this->assertEquals($infoactivity2[$i], $row[$i]);
        }
        */

    }

    public function test_showMaintainers()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->showMaintainers();
        $row = pg_fetch_row($ret);
        $availAmeliaMonday = [10, 1, 'Amelia Hill', '062272'];
        for ($i = 0; $i < count($availAmeliaMonday); $i++) {
            $this->assertEquals($availAmeliaMonday[$i], $row[$i]);
        }
        //se si vuole fare la prova per tutta la tabella, fare su postgres:
        // SELECT percGiornata, giorno, nome, matricola FROM giorni JOIN manutentore ON giorni.manutentore = manutentore.matricola ORDER BY (nome,giorno);
        // creare una matrice contenente i risultati della query
        //in questo caso è stata fatta la prova solo per la prima riga del risultato della query.

        // -FAIL-
        /*
         $availAmeliaMonday1 = [10, 1, 'Amelia Hillsss', '062272'];
        for ($i=0; $i<count($availAmeliaMonday1); $i++){
            $this->assertEquals($availAmeliaMonday1[$i], $row[$i]);
        }
         */
    }

    public function test_countSkillsNeeded()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->countSkillsNeeded(1);
        $this->assertEquals(4, $ret);
    }

    public function test_countSkillsEffective()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->countSkillsEffective(1, '062271');
        $this->assertEquals(2, $ret);
    }


    public function test_insertUtente()
    {
        //restituisce false se empty($nome) || empty($username1)
        // $hash = password_hash($password1, PASSWORD_DEFAULT);
        //        $sql = "INSERT INTO ACCOUNT(nome, username, password) VALUES('$nome', '$username1', '$hash');";
        //dopo questo $ret è false se !$ret oppure true.
        $micro = ServiceFactory::create();
        $ret = $micro->insertUtente('MartinaTest', 'planner', '1234', 'martinalamberti3@gmail.com', 'Planner');
        $this->assertTrue($ret);

        $ret_empty = $micro->insertUtente('', '', '1234', '', '');
        $this->assertFalse($ret_empty);

        // -- FAIL --
        /*
         $ret_empty = $this->micro->insertUtente('', '', '1234', '', '');
        $this->assertTrue($ret_empty);
         * */
    }

    public function test_get_users(){
        $micro = ServiceFactory::create();
        $ret = $micro->getUsers('martina');
        $result = ['MartinaTest', 'planner', '1234', 'martinalamberti3@gmail.com'];
        for ($i = 0; $i < pg_num_rows($ret); $i++) {
            $row = pg_fetch_row($ret);
            $this->assertEquals($result[$i], $row[0]);
        }
    }

    public function test_usernameExist()
    {
        $micro = ServiceFactory::create();
        $ret_new = $micro->usernameExist('MaintainerAmelia');
        $this->assertFalse($ret_new); //l'utente non esiste, quindi correttamente la funzione restituisce False

        /*
        $ret = $this->micro->usernameExist('planner');
        $this->assertFalse($ret); //l'utente esiste, quindi il risultato è True: il test non va a buon fine
         */

    }

    public function test_getPassword()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->getPassword('planner', 'Planner');
        $this->assertTrue(password_verify("1234", $ret));

        // - FAIL : la pass è 1234, non 1235
        // $this->assertTrue(password_verify("1235", $ret));
    }

    public function test_getTimeslot()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->getTimeslot("062271", 1);
        $percLunMaintein = ["50", "50", "40", "40", "50", "50", "50", "50", "50", "50",];

        for ($i = 0; $i < pg_num_rows($ret); $i++) {
            $row = pg_fetch_row($ret);
            $this->assertEquals($percLunMaintein[$i], $row[0]);
        }
    }

    public function test_getMaintainers(){
        $micro = ServiceFactory::create();
        $ret = $micro->getMaintainers();
        $row = pg_fetch_row($ret);
        $M= ['062271', 'Kylie Johnson', "kyliejohnson@gmail.com"];
        for ($i = 0; $i < count($M); $i++) {
            $this->assertEquals($M[$i], $row[$i]);
        }
        $row = pg_fetch_row($ret);
        $M = ['062272', 'Amelia Hill', "ameliahill@hotmail.com"];
        for ($i = 0; $i < count($M); $i++) {
            $this->assertEquals($M[$i], $row[$i]);
        }
        $row = pg_fetch_row($ret);
        $M = ['062273', 'Olivia Brook', "oliviabrook@outlook.it"];
        for ($i = 0; $i < count($M); $i++) {
            $this->assertEquals($M[$i], $row[$i]);
        }

        $row = pg_fetch_row($ret);
        $M = ['062274', 'John Butler', "jbutler45@gmail.com"];
        for ($i = 0; $i < count($M); $i++) {
            $this->assertEquals($M[$i], $row[$i]);
        }
        $row = pg_fetch_row($ret);
        $M = ['062275', 'Wilson Fisher', "wilsonf@gmail.com"];
        for ($i = 0; $i < count($M); $i++) {
            $this->assertEquals($M[$i], $row[$i]);
        }
        $row = pg_fetch_row($ret);
        $M = ['062276', 'Harry Smith', "hs45@hotmail.it"];
        for ($i = 0; $i < count($M); $i++) {
            $this->assertEquals($M[$i], $row[$i]);
        }

    }

    public function test_getMaintainerSkills()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->getMaintainerSkills("062273");
        $row = pg_fetch_row($ret);
        $atteso = [1, 'Pav Certification'];

        for ($i = 0; $i < count($atteso); $i++) {
            $this->assertEquals($atteso[$i], $row[$i]);
        }
    }

    public function test_deleteMaintainerSkill()
    {
        $micro = ServiceFactory::create();
        $esito = $micro->deleteMaintainerSkill("062273", 1);
        $this->assertTrue($esito); //la cancellazione è andata a buon fine
    }

    public function test_getAllSkills()
    {
        $micro = ServiceFactory::create();
        $ret = $micro->getAllSkills();
        $row = pg_fetch_row($ret);
        $Pav = [1, "Pav Certification"];
        for ($i = 0; $i < count($Pav); $i++) {
            $this->assertEquals($Pav[$i], $row[$i]);
        }
        $row = pg_fetch_row($ret);
        $Electrical = [2, "Electrical Maintenance"];
        for ($i = 0; $i < count($Electrical); $i++) {
            $this->assertEquals($Electrical[$i], $row[$i]);
        }
        $xyz = [3, "xyz- type robot knowledge"];
        $row = pg_fetch_row($ret);
        for ($i = 0; $i < count($xyz); $i++) {
            $this->assertEquals($xyz[$i], $row[$i]);
        }
        $robot = [4, "Knowledge of robot workstation 23"];
        $row = pg_fetch_row($ret);
        for ($i = 0; $i < count($robot); $i++) {
            $this->assertEquals($robot[$i], $row[$i]);
        }
        $experience = [5, "Experience in Machining, Fabricating, and Complex Assembly"];
        $row = pg_fetch_row($ret);
        for ($i = 0; $i < count($experience); $i++) {
            $this->assertEquals($experience[$i], $row[$i]);
        }
    }

    public function test_addSkillToMaintainer()
    {
        $micro = ServiceFactory::create();
        $esito = $micro->addSkillToMaintainer("062273", 5);
        $this->assertTrue($esito); //è andato a buon fine siccome il manutentore non possiede la skill con codice 5
        $esito_neg = $micro->addSkillToMaintainer("062273", 5);
        $this->assertFalse($esito_neg);

    }

    public function test_deleteUser(){
        $micro = ServiceFactory::create();
        $esito = $micro->deleteUser('System Administrator');
        $this->assertNotEquals(True, $esito); //l'esito è false perchè l'utente non esiste nel db

    }

    public function test_addSkillToActivity(){
        $micro = ServiceFactory::create();
        $esito = $micro->addSkillToActivity(1, 5);
        $this->assertTrue($esito); //l'assegnazione della skill all'attività è andata a buon fine
    }


    public function test_updateAssignedActivity(){
        $micro = ServiceFactory::create();
        $esito = $micro->updateAssignedActivity(7, '062274');
        $this->assertTrue($esito); //l'attività è stata correttamente assegnata al manutentore

        $esito = $micro->updateAssignedActivity(7, '062274');
        $this->assertFalse($esito); //l'attività è stata già assegnata al manutentore, quindi l'aggiornamento non va a buon fine
    }

    public function test_getAssignedActivities(){
        $micro = ServiceFactory::create();
        $ret = $micro->getAssignedActivities();
        $row = pg_fetch_row($ret);
        $result = ['062274', "John Butler", '7', 'Salerno - Molding', 'Hydraulic'];
        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($result[$i], $row[$i]);
        }

    }

    public function test_getMaintainerEmail(){
        $micro = ServiceFactory::create();
        $ret = $micro->getMaintainerEmail("062273");
        $row = pg_fetch_row($ret);
        $this->assertEquals("oliviabrook@outlook.it", $row[0]);
    }

    public function test_getMaintainerName(){
        $micro = ServiceFactory::create();
        $ret = $micro->getMaintainerName("062273");
        $row = pg_fetch_row($ret);
        $this->assertEquals("Olivia Brook", $row[0]);
    }

    public function test_getNotAssociatedSkills(){
        $micro = ServiceFactory::create();
        $ret = $micro->getNotAssociatedSkills(4);
        $row = pg_fetch_row($ret);
        $result = [3, "xyz- type robot knowledge"];
        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($result[$i], $row[$i]);
        }
        $row = pg_fetch_row($ret);
        $result = [5, "Experience in Machining, Fabricating, and Complex Assembly"];
        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($result[$i], $row[$i]);
        }
    }

    public function test_getDayName(){
        $this->assertEquals('Monday', getDayName(1) );
        $this->assertEquals('Tuesday', getDayName(2) );
        $this->assertEquals('Wednesday', getDayName(3) );
        $this->assertEquals('Thursday', getDayName(4) );
        $this->assertEquals('Friday', getDayName(5));
        $this->assertEquals('Saturday', getDayName(6) );
        $this->assertEquals('Sunday', getDayName(7) );
        $this->assertNotEquals('Sunday', getDayName(1) );
    }

}