/**This is the Javascript file that defines  the behaviour of the page4.php*/

//colorare le skills e la percentuale + gestione dell'assegnamento delle attività
window.onload = function () {

    colorSkills(); // call the function to colour the cell of the skills

    var label = document.getElementById('perc'); // qui mi prendo la label con id perc
    var percSymbol = document.getElementById('percSymbol');
    var value = label.innerHTML; // mi salvo il valore e poi dopo lo confronto per colorare in maniera differente
    if (value <= 20) {
        label.style.backgroundColor = '#FF8C00';//orange
        percSymbol.style.backgroundColor = '#FF8C00';//orange;
    }
    if (value > 20 && value < 50) {
        label.style.backgroundColor = 'yellow';
        percSymbol.style.backgroundColor = 'yellow';
    }
    if (value >= 50) {
        label.style.backgroundColor = '#59bd7a';//green
        percSymbol.style.backgroundColor = '#59bd7a';//green
    }
    if (value == 100) {
        label.style.backgroundColor = '#2e8b57';//green dark
        percSymbol.style.backgroundColor = '#2e8b57';//green dark
    }


    manageOfActivityAssigment(true);// parametro= true per segnalare l'assegnamento di un'attività planned





}





