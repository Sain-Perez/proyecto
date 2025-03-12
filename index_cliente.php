<?php
    include_once 'conexion.php';

    $sentencia_selelct = $con->prepare('
        SELECT cliente.id, cliente.nombre, cliente.apellido, cliente.id_ciudad, cliente.genero, ciudad.nombre AS ciudad_nombre 
        FROM cliente 
        JOIN ciudad ON cliente.id_ciudad = ciudad.id 
        ORDER BY cliente.id DESC
    ');

    $sentencia_selelct->execute();
    $resultado=$sentencia_selelct->fetchAll();
    
    if(isset($_POST['btn_buscar'])){
        $buscar_text=$_POST['buscar'];
        $select_buscar = $con->prepare('
            SELECT cliente.id, cliente.nombre, cliente.apellido, cliente.id_ciudad, cliente.genero, ciudad.nombre AS ciudad_nombre 
            FROM cliente 
            JOIN ciudad ON cliente.id_ciudad = ciudad.id
            WHERE cliente.nombre LIKE :campo OR cliente.id LIKE :campo
        ');

        $buscar_text = "%" . $buscar_text . "%";

        $select_buscar->execute(array(':campo' => $buscar_text));

        $resultado=$select_buscar->fetchAll();

    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="contenedor">
        <h2>CLIENTES</h2>
        <div class="barra_buscador">
            <form action="" class="formulario" method="post">
                <input type="text" name="buscar" placeholder="Buscar Cliente" value="<?php if(isset($buscar_text)) echo $buscar_text; ?>" class="input_text">
                <input type="submit" class="btn" name="btn_buscar" value="Buscar">
                <a href="insert_cliente.php" class="btn btn_nuevo">Nuevo</a>
            </form>
        </div>
        <table>
            <tr class="head">
                <td>documento</td>
                <td>Nombre</td>
                <td>Apellido</td>
                <td>Ciudad</td>
                <td>Genero</td>
                <td colspan="2">Acción</td>
            </tr>
            <?php foreach($resultado as $fila):?>
                <tr>
                    <td><?php echo $fila['id']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['ciudad_nombre']; ?></td>
                    <td><?php echo $fila['genero']; ?></td>

                    <td><a href="update_cliente.php?id=<?php echo $fila['id']; ?>" class="btn_update">Editar</a></td>
                    <td><a href="delete_cliente.php?id=<?php echo $fila['id']; ?>" class="btn_delete">Eliminar</a></td>
                </tr>
            <?php endforeach ?>

        </table>
    </div>
</body>
</html>