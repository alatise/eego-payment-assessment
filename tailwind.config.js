/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./app/Views/**/*.php", "./public/**/*.js"],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Lufga", "ui-sans-serif", "system-ui", "sans-serif"],
        lufga: ["Lufga", "sans-serif"],
      },
    },
  },
  plugins: [],
};
