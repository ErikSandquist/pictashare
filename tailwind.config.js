/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{php,js}"],
  theme: {
    fontFamily: {
      sans: ["quicksand-regular"],
      light: ["quicksand-light"],
      regular: ["quicksand-regular"],
      medium: ["quicksand-medium"],
      semibold: ["quicksand-semibold"],
      bold: ["quicksand-bold"],
    },
    extend: {
      colors: {
        primary: "#1044f2",
        secondary: "#c1dbff",
        accent: "#fff",
        neutral: "#fff",
        "base-100": "#35393B",
        "base-200": "#2A2D2E",
        "base-300": "#1E2021",
        info: "#99B5F4",
        success: "#16AC70",
        warning: "#fde047",
        error: "#E71F18",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
