/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#6366f1',
        secondary: '#f59e0b',
        success: '#10b981',
        danger: '#ef4444',
        background: '#0f172a',
        card: '#1e293b',
        text: '#f1f5f9',
        muted: '#64748b',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
