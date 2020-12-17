<?php

interface UserInterface {

    public function getActivities($data);

    public function infoActivity($data);

    public function showSkills($data);

    public function updateNote($data);
}