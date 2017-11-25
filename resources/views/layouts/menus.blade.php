<li>
  <a href="{{ url('/clientes') }}">
    <i class="fa fa-users"></i> <span>Clientes</span>
  </a>
</li>
<li class="treeview">
  <a href="#">
    <i class="fa fa-calculator"></i> <span>Prestamos</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li>
      <a href="{{ url('/pagos') }}"><i class="fa fa-calculator"></i> <span>Lista</span></a>
    </li>    
    <li>
      <a href="{{ url('/pagos/calculadora') }}"><i class="fa fa-calculator"></i> <span>Calculadora</span></a>
    </li>
  </ul>
</li>
<li class="treeview">
  <a href="#">
    <i class="fa fa-calculator"></i> <span>Caja</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">    
    <li>
      <a href="{{ url('/pagos/create') }}"><i class="fa fa-calculator"></i> <span>Abonos</span></a>
    </li>
    <li>
      <a href="{{ url('/caja/corte_caja') }}"><i class="fa fa-calculator"></i> <span>Corte de caja</span></a>
    </li>
  </ul>
</li>
<li class="treeview">
  <a href="#">
    <i class="fa fa-calculator"></i> <span>Cobros</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li>
      <a href="{{ url('/cobros/colectas_saldos') }}"><i class="fa fa-calculator"></i> <span>Colectas y saldos</span></a>
    </li>    
    <li>
      <a href="{{ url('/pagos/create') }}"><i class="fa fa-calculator"></i> <span>Creditos por vencer</span></a>
    </li>
    <li>
      <a href="{{ url('/pagos/create') }}"><i class="fa fa-calculator"></i> <span>Cuadro de mora</span></a>
    </li>
    <li>
      <a href="{{ url('/pagos/create') }}"><i class="fa fa-calculator"></i> <span>Nota de cobro</span></a>
    </li>
    <li>
      <a href="{{ url('/configuracion/prestamos') }}"><i class="fa fa-calculator"></i> <span>Asesores y Cobradores</span></a>
    </li>

  </ul>
</li>
<li>
  <a href="{{ url('/reportes') }}">
    <i class="fa fa-pie-chart"></i> <span>Reportes</span>
  </a>
</li>
<li class="treeview">
  <a href="#">
    <i class="fa fa-dashboard"></i> <span>Catalogos</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li><a href="{{ url('/asesores') }}"><i class="fa fa-users"></i> Asesores</a></li>
    <li><a href="{{ url('/cobradores') }}"><i class="fa fa-users"></i> Cobradores</a></li>
    <li><a href="{{ url('/profesiones') }}"><i class="fa fa-list"></i> Profesiones</a></li>
    <li><a href="{{ url('/linea') }}"><i class="fa fa-exchange"></i> Lineas</a></li>
    <li><a href="{{ url('/zonas') }}"><i class="fa fa-map-o"></i> Zonas</a></li>
    <li><a href="{{ url('/estados') }}"><i class="fa fa-reorder"></i> Estados</a></li>
    <li><a href="{{ url('/tipo_negocio') }}"><i class="fa fa-reorder"></i> Tipos de negocio</a></li>
    <li><a href="{{ url('/tipo_gasto') }}"><i class="fa fa-reorder"></i> Tipos de gasto</a></li>
  </ul>
</li>
<li class="treeview">
  <a href="#">
    <i class="fa fa-gears"></i> <span>Sistema</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li><a href="{{ url('/usuarios') }}"><i class="fa fa-users"></i> Usuarios</a></li>
    <li><a href="{{ url('/roles') }}"><i class="fa fa-book"></i> Roles</a></li>
    <li><a href="{{ url('/permisos') }}"><i class="fa fa-unlock-alt"></i> Permisos</a></li>
  </ul>
</li>