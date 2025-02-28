document.addEventListener("DOMContentLoaded", function () {
    cargarContratos();
    document.getElementById("btn_guardar_contrato").addEventListener("click", guardarContrato);
});

async function fetchJSON(url) {
    try {
        let response = await fetch(url);
        return await response.json();
    } catch (error) {
        console.error(`Error cargando ${url}:`, error);
        return [];
    }
}

async function cargarContratos() {
    let contratos = await fetchJSON("data/historico.json");
    let tabla = document.getElementById("tabla_contratos").querySelector("tbody");
    tabla.innerHTML = "";
    
    contratos.forEach(contrato => {
        let row = tabla.insertRow();
        row.innerHTML = `
            <td>${contrato.db_COD_CONTRATO}</td>
            <td>${contrato.db_PROVEEDOR}</td>
            <td>${contrato.db_FECHA_INICIO}</td>
            <td>${contrato.db_FECHA_FIN}</td>
            <td>${contrato.db_DETALLES}</td>
            <td>
                <button onclick="editarContrato('${contrato.db_COD_CONTRATO}')">✏️ Editar</button>
                <button onclick="eliminarContrato('${contrato.db_COD_CONTRATO}')">🗑️ Eliminar</button>
            </td>
        `;
    });
}

function guardarContrato() {
    let nuevoContrato = {
        accion: "crear",
        proveedor: document.getElementById("proveedor").value,
        fecha_inicio: document.getElementById("fecha_inicio").value,
        fecha_fin: document.getElementById("fecha_fin").value,
        detalles: document.getElementById("detalles").value
    };
    
    fetch("backend/gestionar_contratos.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(nuevoContrato)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Contrato guardado con éxito");
            cargarContratos();
        } else {
            alert("Error al guardar contrato");
        }
    });
}
