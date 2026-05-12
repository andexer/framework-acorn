{{--
    Admin: Configuración del Mapa Interactivo
    Vista renderizada por MapSettingsController::renderPage()

    Variables disponibles:
        $pageSlug      — slug de la página de opciones
        $settingsGroup — grupo de settings para settings_fields()
        $currentCode   — código ISO del país activo (ej: "VE")
        $currentName   — nombre del país activo (ej: "🇻🇪 Venezuela (por defecto)")
--}}
<div class="wrap" id="framework-map-settings-page">

    {{-- Cabecera ──────────────────────────────────────────────────────── --}}
    <div style="
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding: 20px 24px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 12px;
        color: #fff;
        box-shadow: 0 4px 16px rgba(99,102,241,.35);
    ">
        <span style="font-size: 2rem; line-height:1;">🗺️</span>
        <div>
            <h1 style="margin:0; font-size:1.4rem; font-weight:700; color:#fff;">
                {{ __('Mapa Interactivo', 'framework') }}
            </h1>
            <p style="margin:4px 0 0; opacity:.85; font-size:.9rem;">
                {{ __('Configura el país por defecto del shortcode', 'framework') }}
                <code style="background:rgba(255,255,255,.2); padding:2px 6px; border-radius:4px; font-size:.85rem;">[framework_map]</code>
            </p>
        </div>
    </div>

    {{-- País activo (badge) ───────────────────────────────────────────── --}}
    <div style="
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .95rem;
    ">
        <span style="color: #16a34a; font-weight:600;">✓ País activo:</span>
        <strong>{{ $currentName }}</strong>
        <code style="
            background: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: .8rem;
            font-family: monospace;
        ">{{ $currentCode }}</code>
    </div>

    {{-- Formulario de WordPress Settings API ─────────────────────────── --}}
    <div style="
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        max-width: 680px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    ">
        <form method="POST" action="options.php" id="framework-map-settings-form">
            @php settings_fields($settingsGroup) @endphp
            @php do_settings_sections($pageSlug) @endphp

            <hr style="border:none; border-top:1px solid #f3f4f6; margin: 20px 0;">

            <div style="display:flex; align-items:center; gap:12px;">
                @php submit_button(__('Guardar configuración', 'framework'), 'primary', 'submit', false) @endphp
                <a
                    href="{{ get_home_url() }}"
                    target="_blank"
                    style="color:#6366f1; text-decoration:none; font-size:.9rem;"
                >
                    {{ __('Ver en el sitio →', 'framework') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Instrucciones de uso ──────────────────────────────────────────── --}}
    <div style="
        margin-top: 24px;
        max-width: 680px;
        background: #fafaff;
        border: 1px solid #e0e7ff;
        border-radius: 12px;
        padding: 20px 24px;
    ">
        <h3 style="margin-top:0; color:#4f46e5; font-size:1rem;">
            📋 {{ __('Cómo usar el shortcode', 'framework') }}
        </h3>
        <table style="width:100%; border-collapse:collapse; font-size:.9rem;">
            <tr>
                <td style="padding:6px 0; vertical-align:top; width:50%; color:#374151;">
                    <strong>{{ __('País por defecto (desde ajustes)', 'framework') }}</strong>
                </td>
                <td style="padding:6px 0; vertical-align:top;">
                    <code style="background:#e0e7ff; color:#3730a3; padding:3px 8px; border-radius:4px;">[framework_map]</code>
                </td>
            </tr>
            <tr>
                <td style="padding:6px 0; vertical-align:top; color:#374151;">
                    <strong>{{ __('País específico', 'framework') }}</strong>
                </td>
                <td style="padding:6px 0; vertical-align:top;">
                    <code style="background:#e0e7ff; color:#3730a3; padding:3px 8px; border-radius:4px;">[framework_map country="AR"]</code>
                </td>
            </tr>
            <tr>
                <td style="padding:6px 0; vertical-align:top; color:#374151;">
                    <strong>{{ __('Altura personalizada', 'framework') }}</strong>
                </td>
                <td style="padding:6px 0; vertical-align:top;">
                    <code style="background:#e0e7ff; color:#3730a3; padding:3px 8px; border-radius:4px;">[framework_map height="600"]</code>
                </td>
            </tr>
            <tr>
                <td style="padding:6px 0; vertical-align:top; color:#374151;">
                    <strong>{{ __('Todo combinado', 'framework') }}</strong>
                </td>
                <td style="padding:6px 0; vertical-align:top;">
                    <code style="background:#e0e7ff; color:#3730a3; padding:3px 8px; border-radius:4px;">[framework_map country="MX" height="400"]</code>
                </td>
            </tr>
        </table>
    </div>

</div>
