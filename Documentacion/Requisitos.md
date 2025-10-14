<h1> Desglose de Requisitos </h1>
<h2 align="center"> Proyecto: Sistema de Organización enfocado en la FILEY </h2>
<h3 align="center"> (Feria Internacional de Yucatán) </h3>

### Requisitos Funcionales
<strong>RF-01: Registro de Usuario</strong>
El sistema debe permitir a los nuevos usuarios crear una cuenta utilizando su correo electrónico, un nombre de usuario y una contraseña. Por fines de seguridad, la contraseña deberá tener al menos 8 caracteres, una mayúscula y un entero.
<strong>RF-02: Autenticación de Usuario</strong>
El sistema debe permitir a los usuarios registrados iniciar sesión con su correo y contraseña.
<strong>RF-03: Ver Detalles del Taller</strong>
Los usuarios deben poder hacer clic en un taller para ver su descripción completa, imágenes, ponente y horarios.
<strong>RF-04: Gestión de Talleres</strong>
El administrador debe poder añadir nuevos talleres, programarlos, editar los existentes y actualizarlos en caso de ser necesario.
<strong>RF-05: Gestión de Premiaciones</strong>
El administrador debe poder añadir nuevas premiaciones, programarlas, editar las existentes y actualizarlas en caso de ser necesario.
<strong>RF-06: Gestión de Conferencias</strong>
El administrador debe poder añadir nuevas conferencias, programarlas, editar las existentes y actualizarlas en caso de ser necesario.
<strong>RF-07: Gestión de Usuarios</strong>
El administrador debe tener un panel para ver, desactivar o eliminar cuentas de usuario.
<strong>RF-08: Dashboard de Programación</strong>
El sistema debe presentar al administrador un panel con los horarios, cursos, tallleres, visitantes registrados, ponentes del día.
<strong>RF-09: Registro de Editoriales</strong>
El administrador deberá poder ingresar nuevas editoriales, clasificarlas sobre Nacionales o Internacionales, así como el lenguaje que utilizan.
<strong>RF-10: Dashboard de Registros</strong>
Los usuarios deberán tener acceso a un panel que les enseñe los eventos a los que se han registrado.
<strong>RF-11: Capacidad de Eventos</strong>
Los eventos deberán tener la capacidad de ser designados de cupo libre o de cupo limitado.
<strong>RF-12: Generación de QR</strong>
El sistema deberá generar códigos QR, con los que los usuarios podrán ingresar a las platicas con limitación de cupo.
<strong>RF-13: Generación de Constancias</strong>
El sistema deberá generar constancias a los asistentes de los eventos. Este se realizará mediante la comprobación de usuarios que asistieron al evento, por lo que se entregará al final de la conferencia.
<!-- <strong>RF-13: Exportación de Horarios</strong>
El administrador debe poder exportar el listado de cursos planificados para un día a un archivo CSV.
<strong>RF-03: Bloqueo de Cuenta</strong>
El sistema debe bloquear la cuenta de un usuario durante 15 minutos después de 5 intentos fallidos de inicio de sesión. -->

### Requisitos No Funcionales
<strong>RNF-01: Seguridad de las cuentas</strong>
Para garantizar la seguridad de los usuarios y administradores, cada una de las cuentas estará asegurada por una contraseña que se validará mediante un algoritmo al momento de iniciar sesión, todas las contraseñas estarán almacenadas en una base de datos.  
<strong>RNF-02: Seguridad del programa</strong>
Solo los usuarios con el rol de "Administrador" podrán acceder a las funciones de gestión. El sistema debe denegar el acceso a cualquier otro rol.
<strong>RNF-03: Usabilidad de los usuarios</strong>
La interfaz deberá estar hecha de manera que se pueda usar de manera intuitiva por todos los usuarios, por lo que la estructura debe ser clara, todos los botones y menús deben seguir un estilo consistente en todo el programa para minimizar la curva de aprendizaje del usuario.
<strong>RNF-04: Rendimiento</strong>
Los tiempos de respuesta deben ser de, a lo mucho, 3 segundos en el equipo, con esto aseguramos que los usuarios puedan usar el programa de manera óptima, y garantizando que el programa tenga una interfaz que se vea fluida. 
<strong>RNF-06: Consistencia</strong>
El programa deberá validar que el cupo a los talleres no se exceda, y no deberá aceptar más usuarios una vez llegado el límite. 
<strong>RNF-08: Mantenibilidad</strong>
Para que el código se pueda mantener de manera adecuada en un futuro, deberá estar documentado y redactado con los estándares de calidad. 
<strong>RNF-09: Usabilidad</strong>
En caso de tener un error inesperado, el sistema deberá mostrar una página de error que sea amigable para el usuario en lugar de un error de código.
<strong>RNF-10: Confiabilidad</strong>
Se realizarán copias de la base de datos de manera diaria, garantizando que los datos y acciones (en caso de inscribirse a un taller) de los usuarios se queden guardadas de manera segura. 