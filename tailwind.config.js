import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // Tambahkan ini jika kamu pakai Javascript/AlpineJS untuk manipulasi class
        './resources/js/**/*.js', 
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    // INI SOLUSI UTAMANYA: Safelist
    // Masukkan class yang sering hilang atau class yang dipanggil secara dinamis
    safelist: [
        'bg-green-100', 'text-green-700',
        'bg-red-100', 'text-red-700',
        'bg-blue-900', 'bg-blue-800',
        'opacity-0', 'opacity-100', // Untuk animasi modal
    ],

    plugins: [forms],
};