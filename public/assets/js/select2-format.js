document.addEventListener("livewire:load", function () {
    var selectUser = $("#selectUser");
    var selectUser2 = $("#selectUser2");
    var selectUser3 = $("#selectUser3");
    var selectUser4 = $("#selectUser4");
    var selectUser5 = $("#selectUser5");
    var selectUser6 = $("#selectUser6");
    var selectUser7 = $("#selectUser7");
    var selectUser8 = $("#selectUser8");
    var selectUser9 = $("#selectUser9");
    var selectCategory = $("#selectCategory");
    var selectYear = $("#selectYear");
    var selectYear2 = $("#selectYear2");
    var selectYear3 = $("#selectYear3");
    var selectYear4 = $("#selectYear4");
    var selectYear5 = $("#selectYear5");
    var selectYear6 = $("#selectYear6");
    var selectMonth = $("#selectMonth");
    var selectBudgetMonth = $("#selectBudgetMonth");
    var selectBudgetMonthIncome = $("#selectBudgetMonthIncome");
    var selectUserChart = $("#selectUserChart");
    var selectYearChart = $("#selectYearChart");
    var selectUserChart2 = $("#selectUserChart2");
    var selectYearChart2 = $("#selectYearChart2");
    var selectYearChart3 = $("#selectYearChart3");
    var selectCategoryChart = $("#selectCategoryChart");
    var selectUserChart3 = $("#selectUserChart3");
    var selectUserChart4 = $("#selectUserChart4");
    var selectMonthChart = $("#selectMonthChart");
    var selectUserChart5 = $("#selectUserChart5");
    var selectYearChart4 = $("#selectYearChart4");
    var selectUserChart6 = $("#selectUserChart6");
    var selectUserChart6 = $("#selectUserChart6");
    var selectMonthChart2 = $("#selectMonthChart2");
    var selectYearChart5 = $("#selectYearChart5");

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
    initializeSelect2(selectUser5, "userSelected5");
    initializeSelect2(selectUser6, "userSelected6");
    initializeSelect2(selectUser7, "userSelected7");
    initializeSelect2(selectUser8, "userSelected8");
    initializeSelect2(selectUser9, "userSelected9");
    initializeSelect2(selectCategory, "categorySelected");
    initializeSelect2(selectYear, "YearSelected");
    initializeSelect2(selectYear2, "YearSelected2");
    initializeSelect2(selectYear3, "YearSelected3");
    initializeSelect2(selectYear4, "YearSelected4");
    initializeSelect2(selectYear5, "YearSelected5");
    initializeSelect2(selectYear6, "YearSelected6");
    initializeSelect2(selectMonth, "MonthSelected");
    initializeSelect2(selectBudgetMonth, "MonthSelectedBudget");
    initializeSelect2(selectBudgetMonthIncome, "MonthSelectedIncomeBudget");
    initializeSelect2(selectUserChart, "userSelectedChart");
    initializeSelect2(selectYearChart, "YearSelectedChart");
    initializeSelect2(selectUserChart2, "userSelectedChart2");
    initializeSelect2(selectYearChart2, "YearSelectedChart2");
    initializeSelect2(selectCategoryChart, "categorySelected2");
    initializeSelect2(selectUserChart3, "userSelectedChartBetween");
    initializeSelect2(selectUserChart4, "userSelected4");
    initializeSelect2(selectMonthChart, "MonthSelected");
    initializeSelect2(selectYearChart3, "YearSelected3");
    initializeSelect2(selectUserChart5, "userSelectedChart5");
    initializeSelect2(selectYearChart4, "YearSelectedChart4");
    initializeSelect2(selectUserChart6, "userSelectedChart6");
    initializeSelect2(selectMonthChart2, "MonthSelectedBudget2");
    initializeSelect2(selectYearChart5, "YearSelectedChart5");
});
