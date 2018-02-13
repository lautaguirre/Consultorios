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
                    for(pdatai=0 ; pdatai < pdata.length ; pdatai++){
                        $('#price'+(pdatai+1)).html(pdata[pdatai].price);
                        $('#pricedesc'+(pdatai+1)).html(pdata[pdatai].desc);
                        $('#pricetitle'+(pdatai+1)).html(pdata[pdatai].title);
                    }
                }
            );

            //Show new values in DOM
            $(".datastate").keyup(function(e){
                $("#"+e.target.id).html($(this).val());
            });

            //Send new values to db
            $('.dataform').submit(function(e){
                thisform=this;
                e.preventDefault();
                $.post(
                    '../scripts/updatepricingdata.php',
                    $(this).serialize(),
                    function(){
                        $('#dataupdated').html('<div class="alert alert-success"><strong>Exito!</strong> Informacion actualizada.</div>');
                        $(thisform).each(function() {this.reset();} );
                    }       
                );
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

        <!-- Footer --> 
        <script src="../templates/footer.js"></script>
</body>
</html>