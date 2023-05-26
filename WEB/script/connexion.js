$(document).ready(function() {

    $(document).on("click", "#connexion", function(event) {
        var log = $("#name").val();
        var pass = $("#pass").val();
        var auth = generateAuthToken(32);

        $.ajax({
            method: "POST",
            url: URICONNECT,
            data: JSON.stringify({login: log, mdp: pass}),
        })
        .done(function(data, textStatus, jqXHR) {
            document.cookie = 'token='+auth+"; path=/; max-age=31536000;";
            console.log(data);
            $.ajax({
                method: "POST",
                url: URICOOKIE,
                data: JSON.stringify({token: auth, idPatient: log}),
            })
            .done(function(data, textStatus, jqXHR) {
                console.log(data); // Réponse HTTP (body)
                console.log(textStatus); // Statut de la requête AJAX
                console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
                window.location.href = "../";
            })
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 404) {
                // Handle 404 error
                alert("The requested page was not found.");
            } else if (jqXHR.status === 500) {
                // Handle 500 error
                alert("Une erreur interne est survenue");
            } else {
                // Handle other errors
                alert("Une erreur est survenue.");
            }
            console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
            console.log(textStatus); // Statut de la requête AJAX
            console.log(errorThrown); // = statusText (type de l’erreur)
        });
    });
});