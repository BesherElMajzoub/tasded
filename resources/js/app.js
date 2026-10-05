const track = (eventName, parameters = {}) => {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: eventName, ...parameters });

    if (typeof window.gtag !== 'function') {
        return;
    }

    window.gtag('event', eventName, parameters);

    const analytics = JSON.parse(document.body.dataset.analytics || '{}');
    const labelKey = eventName === 'whatsapp_click'
        ? 'google_ads_whatsapp_label'
        : eventName === 'phone_click'
            ? 'google_ads_phone_label'
            : null;

    if (labelKey && analytics.google_ads_id && analytics[labelKey]) {
        window.gtag('event', 'conversion', {
            send_to: `${analytics.google_ads_id}/${analytics[labelKey]}`,
            transaction_id: parameters.lead_ref,
            transport_type: 'beacon',
        });
    }
};

const attributionKeys = [
    'gclid', 'gbraid', 'wbraid', 'utm_source', 'utm_medium',
    'utm_campaign', 'utm_content', 'utm_term',
];

// Kept for 90 days (the gclid lifetime) so a visitor who returns later is still attributed.
const ATTRIBUTION_STORAGE_KEY = 'landing_attribution';
const ATTRIBUTION_TTL_MS = 90 * 24 * 60 * 60 * 1000;

const readStoredAttribution = () => {
    try {
        const stored = JSON.parse(localStorage.getItem(ATTRIBUTION_STORAGE_KEY) || 'null');

        return stored && stored.expires > Date.now() ? stored.data : {};
    } catch {
        return {};
    }
};

const searchParams = new URLSearchParams(window.location.search);
const urlAttribution = Object.fromEntries(
    attributionKeys
        .filter((key) => searchParams.has(key))
        .map((key) => [key, searchParams.get(key).slice(0, 200)]),
);

let attribution = readStoredAttribution();

if (Object.keys(urlAttribution).length > 0) {
    attribution = urlAttribution;

    try {
        localStorage.setItem(ATTRIBUTION_STORAGE_KEY, JSON.stringify({
            data: attribution,
            expires: Date.now() + ATTRIBUTION_TTL_MS,
        }));
    } catch {
        // Storage can be blocked (private mode); attribution still applies to this page view.
    }
}

// Short code shown in the WhatsApp message so a qualified chat can be matched back to its ad click.
const REF_ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
const generateRef = () => Array.from(
    crypto.getRandomValues(new Uint8Array(6)),
    (byte) => REF_ALPHABET[byte % REF_ALPHABET.length],
).join('');

const withRef = (whatsappHref, ref) => {
    const [base, query = ''] = whatsappHref.split('?text=');
    const text = decodeURIComponent(query);

    return `${base}?text=${encodeURIComponent(`${text}\nرقم المرجع: ${ref}`)}`;
};

const logContactClick = (channel, ref) => {
    const endpoint = document.body.dataset.clickEndpoint;

    if (!endpoint || typeof navigator.sendBeacon !== 'function') {
        return;
    }

    const payload = new FormData();
    payload.append('_token', document.body.dataset.csrf || '');
    payload.append('ref', ref);
    payload.append('channel', channel);
    payload.append('variant', document.body.dataset.variant || '');
    payload.append('page_path', window.location.pathname.slice(0, 200));
    Object.entries(attribution).forEach(([key, value]) => payload.append(key, value));

    navigator.sendBeacon(endpoint, payload);
};

document.querySelectorAll('[data-track]').forEach((link) => {
    link.dataset.baseHref = link.href;

    link.addEventListener('click', () => {
        const eventName = link.dataset.track;
        const channel = eventName === 'whatsapp_click' ? 'whatsapp' : 'phone';
        const ref = generateRef();

        if (channel === 'whatsapp') {
            link.href = withRef(link.dataset.baseHref, ref);
        }

        logContactClick(channel, ref);
        track(eventName, {
            lead_ref: ref,
            landing_variant: document.body.dataset.variant || 'default',
            link_url: link.dataset.baseHref,
            page_location: window.location.href.split('?')[0],
        });
    });
});

document.querySelectorAll('[data-faq]').forEach((item, index) => {
    item.addEventListener('toggle', () => {
        if (item.open) {
            track('faq_interaction', { faq_index: index + 1 });
        }
    });
});

const menuToggle = document.querySelector('[data-menu-toggle]');
const mobileMenu = document.querySelector('[data-mobile-menu]');

menuToggle?.addEventListener('click', () => {
    const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!isOpen));
    menuToggle.setAttribute('aria-label', isOpen ? 'فتح قائمة التنقل' : 'إغلاق قائمة التنقل');
    mobileMenu.hidden = isOpen;
});

mobileMenu?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.hidden = true;
    });
});
