function abrirModalToken() {
  // Mostrar modal
  document.getElementById('modalToken').style.display = 'block';

  // Cargar contenido vía AJAX desde modal_token.php
  fetch('modal_token.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('modalTokenContent').innerHTML = data;
    })
    .catch(error => {
      document.getElementById('modalTokenContent').innerHTML = "<p>Error al generar el enlace.</p>";
      console.error(error);
    });
}

function cerrarModalToken() {
  document.getElementById('modalToken').style.display = 'none';
}


function copiarLink() {
  const input = document.getElementById('tokenLinkInput');
  input.select();
  input.setSelectionRange(0, 99999); // para móviles
  navigator.clipboard.writeText(input.value)
    .then(() => {
      alert("✅ Enlace copiado al portapapeles");
    })
    .catch(err => {
      console.error("Error al copiar: ", err);
    });
}
