<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/amigos.css">
    <title>Amigos</title>
</head>
<body id="main-body"> 
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>
   
    <div class="container" id="main-container">
        <!-- Botón para agregar un amigo -->
        <button id="add-button" onclick="alert('Agregar nuevo amigo')">Agregar Amigo</button>
        
        <h1 id="main-heading">Amigos</h1>
        <ul id="friends-list">
            <li>
                <img src="media/user.png    " alt="Amigo1" class="friend-image">
                Amigo1 - <span class="online">En línea</span>
            </li>
            <li>
                <img src="media/user.png" alt="binchis" class="friend-image">
                binchis - <span class="online">En línea</span>
            </li>
            <li>
                <img src="media/user.png" alt="Amigo2" class="friend-image">
                Amigo2 - <span class="offline">Desconectado</span>
            </li>
            <li>
                <img src="media/user.png" alt="Amigo4" class="friend-image">
                Amigo4 - <span class="offline">Desconectado</span>
            </li>
            <li>
                <img src="media/user.png" alt="Amigo3" class="friend-image">
                Amigo3 - <span class="online">En línea</span>
            </li>
        </ul>
    </div>
</body>
</html>
