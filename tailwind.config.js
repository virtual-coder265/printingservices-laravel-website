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
            colors: {
                forest: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    800: '#157638',
                    900: '#198240',
                    950: '#0f5a2a',
                },
                gold: {
                    400: '#d4bc96',
                    500: '#C5A880',
                },
                'green-accent': '#198240',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
