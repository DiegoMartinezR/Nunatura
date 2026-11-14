/** @type {import('tailwindcss').Config} */
export default {
  // 1. EL PREFIJO ES VITAL
  prefix: 'tw-',

  // 2. RUTA DE TUS ARCHIVOS
  content: [
    "./*.php",
    "./inc/**/*.php",
    "./template-parts/**/*.php",
    "./woocommerce/**/*.php",
    "./src/**/*.js",
  ],

  theme: {
    extend: {
      screens: {
        'xxs': '320px',    // Pantallas de móviles muy pequeñas
        'xs': '405px',     // Pantallas de móviles muy pequeñas
        'mn': '450px',     // Pantallas de móviles muy pequeñas
        'lgl': '1150px',     
        '3xl': '1920px',   // Monitores grandes
        '4k': '2560px',    // Pantallas ultra anchas
      },
      colors: {
        'primary-dev': '#1e40af', 
        'secondary-dev': '#64748b',
      },
      fontFamily: {
        'woodmart': ['inherit'], 
      },
    },
  },

  plugins: [],
}