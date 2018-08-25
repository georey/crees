<?php
  $menus = Request::get('menu');
?>
@foreach ($menus as $menu)
          @include('layouts.childs_menus', ["menu" => $menu, "parent_id" => 0])
@endforeach
