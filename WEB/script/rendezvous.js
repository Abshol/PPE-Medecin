$(document).ready(function() {
    /**
     * Loads data into the table
     */
    tableFill();
    $(document).on("click", "#recharger", function() {
        tableFill();
    });
    setInterval(tableFill, 60000);
    
    /**
     * Deletes a task
     * @param {*} rdvId id of the rendez-vous we need to delete
     */
    function supprimer(rdvId){
        $.ajax({
            method: "DELETE",
            url: URIRDV,
            dataType: "json",
            data: JSON.stringify({idRdv: rdvId}),
            success: function(data) {
                tableFill();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        })
    }

    /**
     * Fills the table with the tasks
     */
    function tableFill() {
        $('#rendezvous').find('tbody').empty();
        $.ajax({
            method: "GET",
            url: URIRDV,
            dataType: "json",
            success: function(data) {
                // Define an empty array to store trs in
                var rows = [];
                

                $.each(data, function(i, rdv) {
                    var $tableCell = $('<td>');
                    var supprButton = $('<button>', {
                        class: 'task button supprimer', 
                        text: 'Supprimer', 
                        id: rdv.idRdv,
                    }).click(function () {
                        supprimer(rdv.idRdv);
                    });

                    const rdvDate = rdv.dateHeureRdv;
                    
                    const rdvMed = rdv.idMedecin;
                    
                    //create select box for importance filled options from 1 to 5
                    const selectImp = $('<select />', {
                        html: '<option value="">Veuillez choisir une option</option><option value="1">Très Peu Important</option><option value="2">Peu important</option><option value="3">Important</option><option value="4">Très Important</option><option value="5">URGENT</option>'
                    })
                    selectImp.val(rdv.importance);

                    // Build row, and append it to table body
                    const row = $("<tr id='"+rdv.idRdv+"'>").append(
                        $("<td class='date"+rdv.idRdv+"''>").append(rdvDate),
                        $("<td class='medecin"+rdv.idRdv+"''>").append(rdvMed),
                        $("<td>").append(supprButton),
                    );

                    $("#rendezvous tbody").append(row);
                    });

                // Append all rows to the tbody at once for better performance
                $('#rendezvous').find('tbody').append(rows);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        })
    }
});