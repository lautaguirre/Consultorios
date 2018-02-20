<?php
    header('Content-Type: text/plain; charset=utf-8');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['filepos'])){
            for($i=1 ; $i<=6 ; $i++){
                if($_POST['filepos'] == 'imgdesc'.$i){
                    $idtochange=$i;
                    break;
                }
            }
        }
    }

    try {
        
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['file-0']['error']) ||
            is_array($_FILES['file-0']['error'])
        ) {
            throw new RuntimeException('No se subio imagen.');
        }
    
        // Check $_FILES['upfile']['error'] value.
        switch ($_FILES['file-0']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('Ninguna imagen enviada.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Limite de tamaño excedido.');
            default:
                throw new RuntimeException('Error desconocido.');
        }
    
        // You should also check filesize here. 
        if ($_FILES['file-0']['size'] > 16000000) {
            throw new RuntimeException('Limite de tamaño excedido.');
        }
    
        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['file-0']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
            ),
            true
        )) {
            throw new RuntimeException('Formato de imagen invalido (SOLO JPG).');
        }
    
        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        if (!move_uploaded_file(
            $_FILES['file-0']['tmp_name'],
            sprintf('../images/interior'.$idtochange.'.'.$ext,
                sha1_file($_FILES['file-0']['tmp_name']),
                $ext
            )
        )) {
            throw new RuntimeException('Falla al mover la imagen.');
        }
    
        echo 'Imagen subida exitosamente.';
    
    } catch (RuntimeException $e) {
    
        echo $e->getMessage();    

    }
?>