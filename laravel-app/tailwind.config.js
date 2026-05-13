import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    900: '#1e3a5f',
                    800: '#1e4976',
                    700: '#1d5e8e',
                    600: '#2563eb',
                    500: '#3b82f6',
                    100: '#dbeafe',
                    50:  '#eff6ff',
                },
            },
        },
    },

    plugins: [forms],
};
