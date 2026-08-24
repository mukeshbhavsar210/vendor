@php
    $vendor = $getRecord();
    use Illuminate\Support\Facades\Storage;
@endphp

<div>    
    <div class="font-semibold">
        {{ $vendor->business_name }}        
        {{ $vendor->city }}
    </div>

    <div class="text-sm text-gray-500">
        {{ $vendor->user?->name ?? 'No Owner' }}
        •
        {{ $vendor->category?->name ?? 'No Category' }}
    </div>

    <div class="mt-1">
        @if($vendor->status)
            <span class="text-success-600 text-sm font-medium">
                Active
            </span>
        @else
            <span class="text-danger-600 text-sm font-medium">
                Blocked
            </span>
        @endif
    </div>
</div>