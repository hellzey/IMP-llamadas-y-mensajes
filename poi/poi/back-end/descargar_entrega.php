<?php
include 'conex.php';

if (!isset($_GET['id_entrega'])) {
    exit("ID de entrega no especificado.");
}

$id_entrega = $_GET['id_entrega'];

$stmt = $conexion->prepare("SELECT archivo_pdf, nombre_archivo FROM entregas WHERE id_entrega = ?");
$stmt->bind_param("i", $id_entrega);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    exit("Entrega no encontrada.");
}

$stmt->bind_result($archivo, $nombre);
$stmt->fetch();

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"" . $nombre . "\"");
echo $archivo;
exit;
