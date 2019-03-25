// sélection des boutons supprimer du tableau 
// Array from permet de convertir querySelect => Nodelist
let btnDelete = Array.from( document.querySelectorAll('table .btn-danger')); 


// selection du bounton de la confirmation de la fenetre modal
let btnConfirmModal = (document.querySelector('.modal-confirm'));
 //console.log(btnDelete, btnConfirmModal);
 
// parcourir les boutons supprimer et ajouter un événement clic
btnDelete.forEach(value => value.addEventListener('click', clickBtnDelete));

function clickBtnDelete(e){
    // propriété target de l'évenement permet de récupérer l'élément déclencheur
    let href = event.target.getAttribute('href');
    console.log(href);
    
    //définir l'attribut href du bouton de confirmation de la fenetre modale
    btnConfirmModal.setAttribute('href', href);
}
//syntaxe arrow function (ES6) : fonction fléchée
//btn.forEach( function(value){ value.addEvent....} );