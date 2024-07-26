<script>

</script>

<template>
    <main class="container">
        <header class="flex flex-wrap sm:justify-start sm:flex-nowrap w-full bg-white text-sm py-3 dark:bg-neutral-800">
            <nav class="max-w-[85rem] w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between">
                <a class="flex-none font-semibold text-xl text-black focus:outline-none focus:opacity-80 dark:text-white"
                    href="#" aria-label="Brand">Brand</a>
                <div class="flex flex-row items-center gap-5 mt-5 sm:justify-end sm:mt-0 sm:ps-5">
                    <a class="font-medium text-blue-500 focus:outline-none" href="#" aria-current="page">Login</a>
                </div>
            </nav>
        </header>

        <body>
            <slot></slot>
        </body>
    </main>
</template>