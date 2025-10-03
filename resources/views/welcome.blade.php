<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AME Rentals Ltd</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                color-scheme: light;
                --primary: #0d9488;
                --primary-dark: #0f766e;
                --secondary: #1f2937;
                --background: #f7f9fb;
                --text: #0b1120;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Manrope', sans-serif;
                background: var(--background);
                color: var(--text);
                line-height: 1.6;
            }

            header {
                background: linear-gradient(135deg, rgba(13, 148, 136, 0.95), rgba(15, 118, 110, 0.95)),
                    url('https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1400&q=80') center/cover;
                color: white;
                padding: 3.5rem 1.5rem 5rem;
                text-align: center;
            }

            nav {
                display: flex;
                justify-content: space-between;
                align-items: center;
                max-width: 1100px;
                margin: 0 auto 3.5rem;
                gap: 1.5rem;
                flex-wrap: wrap;
            }

            nav .brand {
                font-weight: 700;
                font-size: 1.5rem;
                letter-spacing: 1px;
            }

            nav .cta {
                background: white;
                color: var(--primary);
                padding: 0.75rem 1.5rem;
                border-radius: 999px;
                font-weight: 600;
                text-decoration: none;
                box-shadow: 0 8px 20px rgba(8, 94, 84, 0.3);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            nav .cta:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 24px rgba(8, 94, 84, 0.35);
            }

            .hero {
                max-width: 900px;
                margin: 0 auto;
                text-align: center;
            }

            .hero h1 {
                font-size: clamp(2.5rem, 6vw, 3.6rem);
                margin-bottom: 1rem;
                font-weight: 700;
            }

            .hero p {
                font-size: 1.15rem;
                margin-bottom: 2rem;
                color: rgba(255, 255, 255, 0.9);
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
                gap: 0.5rem;
                padding: 0.85rem 1.7rem;
                border-radius: 999px;
                text-decoration: none;
                font-weight: 600;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .btn-primary {
                background: white;
                color: var(--primary);
                box-shadow: 0 8px 20px rgba(15, 118, 110, 0.35);
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.12);
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .btn:hover {
                transform: translateY(-2px);
            }

            main {
                max-width: 1100px;
                margin: -3rem auto 4rem;
                padding: 0 1.5rem;
            }

            section {
                background: white;
                border-radius: 28px;
                padding: 2.5rem;
                margin-bottom: 2.5rem;
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            }

            h2 {
                font-size: 2rem;
                margin-bottom: 1.25rem;
                color: var(--secondary);
            }

            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.75rem;
            }

            .feature-card {
                padding: 1.5rem;
                border-radius: 20px;
                background: var(--background);
                border: 1px solid rgba(15, 118, 110, 0.1);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .feature-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            }

            .feature-card h3 {
                margin-top: 0.5rem;
                margin-bottom: 0.75rem;
                font-size: 1.25rem;
                color: var(--primary-dark);
            }

            .fleet {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.5rem;
            }

            .van-card {
                border-radius: 20px;
                overflow: hidden;
                border: 1px solid rgba(15, 118, 110, 0.1);
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                transition: transform 0.2s ease;
                background: white;
            }

            .van-card img {
                width: 100%;
                height: 180px;
                object-fit: cover;
            }

            .van-card:hover {
                transform: translateY(-4px);
            }

            .van-card .content {
                padding: 1.5rem;
            }

            .van-card h3 {
                margin-top: 0;
                margin-bottom: 0.5rem;
                color: var(--secondary);
            }

            .van-card span {
                display: inline-block;
                background: rgba(13, 148, 136, 0.1);
                color: var(--primary-dark);
                padding: 0.4rem 0.8rem;
                border-radius: 999px;
                font-size: 0.85rem;
                font-weight: 600;
                margin-top: 0.75rem;
            }

            .testimonials {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 1.5rem;
            }

            .testimonial {
                background: var(--background);
                border-radius: 20px;
                padding: 1.75rem;
                border: 1px solid rgba(15, 118, 110, 0.1);
                position: relative;
            }

            .testimonial::before {
                content: '“';
                font-size: 3rem;
                color: rgba(15, 118, 110, 0.2);
                position: absolute;
                top: 1rem;
                left: 1.25rem;
            }

            .testimonial strong {
                display: block;
                margin-top: 1rem;
                color: var(--secondary);
            }

            .cta-section {
                text-align: center;
                background: linear-gradient(135deg, rgba(13, 148, 136, 0.95), rgba(15, 118, 110, 0.95)),
                    url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1400&q=80') center/cover;
                color: white;
                padding: 3rem;
            }

            .cta-section h2 {
                color: white;
                margin-bottom: 1rem;
            }

            .cta-section p {
                max-width: 520px;
                margin: 0.5rem auto 2rem;
                color: rgba(255, 255, 255, 0.85);
            }

            footer {
                text-align: center;
                padding: 2rem 1.5rem 3rem;
                color: #6b7280;
                font-size: 0.95rem;
            }

            @media (max-width: 768px) {
                header {
                    padding: 2.5rem 1.25rem 4rem;
                }

                nav {
                    justify-content: center;
                }

                main {
                    margin-top: -2rem;
                }

                section {
                    padding: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <header>
            <nav>
                <div class="brand">AME Rentals Ltd</div>
                <a class="cta" href="tel:+447123456789">Book now</a>
            </nav>
            <div class="hero">
                <h1>Experience the road with AME Rentals Ltd premium vans</h1>
                <p>Planning a family road trip, moving your crew to an event, or need flexible mobility for business deliveries? AME Rentals Ltd keeps you moving with a modern fleet tailored to every journey.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#fleet">Explore our fleet</a>
                    <a class="btn btn-secondary" href="#about">Discover our story</a>
                </div>
            </div>
        </header>

        <main>
            <section id="about">
                <h2>Why choose AME Rentals Ltd?</h2>
                <div class="features">
                    <article class="feature-card">
                        <h3>Trusted UK operator</h3>
                        <p>Founded in London in 2017, AME Rentals Ltd supports corporate partners and families alike with reliable, fully insured vehicles and professional service.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Flexible mobility</h3>
                        <p>Short-term rentals, long-term agreements, and bespoke logistics packages ensure you only pay for the time and equipment you truly need.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Dedicated assistance</h3>
                        <p>Our multilingual support team is available 24/7 with route planning, roadside assistance, and safety briefings for every driver.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Transparent pricing</h3>
                        <p>No hidden fees—just clear, competitive rates with maintenance, insurance, and GPS tracking included as standard.</p>
                    </article>
                </div>
            </section>

            <section id="fleet">
                <h2>Our signature fleet</h2>
                <div class="fleet">
                    <article class="van-card">
                        <img src="https://images.unsplash.com/photo-1525104698733-6f6c4d54936f?auto=format&fit=crop&w=900&q=80" alt="Van premium">
                        <div class="content">
                            <h3>Urban Explorer</h3>
                            <p>Compact nine-seater ideal for airport shuttles and inner-city deliveries with efficient fuel consumption.</p>
                            <span>From £69 / day</span>
                        </div>
                    </article>
                    <article class="van-card">
                        <img src="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=80" alt="Van pentru aventuri">
                        <div class="content">
                            <h3>Adventure XL</h3>
                            <p>Spacious tourer equipped with sleeping pods, portable fridge, and bike racks for week-long getaways.</p>
                            <span>From £92 / day</span>
                        </div>
                    </article>
                    <article class="van-card">
                        <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=900&q=80" alt="Van business">
                        <div class="content">
                            <h3>Business Shuttle</h3>
                            <p>Premium Mercedes-Benz vans with Wi-Fi, conference seating, and chauffeur options for executive travel.</p>
                            <span>From £115 / day</span>
                        </div>
                    </article>
                </div>
            </section>

            <section>
                <h2>Client feedback</h2>
                <div class="testimonials">
                    <article class="testimonial">
                        <p>“AME Rentals Ltd kept our touring production on schedule across the UK. Immaculate vehicles and proactive communication.”</p>
                        <strong>Sarah Mitchell, Tour Manager</strong>
                    </article>
                    <article class="testimonial">
                        <p>“Ideal partner for corporate events. Their chauffeur-driven Business Shuttle delivered a flawless VIP experience.”</p>
                        <strong>James Turner, HR Director</strong>
                    </article>
                    <article class="testimonial">
                        <p>“We hired the Adventure XL for a Scottish Highlands adventure and loved every mile—well-equipped and ready for the wild.”</p>
                        <strong>Emma Wilson, Travel Enthusiast</strong>
                    </article>
                </div>
            </section>

            <section id="contact" class="cta-section">
                <h2>Ready to start your journey?</h2>
                <p>Call or email our London operations team with your itinerary. We will prepare a tailored quote within 30 minutes, including delivery and collection options across the UK.</p>
                <a class="btn btn-primary" href="tel:+447123456789">+44 7123 456 789</a>
                <a class="btn btn-secondary" href="mailto:booking@amerentals.co.uk">booking@amerentals.co.uk</a>
            </section>
        </main>

        <footer>
            &copy; {{ date('Y') }} AME Rentals Ltd · Registered in England & Wales · Company No. 10876543
        </footer>
    </body>
</html>
