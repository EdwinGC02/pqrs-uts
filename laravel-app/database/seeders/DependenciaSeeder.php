<?php

namespace Database\Seeders;

use App\Models\Dependencia;
use Illuminate\Database\Seeder;

class DependenciaSeeder extends Seeder
{
    public function run(): void
    {
        $dependencias = [
            [
                'nombre'           => 'Registro y Control',
                'descripcion'      => 'Gestión de notas, certificados y matrículas estudiantiles',
                'email_responsable' => 'registro@institucion.edu.co',
            ],
            [
                'nombre'           => 'Bienestar Universitario',
                'descripcion'      => 'Apoyos socioeconómicos, becas y servicios de salud',
                'email_responsable' => 'bienestar@institucion.edu.co',
            ],
            [
                'nombre'           => 'Soporte TI',
                'descripcion'      => 'Aula virtual, sistemas de información y gestión de accesos',
                'email_responsable' => 'soporte.ti@institucion.edu.co',
            ],
            [
                'nombre'           => 'Coordinación Académica',
                'descripcion'      => 'Horarios, docentes y gestión del pensum académico',
                'email_responsable' => 'coordinacion@institucion.edu.co',
            ],
            [
                'nombre'           => 'Secretaría General',
                'descripcion'      => 'Documentos oficiales, paz y salvos y certificaciones',
                'email_responsable' => 'secretaria@institucion.edu.co',
            ],
            [
                'nombre'           => 'Financiero',
                'descripcion'      => 'Pagos, descuentos, facturas y obligaciones financieras',
                'email_responsable' => 'financiero@institucion.edu.co',
            ],
        ];

        foreach ($dependencias as $dep) {
            Dependencia::firstOrCreate(['nombre' => $dep['nombre']], $dep);
        }
    }
}
