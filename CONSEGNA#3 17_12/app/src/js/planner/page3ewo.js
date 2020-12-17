/**This is the Javascript file that defines  the behaviour of the page3ewo.php*/

//colorare le skills e la percentuale + gestione dell'assegnamento delle attività
window.onload = function () {
    colorSkills();// call the function to colour the cell of the skills
    manageOfActivityAssigment(false);// parametro= false per segnalare l'assegnamento di un'attività EWO
}
