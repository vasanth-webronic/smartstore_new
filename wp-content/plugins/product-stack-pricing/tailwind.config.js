/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: { 
        'psp-blue': '#2271B1', 
        'psp-grey': '#E7E7E7', 
        'psp-red': '#CC071D', 
    }, 
    },
  },
  plugins: [],
}

// npx tailwindcss -i ./css/psp-input.css -o ./css/psp-style.css --watch