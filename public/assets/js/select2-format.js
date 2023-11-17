document.addEventListener("livewire:load", function () {
    var selectUser = $("#selectUser");
    var selectUser2 = $("#selectUser2");
    var selectUser3 = $("#selectUser3");
    var selectUser4 = $("#selectUser4");
    var selectCategory = $("#selectCategory");
    var selectYear = $("#selectYear");
    var selectYear2 = $("#selectYear2");
    var selectYear3 = $("#selectYear3");
    var selectMonth = $("#selectMonth");

    var isDropdownOpen = false;

    function initializeSelect2(selectElement, eventName) {
        selectElement.select2();

        selectElement.on("change", function (e) {
            Livewire.emit(eventName, e.target.value);
        });

        selectElement.on("select2:opening", function (e) {
            isDropdownOpen = true;
        });

        Livewire.hook("message.received", function (message, component) {
            isDropdownOpen = selectElement
                .next()
                .hasClass("select2-container--open");
        });

        Livewire.hook("message.processed", function (message, component) {
            if (isDropdownOpen) {
                selectElement.trigger("change.select2");
            }
            selectElement.select2();
        });
    }

    initializeSelect2(selectUser, "userSelected");
    initializeSelect2(selectUser2, "userSelected2");
    initializeSelect2(selectUser3, "userSelected3");
    initializeSelect2(selectUser4, "userSelected4");
    initializeSelect2(selectCategory, "categorySelected");
    initializeSelect2(selectYear, "YearSelected");
    initializeSelect2(selectYear2, "YearSelected2");
    initializeSelect2(selectYear3, "YearSelected3");
    initializeSelect2(selectMonth, "MonthSelected");
});