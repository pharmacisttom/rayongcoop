/**
 * RayongCoop Professional Loan Calculator
 * Supports Flat Interest Rate and Reducing Balance (Effective Rate) calculation methods,
 * full month-by-month amortization schedule generation, and PDF/Print support.
 */

const LoanCalculator = (function() {
    function calculate(principal, annualRatePercent, termMonths, method = 'effective') {
        principal = parseFloat(principal) || 0;
        annualRatePercent = parseFloat(annualRatePercent) || 0;
        termMonths = parseInt(termMonths, 10) || 1;

        if (principal <= 0 || termMonths <= 0) {
            return {
                monthlyPayment: 0,
                totalPayment: 0,
                totalInterest: 0,
                schedule: []
            };
        }

        const monthlyRate = (annualRatePercent / 100) / 12;
        let monthlyPayment = 0;
        let totalPayment = 0;
        let totalInterest = 0;
        const schedule = [];

        if (method === 'flat') {
            // Flat rate
            totalInterest = principal * (annualRatePercent / 100) * (termMonths / 12);
            totalPayment = principal + totalInterest;
            monthlyPayment = totalPayment / termMonths;
            const monthlyPrincipal = principal / termMonths;
            const monthlyInterest = totalInterest / termMonths;

            let balance = principal;
            for (let m = 1; m <= termMonths; m++) {
                balance -= monthlyPrincipal;
                schedule.push({
                    period: m,
                    payment: monthlyPayment,
                    principal: monthlyPrincipal,
                    interest: monthlyInterest,
                    balance: Math.max(0, balance)
                });
            }
        } else {
            // Effective Rate (Reducing Balance / PMT formula)
            if (monthlyRate > 0) {
                monthlyPayment = principal * (monthlyRate * Math.pow(1 + monthlyRate, termMonths)) / (Math.pow(1 + monthlyRate, termMonths) - 1);
            } else {
                monthlyPayment = principal / termMonths;
            }

            let balance = principal;
            for (let m = 1; m <= termMonths; m++) {
                const interest = balance * monthlyRate;
                const principalPayment = monthlyPayment - interest;
                balance -= principalPayment;
                if (m === termMonths && balance !== 0) {
                    balance = 0;
                }

                totalInterest += interest;
                schedule.push({
                    period: m,
                    payment: monthlyPayment,
                    principal: principalPayment,
                    interest: interest,
                    balance: Math.max(0, balance)
                });
            }
            totalPayment = principal + totalInterest;
        }

        return {
            monthlyPayment: monthlyPayment,
            totalPayment: totalPayment,
            totalInterest: totalInterest,
            schedule: schedule
        };
    }

    function formatNumber(num) {
        return (num || 0).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function initUI() {
        const principalInput = document.getElementById('calcPrincipal');
        const termInput = document.getElementById('calcTerm');
        const rateInput = document.getElementById('calcRate');
        const methodSelect = document.getElementById('calcMethod');
        const productSelect = document.getElementById('calcProduct');

        const monthlyDisplay = document.getElementById('calcResultMonthly');
        const principalDisplay = document.getElementById('calcResultPrincipal');
        const interestDisplay = document.getElementById('calcResultInterest');
        const totalDisplay = document.getElementById('calcResultTotal');
        const scheduleTableBody = document.querySelector('#calcScheduleTable tbody');

        function update() {
            if (!principalInput || !termInput || !rateInput) return;

            const p = parseFloat(principalInput.value) || 0;
            const t = parseInt(termInput.value, 10) || 12;
            const r = parseFloat(rateInput.value) || 0;
            const m = methodSelect ? methodSelect.value : 'effective';

            const result = calculate(p, r, t, m);

            if (monthlyDisplay) monthlyDisplay.textContent = formatNumber(result.monthlyPayment) + ' บาท';
            if (principalDisplay) principalDisplay.textContent = formatNumber(p) + ' บาท';
            if (interestDisplay) interestDisplay.textContent = formatNumber(result.totalInterest) + ' บาท';
            if (totalDisplay) totalDisplay.textContent = formatNumber(result.totalPayment) + ' บาท';

            if (scheduleTableBody) {
                let html = '';
                result.schedule.forEach(row => {
                    html += `
                        <tr>
                            <td class="text-center">${row.period}</td>
                            <td class="text-end">${formatNumber(row.payment)}</td>
                            <td class="text-end">${formatNumber(row.principal)}</td>
                            <td class="text-end">${formatNumber(row.interest)}</td>
                            <td class="text-end fw-bold">${formatNumber(row.balance)}</td>
                        </tr>
                    `;
                });
                scheduleTableBody.innerHTML = html;
            }
        }

        if (productSelect) {
            productSelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (opt && opt.dataset.rate) {
                    rateInput.value = opt.dataset.rate;
                }
                if (opt && opt.dataset.maxTerm) {
                    termInput.value = Math.min(parseInt(termInput.value, 10), parseInt(opt.dataset.maxTerm, 10));
                }
                update();
            });
        }

        [principalInput, termInput, rateInput, methodSelect].forEach(el => {
            if (el) {
                el.addEventListener('input', update);
                el.addEventListener('change', update);
            }
        });

        // Initialize on load
        if (principalInput) update();
    }

    return {
        calculate: calculate,
        formatNumber: formatNumber,
        initUI: initUI
    };
})();

document.addEventListener('DOMContentLoaded', function() {
    LoanCalculator.initUI();
});
