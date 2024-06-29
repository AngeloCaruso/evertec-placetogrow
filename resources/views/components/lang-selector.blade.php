<div class="flex items-right">
    <div class="relative inline-block text-left">
        <div>
            <button type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="menu-button" aria-expanded="false" aria-haspopup="true">
                <img class="{{app()->getLocale() !== 'en' ? 'hidden' : ''}}" width="30" height="30" src="https://img.icons8.com/color/30/usa-circular.png" alt="usa-circular" />
                <img class="{{app()->getLocale() !== 'es' ? 'hidden' : ''}}" width="30" height="30" src="https://img.icons8.com/color/30/spain-circular.png" alt="spain-circular" />
                <svg class="-mr-1 h-5 w-5 mt-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div id="menu" class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none transition transform opacity-0 scale-95" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <a href="{{ url('locale/es') }}" class="flex block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">
                    <img width="30" height="30" src="https://img.icons8.com/color/30/spain-circular.png" alt="spain-circular" />
                    <span class="mt-2 pl-2">Español</span>
                </a>
                <a href="{{ url('locale/en') }}" class="flex block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-1">
                    <img width="30" height="30" src="https://img.icons8.com/color/30/usa-circular.png" alt="usa-circular" />
                    <span class="mt-2 pl-2">English</span>
                </a>
            </div>
        </div>
    </div>

</div>