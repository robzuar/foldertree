<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Document;

/**
 * Class LoadDocumentData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class LoadDocumentData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $documentos = $this->getDocumentos();

        foreach ($documentos as $documento) {
            $newDocumento = new Document();
            $newDocumento->setName($documento['name']);
            $manager->persist($newDocumento);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getDocumentos()
    {
        return [
                    [ 'name' => '01 DOCUMENTOS OBRA A POSTVENTA'],
                    [ 'name' => '01-01 ACTA DE ENTREGA GENERAL OBRA A PV FIRMADA - N°001'],
                    [ 'name' => '01-02 ACTA DE ENTREGA DEPARTAMENTOS OBRA A PV'],
                    [ 'name' => '01-02-01 ACTA ENTREGA DEPARTAMENTOS OBRA A POSTVENTA - TERMINACIONES - N°002'],
                    [ 'name' => '01-02-02 ACTA ENTREGA INSTALACIONES DEPARTAMENTOS- N°003'],
                    [ 'name' => '01-02-03 ACTA BODEGAS Y ESTACIONAMIENTOS- N°004'],
                    [ 'name' => '01-02-04 ACTA ENTREGA AREAS COMUNES OBRA A PV- N°005'],
                    [ 'name' => '01-02-05 ACTA ENTREGA AREAS COMUNES PV A COMERCIAL - N°006'],
                    [ 'name' => '01-02-06 ACTA ENTREGA DEPARTAMENTOS PILOTO PV A COMERCIAL - N°007'],
                    [ 'name' => '01-02-07 ACTAS R2 DEPARTAMENTOS'],
                    [ 'name' => '01-02-08 ACTAS RF DEPARTAMENTOS'],
                    [ 'name' => '01-03 PLANILLA CONTACTO MANTENEDORES - N°008'],
                    [ 'name' => '01-04 PLANILLA FECHA CAPACITACIONES Y FIRMA CAPACITADOS - N°009'],
                    [ 'name' => '01-05 ACTA ENTREGA BOLETA SERVICIOS BASICOS PAGADOS DE AC Y DEPTOS - N°010'],
                    [ 'name' => '02 DOCUMENTOS LEGALES ADMINISTRACION'],
                    [ 'name' => '02-01 CONTRATO ADMINISTRADOR'],
                    [ 'name' => '02-02 PODER ADMINISTRADOR'],
                    [ 'name' => '02-03 ASAMBLEA ADMINISTRACION'],
                    [ 'name' => '02-04 TABLA DE PRORRATEO'],
                    [ 'name' => '02-05 SEGURO AREAS COMUNES'],
                    [ 'name' => '03 DOCUMENTOS OPERATIVOS ADMINISTRACION'],
                    [ 'name' => '03-01 ACTA DE ENTREGA ADMINISTRACION FIRMADA - N°011'],
                    [ 'name' => '03-02 CHECK LIST REVISIÓN AREAS COMUNES FIRMADA - N°012'],
                    [ 'name' => '03-03 CONTRATOS DE MANTENCIÓN'],
                    [ 'name' => '03-03-01 ASCENSORES'],
                    [ 'name' => '03-03-02 SALA DE BOMBAS'],
                    [ 'name' => '03-03-03 PORTON, CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-03-04 SALA DE CALDERAS'],
                    [ 'name' => '03-03-05 SISTEMA ELECTRICO'],
                    [ 'name' => '03-03-06 GRUPO ELECTROGENO'],
                    [ 'name' => '03-03-07 EXTRACCION - PRESURIZACION'],
                    [ 'name' => '03-03-08 PISCINA'],
                    [ 'name' => '03-03-09 PAISAJISMO Y RIEGO'],
                    [ 'name' => '03-03-10 TERMOS'],
                    [ 'name' => '03-03-11 VERTICALES'],
                    [ 'name' => '03-04 PROTOCOLO DE INDUCCION - N°013'],
                    [ 'name' => '03-04-01 ASCENSORES'],
                    [ 'name' => '03-04-02 SALA DE BOMBAS'],
                    [ 'name' => '03-04-03 PORTON, CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-04-04 SALA DE CALDERAS'],
                    [ 'name' => '03-04-05 SISTEMA ELECTRICO'],
                    [ 'name' => '03-04-06 GRUPO ELECTROGENO'],
                    [ 'name' => '03-04-07 EXTRACCIÓN - PRESURIZACIÓN'],
                    [ 'name' => '03-04-08 PISCINA'],
                    [ 'name' => '03-04-09 PAISAJISMO - RIEGO'],
                    [ 'name' => '03-05 VIDEOS DE INDUCCION'],
                    [ 'name' => '03-05-01 ASCENSORES'],
                    [ 'name' => '03-05-02 SALA DE BOMBAS'],
                    [ 'name' => '03-05-03 PORTON, CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-05-04 SALA DE CALDERAS'],
                    [ 'name' => '03-05-05 SISTEMA ELECTRICO'],
                    [ 'name' => '03-05-06 GRUPO ELECTROGENO'],
                    [ 'name' => '03-05-07 EXTRACCION Y PRESURIZACION'],
                    [ 'name' => '03-05-08 PISCINA'],
                    [ 'name' => '03-05-09 PAISAJISMO Y RIEGO'],
                    [ 'name' => '03-06 MANUALES DE MANTENCION'],
                    [ 'name' => '03-06-01 MANUAL DE MANTENCIÓN ASCENSOR'],
                    [ 'name' => '03-06-02 MANUAL DE MANTENCIÓN BOMBAS'],
                    [ 'name' => '03-06-03 MANUAL DE MANTENCIÓN PORTON, CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-06-04 MANUAL DE MANTENCIÓN SALA DE CALDERA'],
                    [ 'name' => '03-06-05 MANUAL DE MANTENCIÓN GRUPO ELECTRÓGENO'],
                    [ 'name' => '03-06-06 MANUAL DE MANTENCIÓN EXTRACCIÓN - PRESURIZACION'],
                    [ 'name' => '03-06-07 MANUAL DE MANTENCION DE PISCINA'],
                    [ 'name' => '03-06-08 MANUAL DE MANTENCIÓN Y USO FOGONES'],
                    [ 'name' => '03-07 PLANOS AS BUILT'],
                    [ 'name' => '03-07-01 PLANOS AS BUILT AGUA FRIA'],
                    [ 'name' => '03-07-02 PLANOS AS BUILT AGUA CALIENTE'],
                    [ 'name' => '03-07-03 PLANOS AS BUILT AGUA DE LLUVIA'],
                    [ 'name' => '03-07-04 PLANOS AS BUILT ALCANTARILLADO'],
                    [ 'name' => '03-07-05 PLANOS AS BUILT GAS'],
                    [ 'name' => '03-07-06 PLANOS AS BUILT ASCENSORES'],
                    [ 'name' => '03-07-07 PLANOS AS BUILT CLIMATIZACIÓN'],
                    [ 'name' => '03-07-08 PLANOS AS BUILT SALA DE CALDERAS'],
                    [ 'name' => '03-07-09 PLANOS AS BUILT CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-07-10 PLANOS AS BUILT ELECTRICIDAD'],
                    [ 'name' => '03-07-10 PLANOS PISCINA'],
                    [ 'name' => '03-08 CERTIFICADOS'],
                    [ 'name' => '03-08-01 CERTIFICADO DE INSTALACIÓN ASCENSOR'],
                    [ 'name' => '03-08-02 CERTIFICADO DE INSTALACIÓN BOMBAS'],
                    [ 'name' => '03-08-03 CERTIFICADO DE INSTALACIÓN PORTON, CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-08-04 CERTIFICADO DE INSTALACIÓN DE CALDERA'],
                    [ 'name' => '03-08-05 CERTIFICADO DE INSTALACIÓN ELECTRICA'],
                    [ 'name' => '03-08-06 CERTIFICADO DE INSTALACIÓN GRUPO ELECTRÓGENO'],
                    [ 'name' => '03-08-07 CERTIFICADO DE INSTALACIÓN EXTRACCIÓN - VEX'],
                    [ 'name' => '03-08-08 CERTIFICADO DE INSTALACIÓN DE GAS'],
                    [ 'name' => '03-08-09 CERTIFICADO DE GARANTÍA ASCENSOR'],
                    [ 'name' => '03-08-10 CERTIFICADO DE GARANTÍA BOMBAS'],
                    [ 'name' => '03-08-11 CERTIFICADO DE GARANTÍA PORTON, CITOFONO, ALARMA, CAMARAS'],
                    [ 'name' => '03-08-12 CERTIFICADO DE GARANTÍA DE CALDERA'],
                    [ 'name' => '03-08-13 CERTIFICADO DE GARANTIA ELECTRICA'],
                    [ 'name' => '03-08-14 CERTIFICADO DE GARANTÍA GRUPO ELECTRÓGENO'],
                    [ 'name' => '03-08-15 CERTIFICADO DE GARANTÍA EXTRACCIÓN - PRESURIZACIÓN'],
                    [ 'name' => '03-08-16 CERTIFICADO SANITIZACIÓN ESTANQUES'],
                    [ 'name' => '03-08-17 CERTIFICADO DE BASURA'],
                    [ 'name' => '03-08-18 CERTIFICADO CUERPO DE BOMBEROS'],
                    [ 'name' => '03-08-19 CERTIFICADO DE INSTALACION DE AGUA POTABLE Y ALCANTARILLADO'],
                    [ 'name' => '03-08-20 CERTIFICADO PISCINA SEREMI'],
                    [ 'name' => '03-08-21 LIBRO DE VISITAS SALA CALDERA'],
                    [ 'name' => '03-08-22 CERTIFICADO DE INSTALACIÓN, REVISION Y GARANTÍA TERMOS'],
                    [ 'name' => '03-09 INFORME Y VIDEO DUCTOSCOPIA - PROTOCOLO DE ASISTENCIA'],
                    [ 'name' => '03-10 ACTA DE ENTREGA INFORMACION COMITÉ DE COPROPIETARIOS Y ADMINISTRACIÓN - N°014'],
                    [ 'name' => '04 DOCUMENTOS ENTREGA A CLIENTES'],
                    [ 'name' => '04-01 FICHAS TÉCNICAS'],
                    [ 'name' => '04-01-01 FICHA TÉCNICA WC'],
                    [ 'name' => '04-01-02 FICHA TÉCNICA GRIFERIAS'],
                    [ 'name' => '04-01-03 FICHA TÉCNICA SET DE DUCHA'],
                    [ 'name' => '04-02 DOCUMENTOS LEGALES'],
                    [ 'name' => '04-02-01 REGLAMENTO DE COPROPIEDAD'],
                    [ 'name' => '04-02-02 RECEPCION MUNICIPAL Y LEY DE COPROPIEDAD'],
                    [ 'name' => '04-02-03 PERMISO DE EDIFICACION'],
                    [ 'name' => '04-02-04 NÓMINA DE PROFESIONALES'],
                    [ 'name' => '04-02-05 ANTECEDENTES LEGALES Y TÍTULOS'],
                    [ 'name' => '04-03 MANUALES'],
                    [ 'name' => '04-03-01 MANUAL CCHC'],
                    [ 'name' => '04-03-02 MANUAL DE ALARMA'],
                    [ 'name' => '04-03-03 MANUAL DE CITOFONIA'],
                    [ 'name' => '04-03-04 MANUAL CAMPANA'],
                    [ 'name' => '04-03-05 MANUAL ENCIMERA'],
                    [ 'name' => '04-03-06 MANUAL HORNO'],
                    [ 'name' => '04-03-07 MANUAL TERMO'],
                    [ 'name' => '04-03-08 MANUAL DEL PROPIETARIO'],
                    [ 'name' => '04-04 PLAN DE EVACUACION'],
                    [ 'name' => '04-05 PLANOS TIPOS DEPARTAMENTOS'],
                    [ 'name' => '04-06 ACTAS DE ENTREGA CLIENTE'],
                    [ 'name' => 'CONCEPTO XX - N° DEPARTAMENTO'],
                    [ 'name' => '05 DOCUMENTOS POSTVENTA'],
                    [ 'name' => '05-01 ACTA POSTVENTA CLIENTE'],
                    [ 'name' => 'CONCEPTO XX - N° DEPARTAMENTO'],
                    [ 'name' => '05-02 ACTAS POSTVENTA AREAS COMUNES ADMINISTRACION'],
                    [ 'name' => '05-02-FECHA - DESCRIPCION BREVE POSTVENTA'],
                    [ 'name' => '06 DOCUMENTOS GO INSTALACIONES'],
                    [ 'name' => '05-01 PROTOCOLO RECEPCION INSTALACIONES - "GO"'],
                    [ 'name' => '05-02 INFORMES ITO'],
                    [ 'name' => '05-02-01 INFORME ITO ASCENSORES-INFORME INAE'],
                    [ 'name' => '05-02-02 INFORME ITO MECANICO (EXTRACCIÓN Y SANITARIO)'],
                    [ 'name' => '05-03-03 INFORME ITO TECHUMBRE'],
                    [ 'name' => '05-02-04 INFORME ITO IMPERMEABILIZACION'],
                    [ 'name' => '05-02-05 INFORME ITO ELECTRICO'],
                    [ 'name' => '05-04 INFORME FINAL PV MIGUEL MARTINEZ'],
                    [ 'name' => '04-05 PLANOS TIPOS DEPARTAMENTOS'],
                    [ 'name' => '05 DOCUMENTOS GO INSTALACIONES'],
                    [ 'name' => '05-01 ACTA DE ENTREGA INSTALACIONES GENERALES - "GO"-  N°015'],
                    [ 'name' => '05-02 INFORMES ITO'],
                    [ 'name' => '05-02-01 INFORME ITO ASCENSORES'],
                    [ 'name' => '05-02-02 INFORME ITO MECANICO (EXTRACCIÓN Y SANITARIO)'],
                    [ 'name' => '05-02-03 INFORME ITO TECHUMBRE'],
                    [ 'name' => '05-02-04 INFORME ITO IMPERMEABILIZACION'],
                    [ 'name' => '05-02-05 INFORME ITO ELECTRICO'],
                    [ 'name' => '05-02-06 INFORME ITO REVISIÓN TDA DEPARTAMENTOS (UNO POR PISO)'],
                    [ 'name' => '06 DOCUMENTOS SUPERVISORES'],
                    [ 'name' => '06-01 INFORME ENTREGA DEPARTAMENTOS, ESTAC Y BODEGAS']
            ];
    }
}
