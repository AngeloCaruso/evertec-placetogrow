@if($template)
    {{__('Download the template for this entity') }} <a class="text-orange-600 font-bold" href="{{asset($template)}}?no-cache={{now()->timestamp}}">{{__('here.')}}</a>
@endif