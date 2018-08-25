<?php
  $menus = Request::get('menu');
?>
@foreach($menus as $menu)
	@if($menu["parent_id"] == 0)
          @include('layouts.childs_menus', ["menus" => $menus, "menu" => $menu])
    @endif
@endforeach
