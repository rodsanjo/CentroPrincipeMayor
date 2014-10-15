<!-- Fixed navbar -->
<div id="menu_up" class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <?php 
            $controlador = \core\Configuracion::$controlador_por_defecto;
            $metodo = \core\Configuracion::$metodo_por_defecto;
            $href = core\URL::generar($controlador.'/'.$metodo);
            echo "
                <a class='navbar-brand' href='$href'>Centro Príncipe Mayor</a>
                ";
        ?>
    </div>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            
            <?php
        $menu = \modelos\Menus::get_menuUp();
        foreach ($menu as $key => $item) {
            if(!is_array($item)){
                $item = explode(",", $item);
                $href = \core\URL::generar("$item[0]/$item[1]");
                $title = $item[2]; //ucfirst( iText($item[2], 'dicc') );
                $texto = $key; //ucfirst(\core\Idioma::text($key, 'dicc'));
                $class = ''; //Reiniciamos la varailble class
                if($item[0]== core\Controlador::get_controlador_instanciado())
                    $class = 'active';
                $class = $class.' item';
                echo"
                    <li class='$class' title='$title'>
                        <a href='$href'>$texto</a>
                    </li>
                    ";
            }else{
                $class = ''; //Reiniciamos la varailble class
                if($key == core\Controlador::get_controlador_instanciado())
                    $class = 'active';
                else
                    $class = '';
    ?>
                <li class='dropdownitem'>
                    <a href="#" class="dropdown-toggle <?php echo $class ?>" data-toggle="dropdown"><?php echo $key ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        foreach ($item as $key => $subitem) {
                            $subitem = explode (",", $subitem);
                            $href = \core\URL::generar("$subitem[0]/$subitem[1]");
                            $title = $subitem[2]; //ucfirst( iText($item[2], 'dicc') );
                            $texto = $key; //ucfirst(\core\Idioma::text($key, 'dicc'));
                            echo "
                                <li class='subitem' title='$title'>
                                    <a href='$href'>$texto</a>
                                </li>
                                ";
                        }

                        ?>                                
                    </ul>                            
                </li>
<?php 
            }
        }
?>          
        </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>
