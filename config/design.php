<?php

return [
    'brand' => [
        'name'    => env('APP_NAME', 'VertexGrad'),
        'tagline' => 'Where Innovation Meets Opportunity',
        'logo'    => 'images/logo.png',
    ],

    'default_theme' => 'brand',

    /*
    |--------------------------------------------------------------------------
    | Brand Identity Colors
    |--------------------------------------------------------------------------
    | These define VertexGrad visual identity and remain usable across themes.
    |--------------------------------------------------------------------------
    */
    'identity' => [
        'primary'       => '#00E0FF',
        'secondary'     => '#00B0FF',
        'accent'        => '#19F2FF',
        'glow_primary'  => '#00E0FF',
        'glow_secondary'=> '#00B0FF',
        'gradientStart' => '#00E0FF',
        'gradientEnd'   => '#005E7A',
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Surface Tokens
    |--------------------------------------------------------------------------
    | These are the real theme-changing values.
    |--------------------------------------------------------------------------
    */
    'themes' => [
        'brand' => [
            'label' => 'VertexGrad',
            'surface' => [
                'bg'       => '#0F172A',
                'surface'  => '#111B31',
                'surface2' => '#1F2937',
                'text'     => '#E5E7EB',
                'muted'    => '#94A3B8',
                'border'   => 'rgba(0, 224, 255, 0.18)',
            ],
        ],

        'dark' => [
            'label' => 'Dark',
            'surface' => [
                'bg'       => '#020617',
                'surface'  => '#0B1220',
                'surface2' => '#111827',
                'text'     => '#E5E7EB',
                'muted'    => '#94A3B8',
                'border'   => 'rgba(255, 255, 255, 0.10)',
            ],
        ],

        'light' => [
            'label' => 'Light',
            'surface' => [
                'bg'       => '#F8FBFF',
                'surface'  => '#FFFFFF',
                'surface2' => '#EEF4FA',
                'text'     => '#0F172A',
                'muted'    => '#475569',
                'border'   => 'rgba(15, 23, 42, 0.08)',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backward Compatibility
    |--------------------------------------------------------------------------
    | Keep old keys so old pages do not explode immediately.
    |--------------------------------------------------------------------------
    */
    'colors' => [
        'primary'       => '#00E0FF',
        'secondary'     => '#00B0FF',
        'accent'        => '#19F2FF',
        'dark'          => '#0F172A',
        'darker'        => '#0D1322',
        'light'         => '#E5E7EB',
        'cardLight'     => '#1F2937',
        'cardDark'      => '#0F172A',
        'gradientStart' => '#00E0FF',
        'gradientEnd'   => '#005E7A',
    ],

    'classes' => [
        'container'       => 'max-w-6xl mx-auto px-4 sm:px-6 lg:px-8',
        'section_y'       => 'py-20 lg:py-28',
        'transition_base' => 'transition duration-300 ease-in-out',

        'card'            => 'rounded-xl shadow-xl transition duration-300 ease-in-out',
        'card_dark'       => 'rounded-xl shadow-lg transition duration-300 ease-in-out',

        'heading_primary' => 'font-extrabold text-4xl lg:text-6xl tracking-tight',
        'text_accent'     => 'text-primary font-semibold',
        'text_gradient'   => 'bg-clip-text text-transparent bg-gradient-to-r from-secondary to-accent font-extrabold',

        'btn_base'        => 'inline-flex items-center justify-center font-semibold rounded-lg text-lg px-6 py-3 transition duration-300 ease-in-out whitespace-nowrap',
        'btn_primary'     => 'bg-primary text-dark hover:bg-secondary shadow-neon hover:shadow-neon_md',
        'btn_secondary'   => 'bg-transparent border-2 border-primary text-primary hover:bg-primary/10',
    ],
];