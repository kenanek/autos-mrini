<style>
    .form-wrapper { padding: 32px; display: flex; flex-direction: column; gap: 40px; }
    .form-section { border-bottom: 1px solid #e2e8f0; padding-bottom: 32px; }
    .form-section:last-child { border-bottom: none; padding-bottom: 0px; }
    .form-section-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
    .form-section-title i { font-size: 20px; color: #3b82f6; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .form-label { display: block; margin-bottom: 10px; font-weight: 600; font-size: 14px; color: #1e293b; }
    .form-control { width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid #cbd5e1; background: white; color: #1e293b; font-family: inherit; font-size: 15px; transition: 0.2s; }
    .form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.1); }
    textarea.form-control { resize: vertical; min-height: 120px; }
    .image-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; margin-top: 24px; }
    .image-preview-item { position: relative; border-radius: 14px; overflow: hidden; background: #000; border: 3px solid transparent; transition: 0.2s; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
    .image-preview-item.is-primary { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.2); }
    .image-preview-item img { width: 100%; height: 130px; object-fit: cover; display: block; filter: brightness(0.9); transition: 0.3s; }
    .image-preview-item:hover img { filter: brightness(1.1); transform: scale(1.05); }
    .image-actions { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 12px; display: flex; justify-content: space-between; align-items: center; opacity: 1; transition: 0.2s; }
    .badge-primary-check { background: #3b82f6; color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
</style>

<div class="form-wrapper">

<!-- SECCIÓN 1: Información Principal -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-car"></i> Información Principal</div>
    <div class="form-grid">
        <div style="grid-column: 1 / -1;">
            <label class="form-label">Título del Anuncio *</label>
            <input type="text" name="title" value="{{ old('title', $vehicle->title ?? '') }}" class="form-control" required placeholder="Ej: BMW X5 xDrive30d M Sport">
        </div>
        <div>
            <label class="form-label">Marca *</label>
            <select name="brand_id" class="form-control" required>
                <option value="">Seleccionar Marca</option>
                @foreach($brands as $b) <option value="{{ $b->id }}" @if(old('brand_id', $vehicle->brand_id ?? '') == $b->id) selected @endif>{{ $b->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Modelo *</label>
            <select name="car_model_id" class="form-control" required>
                <option value="">Seleccionar Modelo</option>
                @foreach($models as $m) <option value="{{ $m->id }}" @if(old('car_model_id', $vehicle->car_model_id ?? '') == $m->id) selected @endif>{{ $m->name }}</option> @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Condición *</label>
            <select name="condition" class="form-control" required>
                <option value="used" @if(old('condition', $vehicle->condition ?? '')=='used') selected @endif>Ocasión / Usado</option>
                <option value="new" @if(old('condition', $vehicle->condition ?? '')=='new') selected @endif>Nuevo</option>
            </select>
        </div>
        <div>
            <label class="form-label">Año *</label>
            <input type="number" name="year" value="{{ old('year', $vehicle->year ?? '') }}" class="form-control" required min="1900" max="{{ date('Y')+1 }}">
        </div>
        <div>
            <label class="form-label">Kilometraje (km) *</label>
            <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage ?? '') }}" class="form-control" required min="0">
        </div>
        <div>
            <label class="form-label">VIN (Número de Bastidor)</label>
            <input type="text" name="vin" value="{{ old('vin', $vehicle->vin ?? '') }}" class="form-control" placeholder="17 caracteres">
        </div>
    </div>
</div>

<!-- SECCIÓN 2: Especificaciones Técnicas -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-settings-2"></i> Especificaciones Técnicas</div>
    <div class="form-grid">
        <div>
            <label class="form-label">Combustible *</label>
            <select name="fuel_type" class="form-control" required>
                <option value="diesel" @if(old('fuel_type', $vehicle->fuel_type ?? '')=='diesel') selected @endif>Diesel</option>
                <option value="gasoline" @if(old('fuel_type', $vehicle->fuel_type ?? '')=='gasoline') selected @endif>Gasolina</option>
                <option value="hybrid" @if(old('fuel_type', $vehicle->fuel_type ?? '')=='hybrid') selected @endif>Híbrido</option>
                <option value="electric" @if(old('fuel_type', $vehicle->fuel_type ?? '')=='electric') selected @endif>Eléctrico</option>
            </select>
        </div>
        <div>
            <label class="form-label">Transmisión *</label>
            <select name="transmission" class="form-control" required>
                <option value="automatic" @if(old('transmission', $vehicle->transmission ?? '')=='automatic') selected @endif>Automática</option>
                <option value="manual" @if(old('transmission', $vehicle->transmission ?? '')=='manual') selected @endif>Manual</option>
            </select>
        </div>
        <div>
            <label class="form-label">Carrocería</label>
            <select name="body_type" class="form-control">
                <option value="">Cualquiera</option>
                @foreach(['SUV','Berlina','Utilitario','Coupé','Cabriolet','Familiar','Monovolumen'] as $b) <option value="{{ $b }}" @if(old('body_type', $vehicle->body_type ?? '')==$b) selected @endif>{{ $b }}</option> @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Potencia (CV)</label>
            <input type="number" name="horsepower" value="{{ old('horsepower', $vehicle->horsepower ?? '') }}" class="form-control" min="0">
        </div>
        <div>
            <label class="form-label">Cilindrada (L)</label>
            <input type="text" name="engine_size" value="{{ old('engine_size', $vehicle->engine_size ?? '') }}" class="form-control" placeholder="Ej: 2.0L">
        </div>
        <div style="display:flex; gap: 15px;">
            <div style="flex:1">
                <label class="form-label">Puertas</label>
                <input type="number" name="doors" value="{{ old('doors', $vehicle->doors ?? '') }}" class="form-control" min="2" max="6">
            </div>
            <div style="flex:1">
                <label class="form-label">Plazas</label>
                <input type="number" name="seats" value="{{ old('seats', $vehicle->seats ?? '') }}" class="form-control" min="2" max="9">
            </div>
        </div>
    </div>
</div>

<!-- SECCIÓN 3: Precios y Apariencia -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-tag"></i> Precios y Apariencia</div>
    <div class="form-grid">
        <div>
            <label class="form-label">Precio Actual ({{ \App\Models\Setting::getVal('currency', '€') }}) *</label>
            <input type="number" name="price" value="{{ old('price', $vehicle->price ?? '') }}" class="form-control" required min="0">
        </div>
        <div>
            <label class="form-label">Precio Anterior ({{ \App\Models\Setting::getVal('currency', '€') }})</label>
            <input type="number" name="old_price" value="{{ old('old_price', $vehicle->old_price ?? '') }}" class="form-control" min="0">
        </div>
        <div>
            <label class="form-label">Estado de Venta *</label>
            <select name="status" class="form-control" required>
                <option value="available" @if(old('status', $vehicle->status ?? '')=='available') selected @endif>Disponible</option>
                <option value="reserved" @if(old('status', $vehicle->status ?? '')=='reserved') selected @endif>Reservado</option>
                <option value="sold" @if(old('status', $vehicle->status ?? '')=='sold') selected @endif>Vendido</option>
                <option value="draft" @if(old('status', $vehicle->status ?? '')=='draft') selected @endif>Borrador / Oculto</option>
            </select>
        </div>
        <div style="grid-column: 1 / -1; display:flex; gap:28px;">
            <div style="flex:1">
                <label class="form-label">Color Exterior</label>
                <input type="text" name="color" value="{{ old('color', $vehicle->color ?? '') }}" class="form-control">
            </div>
            <div style="flex:1">
                <label class="form-label">Color Interior</label>
                <input type="text" name="interior_color" value="{{ old('interior_color', $vehicle->interior_color ?? '') }}" class="form-control">
            </div>
        </div>
        <div style="grid-column: 1 / -1; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <label style="display:flex;align-items:center;gap:12px; cursor:pointer;"><input type="checkbox" name="is_featured" value="1" @if(old('is_featured', $vehicle->is_featured ?? false)) checked @endif style="width:20px; height:20px;"> <b style="color:#1e293b;">Destacar este vehículo en la página principal</b></label>
        </div>
    </div>
</div>

<!-- SECCIÓN 4: Descripciones -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-file-text"></i> Descripciones</div>
    <div style="margin-bottom:24px;">
        <label class="form-label">Descripción Corta (Resumen)</label>
        <textarea name="short_description" class="form-control" rows="2" placeholder="Un breve resumen que aparecerá en los listados...">{{ old('short_description', $vehicle->short_description ?? '') }}</textarea>
    </div>
    <div>
        <label class="form-label">Descripción Completa (Detalles)</label>
        <textarea name="description" class="form-control" rows="6" placeholder="Detalla aquí todo el equipamiento, historial de mantenimiento, etc.">{{ old('description', $vehicle->description ?? '') }}</textarea>
    </div>
</div>

<!-- SECCIÓN 5: Equipamiento (Opciones) -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-list"></i> Equipamiento y Opciones</div>
    @php
        $groupedFeatures = $features->groupBy('category');
        $selectedFeatures = isset($vehicle) ? $vehicle->features->pluck('id')->toArray() : [];
    @endphp
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px;">
        @foreach($groupedFeatures as $category => $catFeatures)
        <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
            <h4 style="margin-bottom: 16px; font-size: 15px; color: #3b82f6; border-bottom: 1px solid #cbd5e1; padding-bottom: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">{{ $category }}</h4>
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($catFeatures as $f)
                <label style="font-size: 14px; display:flex; gap:10px; align-items:center; color:#475569; cursor:pointer;">
                    <input type="checkbox" name="features[]" value="{{ $f->id }}" @if(in_array($f->id, old('features', $selectedFeatures))) checked @endif style="width:18px; height:18px;"> {{ $f->name }}
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- SECCIÓN 6: Atributos Personalizados -->
@if(count($customAttributes) > 0)
<div class="form-section">
    <div class="form-section-title"><i class="icon-sliders"></i> Datos Específicos</div>
    <div class="form-grid">
        @foreach($customAttributes as $attr)
        @php
            $attrVal = isset($vehicle) ? ($vehicle->customAttributes->firstWhere('id', $attr->id)->pivot->value ?? '') : '';
        @endphp
        <div>
            <label class="form-label">{{ $attr->name }} @if($attr->unit) <small style="color:#94a3b8;">({{ $attr->unit }})</small> @endif</label>
            @if($attr->type == 'select' && !empty($attr->options))
                <select name="custom_attributes[{{ $attr->id }}]" class="form-control">
                    <option value="">-- Seleccionar --</option>
                    @foreach($attr->options as $opt)
                        <option value="{{ $opt }}" @if(old("custom_attributes.$attr->id", $attrVal) == $opt) selected @endif>{{ $opt }}</option>
                    @endforeach
                </select>
            @elseif($attr->type == 'boolean')
                <select name="custom_attributes[{{ $attr->id }}]" class="form-control">
                    <option value="">-- Seleccionar --</option>
                    <option value="Sí" @if(old("custom_attributes.$attr->id", $attrVal) == 'Sí') selected @endif>Sí</option>
                    <option value="No" @if(old("custom_attributes.$attr->id", $attrVal) == 'No') selected @endif>No</option>
                </select>
            @else
                <input type="{{ $attr->type=='number' ? 'number' : 'text' }}" name="custom_attributes[{{ $attr->id }}]" value="{{ old("custom_attributes.$attr->id", $attrVal) }}" class="form-control" placeholder="Entrada de datos...">
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- SECCIÓN 7: Galería de Imágenes -->
<div class="form-section">
    <div class="form-section-title"><i class="icon-image"></i> Galería de Fotos</div>
    
    <div style="background: #f8fafc; padding: 32px; border-radius: 20px; border: 2px dashed #cbd5e1; text-align: center;">
        <label class="form-label" style="margin-bottom: 12px; font-size: 16px; color: #1e293b;">Subir nuevas fotos</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control" style="max-width: 400px; margin: 0 auto; background: white; border-color: #cbd5e1;">
        <p style="color:#64748b; font-size: 13px; margin-top: 15px;">Formatos permitidos: JPG, PNG, WEBP. Puedes seleccionar varias imágenes a la vez.</p>
    </div>

    @if(isset($vehicle) && count($vehicle->images) > 0)
    <div class="image-preview-grid">
        @foreach($vehicle->images as $img)
        <div class="image-preview-item @if($img->is_primary) is-primary @endif">
            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Foto Vehículo">
            <div class="image-actions">
                <label style="color:white; font-size:12px; cursor:pointer; display:flex; align-items:center; gap:6px;">
                    <input type="radio" name="primary_image" value="{{ $img->id }}" @if($img->is_primary) checked @endif> 
                    {{ $img->is_primary ? 'Portada' : 'Hacer Portada' }}
                </label>
                <a href="javascript:void(0)" onclick="if(confirm('¿Seguro que quieres borrar esta imagen?')) { document.getElementById('del-img-{{$img->id}}').submit(); }" style="color:#f87171; font-weight:700; font-size:12px; text-decoration:none; background:rgba(0,0,0,.5); padding:4px 8px; border-radius:6px;">Eliminar</a>
            </div>
            <form id="del-img-{{$img->id}}" action="{{ route('admin.vehicles.images.destroy', [$vehicle->id, $img->id]) }}" method="POST" style="display:none;">
                @csrf @method('DELETE')
            </form>
        </div>
        @endforeach
    </div>
    @endif
</div>
</div>