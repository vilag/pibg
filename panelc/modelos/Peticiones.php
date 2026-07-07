<?php
require "../config/Conexion.php";

class Peticiones
{
    public function __construct()
    {
        ejecutarConsulta("ALTER TABLE motivos_oracion ADD COLUMN IF NOT EXISTS atendida TINYINT(1) DEFAULT 0");
    }

    public function listar($filtro = 'todas')
    {
        $where = '';
        if ($filtro === 'pendientes') $where = 'WHERE atendida = 0';
        if ($filtro === 'atendidas')  $where = 'WHERE atendida = 1';
        return ejecutarConsulta("SELECT * FROM motivos_oracion $where ORDER BY fecha_hora DESC");
    }

    public function contar_pendientes()
    {
        $r = ejecutarConsultaSimpleFila("SELECT COUNT(*) AS n FROM motivos_oracion WHERE atendida = 0");
        return $r ? (int)$r['n'] : 0;
    }

    public function toggle_atendida($id, $valor)
    {
        $id    = (int)$id;
        $valor = $valor ? 1 : 0;
        return ejecutarConsulta("UPDATE motivos_oracion SET atendida = $valor WHERE id = $id");
    }

    public function eliminar($id)
    {
        return ejecutarConsulta("DELETE FROM motivos_oracion WHERE id = " . (int)$id);
    }
}
?>
