<?php
    session_start();

    //Check if admin
    require '../scripts/checkadminsession.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Administracion de contenido</title>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/index.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            //Get pricing data
            $.post(
                '../scripts/getpricingdata.php',
                function(data){
                    pdata=JSON.parse(data);
                    for(imgdatai=0 ; imgdatai < pdata.length ; imgdatai++){
                        $('#price'+(imgdatai+1)).html(pdata[imgdatai].price);
                        $('#pricedesc'+(imgdatai+1)).html(pdata[imgdatai].desc);
                        $('#pricetitle'+(imgdatai+1)).html(pdata[imgdatai].title);
                    }
                }
            );

            //Show new values in DOM
            $(".datastate").keyup(function(e){
                if(e.keyCode != 13){
                    $("#"+e.target.id).html($(this).val());
                }
            });
            $('.imagestate').keyup(function(e){
                if(e.keyCode != 13){
                    $('#'+e.target.id).html($(this).val());
                }
            });

            //Send new data values to db
            $('.dataform').submit(function(e){
                thisform=this;
                e.preventDefault();
                $.post(
                    '../scripts/updatepricingdata.php',
                    $(this).serialize(),
                    function(data){
                        $('#dataupdated').html('<div class="alert alert-success"> '+data+'</div>');
                        $(thisform).each(function() {this.reset();} );
                    }       
                );
            });

            //Get images data
            $.post(
                '../scripts/getimagesdata.php',
                function(data){
                    imgdata=JSON.parse(data);
                    for(imgdatai=0 ; imgdatai < imgdata.length ; imgdatai++){
                        $('#imagedesc'+(imgdatai+1)).html(imgdata[imgdatai].imgdesc);
                    }
                }
            );

            //New image submit
            $('.imageform').submit(function(e){
                thisform=this;
                e.preventDefault();
                var filedata = new FormData();
                checknumber=$(this).find('input[type=text]').attr('name');
                $.each($(this).find('[name=imgfile]')[0].files, function(i, file) {
                    filedata.append('file-'+i, file);
                    filedata.append('filepos', checknumber); //Use textfield name number to set file number
                });
                $('#progress').html(`<div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="25"
                        aria-valuemin="0" aria-valuemax="100" style="width:25%">
                        <span>25% Completado</span>
                    </div>
                </div>`);
                $.ajax({
                    url: '../scripts/uploadimagetoserver.php',
                    data: filedata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success: function(data){
                        $(thisform).parent().find('img').attr('src', $(thisform).parent().find('img').attr('src') + '?' + new Date().getTime());
                        uploaddata=data;
                        $('#progress').html(`<div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="75"
                                aria-valuemin="0" aria-valuemax="100" style="width:75%">
                                <span>75% Completado</span>
                            </div>
                        </div>`);
                        $.post(
                            '../scripts/updateimagedata.php',
                            $(thisform).serialize(),
                            function(data){
                                $('#imgupdated').html('<div class="alert alert-success"> '+data+' '+uploaddata+'</div>');
                                $(thisform).each(function() {this.reset();} );
                                $('#progress').html(`<div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        <span>100% Completado</span>
                                    </div>
                                </div>`);
                            }
                        );
                    }
                });
            });

        });
    </script>
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <!-- Navbar section-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header navbar-left">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" id='collapseitems' href="#myPage">Consultorios Villa Martina</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="../index.php" id='collapseitems'>INICIO</a>
                        </li>
                        <li>
                            <a href="login.php" id="collapseitems" class="alterlogo">
                                <span class="glyphicon glyphicon-user"></span>
                                PANEL DE USUARIO
                            </a>
                        </li>
                        <li>
                            <a class="alterlogo2" href="../scripts/logout.php">
                                <span class="glyphicon glyphicon-log-out"></span>
                                CERRAR SESION
                            </a>
                        </li>
                        <?php
                            require '../scripts/showadminbutton.php';
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Price modification -->
        <div class="container-fluid">
            <div class='container'>
                <div class="text-center">
                    <h2>PRECIOS</h2>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h1 id='pricetitle1' ></h1>
                            </div>
                            <div class="panel-body">
                                <p id='pricedesc1' ></p>
                            </div>
                            <div class="panel-footer">
                                <h3 id='price1' ></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h1 id='pricetitle2' ></h1>
                            </div>
                            <div class="panel-body">
                                <p id='pricedesc2' ></p>
                            </div>        
                            <div class="panel-footer">
                                <h3 id='price2' ></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h1 id='pricetitle3' ></h1>
                            </div>
                            <div class="panel-body">
                                <p id='pricedesc3' ></p>              
                            </div>
                            <div class="panel-footer">
                                <h3 id='price3' ></h3>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class='container'>
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <form class='dataform'>
                            <div class="form-group">
                                <input type="text" name="datatitle1" id='pricetitle1' placeholder='Titulo' class="form-control datastate" >
                            </div>
                            <div class="form-group">
                                <textarea name="datadesc1" id='pricedesc1' placeholder='Descripcion' class="form-control datastate" ></textarea>
                            </div>
                            <div class="form-group">
                                <input type="number" name="dataprice1" id='price1' placeholder='Precio' class="form-control datastate" >
                            </div>
                            <button type="submit" class='btn btn-success'>Actualizar primer panel</button>
                        </form>
                    </div>
                    <div class="col-sm-4 text-center">
                        <form class='dataform' >
                            <div class="form-group">
                                <input type="text" name="datatitle2" id='pricetitle2' placeholder='Titulo' class="form-control datastate" >
                            </div>
                            <div class="form-group">
                                <textarea name="datadesc2" id='pricedesc2' placeholder='Descripcion' class="form-control datastate" ></textarea>
                            </div>
                            <div class="form-group">
                                <input type="number" name="dataprice2" id='price2' placeholder='Precio' class="form-control datastate" >
                            </div>
                            <button type="submit" class='btn btn-success'>Actualizar segundo panel</button>
                        </form>
                        &nbsp;
                        <div id='dataupdated'></div>
                    </div>
                    <div class="col-sm-4 text-center">
                        <form class='dataform' >
                            <div class="form-group">
                                <input type="text" name="datatitle3" id='pricetitle3' placeholder='Titulo' class="form-control datastate" >
                            </div>
                            <div class="form-group">
                                <textarea name="datadesc3" id='pricedesc3' placeholder='Descripcion' class="form-control datastate" ></textarea>
                            </div>
                            <div class="form-group">
                                <input type="number" name="dataprice3" id='price3' placeholder='Precio' class="form-control datastate" >
                            </div>
                            <button type="submit" class='btn btn-success'>Actualizar tercer panel</button>
                        </form>             
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Image modification -->
        <div class="container-fluid text-center bg-success">
            <div class='container'>
                <h2>IMAGENES</h2>
                <div class="row text-center">
                    <div class="col-sm-4">
                        <div class="thumbnail">
                            <img src="../images/interior1.jpg" class='flatbottomrounded' width="400" height="300">
                            <p>
                                <strong id='imagedesc1' ></strong>
                            </p>
                            <form  class='imageform'>
                                <div class="form-group">
                                    <input name='imgdesc1' type="text" placeholder='Descripcion' id='imagedesc1' class='form-control imagestate'>
                                </div>
                                <div class='form-group'>
                                    <input name='imgfile' type="file" class="form-control-file btn" >
                                </div>
                                <button type='submit' class='btn btn-success'>Actualizar primer panel</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="thumbnail">
                            <img src="../images/interior2.jpg" class='flatbottomrounded' width="400" height="300">
                            <p>
                                <strong id='imagedesc2'></strong>
                            </p>
                            <form class='imageform'>
                                <div class="form-group">
                                    <input name='imgdesc2' type="text" placeholder='Descripcion' id='imagedesc2' class='form-control imagestate'>
                                </div>
                                <div class='form-group'>
                                    <input name='imgfile' type="file" class="form-control-file btn" >
                                </div>
                                <button type='submit' class='btn btn-success'>Actualizar segundo panel</button>
                            </form>
                        </div>
                        <div id='imgupdated'></div>
                        <div id='progress'></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="thumbnail">
                            <img src="../images/interior3.jpg" class='flatbottomrounded' width="400" height="300">
                            <p>
                                <strong id='imagedesc3'></strong>
                            </p>
                            <form class='imageform'>
                                <div class="form-group">
                                    <input name='imgdesc3' type="text" placeholder='Descripcion' id='imagedesc3' class='form-control imagestate'>
                                </div>
                                <div class='form-group'>
                                    <input name='imgfile' type="file" class="form-control-file btn" >
                                </div>
                                <button type='submit' class='btn btn-success'>Actualizar tercer panel</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-sm-4">
                        <div class="thumbnail">
                            <img src="../images/interior4.jpg" class='flatbottomrounded' width="400" height="300">
                            <p>
                                <strong id='imagedesc4'></strong>
                            </p>
                            <form class='imageform'>
                                <div class="form-group">
                                    <input name='imgdesc4' type="text" placeholder='Descripcion' id='imagedesc4' class='form-control imagestate'>
                                </div>
                                <div class='form-group'>
                                    <input name='imgfile' type="file" class="form-control-file btn" >
                                </div>
                                <button type='submit' class='btn btn-success'>Actualizar cuarto panel</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="thumbnail">
                            <img src="../images/interior5.jpg" class='flatbottomrounded' width="400" height="300">
                            <p>
                                <strong id='imagedesc5'></strong>
                            </p>
                            <form class='imageform' >
                                <div class="form-group">
                                    <input name='imgdesc5' type="text" placeholder='Descripcion' id='imagedesc5' class='form-control imagestate'>
                                </div>
                                <div class='form-group'>
                                    <input name='imgfile' type="file" class="form-control-file btn" >
                                </div>
                                <button  type='submit' class='btn btn-success'>Actualizar quinto panel</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="thumbnail">
                            <img src="../images/interior6.jpg" class='flatbottomrounded' width="400" height="300">
                            <p>
                                <strong id='imagedesc6'></strong>
                            </p>
                            <form class='imageform'>
                                <div class="form-group">
                                    <input name='imgdesc6' type="text" placeholder='Descripcion' id='imagedesc6' class='form-control imagestate'>
                                </div>
                                <div class='form-group'>
                                    <input name='imgfile' type="file" class="form-control-file btn" >
                                </div>
                                <button type='submit' class='btn btn-success'>Actualizar sexto panel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <!-- Footer --> 
        <script src="../templates/footer.js"></script>
</body>
</html>