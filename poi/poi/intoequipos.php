<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/intoequipo.css">
    <title>@equipo</title>
</head>
<body id="main-body"> 
    <?php include 'navtop.php'; ?>
    <?php include 'navleft.php'; ?>
   
    <div class="container" id="main-container">
        <div id="sidebar">
            <h2>Canales</h2>
            <ul>
                <li onclick="showPosts('general')">General</li>
                <li onclick="showPosts('random')">tareas</li>
                <li onclick="showPosts('noticias')">idk</li>
            </ul>
        </div>
        <div id="content">
            <h2>Publicaciones</h2>
            <div id="posts">
                <p>Selecciona un canal para ver publicaciones.</p>
            </div>
        </div>
    </div>

    <script>
        function showPosts(channel) {
            let posts = {
                'general': '<p>Bienvenidos al canal General.</p>',
                'random': '<p>Publicaciones Random aquí.</p>',
                'noticias': '<p>Últimas noticias en este canal.</p>'
            };
            document.getElementById('posts').innerHTML = posts[channel] || '<p>No hay publicaciones.</p>';
        }
    </script>
</body>
</html>
