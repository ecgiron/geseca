document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("login_form").addEventListener("submit", iniciarSesion);
});

function iniciarSesion(event) {
    event.preventDefault();
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    
    fetch("backend/auth.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ accion: "login", email, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href = "index.html";
        } else {
            document.getElementById("error_message").textContent = "Credenciales incorrectas";
        }
    });
}