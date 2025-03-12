<?php
    include_once 'conexion.php';

    if (isset($_GET['id'])) {
       
        $id = (Int) $_GET['id'];
        $buscar_id = $con->prepare('SELECT * FROM cliente WHERE id=:id');
        $buscar_id->execute(array(':id' => $id));
        $resultado = $buscar_id->fetch();
    } 
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $id_ciudad = $_POST['id_ciudad'];
        $genero = $_POST['genero'];
    
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            
            $id = (int) $_POST['id'];
            $consulta_update = $con->prepare('UPDATE cliente SET 
                nombre=:nombre, 
                apellido=:apellido, 
                id_ciudad=:id_ciudad, 
                genero=:genero
                WHERE id=:id');
            $consulta_update->execute(array(
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':id_ciudad' => $id_ciudad,
                ':genero' => $genero,
                ':id' => $id
            ));
            header('Location: index_cliente.php');
            exit();
        } else {
            
            $consulta_insert = $con->prepare('INSERT INTO cliente (nombre, apellido, id_ciudad, genero) 
                VALUES (:nombre, :apellido, :id_ciudad, :genero)');
            $consulta_insert->execute(array(
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':id_ciudad' => $id_ciudad,
                ':genero' => $genero
            ));
            header('Location: index_cliente.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($resultado) ? 'Editar Información' : 'Registrar Cliente'; ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="contenedor">
        <h2><?php echo isset($resultado) ? 'Editar Información' : 'Registrar Cliente'; ?></h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo isset($resultado) ? $resultado['id'] : ''; ?>">

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" value="<?php echo isset($resultado) ? $resultado['nombre'] : ''; ?>" class="input_text" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" value="<?php echo isset($resultado) ? $resultado['apellido'] : ''; ?>" class="input_text" required>
            </div>
            <div class="form-group">
                <label for="id_ciudad">Ciudad:</label>
                <select name="id_ciudad" id="id_ciudad" class="input_text" required>
                    <option value="">Seleccionar Ciudad</option>
                    <?php
                    $sql = "SELECT id, nombre FROM ciudad ORDER BY nombre ASC";
                    $result = $con->query($sql);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($resultado) && $resultado['id_ciudad'] == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Género:</label><br>
                <div class="genero-group">
                <label>
                        <input type="radio" name="genero" value="F" <?php echo (isset($resultado) && $resultado['genero'] == 'F') ? 'checked' : ''; ?>> Femenino
                    </label>
                    <label>
                        <input type="radio" name="genero" value="M" <?php echo (isset($resultado) && $resultado['genero'] == 'M') ? 'checked' : ''; ?>> Masculino
                    </label>
                </div>
            </div>
            <div class="btn_group">
                <a href="index_cliente.php" class="btn btn_danger">Cancelar</a>
                <input type="submit" value="Guardar" class="btn btn_primary">
            </div>
        </form>
    </div>
</body>
</html>