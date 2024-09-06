@unless (Breadcrumbs::generate()->isEmpty())
<nav class="flex" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-4">
        @foreach (Breadcrumbs::generate() as $breadcrumb)

            @if (!is_null($breadcrumb->url))
                <li>
                    <div class="flex items-center">
                        @if(!$loop->first)
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        @endif

                        @if($loop->last)
                            <a wire:navigate href="{{ $breadcrumb->url }}" class="ml-4 text-sm font-semibold text-orange-600 hover:text-gray-700" aria-current="page">{{__(ucfirst($breadcrumb->title))}}</a>
                        @else
                            <a wire:navigate href="{{ $breadcrumb->url }}" class="ml-4 text-sm font-semibold text-gray-600 hover:text-gray-700" aria-current="page">{{__(ucfirst($breadcrumb->title))}}</a>
                        @endif
                    </div>
                </li>
            @endif

        @endforeach
    </ol>
</nav>
@endunless