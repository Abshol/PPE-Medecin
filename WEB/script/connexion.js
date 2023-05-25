if (sessionStorage.getItem("connecte") == true) {
    window.location.href = "../";
}

$(document).ready(function() {

    $(document).on("click", "#connexion", function(event) {
        var log = $("#name").val();
        var pass = $("#pass").val();
        console.log(log);
        if (log == "login" && pass == "12345") {
            window.location.href = "../";
            var idUser = "1";
            var auth = generateAuthToken(32);

        } else {
            alert("Login ou mot de passe incorrect.");
        }

        $.ajax({
            method: "POST",
            url: URICONNECT,
            data: JSON.stringify({login: log, mdp: pass}),
        })
        .done(function(data, textStatus, jqXHR) {
            document.cookie = 'token='+auth+"; path=/; max-age=31536000;";
            $.ajax({
                method: "POST",
                url: URICOOKIE,
                data: JSON.stringify({login: log, mdp: pass}),
            })
            console.log(data); // Réponse HTTP (body)
            console.log(textStatus); // Statut de la requête AJAX
            console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert("Erreur lors de l'envoi des données");
            if (jqXHR.status === 404) {
                // Handle 404 error
                alert("The requested page was not found.");
            } else if (jqXHR.status === 500) {
                // Handle 500 error
                alert("Internal Server Error. Please try again later.");
            } else {
                // Handle other errors
                alert("An error occurred. Please try again later.");
            }
            console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
            console.log(textStatus); // Statut de la requête AJAX
            console.log(errorThrown); // = statusText (type de l’erreur)
        });
    });
});