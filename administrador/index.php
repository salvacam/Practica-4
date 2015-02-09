<?php
require '../require/comun.php';

$dir = "..";
?>
<?php include "../include/headBack.php"; ?>
<section id="contenido">
    <div id="login" class="flex">
        <span id="tituloLogin">Login</span>
        <input id="nombreUser" type="text" name="nombreUser" placeholder="Nombre" tabindex="1" class="txtinput">                        
        <input id="clave" type="password" name="clave" placeholder="Contraseña" tabindex="2" class="txtinput">
        <input type="button" id="btLogin" value="Login" tabindex="3">
    </div>

    <div id='modal_dialog' class="oculto">
        <div id='MDtitle'>
        </div>
        <div id='MDcontent'>
        </div>
        <input type='button' value='Aceptar' id='btMYes' />
        <input type='button' value='Cancelar' id='btMNo' />
    </div>

    <div id="tabla" class="oculto">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <a id="insertar" href="#">Insertar</a>

    </div>

    <div id="formulario" class="oculto">
        <div id='formTitle'>
        </div>
        <div class="linea">
            <label for="nombre">Nombre</label>
            <input id="nombre" type="text" name="nombre" tabindex="1" class="forminput"> 
            <span id="nombreValido">Necesario un nombre</span>
        </div>

        <div class="linea">
            <label for="descripcion">Descripcion</label>
            <input id="descripcion" type="text" name="descripcion" tabindex="2" class="forminput">  
            <span id="descripcionValido">Necesario una descripcion</span>                              
        </div>

        <div class="linea">
            <label for="precio">Precio</label>
            <input id="precio" type="text" name="precio" tabindex="3" class="forminput">                        
            <span id="precioValido">Necesario un precio</span>                              
        </div>

        <div class="linea">
            <label for="imagen1">Imagen 1</label>
            <input id="imagen1" type="file" name="imagen1" tabindex="4" class="forminput img" accept="image/*" >                                                          
            <input id="reset1" data-id="imagen1" type="button" name="reset1" tabindex="4" class="forminput reset" value="Limpiar"> 
            <div class="preImg" id="preImg1">                
            </div>
        </div>
        <div class="linea">            
            <label for="ppal1">&nbsp;Imagen Principal</label>
            <input type="radio" name="ppal" id="ppal1" value="1" class="ppal">
        </div>        

        <div class="linea">
            <label for="imagen2">Imagen 2</label>
            <input id="imagen2" type="file" name="imagen2" tabindex="5" class="forminput img" accept="image/*">                                    
            <input id="reset2" data-id="imagen2" type="button" name="reset2" tabindex="5" class="forminput reset" value="Limpiar"> 
            <div class="preImg" id="preImg2"></div>
        </div>

        <div class="linea">
            <label for="ppal2">&nbsp;Imagen Principal</label>
            <input type="radio" name="ppal" id="ppal2" value="2" class="ppal">
        </div>

        <div class="linea">
            <label for="imagen3">Imagen 3</label>
            <input id="imagen3" type="file" name="imagen3" tabindex="6" class="forminput img" accept="image/*">                                    
            <input id="reset3" data-id="imagen3" type="button" name="reset3" tabindex="6" class="forminput reset" value="Limpiar"> 
            <div class="preImg" id="preImg3"></div>
        </div>

        <div class="linea">
            <label for="ppal3">&nbsp;Imagen Principal</label>
            <input type="radio" name="ppal" id="ppal3" value="3" class="ppal">
        </div>

        <div class="linea">
            <label for="imagen4">Imagen 4</label>
            <input id="imagen4" type="file" name="imagen4" tabindex="7" class="forminput img" accept="image/*">                                    
            <input id="reset4" data-id="imagen4" type="button" name="reset4" tabindex="7" class="forminput reset" value="Limpiar"> 
            <div class="preImg" id="preImg4"></div>
        </div>

        <div class="linea">
            <label for="ppal4">&nbsp;Imagen Principal</label>
            <input type="radio" name="ppal" id="ppal4" value="4" class="ppal">
        </div>
        <div id="btForm">
            <input type="button" id="btAceptar" value="Aceptar" tabindex="10">
            <input type="button" id="btCancelar" value="Cancelar" tabindex="9">
        </div>
    </div>

    <div id="mensaje" class="oculto"></div>
</section>
<?php include "../include/footerBack.php"; ?>

</body>

</html>
