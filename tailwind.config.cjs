/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
    './resources/**/*.vue'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#091c47',
        active: '#5AA9E6'
      }
    },
  },
  plugins: [],
}
