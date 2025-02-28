document.addEventListener("DOMContentLoaded", function () {
    cargarUsuarios();
    document.getElementById("usuario_form").addEventListener("submit", guardarUsuario);
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

async function cargarUsuarios() {
    let usuarios = await fetchJSON("data/usuarios.json");
    let tabla = document.getElementById("tabla_usuarios").querySelector("tbody");
    tabla.innerHTML = "";
    
    usuarios.forEach(usuario => {
        let row = tabla.insertRow();
        row.innerHTML = `
            <td>${usuario.db_NOMBRE}</td>
            <td>${usuario.db_EMAIL}</td>
            <td>${usuario.db_ROL}</td>
            <td>
                <button onclick="editarUsuario('${usuario.db_COD_USUARIO}')">✏️ Editar</button>
                <button onclick="eliminarUsuario('${usuario.db_COD_USUARIO}')">🗑️ Eliminar</button>
            </td>
        `;
    });
}

function guardarUsuario(event) {
    event.preventDefault();
    let nuevoUsuario = {
        accion: "crear",
        nombre: document.getElementById("nombre").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        rol: document.getElementById("rol").value
    };
    
    fetch("backend/gestionar_usuarios.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(nuevoUsuario)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Usuario guardado con éxito");
            cargarUsuarios();
        } else {
            alert("Error al guardar usuario");
        }
    });
}
