$(function () {
    function loadElement() {
        /* Show Loading */
        $("#loading-content").removeClass("d-none");

        /* Listen Event */
        const hash = window.location.hash.substring(1);
        let url = baseurl + hash;

        if (hash === "" || hash === "dashboard") {
            url = baseurl + "dashboard";
        }

        /* Reset Page & Javascript Event */
        $("#app").html("");
        $("#app").off().find("*").off();

        /* Load Page */
        $("#app").load(url, function (response, status, req) {
            if (req.status === 404) {
                /* Load Page Not Found */
                $("#app").load(baseurl + "notfound", function () {
                    $("#loading-content").addClass("d-none");
                });
            }

            if (req.status === 401) {
                /* Reload Page If User Not Authorized */
                window.location.reload();
            }

            /* Hide Loading */
            $("#loading-content").addClass("d-none");
        });
    }

    loadElement();

    /* Listen Event */
    $(window).on("hashchange", function () {
        loadElement();
    });
});
