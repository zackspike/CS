<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Evento.php';
require_once __DIR__ . '/../../Modelo/Fecha.php';
require_once __DIR__ . '/../../Modelo/Hora.php';
require_once __DIR__ . '/../../Modelo/Categoria.php';
require_once __DIR__ . '/../../Modelo/Salon.php';


class EventoTest extends TestCase {

    // PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){
        $id = 1;
        $titulo = 'Presentación Jesús Tec: Real Life';
        $descripcion = 'Una plática sobre la vida real.';
        $ponente = 'Jesús Tec';
        $numParticipantes = 100;
        $fecha = new Fecha(18, 11, 2025);
        $horaI = new Hora(12, 0);
        $horaF = new Hora(13, 30);
        $tipoCupo = 'Limitado';
        $categoria = new Categoria(1, "Autobiografía", "Experiencia del ponente");
        $salon = new Salon(101, "Auditorio A", 150);
        

        $evento = new Evento(
            $id, $titulo, $descripcion, $ponente,
            $numParticipantes, $fecha, $horaI, $horaF,
            $tipoCupo, $categoria, $salon
        );

        //PRUEBA GETTERS
        $this->assertEquals($id, $evento->getIdEvento());
        $this->assertEquals($titulo, $evento->getTitulo());
        $this->assertEquals($descripcion, $evento->getDescripcion());
        $this->assertEquals($ponente, $evento->getPonente());
        $this->assertEquals($numParticipantes, $evento->getNumParticipantes());
        $this->assertEquals($tipoCupo, $evento->getTipoCupo());
        $this->assertSame($fecha, $evento->getFecha());
        $this->assertSame($horaI, $evento->getHoraInicio());
        $this->assertSame($horaF, $evento->getHoraFinal());
        $this->assertSame($categoria, $evento->getCategoria());
        $this->assertSame($salon, $evento->getUbicacion());
    }

    // PRUEBA SETTERS
    public function testSetters(){
        $fecha = new Fecha(18, 11, 2025);
        $horaI = new Hora(13, 30);
        $horaF = new Hora(15, 0);
        $categoria = new Categoria(1, "Presentación del libro", "Presentación de un libro nuevo por el autor");
        $salon = new Salon(101, "Auditorio A", 150);
        
        $evento = new Evento(
            1, "Gatos", "La vida de los gatos", "Karina Puch", 100,
            $fecha, $horaI, $horaF, "General",
            $categoria, $salon
        );

        $nuevoNombre = "Perros";
        $nuevaHoraFinal = new Hora(15, 30);
        $nuevoSalon = new Salon(102, "Auditorio B", 200);
        $nuevoPonente="Isaac Pech";

        $evento->setTitulo($nuevoNombre);
        $evento->setHoraFinal($nuevaHoraFinal);
        $evento->setPonente($nuevoPonente);
        $evento->setUbicacion($nuevoSalon);

        //VERIFICAR QUE SE HICIERON LOS CAMBIOS
        $this->assertEquals($nuevoNombre, $evento->getTitulo());
        $this->assertSame($nuevaHoraFinal, $evento->getHoraFinal());
        $this->assertEquals($nuevoPonente, $evento->getPonente());
        $this->assertSame($nuevoSalon, $evento->getUbicacion());

        //VERIFICAR QUE LOS DATOS NO MODIFICADOS SE CONSERVEN
        $this->assertEquals(1, $evento->getIdEvento());
        $this->assertSame($horaI, $evento->getHoraInicio());
        $this->assertSame($fecha, $evento->getFecha());
        $this->assertSame($categoria, $evento->getCategoria());
        $this->assertEquals(100, $evento->getNumParticipantes());
    }
}
