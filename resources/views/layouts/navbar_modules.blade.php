@if($access->can('create',Apps::PRODUCTIONS) && $access->can('index',Apps::PRODUCTIONS))
	<li id="produksi-create"><a href="{{ URL::action('ProductionsController@getCreate') }}"><span class="icon-plus">&nbsp;</span>Produksi</a></li>
@endif