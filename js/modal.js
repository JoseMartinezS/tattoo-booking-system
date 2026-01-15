document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalCita");
  const closeBtn = modal.querySelector(".close");
  const modalBody = document.getElementById("modal-body");

  // Detectar si estamos en admin/ o admin/views/
  const basePath = window.location.pathname.includes("/views/")
    ? "../"
    : "./";

  // Abrir modal con datos de cita
  window.abrirModal = function(id) {
    fetch(`${basePath}detalle_cita.php?id=${id}`)
      .then(res => res.text())
      .then(html => {
        modalBody.innerHTML = html;
        modal.style.display = "block";

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
    fetch(`${basePath}acciones/accion_cita.php?id=${id}&accion=${accion}`)
      .then(() => {
        const msg = accion === "confirmar" ? "✅ Cita confirmada" : "❌ Cita cancelada";
        mostrarNotificacion(msg);
        setTimeout(() => location.reload(), 1500); // recarga después de mostrar mensaje
      });
  }

  // Guardar nota
  function guardarNota(id) {
    const nota = document.getElementById("notaCita").value;
    fetch(`${basePath}acciones/guardar_nota.php`, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${id}&nota=${encodeURIComponent(nota)}`
    }).then(() => {
      mostrarNotificacion("📝 Nota guardada");
      document.getElementById("notaCita").value = "";
      modal.style.display = "none";
    });
  }

  // Función para mostrar notificación visual
  function mostrarNotificacion(texto) {
    const notif = document.createElement("div");
    notif.className = "notif";
    notif.textContent = texto;
    document.body.appendChild(notif);

    setTimeout(() => notif.remove(), 2000);
  }
});
