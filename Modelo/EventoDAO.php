<?php
require_once 'DAO.php';
require_once 'Evento.php';


class EventoDAO extends DAO {

    public function agregar(Evento $evento) {
        try {
            $this->conexion->begin_transaction();

            $sql = "INSERT INTO Eventos (titulo, descripcion, ponente, numParticipantes, fecha, horaInicio, horaFinal, tipoCupo, tipoEvento, idCategoria, idSalon, imagen) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $statement = $this->conexion->prepare($sql);
            
            $titulo = $evento->getTitulo(); $descripcion = $evento->getDescripcion(); $ponente = $evento->getPonente();
            $numParitcipantes = $evento->getNumParticipantes(); $fecha = $evento->getFecha(); 
            $horaInicial = $evento->getHoraInicio(); $horaFin = $evento->getHoraFinal();
            $cupo = $evento->getTipoCupo(); $tipoEvento = $evento->getTipoEvento();
            $idCat = $evento->getIdCategoria(); $idSalon = $evento->getIdSalon(); $imagen = $evento->getImagen();

            // sssisssssii (String, String, String, Int, String, String, String, String, String, Int, Int, String)
            $statement->bind_param("sssisssssiis", $titulo, $descripcion, $ponente, $numParitcipantes, $fecha, $horaInicial, $horaFin, $cupo, $tipoEvento, $idCat, $idSalon, $imagen);
            
            if (!$statement->execute()) {
                throw new Exception("Error al insertar Evento base");
            }

            $idEventoNuevo = $this->conexion->insert_id;

            if ($tipoEvento == 'conferencia') {
                $sqlHijo = "INSERT INTO Conferencias (idEvento, tipoConferencia) VALUES (?, ?)";
                $statementHijo = $this->conexion->prepare($sqlHijo);
                $tipoConf = $evento->getTipoConferencia();
                $statementHijo->bind_param("is", $idEventoNuevo, $tipoConf);
                if (!$statementHijo->execute()){ throw new Exception("Error al insertar Conferencia");} 
                
            } elseif ($tipoEvento == 'premiacion') {
                $sqlHijo = "INSERT INTO Premiaciones (idEvento, ganadorPremiacion) VALUES (?, ?)";
                $statementHijo = $this->conexion->prepare($sqlHijo);
                $ganador = $evento->getGanadorPremiacion();
                $statementHijo->bind_param("is", $idEventoNuevo, $ganador);
                if (!$statementHijo->execute()){ throw new Exception("Error al insertar Premiación");}
                
            } elseif ($tipoEvento == 'taller') {
                $sqlHijo = "INSERT INTO Talleres (idEvento) VALUES (?)";
                $statementHijo = $this->conexion->prepare($sqlHijo);
                $statementHijo->bind_param("i", $idEventoNuevo);
                if (!$statementHijo->execute()){ throw new Exception("Error al insertar Taller");}
            }

            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollback();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function eliminar($idEvento) {
        try {
            $this->conexion->begin_transaction();

            $sqlConf = "DELETE FROM Conferencias WHERE idEvento = ?";
            $stmtConf = $this->conexion->prepare($sqlConf);
            $stmtConf->bind_param("i", $idEvento);
            $stmtConf->execute();
            $stmtConf->close();

            $sqlPrem = "DELETE FROM Premiaciones WHERE idEvento = ?";
            $stmtPrem = $this->conexion->prepare($sqlPrem);
            $stmtPrem->bind_param("i", $idEvento);
            $stmtPrem->execute();
            $stmtPrem->close();

            $sqlTaller = "DELETE FROM Talleres WHERE idEvento = ?";
            $stmtTaller = $this->conexion->prepare($sqlTaller);
            $stmtTaller->bind_param("i", $idEvento);
            $stmtTaller->execute();
            $stmtTaller->close();

            $sql = "DELETE FROM Eventos WHERE idEvento = ?";
            $statement = $this->conexion->prepare($sql);
            $statement->bind_param("i", $idEvento);

            if (!$statement->execute()) {
                throw new Exception("No se pudo eliminar el evento principal.");
            }
            $statement->close();

            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }

    // Listar eventos con sus nombres de Categoria y su Salon correspondientes
    public function obtenerEventos() {
        $sql = "SELECT evento.*, categoria.nombre as nombreCategoria, salon.nombreSalon, salon.maxCapacidad, 
                (SELECT COUNT(*) FROM Registro WHERE idEvento = evento.idEvento) as totalInscritos
                FROM Eventos evento 
                JOIN Categorias categoria ON evento.idCategoria = categoria.idCategoria 
                JOIN Salones salon ON evento.idSalon = salon.idSalon
                ORDER BY evento.fecha DESC, evento.horaInicio ASC";
        
        $result = $this->conexion->query($sql);
        $lista = [];
        
        while ($fila = $result->fetch_assoc()) {
            $lista[] = $fila; 
        }
        return $lista;
    }
}


