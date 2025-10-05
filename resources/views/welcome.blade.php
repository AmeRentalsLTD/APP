<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AME Rentals Ltd Portal</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                color-scheme: light;
                --primary: #0f766e;
                --primary-dark: #0d5f57;
                --secondary: #0f172a;
                --muted: #475569;
                --surface: #ffffff;
                --surface-muted: #f1f5f9;
                --accent: #38bdf8;
            }

            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Inter', sans-serif;
                background: radial-gradient(circle at top, rgba(14, 116, 144, 0.12), transparent 55%), var(--surface-muted);
                color: var(--secondary);
                line-height: 1.6;
            }

            a {
                color: inherit;
            }

            header {
                background: linear-gradient(135deg, rgba(15, 118, 110, 0.96), rgba(56, 189, 248, 0.92));
                color: white;
                padding: 3.5rem 1.5rem 5rem;
                position: relative;
                overflow: hidden;
            }

            header::after {
                content: '';
                position: absolute;
                inset: 0;
                background: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1600&q=80') center/cover;
                mix-blend-mode: soft-light;
                opacity: 0.35;
                pointer-events: none;
            }

            .topbar {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
                max-width: 1100px;
                margin: 0 auto 3rem;
                flex-wrap: wrap;
            }

            .nav {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                font-weight: 500;
                font-size: 0.95rem;
            }

            .nav a {
                color: rgba(255, 255, 255, 0.85);
                text-decoration: none;
                padding: 0.35rem 0;
                position: relative;
            }

            .nav a::after {
                content: '';
                position: absolute;
                left: 0;
                right: 0;
                bottom: -0.45rem;
                height: 2px;
                background: rgba(255, 255, 255, 0.65);
                border-radius: 999px;
                transform: scaleX(0);
                transition: transform 0.2s ease;
            }

            .nav a:hover::after,
            .nav a.active::after {
                transform: scaleX(1);
            }

            .nav a.active {
                color: #ffffff;
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 0.9rem;
                font-weight: 600;
                font-size: 1.35rem;
                letter-spacing: 0.02em;
            }

            .brand span:last-child {
                font-weight: 500;
                font-size: 0.95rem;
                opacity: 0.85;
            }

            .brand .mark {
                width: 44px;
                height: 44px;
                border-radius: 14px;
                background: rgba(15, 118, 110, 0.2);
                display: grid;
                place-items: center;
                border: 1px solid rgba(255, 255, 255, 0.24);
                font-weight: 700;
                font-size: 1rem;
            }

            .hero {
                position: relative;
                max-width: 900px;
                margin: 0 auto;
                text-align: center;
            }

            .hero h1 {
                font-size: clamp(2.5rem, 6vw, 3.6rem);
                font-weight: 700;
                margin-bottom: 1.25rem;
                letter-spacing: -0.02em;
            }

            .hero p {
                font-size: 1.15rem;
                color: rgba(255, 255, 255, 0.92);
                margin: 0 auto 2.5rem;
                max-width: 680px;
            }

            .hero-actions {
                display: flex;
                justify-content: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                padding: 0.85rem 1.8rem;
                border-radius: 999px;
                font-weight: 600;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .btn-primary {
                background: #ffffff;
                color: var(--primary);
                box-shadow: 0 16px 32px rgba(15, 118, 110, 0.28);
            }

            .btn-primary:hover {
                transform: translateY(-3px);
            }

            .btn-outline {
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.4);
                background: rgba(255, 255, 255, 0.08);
            }

            .btn-outline:hover {
                background: rgba(255, 255, 255, 0.18);
                transform: translateY(-3px);
            }

            main {
                max-width: 1100px;
                margin: -3.5rem auto 4.5rem;
                padding: 0 1.5rem;
                position: relative;
                z-index: 1;
            }

            section {
                background: var(--surface);
                border-radius: 28px;
                padding: 2.75rem;
                margin-bottom: 2.5rem;
                box-shadow: 0 24px 45px -20px rgba(15, 23, 42, 0.25);
            }

            h2 {
                font-size: 2rem;
                margin-bottom: 1.5rem;
                color: var(--secondary);
            }

            .grid-3 {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 1.75rem;
            }

            .feature-card {
                padding: 1.75rem;
                border-radius: 22px;
                border: 1px solid rgba(15, 118, 110, 0.15);
                background: linear-gradient(160deg, rgba(15, 118, 110, 0.05), rgba(56, 189, 248, 0.05));
            }

            .feature-card h3 {
                margin-top: 0;
                font-size: 1.2rem;
                color: var(--primary);
            }

            .steps {
                display: grid;
                gap: 1rem;
            }

            .step {
                display: grid;
                grid-template-columns: minmax(0, 48px) 1fr;
                gap: 1rem;
                align-items: start;
                background: var(--surface-muted);
                border-radius: 20px;
                padding: 1.5rem;
            }

            .step-number {
                width: 48px;
                height: 48px;
                border-radius: 16px;
                background: rgba(15, 118, 110, 0.12);
                display: grid;
                place-items: center;
                color: var(--primary);
                font-weight: 600;
            }

            .info-panel {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
                background: linear-gradient(120deg, rgba(15, 118, 110, 0.08), rgba(56, 189, 248, 0.08));
                border-radius: 20px;
                padding: 1.75rem;
            }

            .info-panel strong {
                display: block;
                color: var(--secondary);
                margin-bottom: 0.3rem;
            }

            .info-panel span {
                color: var(--muted);
                font-size: 0.95rem;
            }

            .contact-card {
                background: linear-gradient(120deg, rgba(15, 118, 110, 0.9), rgba(13, 148, 136, 0.88));
                color: #ffffff;
                border-radius: 26px;
                padding: 2.5rem;
                text-align: center;
            }

            .contact-card p {
                margin: 0.75rem auto 2rem;
                max-width: 480px;
                color: rgba(255, 255, 255, 0.88);
            }

            footer {
                text-align: center;
                padding: 2.5rem 1.5rem 3rem;
                color: #64748b;
                font-size: 0.95rem;
            }

            .legal {
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                margin-top: 1rem;
            }

            @media (max-width: 768px) {
                header {
                    padding: 2.75rem 1.25rem 4rem;
                }

                .topbar {
                    justify-content: center;
                    gap: 1rem;
                }

                .nav {
                    width: 100%;
                    justify-content: center;
                }

                section {
                    padding: 2rem;
                }

                .step {
                    grid-template-columns: 1fr;
                }

                .step-number {
                    justify-self: start;
                }
            }
        </style>
    </head>
    <body>
        <header>
            <div class="topbar">
                <div class="brand">
                    <div class="mark">AME</div>
                    <div>
                        <span>AME Rentals Ltd</span>
                        <span>Operations Portal</span>
                    </div>
                </div>
                <nav class="nav">
                    <a href="/" class="active">Acasă</a>
                    <a href="{{ route('reports.profit-and-loss') }}">Profit &amp; Loss</a>
                </nav>
                <a class="btn btn-outline" href="mailto:support@amerentals.co.uk">support@amerentals.co.uk</a>
            </div>
            <div class="hero">
                <h1>Welcome to the AME Rentals operations portal</h1>
                <p>Centralise fleet management, track bookings and support every journey from the dedicated AME Rentals platform. Log in for the latest tools crafted for our teams and trusted partners.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="{{ route('reports.profit-and-loss') }}">Profit &amp; Loss dashboard</a>
                    <a class="btn btn-outline" href="#support">Contact support</a>
                </div>
            </div>
        </header>

        <main>
            <section>
                <h2>Built for the AME Rentals ecosystem</h2>
                <div class="grid-3">
                    <article class="feature-card">
                        <h3>Fleet intelligence</h3>
                        <p>Monitor availability, maintenance windows and vehicle handovers in real time. Our live dashboard keeps dispatch and operations teams aligned across the UK.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Booking lifecycle</h3>
                        <p>Create quotes, approve contracts and capture customer documentation in one place. Every step of a rental—from enquiry to return—is tracked for compliance.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Secure collaboration</h3>
                        <p>Role-based access, audit trails and encrypted records protect partner and client data. Designed to meet the expectations of corporate mobility programmes.</p>
                    </article>
                </div>
            </section>

            <section>
                <h2>Getting started</h2>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div>
                            <h3>Sign in with your AME credentials</h3>
                            <p>Access is restricted to authorised team members and verified partners. Use your company-issued email address and the secure password provided by AME Rentals IT.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div>
                            <h3>Configure your workspace</h3>
                            <p>Pin favourite fleet views, activate notifications for reservations you manage and synchronise calendar reminders for upcoming deliveries or collections.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div>
                            <h3>Stay connected with support</h3>
                            <p>Submit service tickets, request additional vehicles or report incidents directly from the portal. The AME support desk responds within business-critical SLAs.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="support">
                <h2>Dedicated support when you need it</h2>
                <div class="info-panel">
                    <div>
                        <strong>Portal enquiries</strong>
                        <span>support@amerentals.co.uk</span>
                    </div>
                    <div>
                        <strong>Operations hotline</strong>
                        <span>+44 20 7123 4567 · 24/7 assistance for active rentals</span>
                    </div>
                    <div>
                        <strong>Head office</strong>
                        <span>71-75 Shelton Street, Covent Garden, London, United Kingdom, WC2H 9JQ</span>
                    </div>
                </div>
            </section>

            <section class="contact-card">
                <h2>Need access to the portal?</h2>
                <p>New partner or team member? Reach out to our onboarding specialists to receive credentials, schedule training and align the portal with your operational objectives.</p>
                <div class="hero-actions" style="justify-content: center;">
                    <a class="btn btn-primary" href="mailto:onboarding@amerentals.co.uk">Request access</a>
                    <a class="btn btn-outline" href="tel:+442071234567">Call the team</a>
                </div>
            </section>
        </main>

        <footer>
            <div>© {{ date('Y') }} AME Rentals Ltd · Registered in England & Wales · Company number 16733322</div>
            <div class="legal">
                <span>app.amerentals.co.uk is the secure access point for AME Rentals Ltd internal and partner operations.</span>
                <span>Use of this portal is subject to monitoring and AME Rentals Ltd information security policies.</span>
            </div>
        </footer>
    </body>
</html>
