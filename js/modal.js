// modal.js
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalCita");
  const closeBtn = modal.querySelector(".close");
  const modalBody = document.getElementById("modal-body");

  // Abrir modal con datos de cita
  window.abrirModal = function(id) {
    fetch(`../detalle_cita.php?id=${id}`)
      .then(res => res.text())
      .then(html => {
        modalBody.innerHTML = html;
        modal.style.display = "block";

        // Asignar acciones a botones
        document.getElementById("btnConfirmar").onclick = () => accionCita(id, "confirmar");
        document.getElementById("btnCancelar").onclick = () => accionCita(id, "cancelar");
        document.getElementById("btnGuardarNota").onclick = () => guardarNota(id);
      });
  };

  // Cerrar modal
  closeBtn.onclick = () => modal.style.display = "none";
  window.onclick = (event) => {
    if (event.target === modal) modal.style.display = "none";
  };

  // Acción confirmar/cancelar
  function accionCita(id, accion) {
    fetch(`../acciones/accion_cita.php?id=${id}&accion=${accion}`)
      .then(() => location.reload());
  }

  // Guardar nota
  function guardarNota(id) {
    const nota = document.getElementById("notaCita").value;
    fetch("../acciones/guardar_nota.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${id}&nota=${encodeURIComponent(nota)}`
    }).then(() => {
      alert("Nota guardada");
      document.getElementById("notaCita").value = ""; // 👈 limpia el campo
      modal.style.display = "none";
    });
  }
});
