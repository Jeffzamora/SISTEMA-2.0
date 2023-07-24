<?php
    /* TODO: Rol 1 es de Usuario - fama*/
    if ($_SESSION["rol_id"]==1){
        ?>
            <nav class="side-menu">
                <ul class="side-menu-list">
                    <li class="blue-dirty">
                        <a href="..\Home\">
                            <span class="glyphicon glyphicon-home"></span>
                            <span class="lbl">Inicio</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\NuevaRemision\">
                            <span class="glyphicon glyphicon-list-alt"></span>
                            <span class="lbl">Nueva Remision</span>
                        </a>
                    </li>
                    
                    <li class="blue-dirty">
                        <a href="..\PreRemision\">
                            <span class="glyphicon glyphicon-list"></span>
                            <span class="lbl">Remision en Sucursal</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\ConsultarRemision\">
                            <span class="glyphicon glyphicon-check"></span>
                            <span class="lbl">Consultar Remision</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php
        }else{
        ?>
            <nav class="side-menu">
                <ul class="side-menu-list">
                    <li class="blue-dirty">
                        <a href="..\Home\">
                            <span class="glyphicon glyphicon-home"></span>
                            <span class="lbl">Inicio</span>
                        </a>
                    </li>

                    <li class="green with-sub">
	                        <span>
	                            <i class="font-icon font-icon-widget"></i>
	                                <span class="lbl">Remisiones</span>
	                        </span>
	                    <ul>
                            <li class="blue-dirty">
                                <a href="..\NuevaRemision\">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <span class="lbl">Nueva Remision</span>
                                </a>
                            </li>

                            <li class="blue-dirty">
                                <a href="..\PreRemision\">
                                    <span class="glyphicon glyphicon-list"></span>
                                    <span class="lbl">Remision en Sucursal</span>
                                </a>
                            </li>

                            <li class="blue-dirty">
                                <a href="..\ConsultarRemision\">
                                    <span class="glyphicon glyphicon-check"></span>
                                    <span class="lbl">Consultar Remision</span>
                                </a>
                            </li>
	                    </ul>
	                </li>

                    <li class="blue-dirty">
                        <a href="..\NuevaCaja\">
                            <span class="glyphicon glyphicon-folder-close"></span>
                            <span class="lbl">Nueva Caja</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\NuevoExpediente\">
                            <span class="glyphicon glyphicon-folder-open"></span>
                            <span class="lbl">Nuevo Expediente</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\MntUsuario\">
                            <span class="glyphicon glyphicon-user"></span>
                            <span class="lbl">Usuarios</span>
                        </a>
                    </li>

                    <li class="blue-dirty">
                        <a href="..\Sucursal\">
                            <span class="glyphicon glyphicon-globe"></span>
                            <span class="lbl">Sucursal</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php
    }
?>