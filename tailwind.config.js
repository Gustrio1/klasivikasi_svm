import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50:  '#edfaf5',
                    100: '#d3f4e6',
                    200: '#a9e9cf',
                    300: '#71d7b1',
                    400: '#38bd90',
                    500: '#1D9E75',
                    600: '#1D9E75',
                    700: '#0F6E56',
                    800: '#0d5a46',
                    900: '#0b4a3a',
                },
                accent: {
                    500: '#185FA5',
                    600: '#1354949',
                },
                sidebar: {
                    DEFAULT: '#1D4E3A',
                    light:   '#245e46',
                    dark:    '#163829',
                },
            },
            screens: {
                xs: '475px',
            },
        },
    },

    plugins: [],
};
