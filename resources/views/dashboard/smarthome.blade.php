<x-layouts.layout>

<div class="row">

<div class="col s12 m6">
<div class="card light-blue accent-1">
<div class="card-content">
  <span class="card-title">Balances</span>
<div class="table-responsive-vertical">
	<table class="table table-striped table-bordered table-hover">
		<thead><tr><th>Type</th><th>Piutang</th><th>Hutang</th><th>Total</th></tr></thead>
		<tbody>
			<tr><td colspan="4">test data</tr>
{{-- 
			<tr>
				<td data-title="Type">Customer</td>
				<td data-title="Piutang">{{ nf($hp->total_customer_piutang) }}</td>
				<td data-title="Hutang">{{ nf($hp->total_customer_hutang) }}</td>
				<td data-title="Total">{{ nf($hp->total_customer_hutang + $hp->total_customer_piutang) }}</td>
			</tr>
			<tr>
				<td data-title="Type">Reseller</td>
				<td data-title="Piutang">{{ nf($hp->total_reseller_piutang) }}</td>
				<td data-title="Hutang">{{ nf($hp->total_reseller_hutang) }}</td>
				<td data-title="Total">{{ nf($hp->total_reseller_hutang + $hp->total_reseller_piutang) }}</td>
			</tr>
			<tr>
				<td data-title="Type">Supplier</td>
				<td data-title="Piutang">{{ nf($hp->total_supplier_piutang) }}</td>
				<td data-title="Hutang">{{ nf($hp->total_supplier_hutang) }}</td>
				<td data-title="Total">{{ nf($hp->total_supplier_hutang + $hp->total_supplier_piutang) }}</td>
			</tr>
 --}}
		</tbody>
	</table>
</div>
</div>
</div>
</div>

<div class="col s12 m6">
<div class="card yellow accent-1">
<div class="card-content">
  <span class="card-title">Infos</span>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>Assets</td>
				<td>test data</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
</div>

</div>

<div class="row">

<div class="col s12 m6">
<div class="card teal accent-1">
<div class="card-content">
  <span class="card-title"><i class="material-icons">library_books</i> News</span>
  <p>Bye yahoo news</p>
</div>
</div>
</div>

<div class="col s12 m6">
<div class="card purple accent-1" ng-controller="forexController">
<div class="card-content">
  <span class="card-title"><i class="material-icons">attach_money</i> FOREX</span>
	<div class="table-responsive-vertical">
	<table class="table table-striped table-bordered table-hover">
		<thead><th>Currency</th><th>Rate</th><th>Ask</th><th>Bid</th></thead>
		<tbody>
			<tr ng-repeat="f in forexList">
				<td data-title="Currency">@{{ f.Name }}</td>
				<td data-title="Rate">@{{ f.Rate }}</td>
				<td data-title="Ask">@{{ f.Ask }}</td>
				<td data-title="Bid">@{{ f.Bid }}</td>
			</tr>
		</tbody>
	</table>
	</div>
</div>
</div>
</div>

</div>

<div class="row">
<md-card flex>
	<md-toolbar class="md-accent"><div class="md-toolbar-tools"><h3><md-icon aria-label="announcement icon">fiber_new</md-icon> Announcements</h3></div></md-toolbar>
		<ul>
			<li>FIX: HASH search</li>
			<li>NEW: Bank Account STATS</li>
			<li>NEW: Added sales in warehouse/customer/resellers (EXPERIMENTAL)</li>
			<li>MORE performance improvements</li>
			<li>MINI BUG FIXES</li>
		</ul>
</md-card>
</div>

<div flex layout="row" layout-xs="column" layout-fill>
<md-card flex>
	<md-toolbar class="md-primary md-hue-3"><div class="md-toolbar-tools"><h3><md-icon aria-label="notifications icon">notifications_active</md-icon> Notifications</h3></div></md-toolbar>
		<ul>
			<li>DISABLED FOR NOW</li>
		</ul>
</md-card>

<md-card flex>
  <md-toolbar class="md-primary"><div class="md-toolbar-tools"><h3><md-icon aria-label="adb icon">adb</md-icon> Advisor</h3></div></md-toolbar>
  <md-card-content>
      <h1>DISABLED FOR NOW!</h1>
  </md-card-content>
</md-card>
</div>

@push('script')

<script src="{{ asset('js/aria/controllers/forexCtrl.js') }}"></script>
<script src="{{ asset('js/aria/services/forexService.js') }}"></script>
<script src="{{ asset('js/aria/services/newsService.js') }}"></script>
	
@endpush

</x-layouts.layout>

