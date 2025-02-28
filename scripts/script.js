document.addEventListener("DOMContentLoaded", function () {
    cargarDepartamentos();
    document.getElementById("filtro_departamento").addEventListener("change", cargarMunicipios);
    document.getElementById("filtro_municipio").addEventListener("change", cargarSedes);
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

async function cargarDepartamentos() {
    let divisionSedes = await fetchJSON("/data/division_sedes.json");
    let filtroDepartamento = document.getElementById("filtro_departamento");
    filtroDepartamento.innerHTML = `<option value="">Seleccione</option>`;
    
    let departamentosUnicos = [...new Set(divisionSedes.map(sede => sede.db_DEPARTAMENTO))];
    departamentosUnicos.forEach(dep => {
        let option = document.createElement("option");
        option.value = dep;
        option.textContent = dep;
        filtroDepartamento.appendChild(option);
    });
}

function cargarMunicipios() {
    let departamentoSeleccionado = document.getElementById("filtro_departamento").value;
    let filtroMunicipio = document.getElementById("filtro_municipio");
    filtroMunicipio.innerHTML = `<option value="">Seleccione</option>`;
    
    fetchJSON("/data/division_sedes.json").then(divisionSedes => {
        let municipiosFiltrados = divisionSedes.filter(sede => sede.db_DEPARTAMENTO === departamentoSeleccionado);
        let municipiosUnicos = [...new Set(municipiosFiltrados.map(sede => sede.db_MUNICIPIO))];
        
        municipiosUnicos.forEach(mun => {
            let option = document.createElement("option");
            option.value = mun;
            option.textContent = mun;
            filtroMunicipio.appendChild(option);
        });
    });
}

function cargarSedes() {
    let municipioSeleccionado = document.getElementById("filtro_municipio").value;
    let filtroSede = document.getElementById("filtro_sede");
    filtroSede.innerHTML = `<option value="">Seleccione</option>`;
    filtroSede.disabled = false;
    
    fetchJSON("/data/sedes.json").then(sedes => {
        let sedesFiltradas = sedes.filter(sede => sede.db_MUNICIPIO === municipioSeleccionado);
        sedesFiltradas.forEach(sede => {
            let option = document.createElement("option");
            option.value = sede.db_COD_SEDE;
            option.textContent = sede.db_NOMBRE_SEDE;
            filtroSede.appendChild(option);
        });
    });
    async function buscarCanalesPorSede(sedeId) {
    let response = await fetch("backend/gestionar_canales.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ accion: "listar", sede: sedeId })
    });
    let data = await response.json();

    if (data.status === "success") {
        mostrarCanalesEnTabla(data.canales);
    } else {
        console.error("Error al cargar canales:", data.message);
    }
}

}