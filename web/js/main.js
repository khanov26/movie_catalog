$(function () {
    $("#header-search-name")
        .autocomplete({
            minLength: 3,
            source: '/search/search-by-name',
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
        let link = $("<a>").attr("href", item.link)
            .addClass("header-search-result-item row no-gutters p-1 small text-decoration-none shadow-sm border-0 m-0");

        let col1 = $("<div>").addClass("col-1 p-0 ");
        $("<img>").attr("src", item.poster).addClass("img-fluid").appendTo(col1);
        col1.appendTo(link);

        let col2 = $("<div>").addClass("col-6 px-1 d-flex flex-column justify-content-center");
        $("<span>").html(item.name).appendTo(col2);
        $("<span>").html(item.originalName).addClass("text-muted").appendTo(col2);
        col2.appendTo(link);

        let col3 = $("<div>").addClass("col-3 d-flex flex-column justify-content-center");
        $("<span>").html(item.year).addClass("text-muted").appendTo(col3);
        $("<span>").html(item.genre).addClass("text-muted").appendTo(col3);
        col3.appendTo(link);

        let col4 = $("<div>").addClass("col-2 d-flex flex-column justify-content-center");
        let rating = $("<span>").html(item.imdb).addClass("text-danger");
        $("<span>").html("IMDB: ").append(rating).appendTo(col4);
        col4.appendTo(link);

        return $("<li>").append(link).appendTo(ul);
    };
});
