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
                coinpel: {
                    light:   '#f5f2f9', // fundo claro / item de menu ativo
                    DEFAULT: '#593e75', // a cor da marca (usada na maioria das telas)
                    dark:    '#4a3361', // hover (escurece um pouco)
                },
            },
        },
    },

    plugins: [forms],
};
