import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
content: [
    './resources/*.blade.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif',
               'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
      },
      colors: {
          primary: {
              DEFAULT: '#ffffff', // White text for contrast
              light: '#f3f4f6', // Light gray for hover effects
              dark: '#d1d5db', // Slightly darker gray
          },
          secondary: {
              DEFAULT: '#9ca3af', // Medium gray for secondary elements
              light: '#d1d5db', // Lighter gray
              dark: '#6b7280', // Darker gray
          },
          background: {
              DEFAULT: '#c7c7c052', // OFF White
              dark: '#1f2937', // Slightly lighter dark gray
              light: '#c7c7c052', // OFF White
          },
          border: '#afb0b1', // Gray border
          text: '#e5e7eb', // Light text
          muted: '#9ca3af', // Muted gray text
          hover: '#374151', // Slightly lighter gray for hover effects
      },
    },
  },
  plugins: [forms],
}

