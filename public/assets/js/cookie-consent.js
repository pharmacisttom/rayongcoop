/**
 * RayongCoop Cookie Consent Platform (Privacy-by-Design / PDPA Compliant)
 * Strictly blocks analytics and marketing scripts until explicit user consent.
 */

const CookieConsent = (function() {
    const STORAGE_KEY = 'rayongcoop_cookie_consent';
    const CONSENT_VERSION = '1.0';

    const defaultConsent = {
        version: CONSENT_VERSION,
        necessary: true,
        functional: true,
        analytics: false,
        marketing: false,
        third_party: false,
        timestamp: null
    };

    function init() {
        const saved = getConsent();
        if (!saved || saved.version !== CONSENT_VERSION) {
            showBanner();
        } else {
            applyConsent(saved);
        }
        bindEvents();
    }

    function getConsent() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function saveConsent(consent) {
        consent.timestamp = new Date().toISOString();
        consent.version = CONSENT_VERSION;
        consent.necessary = true; // Always true

        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(consent));
        } catch (e) {
            console.error('Failed to save cookie consent to localStorage', e);
        }

        applyConsent(consent);
        hideBanner();
        hideModal();

        // Send anonymous consent log to backend
        logConsentToServer(consent);

        if (typeof showToast === 'function') {
            showToast('success', 'บันทึกการตั้งค่าคุกกี้เรียบร้อยแล้ว');
        }
    }

    function acceptAll() {
        const fullConsent = {
            version: CONSENT_VERSION,
            necessary: true,
            functional: true,
            analytics: true,
            marketing: true,
            third_party: true
        };
        saveConsent(fullConsent);
    }

    function rejectNonEssential() {
        const minimalConsent = {
            version: CONSENT_VERSION,
            necessary: true,
            functional: true,
            analytics: false,
            marketing: false,
            third_party: false
        };
        saveConsent(minimalConsent);
    }

    function applyConsent(consent) {
        // Activate scripts matching consented categories
        const blockedScripts = document.querySelectorAll('script[type="text/plain"][data-cookiecategory]');
        blockedScripts.forEach(script => {
            const category = script.getAttribute('data-cookiecategory');
            if (consent[category] === true) {
                const newScript = document.createElement('script');
                Array.from(script.attributes).forEach(attr => {
                    if (attr.name !== 'type' && attr.name !== 'data-cookiecategory') {
                        newScript.setAttribute(attr.name, attr.value);
                    }
                });
                newScript.type = 'text/javascript';
                newScript.innerHTML = script.innerHTML;
                script.parentNode.replaceChild(newScript, script);
            }
        });
    }

    function showBanner() {
        const banner = document.getElementById('cookieConsentBanner');
        if (banner) {
            banner.style.display = 'block';
        }
    }

    function hideBanner() {
        const banner = document.getElementById('cookieConsentBanner');
        if (banner) {
            banner.style.display = 'none';
        }
    }

    function showModal() {
        const current = getConsent() || defaultConsent;
        const functionalToggle = document.getElementById('cookieFunctionalToggle');
        const analyticsToggle = document.getElementById('cookieAnalyticsToggle');
        const marketingToggle = document.getElementById('cookieMarketingToggle');
        const thirdPartyToggle = document.getElementById('cookieThirdPartyToggle');

        if (functionalToggle) functionalToggle.checked = !!current.functional;
        if (analyticsToggle) analyticsToggle.checked = !!current.analytics;
        if (marketingToggle) marketingToggle.checked = !!current.marketing;
        if (thirdPartyToggle) thirdPartyToggle.checked = !!current.third_party;

        const modalEl = document.getElementById('cookiePreferenceModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    function hideModal() {
        const modalEl = document.getElementById('cookiePreferenceModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    function saveCustomPreferences() {
        const functionalToggle = document.getElementById('cookieFunctionalToggle');
        const analyticsToggle = document.getElementById('cookieAnalyticsToggle');
        const marketingToggle = document.getElementById('cookieMarketingToggle');
        const thirdPartyToggle = document.getElementById('cookieThirdPartyToggle');

        const customConsent = {
            necessary: true,
            functional: functionalToggle ? functionalToggle.checked : true,
            analytics: analyticsToggle ? analyticsToggle.checked : false,
            marketing: marketingToggle ? marketingToggle.checked : false,
            third_party: thirdPartyToggle ? thirdPartyToggle.checked : false
        };

        saveConsent(customConsent);
    }

    function logConsentToServer(consent) {
        let anonId = localStorage.getItem('rayongcoop_anon_id');
        if (!anonId) {
            anonId = 'anon_' + Math.random().toString(36).substring(2, 15) + Date.now().toString(36);
            localStorage.setItem('rayongcoop_anon_id', anonId);
        }

        fetch(window.APP_URL + '/api/cookie-consent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                anonymous_consent_id: anonId,
                consent_version: consent.version,
                necessary: consent.necessary ? 1 : 0,
                functional: consent.functional ? 1 : 0,
                analytics: consent.analytics ? 1 : 0,
                marketing: consent.marketing ? 1 : 0,
                third_party: consent.third_party ? 1 : 0
            })
        }).catch(err => {
            // Silently handle logging errors
        });
    }

    function bindEvents() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('#btnAcceptAllCookies') || e.target.closest('.btn-accept-all-cookies')) {
                acceptAll();
            } else if (e.target.closest('#btnRejectCookies') || e.target.closest('.btn-reject-cookies')) {
                rejectNonEssential();
            } else if (e.target.closest('#btnCookieSettings') || e.target.closest('.btn-open-cookie-settings')) {
                showModal();
            } else if (e.target.closest('#btnSaveCookiePreferences')) {
                saveCustomPreferences();
            }
        });
    }

    return {
        init: init,
        showSettings: showModal,
        acceptAll: acceptAll,
        rejectNonEssential: rejectNonEssential,
        getConsent: getConsent
    };
})();

document.addEventListener('DOMContentLoaded', function() {
    CookieConsent.init();
});
