<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profit &amp; Loss · AME Rentals Portal</title>
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
                --danger: #dc2626;
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
                min-height: 100vh;
            }

            header {
                background: linear-gradient(135deg, rgba(15, 118, 110, 0.96), rgba(56, 189, 248, 0.92));
                color: white;
                padding: 2.75rem 1.5rem 4rem;
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
                margin: 0 auto;
                flex-wrap: wrap;
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 0.9rem;
                font-weight: 600;
                font-size: 1.2rem;
                letter-spacing: 0.02em;
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

            .brand span:last-child {
                font-weight: 500;
                font-size: 0.95rem;
                opacity: 0.85;
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

            .support-link {
                position: relative;
                color: #ffffff;
                text-decoration: none;
                border: 1px solid rgba(255, 255, 255, 0.4);
                padding: 0.65rem 1.4rem;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.1);
                font-weight: 500;
                transition: background 0.2s ease, transform 0.2s ease;
            }

            .support-link:hover {
                background: rgba(255, 255, 255, 0.18);
                transform: translateY(-2px);
            }

            .page-intro {
                position: relative;
                max-width: 1100px;
                margin: 2.5rem auto 0;
            }

            .page-intro h1 {
                font-size: clamp(2.1rem, 4vw, 3rem);
                margin-bottom: 0.75rem;
                font-weight: 700;
            }

            .page-intro p {
                max-width: 640px;
                color: rgba(255, 255, 255, 0.9);
                margin: 0;
                font-size: 1.05rem;
            }

            main {
                max-width: 1100px;
                margin: -2rem auto 4rem;
                padding: 0 1.5rem;
                position: relative;
                z-index: 1;
            }

            section {
                background: var(--surface);
                border-radius: 28px;
                padding: 2.5rem;
                margin-bottom: 2rem;
                box-shadow: 0 24px 45px -20px rgba(15, 23, 42, 0.25);
            }

            h2 {
                margin-top: 0;
                margin-bottom: 1.5rem;
                font-size: 1.65rem;
            }

            .filters {
                display: grid;
                gap: 1.25rem;
            }

            .mode-switch {
                display: flex;
                gap: 1.5rem;
                flex-wrap: wrap;
                font-weight: 500;
                color: var(--muted);
            }

            .mode-switch label {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                cursor: pointer;
            }

            .field-group {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 1rem;
                align-items: end;
            }

            .field {
                display: grid;
                gap: 0.35rem;
                color: var(--muted);
            }

            .field label {
                font-weight: 600;
                font-size: 0.95rem;
                color: var(--secondary);
            }

            .field input,
            .field select {
                border-radius: 12px;
                border: 1px solid rgba(15, 23, 42, 0.1);
                padding: 0.75rem 0.9rem;
                font-size: 0.95rem;
                font-family: inherit;
                transition: border 0.2s ease, box-shadow 0.2s ease;
            }

            .field input:focus,
            .field select:focus {
                outline: none;
                border-color: rgba(15, 118, 110, 0.4);
                box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.1);
            }

            .hidden {
                display: none;
            }

            .actions {
                display: flex;
                justify-content: flex-start;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.6rem;
                padding: 0.85rem 1.6rem;
                border-radius: 14px;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
                font-size: 0.95rem;
            }

            .btn-primary {
                background: var(--primary);
                color: #ffffff;
                box-shadow: 0 16px 32px rgba(15, 118, 110, 0.28);
            }

            .btn-primary:hover {
                background: var(--primary-dark);
                transform: translateY(-2px);
            }

            .btn[disabled] {
                opacity: 0.65;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }

            .status {
                font-size: 0.95rem;
                color: var(--muted);
                align-self: center;
            }

            .status.success {
                color: var(--primary-dark);
            }

            .status.error {
                color: var(--danger);
            }

            .summary-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 1.5rem;
            }

            .summary-card {
                padding: 1.75rem;
                border-radius: 22px;
                background: linear-gradient(160deg, rgba(15, 118, 110, 0.05), rgba(56, 189, 248, 0.05));
                border: 1px solid rgba(15, 118, 110, 0.18);
                display: grid;
                gap: 0.35rem;
            }

            .summary-card.net-positive {
                border-color: rgba(34, 197, 94, 0.35);
                background: linear-gradient(160deg, rgba(34, 197, 94, 0.12), rgba(56, 189, 248, 0.08));
            }

            .summary-card.net-negative {
                border-color: rgba(220, 38, 38, 0.35);
                background: linear-gradient(160deg, rgba(220, 38, 38, 0.12), rgba(56, 189, 248, 0.08));
            }

            .summary-card span {
                font-size: 0.95rem;
                color: var(--muted);
            }

            .summary-card strong {
                font-size: 2rem;
                font-weight: 700;
                color: var(--secondary);
            }

            .period-label {
                font-size: 0.95rem;
                color: var(--muted);
                margin-bottom: 1.5rem;
            }

            .breakdown-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.5rem;
            }

            .breakdown-card {
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: 20px;
                padding: 1.75rem;
                background: var(--surface-muted);
            }

            .breakdown-card h3 {
                margin-top: 0;
                margin-bottom: 1rem;
                font-size: 1.15rem;
                color: var(--secondary);
            }

            .category-list {
                list-style: none;
                margin: 0;
                padding: 0;
                display: grid;
                gap: 0.75rem;
            }

            .category-list li {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 0.95rem;
                color: var(--secondary);
            }

            .category-list li span.value {
                font-weight: 600;
                color: var(--primary-dark);
            }

            .category-list li.empty {
                color: var(--muted);
                font-style: italic;
                justify-content: flex-start;
            }

            footer {
                text-align: center;
                padding: 2.5rem 1.5rem 3rem;
                color: #64748b;
                font-size: 0.95rem;
            }

            @media (max-width: 768px) {
                header {
                    padding: 2.5rem 1.25rem 3.5rem;
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
                    <a href="/">Acasă</a>
                    <a href="{{ route('reports.profit-and-loss') }}" class="active">Profit &amp; Loss</a>
                </nav>
                <a class="support-link" href="mailto:support@amerentals.co.uk">support@amerentals.co.uk</a>
            </div>
            <div class="page-intro">
                <h1>Profit &amp; Loss dashboard</h1>
                <p>Monitor revenue, spending and net performance for AME Rentals. Select a reporting period to see how the business is performing and identify categories driving the result.</p>
            </div>
        </header>

        <main>
            <section class="filters">
                <h2>Select reporting period</h2>
                <div class="mode-switch">
                    <label>
                        <input type="radio" name="pl-mode" value="year" checked>
                        Calendar year
                    </label>
                    <label>
                        <input type="radio" name="pl-mode" value="range">
                        Custom date range
                    </label>
                </div>
                <form id="pl-filters" class="field-group">
                    @php $currentYear = (int) date('Y'); @endphp
                    <div class="field" data-mode="year">
                        <label for="pl-year">Year</label>
                        <select id="pl-year" name="year">
                            @for ($year = $currentYear; $year >= $currentYear - 4; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="field hidden" data-mode="range">
                        <label for="pl-start">Start date</label>
                        <input type="date" id="pl-start" name="start_date">
                    </div>
                    <div class="field hidden" data-mode="range">
                        <label for="pl-end">End date</label>
                        <input type="date" id="pl-end" name="end_date">
                    </div>
                    <div class="actions">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <span id="pl-status" class="status">Selectează perioada pentru a vedea raportul.</span>
                    </div>
                </form>
            </section>

            <section>
                <div class="period-label" id="pl-period">No period selected.</div>
                <div class="summary-grid">
                    <article class="summary-card" id="pl-income-card">
                        <span>Total income</span>
                        <strong id="pl-income-total">£0.00</strong>
                    </article>
                    <article class="summary-card" id="pl-expense-card">
                        <span>Total expenses</span>
                        <strong id="pl-expense-total">£0.00</strong>
                    </article>
                    <article class="summary-card" id="pl-net-card">
                        <span>Net result</span>
                        <strong id="pl-net-total">£0.00</strong>
                    </article>
                </div>
            </section>

            <section>
                <h2>Category breakdown</h2>
                <div class="breakdown-grid">
                    <div class="breakdown-card">
                        <h3>Income categories</h3>
                        <ul id="pl-income-breakdown" class="category-list">
                            <li class="empty">No income recorded.</li>
                        </ul>
                    </div>
                    <div class="breakdown-card">
                        <h3>Expense categories</h3>
                        <ul id="pl-expense-breakdown" class="category-list">
                            <li class="empty">No expenses recorded.</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            © {{ date('Y') }} AME Rentals Ltd · Secure financial reporting module
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const filterForm = document.getElementById('pl-filters');
                const modeInputs = document.querySelectorAll('input[name="pl-mode"]');
                const yearField = document.querySelector('[data-mode="year"]');
                const rangeFields = document.querySelectorAll('[data-mode="range"]');
                const yearSelect = document.getElementById('pl-year');
                const startInput = document.getElementById('pl-start');
                const endInput = document.getElementById('pl-end');
                const statusEl = document.getElementById('pl-status');
                const incomeTotalEl = document.getElementById('pl-income-total');
                const expenseTotalEl = document.getElementById('pl-expense-total');
                const netTotalEl = document.getElementById('pl-net-total');
                const periodEl = document.getElementById('pl-period');
                const incomeBreakdownEl = document.getElementById('pl-income-breakdown');
                const expenseBreakdownEl = document.getElementById('pl-expense-breakdown');
                const netCard = document.getElementById('pl-net-card');
                const submitButton = filterForm.querySelector('button[type="submit"]');

                const currencyFormatter = new Intl.NumberFormat('en-GB', {
                    style: 'currency',
                    currency: 'GBP',
                    minimumFractionDigits: 2,
                });

                const dateFormatter = new Intl.DateTimeFormat('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                });

                function toggleMode(mode) {
                    const useYear = mode === 'year';
                    yearField.classList.toggle('hidden', !useYear);
                    rangeFields.forEach((field) => field.classList.toggle('hidden', useYear));
                }

                function setLoading(isLoading) {
                    submitButton.disabled = isLoading;
                    if (isLoading) {
                        statusEl.textContent = 'Se încarcă datele...';
                        statusEl.className = 'status';
                    }
                }

                function formatCategoryName(value) {
                    return value
                        .split('_')
                        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
                        .join(' ');
                }

                function renderList(container, data, emptyMessage) {
                    container.innerHTML = '';
                    const entries = Object.entries(data || {});

                    if (!entries.length) {
                        const item = document.createElement('li');
                        item.className = 'empty';
                        item.textContent = emptyMessage;
                        container.appendChild(item);
                        return;
                    }

                    entries
                        .sort(([, amountA], [, amountB]) => amountB - amountA)
                        .forEach(([category, value]) => {
                            const item = document.createElement('li');
                            const label = document.createElement('span');
                            label.textContent = formatCategoryName(category);
                            const amount = document.createElement('span');
                            amount.className = 'value';
                            amount.textContent = currencyFormatter.format(value ?? 0);
                            item.append(label, amount);
                            container.appendChild(item);
                        });
                }

                function formatPeriod(start, end) {
                    try {
                        const startDate = new Date(start);
                        const endDate = new Date(end);
                        if (!Number.isNaN(startDate.valueOf()) && !Number.isNaN(endDate.valueOf())) {
                            return `${dateFormatter.format(startDate)} → ${dateFormatter.format(endDate)}`;
                        }
                    } catch (error) {
                        // ignore formatting errors
                    }

                    return `${start} → ${end}`;
                }

                function renderReport(data) {
                    incomeTotalEl.textContent = currencyFormatter.format(data.totals.income ?? 0);
                    expenseTotalEl.textContent = currencyFormatter.format(data.totals.expenses ?? 0);
                    netTotalEl.textContent = currencyFormatter.format(data.totals.net ?? 0);
                    periodEl.textContent = `Reporting period: ${formatPeriod(data.period.start_date, data.period.end_date)}`;

                    renderList(incomeBreakdownEl, data.breakdown.income, 'No income recorded for this period.');
                    renderList(expenseBreakdownEl, data.breakdown.expenses, 'No expenses recorded for this period.');

                    netCard.classList.remove('net-positive', 'net-negative');
                    if ((data.totals.net ?? 0) > 0) {
                        netCard.classList.add('net-positive');
                    } else if ((data.totals.net ?? 0) < 0) {
                        netCard.classList.add('net-negative');
                    }
                }

                async function fetchReport(params) {
                    setLoading(true);
                    try {
                        const response = await fetch(`/api/v1/reports/profit-and-loss?${params.toString()}`);
                        const payload = await response.json();

                        if (!response.ok) {
                            const message = payload.message || 'Nu am putut încărca raportul. Verifică filtrele și încearcă din nou.';
                            throw new Error(message);
                        }

                        renderReport(payload.data);
                        statusEl.textContent = 'Date actualizate.';
                        statusEl.className = 'status success';
                    } catch (error) {
                        statusEl.textContent = error.message || 'A apărut o eroare neașteptată.';
                        statusEl.className = 'status error';
                    } finally {
                        submitButton.disabled = false;
                    }
                }

                function buildParams() {
                    const params = new URLSearchParams();
                    const selectedMode = Array.from(modeInputs).find((input) => input.checked)?.value || 'year';

                    if (selectedMode === 'range') {
                        const start = startInput.value;
                        const end = endInput.value;

                        if (!start || !end) {
                            throw new Error('Completează atât data de început, cât și data de sfârșit pentru perioada personalizată.');
                        }

                        params.append('start_date', start);
                        params.append('end_date', end);
                    } else {
                        params.append('year', yearSelect.value);
                    }

                    return params;
                }

                filterForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    statusEl.className = 'status';
                    try {
                        const params = buildParams();
                        fetchReport(params);
                    } catch (error) {
                        statusEl.textContent = error.message;
                        statusEl.className = 'status error';
                    }
                });

                modeInputs.forEach((input) => {
                    input.addEventListener('change', (event) => {
                        const mode = event.target.value;
                        toggleMode(mode);
                        statusEl.textContent = mode === 'year'
                            ? 'Alege anul pentru raport.'
                            : 'Setează datele de început și sfârșit.';
                        statusEl.className = 'status';
                    });
                });

                yearSelect.addEventListener('change', () => {
                    const params = buildParams();
                    fetchReport(params);
                });

                [startInput, endInput].forEach((input) => {
                    input.addEventListener('change', () => {
                        const selectedMode = Array.from(modeInputs).find((radio) => radio.checked)?.value;
                        if (selectedMode === 'range' && startInput.value && endInput.value) {
                            const params = buildParams();
                            fetchReport(params);
                        }
                    });
                });

                toggleMode('year');
                const defaultParams = buildParams();
                fetchReport(defaultParams);
            });
        </script>
    </body>
</html>
