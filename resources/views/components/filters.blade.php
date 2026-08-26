<div class="{{ $sizeFilter ? 'filter-size' : 'filter-group' }}">
    
    @if(!$mobileView)
        <h5>{{ $title }}</h5>
    @endif
    
    @php
        $selected = request()->get($type) ? explode(',', request()->get($type)) : [];
    @endphp

    <div class="{{ $items->count() > 5 ? 'filter-scroll' : 'test' }}">
        @if($items->isNotEmpty())
            @foreach($items as $item)
                <div class="form-check">
                    <label class="custom-checkbox" for="{{ $type }}-{{ $item->id }}">                        
                        <input class="form-check-input {{ $type }}-label " type="checkbox"
                                name="{{ $type }}[]" value="{{ $item->$valueField }}" id="{{ $type }}-{{ $item->id }}"
                                {{ in_array($item->$valueField, $selected) ? 'checked' : '' }} >

                            @if(!$mobileView)
                                <span class="checkmark"></span>
                            @else
                                
                            @endif                             

                            @if($showColor && isset($item->code))
                                <span class="color-code" style="background-color: {{ $item->code }}"></span>
                            @endif

                            <p class="mobile-control">
                                <span class="{{ $nameClass ?? $type.'-name' }}">
                                    {{ (isset($limit)
                                        ? Str::limit($item->$labelField, $limit, '...')
                                        : $item->$labelField).($showPercent ? '% and above' : '') }}
                                </span>

                                @if(isset($item->products_count))
                                    @if(!$mobileView)
                                        <span class="text-muted tiny-font">({{ $item->products_count }})</span>
                                    @else
                                        <span class="text-muted tiny-font">{{ $item->products_count }}</span>
                                    @endif                                    
                                @endif
                            </p>
                    </label>
                </div>
            @endforeach
        @endif
    </div>    
</div>