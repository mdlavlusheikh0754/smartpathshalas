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
                sans: ['Tiro Bangla', 'SolaimanLipi', ...defaultTheme.fontFamily.sans],
                'display': ['Tiro Bangla', 'SolaimanLipi', ...defaultTheme.fontFamily.sans],
                'export': ['SolaimanLipi', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
