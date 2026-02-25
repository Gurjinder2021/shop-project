@extends('admin.maindesign')

@section('content')
    @php
        $userCount = (int) ($userCount ?? 0);
        $mappedUsersCount = (int) ($mappedUsersCount ?? 0);
        $totalShops = (int) ($totalShops ?? 0);
        $shopEntryPercent = (float) ($shopEntryPercent ?? 0);

        $shopEntryPercent = max(0, min(100, $shopEntryPercent));
        $mappedPercentage = $userCount > 0 ? round(($mappedUsersCount / $userCount) * 100, 1) : 0;
        $unmappedUsers = max($userCount - $mappedUsersCount, 0);
        $shopsWithEntry = (int) round(($shopEntryPercent / 100) * $totalShops);
        $shopsWithoutEntry = max($totalShops - $shopsWithEntry, 0);
    @endphp

    <style>
        .innovation-dashboard {
            --bg-a: #0f172a;
            --bg-b: #11253f;
            --panel: #ffffff;
            --ink: #0e1726;
            --muted: #5c6980;
            --stroke: #e6ebf3;
            --teal: #0f766e;
            --orange: #c2410c;
            --indigo: #4338ca;
            --mint: #059669;
            padding: 18px 22px 28px;
        }

        .innovation-hero {
            background: radial-gradient(1200px 300px at 0% -10%, rgba(20, 184, 166, 0.25), transparent 55%),
                        radial-gradient(900px 300px at 100% -10%, rgba(99, 102, 241, 0.28), transparent 55%),
                        linear-gradient(140deg, var(--bg-a), var(--bg-b));
            border-radius: 20px;
            padding: 24px;
            color: #f5f8ff;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.3);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .innovation-hero::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.22), transparent 65%);
        }

        .innovation-hero h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .innovation-hero p {
            margin: 8px 0 0;
            color: #d7e4ff;
            font-size: 15px;
        }

        .innovation-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 16px;
        }

        .innovation-card {
            background: var(--panel);
            border: 1px solid var(--stroke);
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(11, 24, 45, 0.08);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            color: var(--ink);
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .innovation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 34px rgba(11, 24, 45, 0.14);
            text-decoration: none;
        }

        .innovation-card-main {
            grid-column: span 3;
        }

        .innovation-card-wide {
            grid-column: span 12;
        }

        .innovation-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .innovation-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15px;
        }

        .icon-teal { background: linear-gradient(135deg, #14b8a6, #0f766e); }
        .icon-indigo { background: linear-gradient(135deg, #6366f1, #4338ca); }
        .icon-orange { background: linear-gradient(135deg, #f97316, #c2410c); }
        .icon-mint { background: linear-gradient(135deg, #10b981, #047857); }

        .innovation-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .innovation-value {
            font-size: 30px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 8px;
        }

        .innovation-sub {
            color: var(--muted);
            font-size: 13px;
            margin: 0;
        }

        .bar-wrap {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: #edf2f7;
            overflow: hidden;
            margin-top: 12px;
        }

        .bar-fill {
            height: 100%;
            border-radius: 999px;
        }

        .bar-indigo { background: linear-gradient(90deg, #6366f1, #4338ca); }
        .bar-orange { background: linear-gradient(90deg, #fb923c, #c2410c); }
        .bar-mint { background: linear-gradient(90deg, #34d399, #059669); }

        .ring-card {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 18px;
            align-items: center;
        }

        .progress-ring {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: conic-gradient(#059669 {{ $shopEntryPercent }}%, #ecf2fb 0);
            display: grid;
            place-items: center;
            margin: 0 auto;
        }

        .progress-ring::before {
            content: "";
            width: 94px;
            height: 94px;
            border-radius: 50%;
            background: #fff;
            display: block;
        }

        .progress-text {
            position: absolute;
            font-size: 24px;
            font-weight: 700;
            color: var(--ink);
        }

        .progress-holder {
            position: relative;
            display: grid;
            place-items: center;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-top: 8px;
        }

        .quick-links a {
            border: 1px solid var(--stroke);
            border-radius: 12px;
            padding: 10px 12px;
            color: #1f2d46;
            font-size: 13px;
            font-weight: 600;
            background: #f8fbff;
            transition: background 0.15s ease;
            text-decoration: none;
        }

        .quick-links a:hover {
            background: #eef5ff;
            text-decoration: none;
        }

        @media (max-width: 1199px) {
            .innovation-card-main { grid-column: span 6; }
            .innovation-card-wide { grid-column: span 12; }
            .quick-links { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 767px) {
            .innovation-dashboard { padding: 12px; }
            .innovation-hero { padding: 18px; }
            .innovation-hero h2 { font-size: 24px; }
            .innovation-card-main,
            .innovation-card-wide { grid-column: span 12; }
            .ring-card { grid-template-columns: 1fr; }
            .quick-links { grid-template-columns: 1fr; }
        }
    </style>

    <section class="innovation-dashboard">
         <!--<div class="innovation-hero">
            <h2>Admin Command Center</h2>
            <p>Track user onboarding, shop mapping, and collection coverage in a single live overview.</p>
        </div>-->
<div class="innovation-hero">
            <h4>Admin Command Center</h4>
        </div>
        <div class="innovation-grid">
            <a href="{{ route('add.user') }}" class="innovation-card innovation-card-main">
                <div class="innovation-head">
                    <span class="innovation-icon icon-teal"><i class="fa-solid fa-user-plus"></i></span>
                    <span class="innovation-label">Create</span>
                </div>
                <div class="innovation-value">{{ $userCount }}</div>
                <p class="innovation-sub">Total active users you can manage.</p>
            </a>

            <a href="{{ route('users') }}" class="innovation-card innovation-card-main">
                <div class="innovation-head">
                    <span class="innovation-icon icon-indigo"><i class="fa-solid fa-users"></i></span>
                    <span class="innovation-label">All Users</span>
                </div>
                <div class="innovation-value">{{ $userCount }}</div>
                <p class="innovation-sub">Open full user directory and edit records.</p>
            </a>

            <a href="{{ route('view.user.shops') }}" class="innovation-card innovation-card-main">
                <div class="innovation-head">
                    <span class="innovation-icon icon-orange"><i class="fa-solid fa-link"></i></span>
                    <span class="innovation-label">Mapped Users</span>
                </div>
                <div class="innovation-value">{{ $mappedUsersCount }}</div>
                <p class="innovation-sub">{{ $unmappedUsers }} users are not mapped yet.</p>
                <div class="bar-wrap">
                    <div class="bar-fill bar-orange" style="width: {{ $mappedPercentage }}%"></div>
                </div>
            </a>

            <a href="{{ route('user.collectionreport') }}" class="innovation-card innovation-card-main">
                <div class="innovation-head">
                    <span class="innovation-icon icon-mint"><i class="fa-solid fa-chart-line"></i></span>
                    <span class="innovation-label">Collections</span>
                </div>
                <div class="innovation-value">{{ $totalShops }}</div>
                <p class="innovation-sub">{{ $shopsWithEntry }} shops submitted today.</p>
                <div class="bar-wrap">
                    <div class="bar-fill bar-mint" style="width: {{ $shopEntryPercent }}%"></div>
                </div>
            </a>

            <div class="innovation-card innovation-card-wide">
                <div class="ring-card">
                    <div class="progress-holder">
                        <div class="progress-ring"></div>
                        <div class="progress-text">{{ number_format($shopEntryPercent, 1) }}%</div>
                    </div>
                    <div>
                        <div class="innovation-label">Daily Collection Completion</div>
                        <h3 style="margin: 0 0 8px; font-weight: 700; font-size: 24px; color: #12243f;">
                            {{ $shopsWithEntry }} / {{ $totalShops }} shops reported
                        </h3>
                        <p class="innovation-sub" style="margin-bottom: 12px;">
                            {{ $shopsWithoutEntry }} shops are pending update in today's report cycle.
                        </p>
                        <div class="quick-links">
                            <a href="{{ route('assign.shop.form') }}"><i class="fa-solid fa-store"></i> Assign Shops</a>
                            <a href="{{ route('view.user.shops') }}"><i class="fa-solid fa-diagram-project"></i> Mapping View</a>
                            <a href="{{ route('users') }}"><i class="fa-solid fa-address-card"></i> User Directory</a>
                            <a href="{{ route('user.collectionreport') }}"><i class="fa-solid fa-file-lines"></i> Full Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
