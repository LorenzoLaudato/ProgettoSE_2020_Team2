/**This function takes in input two params to show the check icon for the operation done.
 * Params: 
 * id: It's the identifier of the HTML object on which the user makes the operation;
 * stringa: It's the stringa that identifies the particular operation done.
 */
function okEdit(id, stringa,form) {
    var element_edited = document.getElementById(id);
    element_edited.style.display = 'none';

    document.getElementById('edit-submit').style.display = 'none';
    var img = document.createElement('img');
    img.src = '../../resources/images/verifica.png';
    img.style.height = '50px';
    document.getElementById(form).appendChild(img);

    var p = document.createElement('p');
    p.innerHTML = 'Successfully ' + stringa + '!';
    document.getElementById(form).appendChild(p);

}


/**This function sets to visible the visibility of the Activities Table. */
function setVisibility() {
    var table = document.getElementById('activitiesTable');
    table.style.visibility = 'visible';
}


/**This function returns the number of a day of an Ewo Activity. */
function getDayEWO() {
    var d = new Date();
    var n = d.getDay();
    return n;
}


/**This function returns the number of the day int the month taking in input the number of the day in the week and the weekNumber.
 * Params: 
 * day: number of the day in the week (1<=day<=7);
 * weekNumber: number of the wek in the year (1<=weekNumber<=52).
 */
function getDayFromWeek(day, weekNumber) {

    weekRange = getDateRangeOfWeek(weekNumber);//range della settimana con n°=weekNumber

    var index = weekRange.indexOf('/');
    var mounth = weekRange.substring(0, index);
    mounth = parseInt(mounth);//trovo il mese

    var resto = weekRange.substring(index + 1, weekRange.length);
    index = weekRange.indexOf('/');
    resto = weekRange.substring(index + 1, weekRange.length);
    index = weekRange.indexOf('/');
    dayOne = resto.substring(0, index); 
    dayOne = parseInt(dayOne); //primo giorno della settimana weekRange (lunedi)


    if (day == 1)  //se il giorno è 1 allora restituisco dayOne, il primo giorno di weekRange (lunedi)
        return dayOne;
    if (dayOne == 30) {//se il primo giorno di weekRange e' 30 devo controllare il mese
        if ((mounth == 4 || mounth == 6 || mounth == 9 || mounth == 11)) { //MESI CON 30 GIORNI
            if (day == 2)  //martedi
                return 2;
            if (day == 3)  //mercoledi
                return 3;
            if (day == 4)  //giovedi
                return 4;
            if (day == 5)  //venerdi
                return 5;
            if (day == 6)  //sabato
                return 6;
            if (day == 7)  //domenica
                return 7;
        } else {//MESI CON 31 GIORNI
            if (day == 2)  //martedi
                return 31;
            if (day == 3)  //mercoledi
                return 1;
            if (day == 4)  //giovedi
                return 2;
            if (day == 5)  //venerdi
                return 3;
            if (day == 6)  //sabato
                return 4;
            if (day == 7)  //domenica
                return 5;
        }
    }
    else {
        if (day == 2)  //martedi
            return dayOne + 1;
        if (day == 3)  //mercoledi
            return dayOne + 2;
        if (day == 4)  //giovedi
            return dayOne + 3;
        if (day == 5)  //venerdi
            return dayOne + 4;
        if (day == 6)  //sabato
            return dayOne + 5;
        if (day == 7)  //domenica
            return dayOne + 6;
    }

}


/**This function takes in input the WeekNumber and returns the range of the week during the year
 * Params:
 * weekNo: number of the week.
 */
function getDateRangeOfWeek(weekNo) {
    var d1 = new Date();//creo un oggetto Date
    numOfdaysPastSinceLastMonday = eval(d1.getDay() - 1);
    d1.setDate(d1.getDate() - numOfdaysPastSinceLastMonday);
    var weekNoToday = d1.getWeek();//ottengo il numero della settimana corrente
    var weeksInTheFuture = eval(weekNo - weekNoToday);
    d1.setDate(d1.getDate() + eval(7 * weeksInTheFuture));
    var rangeIsFrom = eval(d1.getMonth() + 1) + "/" + d1.getDate() + "/" + d1.getFullYear();
    d1.setDate(d1.getDate() + 6);
    var rangeIsTo = eval(d1.getMonth() + 1) + "/" + d1.getDate() + "/" + d1.getFullYear();
    return rangeIsFrom + " to " + rangeIsTo;//restituisce il range della settimana numero weekNo (es. 14/12/20 to 21/12/20)
};

/**This function return the current week */
Date.prototype.getWeek = function () { //Restituisce la week corrente
    var onejan = new Date(this.getFullYear(), 0, 1);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
}


/**This function coulours the cells of the skills in different way, depending on the value.
 */
function colorSkills() {
    var table = document.getElementById('maintainerTable'); //mi prendo la tabella dei manutentori
    var tableRows = table.getElementsByTagName("tr"); // mi prendo la riga della tabella con il tag 'tr'

    for (var i = 1; i < tableRows.length; i++) {
        var td = tableRows[i].getElementsByTagName('td');
        var value = td[1].innerHTML; // mi salvo il contenuto della cella
        var index = value.indexOf('/'); // calcolo l'indice in cui è presente lo '/''
        var first = parseInt(value.substring(0, index)); //mi prendo la sottostringa a sx di '/'
        var second = parseInt(value.substring(index + 1, value.length)); //mi prendo la sottostringa a dx di '/'
        if (first / second <= 0.2) {
            td[1].style.backgroundColor = '#FF8C00';//orange
        }
        if (first / second > 0.2 && first / second < 0.5) {
            td[1].style.backgroundColor = 'yellow';
        }
        if (first / second >= 0.5) {
            td[1].style.backgroundColor = '#59bd7a';//green
        }
        if (first / second == 1) {
            td[1].style.backgroundColor = '#2e8b57';//green dark
        }
    }
}


/**This function manages the assignment of an activity to a Maintainer. In particular, it takes the values selected by user and updates the counters. So updates the body of the email to send at the maintainer, and at the end it sends it.
 * Params: 
 * bool: it's a boolean that explains if the function it's called about a EWO Activity(bool=false) or a Planned Activity(bool=true).
 */
function manageOfActivityAssigment(bool) {
    var fasciaoraria1="";
    var table = document.getElementById('maintainerTable'); //mi prendo la tabella dei manutentori
    var tableRows = table.getElementsByTagName("tr"); // mi prendo la riga della tabella con il tag 'tr'
    var riga1 = tableRows[0].getElementsByTagName('th');// mi prendo la riga d'intestazione
    var count = 0; // variabile che conta i minuti assegnati al manutentore
    var stop = false; // booleano che viene messo a true quando è stato raggiunto il tempo da assegnare
    for (var i = 1; i < tableRows.length; i++) {
        var td = tableRows[i].getElementsByTagName('td');
        //listener sulle celle della tabella
        for (j = 2; j < td.length; j++) {
            td[j].addEventListener('click', function () {
                time = this.innerHTML;//mi prendo il tempo dal contenuto della cella HTML 
                var minutes = parseInt(time.substring(0, 2));// calcolo la sotto stringa e la trasformo in intero
                email = this.getAttribute('value'); // mi prendo l'email del manutentore
                nome = this.getAttribute('id'); // mi prendo il nome del manutentore
                var index = this.getAttribute('name'); // mi prendo l'indice di colonna sfruttando il name
                var fasciaoraria = riga1[index]; //mi prendo l'oggetto html dalla tabella
                fasciaoraria = fasciaoraria.getElementsByTagName('label')[0].innerHTML; //mi prendo il contenuto della cella(ossia la fascia oraria)
                fasciaoraria1 = fasciaoraria1 + fasciaoraria + "%0D"; //concateno la fascia oraria
                tempo_richiesto = parseInt(document.getElementById('total').innerHTML); // -- required time
                count = count + minutes;//somma dei minuti selezionati -- assigned time
                tempo_mancante = tempo_richiesto - count; // -- time to be assigned
                if (count <= tempo_richiesto) {
                    // aggiorno i contatori
                    document.getElementById('count1').innerHTML = count;
                    document.getElementById('count2').innerHTML = tempo_mancante;
                }
                if (count >= tempo_richiesto) {
                    if (stop == false) {
                        if (bool == true) {// sto cercando di mandare mail per un'attivita pianificata
                            document.getElementById('a-send').href = document.getElementById('a-send').value; // abilito il pulsante di invio 
                            href = document.getElementById('a-send').getAttribute('value'); // mi prendo l'href corrente 
                            href += "%0DAssigned time - " + tempo_richiesto + " min;" + "%0DTime-slot:%0D" + fasciaoraria1;// aggiorno l'href per poter mandare la mail
                            document.getElementById('a-send').setAttribute('href', href); //setto il nuovo href
                        }
                        document.getElementById('send-button').style.removeProperty('background-color');
                        document.getElementById('send-button').style.removeProperty('color');
                        document.getElementById('count1').innerHTML = tempo_richiesto; // aggiorno il tempo da assegnare
                        stop = true;

                    }
                    document.getElementById('count2').innerHTML = 0;// aggiorno il tempo rimanente
                }
            });
        }
    }
    if (bool == false) {// sto provando a mandare mail per attivita EWO
        document.getElementById('send-button').addEventListener('click', function () {
            window.open("mailto:" + email + "?subject=Assignment of an unplanned activity(EWO)&cc=production.managerscrum2020@gmail.com&body=Dear " + nome +","+ document.getElementById('send-mail-href').value + "%0DAssigned Time - " + tempo_richiesto + " min;" + "%0DTime-Slot:%0D" + fasciaoraria1);        
        });
    }

}

