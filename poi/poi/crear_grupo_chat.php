<?php
include 'back-end/conex.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener la lista de amigos para seleccionar
$query = "
    SELECT u.id_usuario, u.nombre, u.foto_perfil
    FROM usuarios u
    JOIN Amistades a ON (a.id_remitente = u.id_usuario OR a.id_receptor = u.id_usuario)
    WHERE (a.id_remitente = ? OR a.id_receptor = ?)
    AND a.estado = 'aceptada'
    AND u.id_usuario != ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("iii", $id_usuario, $id_usuario, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_grupo = $_POST['nombre_grupo'];
    $miembros = isset($_POST['miembros']) ? $_POST['miembros'] : [];
    
    // Verificar que hay al menos 2 miembros seleccionados (3 incluyendo al creador)
    if (count($miembros) < 2) {
        $error = "Debes seleccionar al menos 2 amigos para crear un grupo de chat.";
    } else {
        // Comenzar transacción
        $conexion->begin_transaction();
        
        try {
            // Crear el grupo
            $query = "INSERT INTO grupo_chat (nombre_grupo, creador_id) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("si", $nombre_grupo, $id_usuario);
            $stmt->execute();
            
            $id_grupo_chat = $conexion->insert_id;
            
            // Añadir al creador como miembro
            $query = "INSERT INTO miembros_grupo_chat (id_grupo_chat, id_usuario) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ii", $id_grupo_chat, $id_usuario);
            $stmt->execute();
            
            // Añadir a los demás miembros
            foreach ($miembros as $miembro_id) {
                $stmt->bind_param("ii", $id_grupo_chat, $miembro_id);
                $stmt->execute();
            }
            
            // Confirmar transacción
            $conexion->commit();
            
            // Redirigir a la página de chat
            header('Location: chat_grupos.php');
            exit();
        } catch (Exception $e) {
            // Revertir cambios en caso de error
            $conexion->rollback();
            $error = "Error al crear el grupo: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/chat.css">
    <title>Crear Grupo de Chat</title>
    <style>
        .amigos-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }
        
        .amigo-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color:rgb(53, 53, 53);
            border-radius: 8px;
        }
        
        .amigo-item input {
            margin-right: 10px;
        }
        
        .amigo-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .form-container {
          background-color:rgb(92, 84, 93);
        }
    </style>
</head>
<body>
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>
    
    <div id="main-container">
        <div class="form-container">
            <h2>Crear Nuevo Grupo de Chat</h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre_grupo">Nombre del Grupo:</label>
                    <input type="text" id="nombre_grupo" name="nombre_grupo" required>
                </div>
                
                <div class="form-group">
                    <label>Selecciona amigos para añadir al grupo (mínimo 2):</label>
                    <div class="amigos-container">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="amigo-item">
                                <input type="checkbox" name="miembros[]" value="<?php echo $row['id_usuario']; ?>" id="amigo-<?php echo $row['id_usuario']; ?>">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto_perfil']); ?>" alt="Foto de Perfil">
                                <label for="amigo-<?php echo $row['id_usuario']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Crear Grupo</button>
            </form>
        </div>
    </div>
</body>
</html>