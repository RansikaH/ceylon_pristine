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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#50946c',
                secondary: '#c52b36',
                success: '#50946c', // override for Bootstrap compatibility
                danger: '#c52b36',  // override for Bootstrap compatibility
            }
        },
    },

    plugins: [forms],

    safelist: [
        'bg-primary',
        'text-primary',
        'bg-secondary',
        'text-secondary',
        '!bg-primary',
        '!text-primary',
        '!bg-secondary',
        '!text-secondary',
        'hover:bg-secondary',
        'hover:!bg-secondary',
        'hover:bg-primary',
        'hover:!bg-primary',
    ],
};
