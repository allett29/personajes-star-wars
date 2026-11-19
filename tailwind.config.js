const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"IBM Plex Sans"', ...defaultTheme.fontFamily.sans],
                titulo: ['"Chakra Petch"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                hoth: '#F5F8FC',
                espacio: {
                    700: '#2A3D61',
                    800: '#1C2D4D',
                    900: '#12203A',
                    950: '#0B1424',
                },
            },
        },
    },
    plugins: [],
};
