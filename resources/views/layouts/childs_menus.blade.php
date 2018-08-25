<li @if($menu['childs']>0)
      class="treeview"
    @endif>
  <a href="#" title="{{$menu['descripcion']}}">
    <i class="fa fa-{{$menu['icono']}}"></i> <span>{{$menu['nombre']}}</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  @if($menu["childs"]>0)
    <ul>
        @foreach($menu as $child)
            @if($child["id"]==$parent_id)
            @include('layouts.childs_menus', ["menu" => $child, "parent_id" => $child["id"]])
        @endforeach
    </ul>
  @endif
</li>