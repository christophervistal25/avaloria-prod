import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/js/Pages/**/*.vue",
        "./resources/js/Layouts/**/*.vue",
        "./resources/js/Components/**/*.vue",
    ],
    theme: {
        extend: {
            animation: {
                pulse: "pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite",
            },
            backgroundImage: {
                noise: "url('/effects/noise.png')",
            },
        },
    },
    plugins: [
    ],
};
