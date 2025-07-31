function updateUrl(organization) {
    return;
    var currentUrl = window.location.href;
    if (!location.search.includes("org")) {
        var newUrl = currentUrl + '?org=' + organization;
        history.pushState({}, '', newUrl);
    }
}
