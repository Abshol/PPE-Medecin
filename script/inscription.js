$(document).ready(function() {
    
    $(document).on("click", "#sub", function(event) {
        var nom = $("#nomPatient").val();
        var prenom = $("#prenomPatient").val();
        var rue = $("#ruePatient").val();
        var cp = $("#cpPatient").val();
        var ville = $("#villePatient").val();
        var tel = $("#telPatient").val();
        var login = $("#loginPatient").val();
        var mdp = $("#mdpPatient").val();

        var continu = true;
        if (nom == "" || prenom == "" || rue == "" || cp == "" || ville == "" || tel == "" || login == "" || mdp == "") {
            continu = false;
        }
        if (continu) {
            $.ajax({
                method: "POST",
                url: "http://localhost/medecin/API/index.php?action=patient",
                data: JSON.stringify({nom: nom, prenom: prenom, rue: rue, cp: cp, ville: ville, tel: tel, login: login, mdp: mdp}),
            })
            .done(function(data, textStatus, jqXHR) {
                alert("Votre compte a bien été créé");
                window.location.href = "connexion/";
                console.log(textStatus); // Statut de la requête AJAX
                console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                alert("Erreur lors de l'envoi des données");
                console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
                console.log(textStatus); // Statut de la requête AJAX
                console.log(errorThrown); // = statusText (type de l’erreur)
            });
        }
        else {
            alert("Merci de bien renseigner toutes les données");
        }
    });
        
});

