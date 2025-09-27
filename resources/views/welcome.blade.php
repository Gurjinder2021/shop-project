<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shop Project') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|newsreader:400,500" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --bg: #f6f4f0;
            --ink: #1b1a16;
            --muted: #5b564c;
            --accent: #c86b2b;
            --accent-2: #1f6f5b;
            --card: #ffffff;
            --stroke: #e6dfd5;
            --shadow: 0 20px 60px rgba(27, 26, 22, 0.12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: radial-gradient(1200px 600px at 15% -10%, #f7e7d3 0%, transparent 60%),
                        radial-gradient(900px 500px at 90% 10%, #d6ece5 0%, transparent 55%),
                        var(--bg);
        }

        a { color: inherit; text-decoration: none; }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: min(1100px, 92vw);
            margin: 0 auto;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 28px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .brand-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(140deg, var(--accent), #f1b576);
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 700;
            box-shadow: var(--shadow);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid var(--stroke);
            font-weight: 600;
            transition: transform 150ms ease, box-shadow 150ms ease, background 150ms ease;
        }

        .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(27,26,22,0.12); }

        .btn-primary {
            background: var(--ink);
            color: #fff;
            border-color: var(--ink);
        }

        .hero {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 24px;
            padding: 40px 0 24px;
        }

        .hero-copy {
            grid-column: span 7;
        }

        .hero-card {
            grid-column: span 5;
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 24px;
            padding: 24px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .eyebrow {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent-2);
            font-weight: 700;
        }

        h1 {
            font-size: clamp(36px, 5vw, 56px);
            line-height: 1.05;
            margin: 14px 0 18px;
            font-weight: 700;
        }

        .lead {
            color: var(--muted);
            font-size: 18px;
            line-height: 1.6;
            max-width: 560px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 22px 0 10px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff6ed;
            color: #7a3a12;
            font-weight: 600;
            border: 1px solid #f2d7c3;
        }

        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 26px;
        }

        .metric {
            background: #fff;
            border: 1px solid var(--stroke);
            border-radius: 16px;
            padding: 14px;
            text-align: center;
        }

        .metric strong { font-size: 20px; display: block; }
        .metric span { color: var(--muted); font-size: 12px; letter-spacing: 0.4px; }

        .panel {
            background: linear-gradient(160deg, #f8ede3, #f3f6f1);
            border: 1px solid var(--stroke);
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .panel h3 {
            margin: 0 0 8px;
            font-size: 16px;
        }

        .panel ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 8px;
            color: var(--muted);
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 18px;
            padding: 20px 0 60px;
        }

        .grid h2 {
            grid-column: span 12;
            font-size: 24px;
            margin: 0 0 12px;
        }

        .feature {
            grid-column: span 4;
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 10px 24px rgba(27,26,22,0.08);
        }

        .feature h3 { margin: 0 0 6px; font-size: 16px; }
        .feature p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.5; }

        .cta {
            margin: 40px 0 60px;
            background: var(--ink);
            color: #fff;
            border-radius: 24px;
            padding: 26px;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            align-items: center;
        }

        .cta p { color: #efe8de; }

        .footer {
            padding: 20px 0 40px;
            color: var(--muted);
            font-size: 13px;
        }

        .float-dot {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200,107,43,0.35), transparent 70%);
            top: -40px;
            right: -40px;
        }

        .float-grid {
            position: absolute;
            inset: auto 16px 16px auto;
            width: 160px;
            height: 120px;
            background-image: linear-gradient(0deg, rgba(31,111,91,0.12) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(31,111,91,0.12) 1px, transparent 1px);
            background-size: 20px 20px;
            border-radius: 14px;
        }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .hero-copy, .hero-card { grid-column: span 1; }
            .grid { grid-template-columns: 1fr; }
            .feature { grid-column: span 1; }
            .hero-metrics { grid-template-columns: 1fr; }
            .cta { grid-template-columns: 1fr; }
            .nav-actions { display: none; }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
            100% { transform: translateY(0px); }
        }

        .animate-float { animation: float 5s ease-in-out infinite; }
    </style>
</head>
<body>
    <div class="page">
        <div class="container">
            <nav class="nav">
                <div class="brand">
                    <div class="brand-badge">SP</div>
                    <div>
                        <div>Shop Project</div>
                        <div style="font-size: 12px; color: var(--muted);">Daily collections, simplified</div>
                    </div>
                </div>

                <div class="nav-actions">
                    @if (Route::has('login'))
                        <a class="btn" href="{{ route('login') }}">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a class="btn btn-primary" href="{{ route('register') }}">Get started</a>
                    @endif
                </div>
            </nav>

            <section class="hero">
                <div class="hero-copy">
                    <div class="eyebrow">Operations console</div>
                    <h1>Keep shops, teams, and daily collections in one clean workflow.</h1>
                    <p class="lead">
                        Assign shops, track daily collections, and export clean reports without spreadsheet chaos.
                        Built for managers who want clarity and accountability across multiple shops.
                    </p>
                    <div class="hero-actions">
                        @if (Route::has('login'))
                            <a class="btn btn-primary" href="{{ route('login') }}">Open dashboard</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="btn" href="{{ route('register') }}">Create account</a>
                        @endif
                        <span class="pill">Excel exports included</span>
                    </div>
                    <div class="hero-metrics">
                        <div class="metric">
                            <strong>Users</strong>
                            <span>Role-based access</span>
                        </div>
                        <div class="metric">
                            <strong>Shops</strong>
                            <span>Assignable in bulk</span>
                        </div>
                        <div class="metric">
                            <strong>Collections</strong>
                            <span>Online + offline totals</span>
                        </div>
                    </div>
                </div>

                <div class="hero-card">
                    <div class="float-dot animate-float"></div>
                    <div class="panel">
                        <h3>Today at a glance</h3>
                        <ul>
                            <li>Check which shops submitted collections</li>
                            <li>Quickly identify missing entries</li>
                            <li>See totals per shop and per day</li>
                        </ul>
                    </div>
                    <div class="panel">
                        <h3>Admin highlights</h3>
                        <ul>
                            <li>Create users and manage access</li>
                            <li>Assign shops and update details</li>
                            <li>Export collections to Excel</li>
                        </ul>
                    </div>
                    <div class="float-grid"></div>
                </div>
            </section>

            <section class="grid">
                <h2>Designed for daily operational clarity</h2>
                <div class="feature">
                    <h3>Role-based dashboards</h3>
                    <p>Admins and shop users see only what they need with clear navigation.</p>
                </div>
                <div class="feature">
                    <h3>Fast shop assignment</h3>
                    <p>Single or bulk shop assignment with unique shop number validation.</p>
                </div>
                <div class="feature">
                    <h3>Collection tracking</h3>
                    <p>Capture online and offline totals with automatic daily rollups.</p>
                </div>
                <div class="feature">
                    <h3>Reporting views</h3>
                    <p>View collection status across users and shops from admin reports.</p>
                </div>
                <div class="feature">
                    <h3>Excel exports</h3>
                    <p>Download shop and collection data in Excel for external sharing.</p>
                </div>
                <div class="feature">
                    <h3>Built with Laravel</h3>
                    <p>Secure auth, clean routing, and structured models for growth.</p>
                </div>
            </section>

            <section class="cta">
                <div>
                    <h2 style="margin: 0 0 8px;">Ready to see it in action?</h2>
                    <p style="margin: 0;">Sign in or create an account to start assigning shops and tracking collections.</p>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
                    @if (Route::has('login'))
                        <a class="btn" href="{{ route('login') }}" style="background: #fff; color: var(--ink);">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a class="btn" href="{{ route('register') }}" style="background: var(--accent); color: #fff; border-color: var(--accent);">Create account</a>
                    @endif
                </div>
            </section>

            <footer class="footer">
                <div>Shop Project - built for daily operations.</div>
            </footer>
        </div>
    </div>
</body>
</html>
