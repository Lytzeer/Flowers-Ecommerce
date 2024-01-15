/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        'poppins': ['Poppins', 'sans-serif']
      },
<<<<<<< HEAD
      colors:{
        secondary: '#38CB89'
=======
      colors: {
        secondary: '#38CB89',
>>>>>>> front
      },
    },
  },
  plugins: [require("daisyui")],
}