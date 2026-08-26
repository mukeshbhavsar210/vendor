@props([
    'modalId' => 'commonOptionModal',
    'title' => 'Select Option',
    'route',
    'class',
    'colors' => [],
    'sizes' => [],
    'qtys' => [],
    'label' => 'Select',    
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 id="{{ $modalId }}Title">{{ $title }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="list-unstyled">
                    @if(!empty($colors))
                        @foreach($colors as $color)
                            <li class="mb-2">
                                <a href="javascript:void(0);" class="select-option" data-type="color" data-value="{{ $color }}">
                                    {{ $color }}
                                </a>
                            </li>
                        @endforeach
                    @endif

                    @if(!empty($sizes))
                        @foreach($sizes as $size)
                            <li class="mb-2">
                                <a href="javascript:void(0);" class="select-option" data-type="size" data-value="{{ $size }}">
                                    {{ $size }}
                                </a>
                            </li>
                        @endforeach
                    @endif

                    @if(!empty($qtys))
                        @foreach($qtys as $qty)
                            <li class="mb-2">
                                <a href="javascript:void(0);" class="select-option" data-type="qty" data-value="{{ $qty }}">
                                    {{ $qty }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>                

                <button type="button"
                        class="btn btn-primary w-100 mt-2"
                        onclick="submitCartUpdate('{{ $route }}','{{ $modalId }}')">
                    {{ $label }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.modalData = {
        colors: @json($colors),
        sizes: @json($sizes),
        qtys: @json($qtys)
    };
</script>