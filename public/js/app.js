var app = {
    init: function(){
        // Ecouteur bouton vote
        $('#vote-button').on('click', app.vote)
    },
    vote: function(e){
        // Stop chargement page suite à clic sur lien
        e.preventDefault();
        // Récupération de l'id de la question
        var id = $(this).data('id');
        console.log(id);
        // Envoi du POST
        $.ajax('/question/vote/' + id,
            {
                method: 'GET'
            }
        )
        .done(function(data){
            // En cas d'erreur, on l'affiche
            if (data.error){
                alert(data.message);
            } else {
                var id = data.question.id;
                var $button = $('a[data-id=' + id + ']');
                $($button).find('span').text(data.question.votes);
            }
        })
        .fail(function(){
            alert('Erreur de connexion au serveur :/');
        });
    }
}

$(app.init);