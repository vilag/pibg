<!DOCTYPE html>
<html lang="es">
<head>
<title>Búsqueda - Primera Iglesia Bautista de Guadalajara</title>
<link href="images/iconos/icono.png" rel="icon">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/news.css">
<link rel="stylesheet" type="text/css" href="styles/news_responsive.css">
<style>
    .buscar_banner {
        background: linear-gradient(135deg, #042C49, #24344B);
        padding: 60px 0 50px;
    }
    .buscar_banner h1 {
        color: #fff;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .buscar_banner p {
        color: rgba(255,255,255,0.7);
        font-size: 15px;
        margin-bottom: 28px;
    }
    .buscar_form_wrap {
        display: flex;
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .buscar_form_wrap input {
        flex: 1;
        border: none;
        outline: none;
        padding: 14px 24px;
        font-size: 15px;
        color: #333;
        background: transparent;
    }
    .buscar_form_wrap button {
        padding: 14px 28px;
        background: #F85E0C;
        border: none;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .buscar_form_wrap button:hover { background: #d94e00; }

    .buscar_seccion { padding: 40px 0 0; }
    .buscar_seccion_titulo {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #F85E0C;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0f0f0;
    }
    .buscar_item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f5f5f5;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }
    .buscar_item:hover { color: inherit; text-decoration: none; }
    .buscar_item:hover .bi_titulo { color: #F85E0C; }
    .bi_icon {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #042C49, #24344B);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: #fff; font-size: 18px;
    }
    .bi_titulo {
        font-size: 16px;
        font-weight: 700;
        color: #24344B;
        margin-bottom: 4px;
        transition: color 0.2s;
    }
    .bi_sub { font-size: 13px; color: #888; }
    .buscar_vacio {
        text-align: center;
        padding: 80px 20px;
        color: #aaa;
    }
    .buscar_vacio i { font-size: 56px; display: block; margin-bottom: 20px; color: #ddd; }
    .buscar_vacio h3 { color: #666; font-size: 20px; margin-bottom: 8px; }

    @media only screen and (max-width: 575px) {
        .buscar_banner { padding: 36px 0 28px; }
        .buscar_banner h1 { font-size: 26px; }
        .buscar_banner p { font-size: 13px; margin-bottom: 20px; }
        .buscar_form_wrap { border-radius: 8px; max-width: 100%; }
        .buscar_form_wrap input { padding: 12px 16px; font-size: 14px; }
        .buscar_form_wrap button { padding: 12px 16px; font-size: 15px; }
        .buscar_seccion { padding: 28px 0 0; }
        .buscar_item { gap: 10px; padding: 12px 0; }
        .bi_icon { width: 38px; height: 38px; font-size: 15px; flex-shrink: 0; }
        .bi_titulo { font-size: 14px; }
        .bi_sub { font-size: 11px; }
        .buscar_vacio { padding: 50px 16px; }
        .buscar_vacio i { font-size: 40px; }
        .buscar_vacio h3 { font-size: 17px; }
    }
</style>
</head>
<body>

<div class="super_container">

    <?php require 'header.php'; ?>

    <?php
        require_once 'config/Conexion.php';
        global $conexion;

        $q_raw      = isset($_GET['q']) ? trim($_GET['q']) : '';
        $q_display  = htmlspecialchars($q_raw);
        $q          = $q_raw !== '' ? mysqli_real_escape_string($conexion, $q_raw) : '';

        $sermones   = [];
        $biografias = [];
        $actividades= [];

        if (mb_strlen($q_raw, 'UTF-8') >= 2) {
            // Sermones
            $r = ejecutarConsulta("SELECT idsermones, nom_sermon, predicador, fecha_eti, imagen
                FROM sermones
                WHERE nom_sermon LIKE '%$q%' OR predicador LIKE '%$q%' OR predicacion LIKE '%$q%'
                ORDER BY idsermones DESC LIMIT 10");
            if ($r) while ($row = $r->fetch_assoc()) $sermones[] = $row;

            // Biografías
            $r = ejecutarConsulta("SELECT idbiografia, nombre, cargo, imagen, biografia
                FROM biografias
                WHERE nombre LIKE '%$q%' OR cargo LIKE '%$q%' OR biografia LIKE '%$q%'
                ORDER BY idbiografia DESC LIMIT 10");
            if ($r) while ($row = $r->fetch_assoc()) $biografias[] = $row;

            // Calendario
            $r = ejecutarConsulta("SELECT idcal, nom_activ, tema, DATE(fecha_hora) AS fecha
                FROM calendario
                WHERE (nom_activ LIKE '%$q%' OR tema LIKE '%$q%') AND DATE(fecha_hora) >= CURDATE()
                ORDER BY fecha_hora ASC LIMIT 10");
            if ($r) while ($row = $r->fetch_assoc()) $actividades[] = $row;
        }

        $total = count($sermones) + count($biografias) + count($actividades);
    ?>

    <!-- Banner de búsqueda -->
    <div class="buscar_banner text-center">
        <div class="container">
            <h1>Búsqueda</h1>
            <?php if ($q_display): ?>
                <p><?php echo $total; ?> resultado<?php echo $total != 1 ? 's' : ''; ?> para "<b><?php echo $q_display; ?></b>"</p>
            <?php else: ?>
                <p>Escribe algo para buscar en el sitio</p>
            <?php endif; ?>
            <form method="get" action="buscar.php">
                <div class="buscar_form_wrap">
                    <input type="text" name="q" value="<?php echo $q_display; ?>" placeholder="¿Qué deseas buscar?" autofocus>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div style="background: #f8f9fa; min-height: 400px; padding-bottom: 60px;">
        <div class="container">

            <?php if (!$q_display): ?>

                <div class="buscar_vacio">
                    <i class="fa fa-search"></i>
                    <h3>Ingresa un término de búsqueda</h3>
                    <p>Puedes buscar predicaciones, biografías o actividades.</p>
                </div>

            <?php elseif ($total === 0): ?>

                <div class="buscar_vacio">
                    <i class="fa fa-frown-o"></i>
                    <h3>Sin resultados</h3>
                    <p>No encontramos nada relacionado con "<b><?php echo $q_display; ?></b>".<br>Intenta con otras palabras.</p>
                </div>

            <?php else: ?>

                <?php if ($sermones): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-microphone"></i> &nbsp;Predicaciones</div>
                    <?php foreach ($sermones as $s): ?>
                        <a href="blog.php?id=<?php echo $s['idsermones']; ?>" class="buscar_item">
                            <div class="bi_icon"><i class="fa fa-microphone"></i></div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($s['nom_sermon']); ?></div>
                                <div class="bi_sub"><?php echo htmlspecialchars($s['predicador']); ?> &nbsp;·&nbsp; <?php echo htmlspecialchars($s['fecha_eti']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($biografias): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-user"></i> &nbsp;Biografías</div>
                    <?php foreach ($biografias as $b): ?>
                        <a href="biografia_detalle.php?id=<?php echo $b['idbiografia']; ?>" class="buscar_item">
                            <div class="bi_icon">
                                <?php if ($b['imagen']): ?>
                                    <img src="<?php echo htmlspecialchars($b['imagen']); ?>" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                                <?php else: ?>
                                    <i class="fa fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($b['nombre']); ?></div>
                                <?php if ($b['cargo']): ?>
                                    <div class="bi_sub"><?php echo htmlspecialchars($b['cargo']); ?></div>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($actividades): ?>
                <div class="buscar_seccion">
                    <div class="buscar_seccion_titulo"><i class="fa fa-calendar"></i> &nbsp;Próximas Actividades</div>
                    <?php foreach ($actividades as $a): ?>
                        <a href="./#calendario" class="buscar_item">
                            <div class="bi_icon"><i class="fa fa-calendar"></i></div>
                            <div>
                                <div class="bi_titulo"><?php echo htmlspecialchars($a['nom_activ']); ?></div>
                                <div class="bi_sub">
                                    <?php if ($a['tema']) echo htmlspecialchars($a['tema']) . ' &nbsp;·&nbsp; '; ?>
                                    <?php echo $a['fecha']; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>

    <?php require 'footer.php'; ?>

</div>

<script src="plugins/parallax-js-master/parallax.min.js"></script>
</body>
</html>
