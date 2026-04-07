<!DOCTYPE html>
<html lang="es">
<head>
<title>Coro J. S. Bach – Voces</title>
<link href="images/iconos/icono.png" rel="icon">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Primera Iglesia Bautista de Guadalajara – Coro Bach">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-3.2.1.min.js"></script>
<style>
  *, *::before, *::after { box-sizing: border-box; }

  body {
    margin: 0;
    background: #f2f4f8;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
    color: #1a1a2e;
    -webkit-font-smoothing: antialiased;
  }

  /* ---- Header ---- */
  .v-header {
    background: #1D4268;
    color: #fff;
    padding: 32px 20px 28px;
    text-align: center;
  }
  .v-header h1 {
    margin: 0 0 6px;
    font-size: clamp(1.2rem, 4vw, 1.6rem);
    font-weight: 700;
    letter-spacing: .3px;
  }
  .v-header p {
    margin: 0;
    font-size: .85rem;
    opacity: .6;
    letter-spacing: .4px;
  }

  /* ---- Container ---- */
  .v-container {
    max-width: 780px;
    margin: 0 auto;
    padding: 28px 16px 80px;
  }

  /* ---- Search ---- */
  .v-search-row {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
  }
  .v-search-row input {
    flex: 1;
    height: 46px;
    border: 1.5px solid #d4dbe8;
    border-radius: 10px;
    padding: 0 16px;
    font-size: .9375rem;
    background: #fff;
    outline: none;
    color: #1a1a2e;
    transition: border-color .2s;
  }
  .v-search-row input:focus { border-color: #1D4268; }
  .v-search-row input::placeholder { color: #a0aec0; }

  .v-btn-primary {
    height: 46px;
    padding: 0 22px;
    background: #1D4268;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: background .2s;
  }
  .v-btn-primary:hover { background: #163454; }

  .v-btn-ghost {
    width: 100%;
    height: 44px;
    background: transparent;
    color: #1D4268;
    border: 1.5px solid #c9d5e8;
    border-radius: 10px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 24px;
    transition: background .2s, border-color .2s;
  }
  .v-btn-ghost:hover { background: #e8eef7; border-color: #1D4268; }

  /* ---- Section label ---- */
  .v-section-label {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 12px;
  }

  /* ---- Obra cards ---- */
  .v-obra-card {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: box-shadow .2s, border-color .2s;
  }
  .v-obra-card:hover {
    box-shadow: 0 4px 16px rgba(29,66,104,.1);
    border-color: #bdd0e8;
  }
  .v-obra-name {
    font-size: 1rem;
    font-weight: 700;
    color: #1D4268;
    margin-bottom: 3px;
  }
  .v-obra-author {
    font-size: .8rem;
    color: #94a3b8;
  }
  .v-obra-arrow {
    color: #c0cfe0;
    font-size: 1.2rem;
    flex-shrink: 0;
    margin-left: 12px;
  }

  /* ---- Voices view ---- */
  .v-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    color: #1D4268;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    margin-bottom: 20px;
  }
  .v-back-btn:hover { text-decoration: underline; }

  .v-obra-heading {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 2px solid #e8edf5;
  }

  /* ---- Voice card ---- */
  .v-voz-card {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 12px;
  }
  .v-voz-name {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.4px;
    color: #1D4268;
    margin-bottom: 10px;
  }
  .v-voz-card audio {
    width: 100%;
    height: 36px;
    border-radius: 8px;
    accent-color: #1D4268;
    display: block;
  }

  /* ---- Partituras ---- */
  .v-partituras-label {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 16px 0 8px;
  }
  .v-partituras-row {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 4px;
    -webkit-overflow-scrolling: touch;
  }
  .v-partituras-row::-webkit-scrollbar { height: 5px; }
  .v-partituras-row::-webkit-scrollbar-track { background: #f0f4fa; border-radius: 4px; }
  .v-partituras-row::-webkit-scrollbar-thumb { background: #bdd0e8; border-radius: 4px; }

  .v-file-item {
    flex-shrink: 0;
    background: #f7f9fd;
    border: 1px solid #e2e8f4;
    border-radius: 10px;
    padding: 10px 14px;
    min-width: 150px;
    max-width: 190px;
  }
  .v-file-name {
    font-size: .78rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .v-file-actions { display: flex; gap: 8px; }
  .v-file-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #1D4268;
    transition: background .2s;
  }
  .v-file-btn:hover { background: #163454; }
  .v-file-btn img { width: 16px; height: 16px; }

  /* ---- Spinner ---- */
  .v-spinner {
    text-align: center;
    padding: 48px 0;
    color: #94a3b8;
    font-size: .9rem;
    display: none;
  }

  /* ---- Responsive ---- */
  @media (max-width: 480px) {
    .v-header { padding: 24px 16px 20px; }
    .v-container { padding: 20px 12px 60px; }
    .v-btn-primary { padding: 0 14px; font-size: .85rem; }
  }
</style>
</head>
<body>

<header class="v-header">
  <h1>Coro Johann Sebastian Bach</h1>
  <p>Primera Iglesia Bautista de Guadalajara</p>
</header>

<div class="v-container">

  <!-- Búsqueda -->
  <div class="v-search-row">
    <input type="text" id="text_nom" placeholder="Buscar obra…"
           onkeydown="if(event.key==='Enter') listar_obras();">
    <button class="v-btn-primary" onclick="listar_obras();">Buscar</button>
  </div>
  <button class="v-btn-ghost" onclick="listar_obras_1();">Ver todas las obras</button>

  <!-- Vista: listado de obras -->
  <div id="div_busquedas">
    <div class="v-section-label">Obras</div>
    <div id="v-spinner-obras" class="v-spinner">Cargando…</div>
    <div id="box_obras"></div>
  </div>

  <!-- Vista: voces de una obra -->
  <div id="div_voces" style="display:none;">
    <button class="v-back-btn" onclick="regresar_obras();">&#8592; Regresar</button>
    <div class="v-obra-heading" id="p_nom_obra"></div>
    <div id="v-spinner-voces" class="v-spinner">Cargando…</div>
    <div id="box_voces"></div>
  </div>

</div>

<script type="text/javascript" src="scripts/voces.js?v=<?php echo(rand()); ?>"></script>
</body>
</html>