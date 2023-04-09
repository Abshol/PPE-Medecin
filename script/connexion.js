if (typeof sessionStorage.getItem("connecte") != 'undefined' || sessionStorage.getItem("connecte") == true) {
    window.location.href = "../";
}

$(document).ready(function() {

    $(document).on("click", "#connexion", function(event) {
        var log = $("#name").val();
        var pass = $("#pass").val();
        console.log(log);
        if (log == "login" && pass == "12345") {
            window.location.href = "../";
            sessionStorage.setItem("connecte", true);
            sessionStorage.setItem("idUser", 1);
        } else {
            alert("Login ou mot de passe incorrect.");
        }

        var auth = generateAuthToken(50);
        $.ajax({
            method: "POST",
            url: "http://localhost/medecin/API/index.php?action=authentification",
            data: JSON.stringify({login: log, mdp: pass, authCookie: auth}),
        })
        .done(function(data, textStatus, jqXHR) {
            sessionStorage.setItem("connecte", true);

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