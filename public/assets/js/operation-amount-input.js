document.addEventListener("livewire:load", function () {
    Livewire.on("modalOpened", function () {
        const operationAmountField =
            document.getElementById("operation_amount");

        operationAmountField.addEventListener("input", function (e) {
            // Remueve todos los caracteres no numéricos, incluyendo puntos
            let numericValue = e.target.value.replace(/[^\d]/g, "");

            // Divide en parte entera y decimal
            let parts = numericValue.split(".");

            // Formatea la parte entera con comas como separadores de miles
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            // Vuelve a unir parte entera y decimal con punto como separador decimal
            numericValue = parts.join(".");

            e.target.value = numericValue;
        });
    });
});
