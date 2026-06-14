import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                diary: {
                    bg: '#F8F9F8',
                    primary: '#1B4332',
                    accent: '#E8F3EE',
                    text: '#374151',
                    muted: '#6B7280',
                },
            },
        },
    },

    plugins: [forms],

    safelist: [
        'grid-cols-7',
        'aspect-square',
    ],
};
