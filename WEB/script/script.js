var URI = "http://localhost/API/index.php?";
var URIPATIENT = URI+"action=patient";
var URIRDV = URI+"action=rdv";
var URICONNECT = URI+"action=authentification";

$(document).ready(function() {

    $(document).on("keydown", '#focusInput', function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        $('.focus').click();    
      }
    }); 
    if (getCookie("connecte") == 'true') {
      $.ajax({
        method: "GET",
        url: URIPATIENT+"&id="+getCookie("user"),
      })
      .done(function(data, textStatus, jqXHR) {
          $("#nav").prepend("<span>Vous êtes connecté en tant que: <strong>"+data.nomPatient+"</strong></span> <a id='deco' class='button'>Se déconnecter</a>");
          console.log(data); // Réponse HTTP (body)
          console.log(textStatus); // Statut de la requête AJAX
          console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
      });
    } else {
      $("#nav").prepend('<a href="./connexion/" class="button">Se Connecter</a><a href="./inscription/" class="button">S\'inscrire</a>');
    }

    $(document).on('click', '#deco' , function() {
        sessionStorage.setItem("connecte", false);
        sessionStorage.setItem("idUser", false);
        document.cookie = 'connecte=false;path=/; max-age=-999999999';
        document.cookie = 'user=0;path=/; max-age=-999999999';
        location.reload();
    });
});

// Randomly generates a string to be used in a cookie
function generateAuthToken(length) {
    var token = "";
    var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < length; i++) {
      token += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return token;
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }