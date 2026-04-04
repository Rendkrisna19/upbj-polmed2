import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#7c3aed',
                'primary-light': '#8b5cf6',
                'primary-dark': '#6d28d9',
                base: '#f8f9fc',
                'base-dark': '#0f172a',
                'card-dark': '#1e293b',
            }
        },
    },
    plugins: [],
};