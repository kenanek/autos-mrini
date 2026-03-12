@extends('layouts.public')
@section('title', 'Todos los Vehículos')
@push('styles')
<style>
    .page-hero { padding: 80px 0 60px; background: var(--surface); border-bottom: 1px solid var(--border); }
    .page-hero h1 { font-size: 44px; margin-bottom: 12px; letter-spacing: -0.03em; }
    .page-hero p { color: var(--text-muted); font-size: 16px; }
    .k-layout { display: grid; grid-template-columns: 280px 1fr; gap: 40px; padding: 48px 0 80px; }
    .k-filters { background: var(--surface); padding: 28px; border-radius: var(--radius-lg); border: 1px solid var(--border); align-self: start; position: sticky; top: 90px; }
    .k-filters h3 { font-size: 18px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
    .fg { margin-bottom: 20px; }
    .fg label { display: block; font-size: 12px; font-weight: 700; margin-bottom: 8px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; }
    .fc { width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: var(--primary); color: var(--text-main); transition: all 0.2s; }
    .fc:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    .fc.loading { opacity: 0.5; pointer-events: none; }
    .vehicle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 28px; }
    .results-bar { margin-bottom: 28px; padding: 14px 18px; background: var(--surface); border-radius: var(--radius-md); border: 1px solid var(--border); font-size: 14px; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center; }
    .empty-results { background: var(--surface); padding: 80px 40px; text-align: center; border-radius: var(--radius-lg); border: 1px solid var(--border); }
    .filter-reset { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #ef4444; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); padding: 10px 18px; border-radius: var(--radius-md); width: 100%; justify-content: center; transition: all 0.2s; margin-top: 12px; }
    .filter-reset:hover { background: rgba(239,68,68,0.2); border-color: rgba(239,68,68,0.4); }
    .filter-count { color: var(--text-muted); font-weight: 400; font-size: 12px; }
    .active-filters { display: flex; flex-wrap: wrap; gap: 8px; }
    .active-tag { display: inline-flex; align-items: center; gap: 6px; background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.25); color: var(--accent); padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; }
    .active-tag .remove-tag { cursor: pointer; opacity: 0.7; font-size: 14px; }
    .active-tag .remove-tag:hover { opacity: 1; }
    @media (max-width: 900px) {
        .k-layout { grid-template-columns: 1fr; }
        .k-filters { position: static; }
        .vehicle-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Nuestros Vehículos</h1>
        <p>Descubre nuestro inventario premium de coches de ocasión y seminuevos verificados.</p>
    </div>
</div>

<div class="container">
    <div class="k-layout">
        <aside class="k-filters">
            <h3><i class="icon-sliders" style="color:var(--accent); font-size:18px;"></i> Filtros</h3>
            <form id="filterForm" action="{{ route('vehicles.index') }}" method="GET">
                <div class="fg">
                    <label>Marca</label>
                    <select name="brand_id" id="f-brand" class="fc">
                        <option value="">Todas las marcas</option>
                        @foreach($brands as $b)
                            <option value="{{ $b['id'] }}" {{ request('brand_id') == $b['id'] ? 'selected' : '' }}>{{ $b['name'] }} ({{ $b['count'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Modelo</label>
                    <select name="car_model_id" id="f-model" class="fc">
                        <option value="">Todos los modelos</option>
                        @foreach($models as $m)
                            <option value="{{ $m['id'] }}" {{ request('car_model_id') == $m['id'] ? 'selected' : '' }}>{{ $m['name'] }} ({{ $m['count'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Año Mínimo</label>
                    <select name="year_min" id="f-year" class="fc">
                        <option value="">Cualquier año</option>
                        @foreach($years as $y)
                            <option value="{{ $y['value'] }}" {{ request('year_min') == $y['value'] ? 'selected' : '' }}>{{ $y['value'] }} ({{ $y['count'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Combustible</label>
                    <select name="fuel_type" id="f-fuel" class="fc">
                        <option value="">Todos</option>
                        @foreach($fuelTypes as $ft)
                            <option value="{{ $ft['value'] }}" {{ request('fuel_type') == $ft['value'] ? 'selected' : '' }}>{{ $ft['label'] }} ({{ $ft['count'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Transmisión</label>
                    <select name="transmission" id="f-trans" class="fc">
                        <option value="">Todas</option>
                        @foreach($transmissions as $t)
                            <option value="{{ $t['value'] }}" {{ request('transmission') == $t['value'] ? 'selected' : '' }}>{{ $t['label'] }} ({{ $t['count'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Precio Máximo</label>
                    <select name="price_max" id="f-price" class="fc">
                        <option value="">Sin límite</option>
                        @foreach($priceSteps as $ps)
                            <option value="{{ $ps['value'] }}" {{ request('price_max') == $ps['value'] ? 'selected' : '' }}>{{ $ps['label'] }} ({{ $ps['count'] }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">Aplicar Filtros</button>
                @if($hasActiveFilters)
                    <a href="{{ route('vehicles.index') }}" class="filter-reset"><i class="icon-x" style="font-size:14px;"></i> Limpiar filtros</a>
                @endif
            </form>
        </aside>
        
        <main>
            <div class="results-bar">
                <span>Mostrando {{ $vehicles->firstItem() ?? 0 }} – {{ $vehicles->lastItem() ?? 0 }} de {{ $vehicles->total() }} vehículos</span>
                @if($hasActiveFilters)
                    <a href="{{ route('vehicles.index') }}" style="color:var(--accent); font-size:13px; font-weight:600; display:flex; align-items:center; gap:4px;"><i class="icon-x" style="font-size:12px;"></i> Limpiar</a>
                @endif
            </div>
            
            @if($vehicles->count() > 0)
                <div class="vehicle-grid">
                    @foreach($vehicles as $vehicle)
                        @include('components.vehicle-card', ['vehicle' => $vehicle])
                    @endforeach
                </div>
                @if($vehicles->hasPages())
                <div style="margin-top: 48px;">
                    {{ $vehicles->links() }}
                </div>
                @endif
            @else
                <div class="empty-results">
                    <i class="icon-search" style="font-size: 40px; color: var(--text-muted); margin-bottom: 16px; display: block;"></i>
                    <h3 style="margin-bottom: 12px;">No se encontraron vehículos</h3>
                    <p style="color: var(--text-muted); margin-bottom: 24px;">Intenta ajustar los filtros para ver más resultados.</p>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-primary">Ver todos los vehículos</a>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var API = "{{ route('api.vehicle-filters') }}";
    var selects = {
        brand: document.getElementById('f-brand'),
        model: document.getElementById('f-model'),
        year:  document.getElementById('f-year'),
        fuel:  document.getElementById('f-fuel'),
        trans: document.getElementById('f-trans'),
        price: document.getElementById('f-price'),
    };

    function getParams() {
        var p = {};
        if (selects.brand.value) p.brand_id = selects.brand.value;
        if (selects.model.value) p.car_model_id = selects.model.value;
        if (selects.year.value)  p.year_min = selects.year.value;
        if (selects.fuel.value)  p.fuel_type = selects.fuel.value;
        if (selects.trans.value) p.transmission = selects.trans.value;
        if (selects.price.value) p.price_max = selects.price.value;
        return p;
    }

    function rebuildSelect(el, options, currentVal, defaults) {
        var defaultText = defaults[el.id] || '';
        el.innerHTML = '';
        var opt0 = document.createElement('option');
        opt0.value = ''; opt0.textContent = defaultText;
        el.appendChild(opt0);

        var stillValid = false;
        options.forEach(function(item) {
            var opt = document.createElement('option');
            var val = String(item.value || item.id || item);
            var label = item.label || item.name || item.value || item.id || item;
            var count = item.count;
            opt.value = val;
            opt.textContent = count !== undefined ? label + ' (' + count + ')' : label;
            if (val === String(currentVal)) { opt.selected = true; stillValid = true; }
            el.appendChild(opt);
        });
        if (!stillValid) el.value = '';
    }

    var defaults = {
        'f-brand': 'Todas las marcas',
        'f-model': 'Todos los modelos',
        'f-year':  'Cualquier año',
        'f-fuel':  'Todos',
        'f-trans': 'Todas',
        'f-price': 'Sin límite',
    };

    var fetchTimer = null;

    function refreshFilters(changedField) {
        clearTimeout(fetchTimer);
        fetchTimer = setTimeout(function() {
            var params = getParams();
            var qs = new URLSearchParams(params).toString();
            Object.values(selects).forEach(function(s) { s.classList.add('loading'); });

            fetch(API + '?' + qs)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var cv = {
                        brand: selects.brand.value, model: selects.model.value,
                        year: selects.year.value, fuel: selects.fuel.value,
                        trans: selects.trans.value, price: selects.price.value,
                    };
                    if (changedField !== 'brand') rebuildSelect(selects.brand, data.brands, cv.brand, defaults);
                    if (changedField !== 'model') rebuildSelect(selects.model, data.models, cv.model, defaults);
                    if (changedField !== 'year')  rebuildSelect(selects.year, data.years, cv.year, defaults);
                    if (changedField !== 'fuel')  rebuildSelect(selects.fuel, data.fuelTypes, cv.fuel, defaults);
                    if (changedField !== 'trans') rebuildSelect(selects.trans, data.transmissions, cv.trans, defaults);
                    if (changedField !== 'price') rebuildSelect(selects.price, data.priceSteps, cv.price, defaults);
                })
                .finally(function() {
                    Object.values(selects).forEach(function(s) { s.classList.remove('loading'); });
                });
        }, 80);
    }

    selects.brand.addEventListener('change', function() { refreshFilters('brand'); });
    selects.model.addEventListener('change', function() { refreshFilters('model'); });
    selects.year.addEventListener('change',  function() { refreshFilters('year'); });
    selects.fuel.addEventListener('change',  function() { refreshFilters('fuel'); });
    selects.trans.addEventListener('change', function() { refreshFilters('trans'); });
    selects.price.addEventListener('change', function() { refreshFilters('price'); });
})();
</script>
@endpush