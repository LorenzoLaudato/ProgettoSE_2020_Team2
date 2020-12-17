<?php
require_once("DatabaseConnection.php");
require_once(__DIR__ . "/../model/MS_Skills.php");
require_once(__DIR__ . "/../model/MS_Maintainer.php");
require_once(__DIR__ . "/../model/MS_Users.php");
require_once(__DIR__ . "/../model/MS_Activity.php");
require_once("UserInterface.php");



/* La classe Service implementa i metodi utilizzati dal front-end per effettuare le operazioni richieste dall'utente*/
class Service implements UserInterface
{
    //Hold the class instance.
    private static $instance = null;

    /**Private constructor*/
    private function __construct()
    {
        //CONNESSIONE AL DATABASE
        $db = DatabaseConnection::getInstance();
        $db->getConnection();
        $this->db = $db;
    }


    /**This method returns an istance of Service class
     * */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Service();
        }

        return self::$instance;
    }

    /**This method returns all the Activities scheduled in the selectedWeekNumber
     * Params:
     * $weekNumber: it represents the weekNumber selected by user.
     */
    public function getActivities($weekNumber)
    {
        if (!empty($weekNumber)) { //se l'utente inserisce un input per weekNumber, viene invocato il Microservizio interfacciandosi col db
            return MS_Activity::get_activities($weekNumber, $this->db); //la funzione restituisce l'insieme delle attività (risultato della query del db) schedulate nella settimana selezionata
        } else {
            return false;
        }
    }


    /**This method returns all the Informations of the selected Activity
     * Params:
     * $code: it represents the code of the selected Activity.
     */
    public function infoActivity($code)
    { //astratti
        return MS_Activity::info_activity($code, $this->db); //la funzione restituisce il risultato della query contenente le informazioni dell'attività selezionata
    }


    /**This method returns all the Skills needed to carry out the selected Activity
     * Params:
     * $code: it represents the identifier of the selected Activity.
     */
    public function getSkillsNeeded($code)
    { //skills necessarie
        return MS_Skills::get_skillsNeeded($code, $this->db); //viene invocato il Microservizio per mostrare l'elenco delle competenze richieste per l'attività selezionata
    }


    /**This method updates the Note attribute in the DB.
     * Params:
     * $code: it represents the identifier of the selected Activity.
     */
    public function updateNote($code)
    { 
        MS_Activity::update_note($code, $this->db); //viene invocato il Microservizio per aggiornare le note dell'attivita selezionata dall'utente
    }


    /**This method returns all the Maintainers available in that week.
     */
    public function showMaintainers()
    { 
        return MS_Maintainer::show_maintainers($this->db);
    }


    /**This method returns the number of the skills needed to carry out the selected Activity.
     * Params:
     * $code: it represents the identifier of the selected Activity.
     */
    public function countSkillsNeeded($code)
    { 
        return MS_Skills::skillsCountNeeded($code, $this->db);
    }


    /**This method returns the number of the skills owned by the Maintainers to carry out the selected Activity.
     * Params:
     * $code: it represents the identifier of the selected Activity;
     * $matricola: it represents the identifier of the Maintainer.
     */
    public function countSkillsEffective($code, $matricola)
    { //$code è $code di attività
        return MS_Skills::skillsCountEffective($code, $matricola, $this->db);
    }


    /**This method inserts a new user in the table Account of the DB.
     * Params:
     * $nome: it represents the name of the new user;
     * $username: it represents the username of the new user;
     * $password: it represents the password of the new user;
     * $email: it represents the email of the new user;
     * $role: it represents the role of the new user.
     */
    public function insertUtente($nome, $username, $password, $email, $role)
    {
        return MS_Users::insert_utente($nome, $username, $password, $email, $role, $this->db);
    }


    /**This method checks if already exists the username inserted. 
     * Params:   
     * $username: it represents the username by checking.
     */
    public function usernameExist($username)
    {
        return MS_Users::username_exist($this->db, $username);
    }


    /**This method returns the password associated to the username. 
     * Params:   
     * $username: it represents the username by checking;
     * $role: it represents the role of the user.
     */
    public function getPassword($username, $role)
    {
        return MS_Users::get_pwd($this->db, $username, $role);
    }


    /**This method returns the availibility minutes of a Maintainer on a specific day.
     * Params:   
     * $matricola: it represents the identifier of the Maintainer;
     * $giorno: it represents the day by checking.
     */
    public function getTimeslot($matricola, $giorno)
    {
        return MS_Maintainer::get_timeslot($matricola, $giorno, $this->db);
    }


    /**This method returns all the Maintainers present in the DB.
     */
    public function getMaintainers()
    {
        return MS_Maintainer::get_maintainers($this->db);
    }


    /**This method returns the skills of a Maintainer.
     * Params:   
     * $matricola: it represents the identifier of the Maintainer.
     */
    public function getMaintainerSkills($matricola)
    {
        return MS_Maintainer::get_maintainer_skills($matricola, $this->db);
    }


    /**This method delete a skill of a Maintainer.
     * Params:   
     * $matricola: it represents the identifier of the Maintainer;
     * $sid: it represents the identifier of the skill of the Maintainer by deleting.
     */
    public function deleteMaintainerSkill($matricola, $sid)
    {
        return MS_Maintainer::delete_maintainer_skill($matricola, $sid, $this->db);
    }


    /**This method returns all the Skills present in the DB.
     */
    public function getAllSkills()
    {
        return MS_Skills::get_skills($this->db);
    }


    /**This method updates a Skill present in the DB.
     * Params:
     * $sid: it represents the identifier of the skill.
     */
    public function updateSkill($sid)
    {
        return MS_Skills::update_skill($sid, $this->db);
    }


    /**This method returns the max identifier of the skills present in the DB.
     */
    public function takeNewSkill()
    {
        return MS_Skills::take_skill($this->db);
    }


    /**This method inserts a new Skill in the DB.
     * Params:
     * $maxsid: it represents the max identifier of the skills present in the DB.
     */
    public function insertNewSkill($maxsid)
    {
        return MS_Skills::insert_skill($this->db, $maxsid);
    }


    /**This method assigns a new Skill to a Maintainer.
     * Params:
     * $matricola: it represents the identifier of the Maintainer;
     * $sid: it represents the identifier of the Skill.
     */
    public function addSkillToMaintainer($matricola, $sid)
    {
        return MS_Maintainer::add_selected_skill($matricola, $sid, $this->db);
    }


    /**This method returns the Users that are registered at the app, without the current user.
     * Params:
     * $username: it represents the username of the current logged user.
     */
    public function getUsers($username)
    {
        return MS_Users::get_users($this->db, $username);
    }


    /**This method deletes a User from the Account table present in the DB.
     * Params:
     * $username: it represents the username of the user by deleting.
     */
    public function deleteUser($username)
    {
        return MS_Users::delete_user($username, $this->db);
    }


    /**This method updates some attributes of a User already present in  the Account table of the DB.
     * Params:
     * $username: it represents the username of the user by updating.
     */
    public function updateUser($username)
    {
        MS_Users::update_user($username, $this->db);
    }


    /**This method add a Skill to the Activity.
     * Params:
     * $code: it represents the identifier of the activity by updating;
     * $sid: it represents the identifier of the new Skill to assign to the Activity.
     */
    public function addSkillToActivity($code, $sid)
    { //$sid è il codice della skill da aggiungere
        return MS_Activity::add_skill_to_activity($code, $sid, $this->db);
    }


    /**This method updates the description of the intevention of an Activity.
     * Params:
     * $code: it represents the identifier of the activity.
     */
    public function updateInterventionDescription($code)
    {
        MS_Activity::update_intervention_description($code, $this->db);
    }


    /**This method updates the time of the intevention of an Activity.
     * Params:
     * $code: it represents the identifier of the activity.
     */
    public function updateEstimatedTime($code)
    {
        MS_Activity::update_estimated_time($code, $this->db);
    }


    /**This method returns the Activities already assigned to different Maintainers.
     */
    public function getAssignedActivities()
    {
        return MS_Activity::get_AssignedActivities($this->db);
    }


    /**This method returns the Skills not already associated to an Activity.
     * Params:
     * $code: it represents the identifier of the activity.
     */
    public function getNotAssociatedSkills($code)
    {
        return MS_Skills::get_not_associated_skills($this->db, $code);
    }


    /**This method assign an Activities to a specific Maintainer.
     * Params:
     * $code: it represents the identifier of the activity;
     * $matricola: it represents the identifier of the Maintainer.
     */
    public function updateAssignedActivity($code, $matricola)
    {
        return MS_Activity::update_assigned_activity($code, $matricola, $this->db);
    }


    /**This method returns the email of the Maintainer.
     * Params:
     * $matricola: it represents the identifier of the Maintainer.
     */
    public function getMaintainerEmail($matricola)
    {
        return MS_Maintainer::get_maintainer_email($matricola, $this->db);
    }


    /**This method returns the name of the Maintainer.
     * Params:
     * $matricola: it represents the identifier of the Maintainer.
     */
    public function getMaintainerName($matricola)
    {
        return MS_Maintainer::get_maintainer_name($matricola, $this->db);
    }

}
