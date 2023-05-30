var URI = "http://localhost/medecins/PPE-Medecin/API/index.php?";
var URIPATIENT = URI+"action=patient";
var URIRDV = URI+"action=rdv";
var URICONNECT = URI+"action=authentification";
var URICOOKIE = URI+"action=cookie";
var CONNECTE;

$.ajax({
    method: "GET",
    url: URICOOKIE+"&token="+getCookie("token"),
})
.done(function(data, textStatus, jqXHR) {
    var NOMPATIENT = data.nomPatient;
    var PRENOMPATIENT = data.prenomPatient;
    sessionStorage.setItem("idPatient", data.loginPatient); // Store the id in session storage
    sessionStorage.setItem("connecte", "true");
    $("#nav").prepend("<span>Vous êtes connecté en tant que: <strong>"+NOMPATIENT+"</strong></span> <a id='deco' class='button'>Se déconnecter</a>");
})
.fail(function() {
    sessionStorage.setItem("connecte", "false");
    $("#nav").prepend('<a href="'+RACINE+'connexion/" class="button">Se Connecter</a><a href="'+RACINE+'inscription/" class="button">S\'inscrire</a>');
})

$(document).on('click', '#deco' , function() {
    document.cookie = 'token=0;path=/; max-age=-999999999';
    location.reload();
});

$(document).ready(function() {
    $(document).on("keydown", '#focusInput', function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        $('.focus').click();    
      }
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