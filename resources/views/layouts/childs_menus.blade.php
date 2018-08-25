<li @if($menu['childs']>0)
      class="treeview"
    @endif>
  <a href="{{url('/'.$menu['ruta'])}}" title="{{$menu['descripcion']}}">
    <i class="fa fa-{{$menu['icono']}}"></i> <span>{{$menu['nombre']}}</span> 
    @if($menu['childs']>0)
    <i class="fa fa-angle-left pull-right"></i>
    @endif
  </a>
  @if($menu["childs"]>0)
    <ul class="treeview-menu">
        @foreach($menus as $child)
            @if($child["parent_id"]==$menu["id"])
            @include('layouts.childs_menus', ["menus" => $menus, "menu" => $child])
            @endif
        @endforeach
    </ul>
  @endif
</li>