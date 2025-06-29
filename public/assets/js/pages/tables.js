window.cargarTablaCursos = function (url, tablaSelector, modalSelector) {
    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            const tbody = document.querySelector(`${tablaSelector} tbody`);
            if (!tbody) return;

            // Limpiar contenido previo
            tbody.innerHTML = "";

            // Llenar la tabla
            data.forEach((curso, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${curso.nombre}</td>
                        <td>${curso.precio}</td>
                        <td>${curso.duracion}</td>
                        <td>${curso.inscripcion ?? "-"}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="seleccionarCurso(${
                                curso.id
                            }, '${curso.nombre}')">
                                Seleccionar
                            </button>
                        </td>
                    </tr>`;
                tbody.insertAdjacentHTML("beforeend", row);
            });

            // Inicializar DataTable
            initDataTable(tablaSelector, {
                order: [[1, "asc"]],
                paging: true,
                searching: true,
            });
        })
        .catch((error) => {
            console.error("Error al cargar cursos:", error);
            const tbody = document.querySelector(`${tablaSelector} tbody`);
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-danger">Error al cargar los cursos.</td></tr>`;
            }
        });
};

window.initDataTable = function (selector, options = {}) {
    const defaultOptions = {
        language: {
            url: "/assets/lang/es/es.json",
        },
        paging: true,
        searching: true,
        ordering: true,
    };

    const finalOptions = Object.assign({}, defaultOptions, options);

    // Si la tabla ya fue inicializada, destruyela antes
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().clear().destroy();
    }

    return $(selector).DataTable(finalOptions);
};