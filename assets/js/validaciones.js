document.addEventListener("DOMContentLoaded", function () {
    function validarCelular(input) {
        // Asegura que solo se ingresen números
        input.value = input.value.replace(/[^0-9]/g, "");

        // Limita la entrada a 10 dígitos
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10);
        }
    }

    // Aplicar la validación a todos los inputs con la clase "celular-input"
    document.querySelectorAll(".celular-input").forEach(function (input) {
        input.addEventListener("input", function () {
            validarCelular(this);
        });
    });
});