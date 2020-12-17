function okEdit(){
    var note = document.getElementById('note');
    note.style.display = 'none';

    document.getElementById('edit-submit').style.display = 'none';
    var img = document.createElement('img');
    img.src = '../resources/images/verifica.png';
    img.style.height = '50px';
    document.getElementById('form').appendChild(img);

    var p = document.createElement('p');
    p.innerHTML = 'Note edited successfully!';
    document.getElementById('form').appendChild(p);

}