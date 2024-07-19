/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [    './views/**/*.php',  ],
  theme: {
    extend: {
      colors: {
        primary: {
        '50': '#f3f1ff',
        '100': '#eae6ff',
        '200': '#d7d0ff',
        '300': '#bbaaff',
        '400': '#9a7aff',
        '500': '#7b45ff',
        '600': '#6d1fff',
        '700': '#5e0ef3',
        '800': '#490abd',
        '900': '#420ba7',
        '950': '#260372',
    }
      }
    },
  },
  plugins: [],
}