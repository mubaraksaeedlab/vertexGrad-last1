/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/views/frontend/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                /*
                |--------------------------------------------------------------------------
                | Brand identity colors
                |--------------------------------------------------------------------------
                */
                primary: '#00E0FF',
                secondary: '#00B0FF',
                accent: '#19F2FF',

                dark: '#0F172A',
                darker: '#0D1322',
                light: '#E5E7EB',

                cardLight: '#1F2937',
                cardDark: '#0F172A',

                gradientStart: '#00E0FF',
                gradientEnd: '#005E7A',

                /*
                |--------------------------------------------------------------------------
                | Theme-aware structural colors
                |--------------------------------------------------------------------------
                */
                themeBg: 'var(--theme-bg)',
                themeSurface: 'var(--theme-surface)',
                themeSurface2: 'var(--theme-surface-2)',
                themeText: 'var(--theme-text)',
                themeMuted: 'var(--theme-muted)',
                themeBorder: 'var(--theme-border)',
            },

            backgroundImage: {
                'gradient-hero': 'linear-gradient(135deg, #00E0FF, #005E7A)',
                'gradient-accent': 'linear-gradient(90deg, #00B0FF, #19F2FF)',
                'theme-soft': 'linear-gradient(180deg, var(--theme-surface), var(--theme-surface-2))',
            },

            boxShadow: {
                neon: '0 0 16px #00E0FF, 0 0 32px #00B0FF',
                neon_md: '0 0 8px #00E0FF, 0 0 16px #00B0FF',
                soft_panel: '0 12px 40px rgba(15, 23, 42, 0.10)',
            },

            fontFamily: {
                sans: ['Instrument Sans', 'Inter', 'ui-sans-serif', 'system-ui'],
            },

            animation: {
                'fade-up': 'fade-up 1.2s ease forwards',
                'pulse-neon': 'pulse 2s infinite',
            },

            keyframes: {
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                pulse: {
                    '0%, 100%': { opacity: '0.7', transform: 'scale(1)' },
                    '50%': { opacity: '1', transform: 'scale(1.05)' },
                },
            },
        },
    },

    plugins: [
        function ({ addUtilities }) {
            addUtilities({
                '.bg-clip-text': {
                    'background-clip': 'text',
                    '-webkit-background-clip': 'text',
                },
            }, ['responsive']);
        },
    ],
};