$(function () {
    let actorSearchAutocompleteOptions = {
        source: "/actor/search",
        minLength: 3
    };

    $('#actors input').autocomplete(actorSearchAutocompleteOptions);

    $('#add-actor-btn').click(function () {
        let lastActorInput = $('#actors input').last();
        let res = lastActorInput.attr('name').match(/(.*)\[(\d+)]$/);
        if (res === null) return;

        let groupName = res[1];
        let lastNumber = res[2];

        let newActorInput = $('<input>').attr({
                type: 'text',
                name: `${groupName}[${++lastNumber}]`,
            })
            .addClass('form-control mb-2')
            .autocomplete(actorSearchAutocompleteOptions);

        lastActorInput.after(newActorInput);
    });
});
