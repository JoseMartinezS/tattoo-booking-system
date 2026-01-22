# Tattoo Booking System 🖋️📅

**Tattoo Booking System** es una solución web integral diseñada para estudios de tatuajes y artistas independientes. El sistema permite a los clientes gestionar sus citas de manera eficiente, mientras que los administradores pueden controlar la agenda, todo optimizado para funcionar como una aplicación móvil gracias a su tecnología **PWA**.

---

## ✨ Características Principales

* **Gestión de Reservas:** Sistema intuitivo para que los clientes seleccionen fechas y servicios.
* **Login de Administrador:** Panel seguro para gestionar el calendario, citas y base de datos de clientes.
* **Progressive Web App (PWA):** Instalable en dispositivos móviles y de escritorio, permitiendo acceso rápido desde el inicio sin usar la App Store/Play Store.
* **Integración con Redes Sociales:** Enlaces configurados para conectar con Instagram, Facebook o WhatsApp del artista.
* **Diseño Responsivo:** Adaptado al 100% para una experiencia fluida en smartphones, tablets y computadoras.

---

## 🛠️ Tecnologías Utilizadas

* **Frontend:** HTML5, CSS3, JavaScript.
* **Backend:** PHP.
* **Base de Datos:** MySQL.
* **PWA:** Service Workers y Manifest JSON.

---

## 🚀 Instalación Local (con XAMPP)

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/JoseMartinezS/tattoo-booking-system.git](https://github.com/JoseMartinezS/tattoo-booking-system.git)
    ```
2.  **Mover el proyecto:** Copia la carpeta descargada y pégala dentro de `C:/xampp/htdocs/`.
3.  **Configurar la Base de Datos:**
    * Inicia **Apache** y **MySQL** desde el Panel de XAMPP.
    * Entra a `http://localhost/phpmyadmin/`.
    * Crea una base de datos llamada `tattoo_db` (o el nombre que prefieras).
    * Importa el archivo `.sql` que se encuentra en la carpeta del proyecto.
4.  **Configurar conexión:**
    * Abre el archivo de conexión PHP (ej. `conexion.php` o `config.php`) y asegúrate de que los datos coincidan:
      ```php
      $host = "localhost";
      $user = "root";
      $password = "";
      $db = "tattoo_db";
      ```
5.  **Acceso:** Abre tu navegador y escribe `http://localhost/tattoo-booking-system`.

---

## 🌐 Despliegue en Hosting (ej. InfinityFree)

1.  **Subida de archivos:** Sube el contenido de la carpeta mediante el Administrador de Archivos o vía FTP a la carpeta `htdocs`.
2.  **Base de Datos en la Nube:**
    * Crea una base de datos MySQL desde el panel de control del hosting.
    * Importa tu archivo `.sql` a través del phpMyAdmin del hosting.
3.  **Ajuste de Credenciales:** Actualiza tus datos de conexión en el archivo PHP con el nombre de host, usuario y contraseña que te proporcione el proveedor de hosting.
4.  **SSL (Importante):** Asegúrate de que el sitio cargue con **HTTPS**, ya que las funciones de PWA requieren una conexión segura.

---

## 📱 Guía de Instalación PWA (Móviles)

Para instalar el sistema como una aplicación en tu celular:

* **En Android (Chrome):** Abre el sitio web, toca los tres puntos verticales en la esquina superior derecha y selecciona **"Instalar aplicación"** o **"Agregar a la pantalla de inicio"**.
* **En iOS (Safari):** Abre el sitio web, toca el icono de **Compartir** (cuadrado con flecha hacia arriba) y selecciona **"Agregar al inicio"**.

---

## ✒️ Autor

* **Jose Martínez Silva** - [JoseMartinezS](https://github.com/JoseMartinezS)

---

## 📄 Licencia

Este proyecto está bajo la Licencia **MIT**. Puedes usarlo, modificarlo y distribuirlo libremente.
