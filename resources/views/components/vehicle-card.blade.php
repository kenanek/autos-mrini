<a href="{{ route('vehicles.show', $vehicle->slug) }}" class="vehicle-card">
    <div class="vehicle-img-wrap" style="aspect-ratio: 16/10; overflow: hidden;">
        @if($vehicle->is_featured)
            <span class="featured-badge">Destacado</span>
        @endif
        @if($vehicle->primary_image)
            <img src="{{ asset('storage/' . $vehicle->primary_image) }}" alt="{{ $vehicle->title }}" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
        @else
            <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&auto=format&fit=crop&q=60" alt="Coche Placeholder" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
        @endif
    </div>
    <div class="vehicle-info">
        <h3 class="vehicle-title">{{ Str::limit($vehicle->title, 45) }}</h3>
        <div class="vehicle-price">{{ $vehicle->formatted_price }}</div>
        <div class="vehicle-specs">
            <div class="spec-item"><i class="icon-calendar" style="font-size:14px;"></i> {{ $vehicle->year }}</div>
            <div class="spec-item"><i class="icon-gauge" style="font-size:14px;"></i> {{ number_format($vehicle->mileage, 0, ',', '.') }} km</div>
            <div class="spec-item"><i class="icon-settings" style="font-size:14px;"></i> {{ $vehicle->transmission == 'automatic' ? 'Auto' : 'Manual' }}</div>
        </div>
        <div class="vehicle-action">
            <span class="btn btn-outline" style="width:100%; padding: 10px; font-size:14px;">Ver Detalles</span>
        </div>
    </div>
</a>