<li>
  <div class="collapsible-header"><i class="material-icons">perm_identity</i>Personnels</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('PersonnelsController@getIndex') }}">Personnels</a>
      <a href="{{ URL::action('PersonnelsController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a href="{{ URL::action('PersonnelsController@getGpu') }}">GPU</a>
    </div>
    <div class="collection-item">
      <a href="{{ URL::action('PersonnelsController@getPrivateGpu') }}">Private GPU</a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('PelanggaranController@getIndex') }}">Pelanggaran</a>
      <a href="{{ URL::action('PelanggaranController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('CutiController@getIndex') }}">Cuti</a>
      <a href="{{ URL::action('CutiController@getCreate') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a href="{{ URL::action('GajiController@getIndex') }}">Gaji</a>
    </div>
    <div class="collection-item">
      <a href="{{ URL::action('GajiController@getPrivate') }}">Private Gaji</a>
    </div>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">build</i>Produksi</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ProduksiController@getProduksiList') }}">Produksi</a>
      <a href="{{ URL::action('ProduksiController@getCreateProduksi') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a href="{{ URL::action('SetoranController@getIndex') }}">Setoran</a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ProduksiController@getPotongList') }}">Potong</a>
      <a href="{{ URL::action('ProduksiController@getCreatePotong') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
    <div class="collection-item">
      <a class="left" href="{{ URL::action('ProduksiController@getJahitList') }}">Jahit</a>
      <a href="{{ URL::action('ProduksiController@getCreateJahit') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
<li>
  <div class="collapsible-header"><i class="material-icons">perm_identity</i>Borongan</div>
  <div class="collapsible-body collection">
    <div class="collection-item">
      <a class="left" href="{{ URL::action('BoronganController@getIndex') }}">Borongan</a>
      <a href="{{ URL::action('BoronganController@getAdd') }}" class="secondary-content right"><i class="material-icons">add_box</i></a>
    </div>
  </div>
</li>
