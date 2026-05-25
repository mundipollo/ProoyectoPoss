<style>
    html { scroll-behavior: smooth; background: #fff; }
    .header-shell { transition: all .3s ease; }
    .header-solid {
        background: rgba(255,255,255,.88);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0,0,0,.08);
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
    }
    .header-solid .brand-link { color: #171717; }
    .header-solid .nav-link { color: #525252; }
    .header-solid .nav-link:hover { color: #171717; }
    .header-solid .pill-link {
        color: #171717;
        border-color: #d4d4d4;
        background: transparent;
    }
    .header-solid .pill-link:hover {
        background: #171717;
        color: #fff;
        border-color: #171717;
    }
    .header-shell:not(.header-solid) .brand-link,
    .header-shell:not(.header-solid) .nav-link { color: #fff; }
    .header-shell:not(.header-solid) .nav-link { color: rgba(255,255,255,.82); }
    .header-shell:not(.header-solid) .nav-link:hover { color: #fff; }
    .header-shell:not(.header-solid) .pill-link {
        color: #fff;
        border-color: rgba(255,255,255,.55);
        background: rgba(255,255,255,.06);
    }
    .header-shell:not(.header-solid) .pill-link:hover {
        background: #fff;
        color: #111827;
        border-color: #fff;
    }
    .reveal { opacity: 0; transform: translateY(32px); transition: opacity .7s ease, transform .7s ease; }
    .reveal.is-visible { opacity: 1; transform: translateY(0); }
    .scroll-card {
        transform: translateY(30px) scale(.98);
        opacity: .92;
        transition: transform .35s ease, opacity .35s ease, box-shadow .35s ease;
        will-change: transform;
    }
    .scroll-card.is-visible {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
    .product-card {
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #e5e5e5;
        background: #fff;
    }
    .product-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,.08); }
    .pill-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .5rem 1rem;
        font-size: .875rem;
        border-radius: 9999px;
        border: 1px solid #d4d4d4;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }
    .pill-btn:hover { background: #171717; color: #fff; border-color: #171717; }
    .pill-btn-primary {
        background: #171717;
        color: #fff;
        border-color: #171717;
    }
    .pill-btn-primary:hover { background: #404040; border-color: #404040; }
    .field-input-store {
        width: 100%;
        border: none;
        border-bottom: 1px solid #e4e4e7;
        background: transparent;
        padding: 8px 0;
        font-size: 15px;
        color: #111827;
        outline: none;
    }
    .field-input-store:focus { border-bottom-color: #9ca3af; }
    .store-alert {
        border-radius: 9999px;
        padding: .75rem 1.25rem;
        font-size: .875rem;
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(0,0,0,.08);
    }
    .store-hero {
        position: relative;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
        min-height: 320px;
        height: 50vh;
        max-height: 520px;
    }
    @media (min-width: 768px) {
        .store-hero {
            min-height: 380px;
            height: 55vh;
        }
    }
    .store-hero-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        z-index: 0;
    }
    .store-hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 1;
    }
    .hero-title span {
        display: inline-block;
        opacity: 0;
        transform: translateY(60px);
        animation: rise .7s ease forwards;
    }
    .hero-title {
        font-size: clamp(110px, 17vw, 320px) !important;
        line-height: .86 !important;
        font-weight: 800 !important;
        letter-spacing: -0.04em !important;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .hero-title span:nth-child(1) { animation-delay: .05s; }
    .hero-title span:nth-child(2) { animation-delay: .13s; }
    .hero-title span:nth-child(3) { animation-delay: .21s; }
    .hero-title span:nth-child(4) { animation-delay: .29s; }
    .hero-title span:nth-child(5) { animation-delay: .37s; }
    .hero-title span:nth-child(6) { animation-delay: .45s; }
    .hero-title span:nth-child(7) { animation-delay: .53s; }
    .hero-title span:nth-child(8) { animation-delay: .61s; }
    .hero-title span:nth-child(9) { animation-delay: .69s; }
    .hero-title span:nth-child(10) { animation-delay: .77s; }
    .hero-subtitle {
        opacity: 0;
        transform: translateY(32px);
        animation: rise .7s ease .55s forwards;
        margin-top: 1rem;
        max-width: 36rem;
        font-size: 1rem;
        line-height: 1.5;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.85);
    }
    @media (min-width: 768px) {
        .hero-subtitle {
            font-size: 1.125rem;
            line-height: 1.6;
        }
    }
    @keyframes rise {
        to { opacity: 1; transform: translateY(0); }
    }
    .store-hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 7rem 1.5rem 3rem;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    .store-hero-heading {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .store-hero-content--cart {
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
    }
    .hero-cart-link {
        opacity: 0;
        animation: rise .7s ease .7s forwards;
        color: #fff;
        border-color: rgba(255, 255, 255, 0.55);
        background: rgba(255, 255, 255, 0.06);
        text-decoration: none;
    }
    .hero-cart-link:hover {
        background: #fff;
        color: #111827;
        border-color: #fff;
    }
    .checkout-tabs {
        display: inline-flex;
        flex-wrap: wrap;
        gap: .5rem;
        padding: .35rem;
        border-radius: 9999px;
        border: 1px solid #e5e5e5;
        background: #fafafa;
        margin-bottom: 2rem;
    }
    .checkout-tab {
        padding: .55rem 1.25rem;
        border-radius: 9999px;
        font-size: .875rem;
        font-weight: 500;
        color: #525252;
        text-decoration: none;
        transition: background .2s ease, color .2s ease;
        border: none;
        background: transparent;
        cursor: pointer;
    }
    .checkout-tab:hover { color: #171717; }
    .checkout-tab.is-active {
        background: #171717;
        color: #fff;
    }
    .checkout-tab:disabled {
        opacity: .45;
        cursor: not-allowed;
    }
    .checkout-panel { display: none; }
    .checkout-panel.is-active { display: block; }
    .payment-field {
        width: 100%;
        border-radius: 9999px;
        border: 1px solid #d4d4d4;
        padding: .75rem 1.25rem;
        font-size: .875rem;
        outline: none;
    }
    .payment-field:focus {
        border-color: #171717;
    }
    .payment-method {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .payment-method-option {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1rem;
        border-radius: 9999px;
        border: 1px solid #e5e5e5;
        font-size: .875rem;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease;
    }
    .payment-method-option:has(input:checked) {
        border-color: #171717;
        background: #f5f5f5;
    }
    .payment-method-option input {
        accent-color: #171717;
    }
    .order-success-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 9999px;
        background: #171717;
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 1.5rem;
        margin: 0 auto 1.25rem;
    }
    @media (min-width: 768px) {
        .store-hero-content {
            padding-bottom: 3.5rem;
        }
    }
    .product-card .thumb {
        position: relative;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: #f5f5f5;
    }
    .product-card .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform .4s ease;
    }
    .product-card:hover .thumb img {
        transform: scale(1.04);
    }
    .product-card .thumb .thumb-label {
        position: absolute;
        left: 12px;
        bottom: 12px;
        padding: 4px 10px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(6px);
        font-size: 11px;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #525252;
    }
</style>
