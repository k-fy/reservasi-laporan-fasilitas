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
                serif: ['Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                chloe: {
                    50:  '#FBF3F5',
                    100: '#F6E7EC',
                    200: '#ECC9D4',
                    300: '#DDA7B8',
                    400: '#C88AA0',
                    500: '#B06E86',
                    600: '#8E5468',
                    700: '#7A465A',
                },
            },
        },
    },

    plugins: [forms],
};
