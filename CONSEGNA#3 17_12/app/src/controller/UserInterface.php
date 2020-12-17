<?php
/**This is the User-Interface used by front-end side to define the operation on the db */
interface UserInterface
{

    public function getActivities($weekNumber);

    public function infoActivity($code);

    public function getSkillsNeeded($code);

    public function updateNote($code);

    public function showMaintainers();

    public function countSkillsNeeded($code);

    public function countSkillsEffective($code, $matricola);

    public function insertUtente($nome, $username, $password, $email, $role);

    public function usernameExist($username);

    public function getPassword($username, $role);

    public function getTimeslot($matricola, $giorno);

    public function getMaintainers();

    public function getMaintainerSkills($matricola);

    public function deleteMaintainerSkill($matricola, $sid);

    public function getAllSkills();

    public function updateSkill($sid);

    public function takeNewSkill();

    public function insertNewSkill($ret);

    public function addSkillToMaintainer($matricola, $sid);

    public function getUsers($username);

    public function deleteUser($username);

    public function updateUser($username);

    public function addSkillToActivity($code, $sid);

    public function updateInterventionDescription($code);

    public function updateEstimatedTime($code);

    public function getAssignedActivities();

    public function getNotAssociatedSkills($code);

    public function updateAssignedActivity($code, $matricola);

    public function getMaintainerEmail($matricola);

    public function getMaintainerName($matricola);
}
