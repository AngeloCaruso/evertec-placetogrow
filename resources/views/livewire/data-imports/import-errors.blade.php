@if($errors)

@foreach($errors as $error)
<div>
    {{ $error }}
</div>
@endforeach

@else
<div>
    {{__('No errors found.')}}
</div>

@endif