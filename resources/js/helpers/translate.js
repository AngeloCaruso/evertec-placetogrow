import { usePage } from '@inertiajs/vue3';

export function useTrans(value) {
    const array = usePage().props.translations;
    return array[value] != null ? array[value] : value;
}