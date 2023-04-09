var URI = "http://localhost/medecin/API/index.php?";
var URIPATIENT = URI+"action=patient";
var URIRDV = URI+"action=rdv";
var URICONNECT = URI+"action=authentification";

$(document).ready(function() {
    if (sessionStorage.getItem("connecte") == 'true') {
        $.ajax({
            method: "GET",
            url: URIPATIENT+"&id="+sessionStorage.getItem("idUser"),
        })
        .done(function(data, textStatus, jqXHR) {
            $("#nav").html("Vous êtes connecté en tant que: "+data.nomPatient+"<button id='deco'>Se deconnecter</button>");
            console.log(data); // Réponse HTTP (body)
            console.log(textStatus); // Statut de la requête AJAX
            console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
        });
    } else {
        $("#nav").html('<a href="./connexion/">Se Connecter</a><a href="./inscription/">S\'inscrire</a>');
    }

    $(document).on('click', '#deco' , function() {
        sessionStorage.setItem("connecte", false);
        sessionStorage.setItem("idUser", false);
        location.reload();
    });
});