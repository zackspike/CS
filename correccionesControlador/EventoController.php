<?php

/**
 * Controlador de eventos de la FILEY.
 *
 * Acciones disponibles (solo para usuarios con rol "admin"):
 *  - POST accion=crear_evento : crea un evento y su subtipo (conferencia, premiación o taller).
 *  - POST accion=eliminar     : elimina un evento; exige token CSRF y que no tenga inscritos.
 *
 * Cualquier otra petición se redirige a la vista de gestión con error=accion_invalida.
 *
 * @author Isaac Herrera
 */

require_once '../Modelo/Evento.php';
require_once '../Modelo/EventoDAO.php';
require_once '../Modelo/SalonDAO.php';
require_once '../Modelo/CategoriaDAO.php';

const VISTA_GESTION = '../Vista/gestionEventos.php';
const VISTA_LOGIN = '../Vista/login.php';
const RUTA_IMAGENES = __DIR__ . '/../assets/';   // D-04: carpeta real del repositorio
const TAMANO_MAX_IMAGEN = 2097152;               // 2 MB
const EXTENSIONES_IMAGEN = ['jpg', 'jpeg', 'png', 'webp'];
const MIME_IMAGEN = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
const TIPOS_EVENTO = ['conferencia', 'taller', 'premiacion'];
const TIPOS_CUPO = ['Limitado', 'Abierto'];
const MAX_TITULO = 150;
const MAX_DESCRIPCION = 1000;
const MAX_PONENTE = 100;
const MAX_TIPO_CONFERENCIA = 50;
const MAX_GANADOR = 100;

/**
 * Redirige y termina la ejecución (D-09: todo header() va seguido de exit()).
 */
function redirigir(string $destino): void {
    header('Location: ' . $destino);
    exit();
}

/**
 * Regresa a la vista de gestión con un parámetro de resultado (msg=... o error=...).
 */
function volverAGestion(string $parametro): void {
    redirigir(VISTA_GESTION . '?' . $parametro);
}

/**
 * Lee un campo de $_POST sin generar avisos si no existe (D-05).
 */
function campo(string $nombre): string {
    return trim((string)($_POST[$nombre] ?? ''));
}

/**
 * Valida una fecha con formato Y-m-d real (por ejemplo, rechaza 2026-02-31).
 */
function fechaValida(string $fecha): bool {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    return $d !== false && $d->format('Y-m-d') === $fecha;
}

/**
 * Valida una hora HH:MM o HH:MM:SS y la devuelve como HH:MM:SS; null si es inválida.
 */
function normalizarHora(string $hora): ?string {
    if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $hora)) {
        return null;
    }
    return strlen($hora) === 5 ? $hora . ':00' : $hora;
}

/**
 * Valida y guarda la imagen subida (D-03).
 *
 * @return string|null Nombre del archivo guardado, '' si no se subió imagen
 *                     (D-14) o null si la imagen no es válida.
 */
function guardarImagen(): ?string {
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    $archivo = $_FILES['imagen'];

    if ($archivo['error'] !== UPLOAD_ERR_OK || $archivo['size'] > TAMANO_MAX_IMAGEN) {
        return null;
    }

    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, EXTENSIONES_IMAGEN, true)) {
        return null;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
    if (!isset(MIME_IMAGEN[$mime]) || getimagesize($archivo['tmp_name']) === false) {
        return null;
    }

    if (!is_dir(RUTA_IMAGENES) && !mkdir(RUTA_IMAGENES, 0755, true)) {
        return null;
    }

    // La extensión final sale del tipo MIME verificado, no del nombre original.
    $nombre = 'evento_' . bin2hex(random_bytes(8)) . '.' . MIME_IMAGEN[$mime];
    return move_uploaded_file($archivo['tmp_name'], RUTA_IMAGENES . $nombre) ? $nombre : null;
}

/**
 * Borra del disco la imagen de un evento, si existe.
 */
function borrarImagen(?string $imagen): void {
    $nombre = basename((string)$imagen);
    if ($nombre !== '' && is_file(RUTA_IMAGENES . $nombre)) {
        unlink(RUTA_IMAGENES . $nombre);
    }
}

/**
 * Acción crear_evento: valida los datos en el servidor, comprueba que el salón
 * esté libre y registra el evento.
 */
function crearEvento(EventoDAO $eventoDAO, SalonDAO $salonDAO, CategoriaDAO $categoriaDAO): void {
    $titulo = campo('titulo');
    $descripcion = campo('descripcion');
    $ponente = campo('ponente');
    $fecha = campo('fecha');
    $horaInicio = normalizarHora(campo('horaInicio'));
    $horaFinal = normalizarHora(campo('horaFinal'));
    $cupo = campo('tipoCupo');
    $tipoEvento = campo('tipoEvento');
    $idSalon = (int)campo('idSalon');
    $idCat = (int)campo('idCategoria');

    // D-05: campos obligatorios presentes
    if ($titulo === '' || $descripcion === '' || $ponente === '' || $fecha === ''
            || $idSalon <= 0 || $idCat <= 0) {
        volverAGestion('error=datos_incompletos');
    }

    // D-06 y D-08: validación en el servidor
    if (!fechaValida($fecha) || $horaInicio === null || $horaFinal === null
            || $horaFinal <= $horaInicio
            || mb_strlen($titulo) > MAX_TITULO
            || mb_strlen($descripcion) > MAX_DESCRIPCION
            || mb_strlen($ponente) > MAX_PONENTE
            || !in_array($cupo, TIPOS_CUPO, true)
            || !in_array($tipoEvento, TIPOS_EVENTO, true)) {
        volverAGestion('error=datos_invalidos');
    }

    $tipoConferencia = null;
    $ganador = null;
    if ($tipoEvento === 'conferencia') {
        $tipoConferencia = campo('tipoConferencia');
        if (mb_strlen($tipoConferencia) > MAX_TIPO_CONFERENCIA) {
            volverAGestion('error=datos_invalidos');
        }
    } elseif ($tipoEvento === 'premiacion') {
        $ganador = campo('ganadorPremiacion');
        if (mb_strlen($ganador) > MAX_GANADOR) {
            volverAGestion('error=datos_invalidos');
        }
    }

    // El salón debe existir
    $salon = $salonDAO->obtenerPorId($idSalon);
    if (!$salon) {
        volverAGestion('error=salon_invalido');
    }

    // D-16: la categoría debe existir
    if (!$categoriaDAO->obtenerPorId($idCat)) {
        volverAGestion('error=categoria_invalida');
    }

    // D-07: el salón no puede estar ocupado en ese horario
    if ($eventoDAO->existeTraslape($idSalon, $fecha, $horaInicio, $horaFinal)) {
        volverAGestion('error=salon_ocupado');
    }

    // D-07 (RF-11): con cupo limitado el aforo es la capacidad del salón; con entrada libre no hay límite de registro
    $numParticipantes = ($cupo === 'Limitado') ? (int)$salon->getMaxCapacidad() : 0;

    // D-03 y D-14: la imagen es opcional; '' en lugar de null (el constructor exige string)
    $imagen = guardarImagen();
    if ($imagen === null) {
        volverAGestion('error=imagen_invalida');
    }

    $evento = new Evento(0, $titulo, $descripcion, $ponente, $numParticipantes, $fecha,
            $horaInicio, $horaFinal, $cupo, $tipoEvento, $idCat, $idSalon, $imagen);

    if ($tipoEvento === 'conferencia') {
        $evento->setTipoConferencia($tipoConferencia);
    } elseif ($tipoEvento === 'premiacion') {
        $evento->setGanadorPremiacion($ganador);
    }

    if ($eventoDAO->agregar($evento)) {
        volverAGestion('msg=creado');
    }
    borrarImagen($imagen);   // no dejar archivos huérfanos si falló la inserción
    volverAGestion('error=fallo_creacion');
}

/**
 * Acción eliminar: exige token CSRF (D-02), bloquea la baja si hay inscritos
 * y borra también la imagen asociada (D-12).
 */
function eliminarEvento(EventoDAO $eventoDAO): void {
    $token = (string)($_POST['csrf_token'] ?? '');
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        volverAGestion('error=token_invalido');
    }

    $idEvento = (int)($_POST['idEvento'] ?? 0);
    if ($idEvento <= 0) {
        volverAGestion('error=datos_incompletos');
    }

    $evento = null;
    foreach ($eventoDAO->obtenerEventos() as $fila) {
        if ((int)$fila['idEvento'] === $idEvento) {
            $evento = $fila;
            break;
        }
    }
    if ($evento === null) {
        volverAGestion('error=no_borrado');
    }

    if ((int)$evento['totalInscritos'] > 0) {
        volverAGestion('error=evento_con_inscritos');
    }

    if ($eventoDAO->eliminar($idEvento)) {
        borrarImagen($evento['imagen'] ?? '');
        volverAGestion('msg=eliminado');
    }
    volverAGestion('error=no_borrado');
}

// ---------------------------------------------------------------- Punto de entrada

// D-01: solo administradores (RNF-02)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    redirigir(VISTA_LOGIN);
}

$eventoDAO = new EventoDAO();
$salonDAO = new SalonDAO();
$categoriaDAO = new CategoriaDAO();

// D-10: toda petición recibe una respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear_evento') {
        crearEvento($eventoDAO, $salonDAO, $categoriaDAO);
    } elseif ($accion === 'eliminar') {
        eliminarEvento($eventoDAO);
    } else {
        volverAGestion('error=accion_invalida');
    }
} else {
    volverAGestion('error=accion_invalida');
}
