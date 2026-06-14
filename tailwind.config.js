import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

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
                    bg: 'var(--color-diary-bg)',
                    primary: 'var(--color-diary-primary)',
                    accent: 'var(--color-diary-accent)',
                    text: 'var(--color-diary-text)',
                    muted: 'var(--color-diary-muted)',
                    surface: 'var(--color-diary-surface)',
                    border: 'var(--color-diary-border)',
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
