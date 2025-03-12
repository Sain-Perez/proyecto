<?php

    include_once 'conexion.php';
    if(isset($_GET['id'])){
        $id=(int) $_GET['id'];
        $delete=$con->prepare('DELETE FROM cliente WHERE id=:id');
        $delete->execute(array(
            ':id'=>$id
        ));
        header('Location: index_cliente.php');
    }else{
        header('Location: index_cliente.php');
    }
?>