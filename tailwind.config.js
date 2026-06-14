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
                    'primary-dark': 'var(--color-diary-primary-dark)',
                    accent: 'var(--color-diary-accent)',
                    'accent-soft': 'var(--color-diary-accent-soft)',
                    text: 'var(--color-diary-text)',
                    muted: 'var(--color-diary-muted)',
                    surface: 'var(--color-diary-surface)',
                    border: 'var(--color-diary-border)',
                    sidebar: 'var(--color-diary-sidebar)',
                },
            },
            boxShadow: {
                diary: '0 4px 20px rgba(90, 122, 82, 0.08)',
            },
        },
    },

    plugins: [forms],

    safelist: [
        'grid-cols-7',
        'aspect-square',
        'mood-circle--great',
        'mood-circle--good',
        'mood-circle--normal',
        'mood-circle--tired',
        'mood-circle--bad',
    ],
};
