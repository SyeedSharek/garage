import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

/**
 * Merges Tailwind CSS classes with clsx
 * @param {...(string | object | undefined)} inputs - Class names or conditional class objects
 * @returns {string} Merged class string
 */
export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

/**
 * Helper function for TanStack Table state updates
 * @param {Function | any} updaterOrValue - Either a function or a value
 * @param {import('vue').Ref} ref - Vue ref to update
 */
export function valueUpdater(updaterOrValue, ref) {
    ref.value = typeof updaterOrValue === 'function' ? updaterOrValue(ref.value) : updaterOrValue;
}

