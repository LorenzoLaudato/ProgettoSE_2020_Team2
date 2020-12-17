/**This is the Javascript file that defines  the behaviour of the maintainerSkills.php*/
var inconeEdit = document.getElementsByClassName('edit');
window.onload = function () {
  var table = document.getElementById('maintainersView');
  var tableRows =table.getElementsByTagName('tr');
  for(var i = 1; i < tableRows.length; i++) {
    var td = tableRows[i].getElementsByTagName('td');
    var imgEdit = td[1].getElementsByClassName('edit');
    if(i==td[1].getAttribute('id')){
        imgEdit[0].addEventListener('click', function() {
            edit(td[1]);
          });
    }
  }
}

function edit(td) {
  var input = document.createElement('input');
  input.type = "text";
  td.appendChild(input);
}
