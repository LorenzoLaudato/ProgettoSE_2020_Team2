/**This is the Javascript file that defines  the behaviour of the page3.php*/
window.onload = function (){
    var table = document.getElementById('maintainersTable');
    var tableRows = table.getElementsByTagName("tr");

    for(var i= 1; i<tableRows.length; i++) {
        var td = tableRows[i].getElementsByTagName('td');
        var value = td[1].innerHTML;
        var index = value.indexOf('/');
        var first = parseInt(value.substring(0,index));
        var second = parseInt(value.substring(index+1,value.length));
        if (first/second <= 0.2){
        td[1].style.backgroundColor = '#FF8C00';//orange
        }
        if (first/second > 0.2 && first/second <0.5){
            td[1].style.backgroundColor = 'yellow';
        }
        if (first/second >= 0.5){
            td[1].style.backgroundColor = '#59bd7a';//green
        }
        if (first/second == 1){
            td[1].style.backgroundColor = '#2e8b57';//green dark
        }
    }
}
