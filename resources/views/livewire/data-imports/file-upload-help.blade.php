@if($template)
    {{__('Download the template for this entity') }} <a class="text-orange-600 font-bold" href="{{asset($template)}}">{{__('here.')}}</a>
@endif