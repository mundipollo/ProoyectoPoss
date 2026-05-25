<script>
    const header = document.getElementById('store-header');
    const reveals = document.querySelectorAll('.reveal');
    const scrollCards = document.querySelectorAll('.scroll-card:not(.product-card)');

    const onScroll = () => {
        if (header) {
            if (window.scrollY > 40) header.classList.add('header-solid');
            else header.classList.remove('header-solid');
        }

        const trigger = window.innerHeight * 0.88;
        reveals.forEach((el) => {
            if (el.getBoundingClientRect().top < trigger) el.classList.add('is-visible');
        });

        scrollCards.forEach((card) => {
            const rect = card.getBoundingClientRect();
            const start = window.innerHeight * 0.95;
            const progress = Math.max(0, Math.min(1, (start - rect.top) / (window.innerHeight * 0.9)));
            const translate = 34 - (progress * 34);
            const scale = 0.97 + (progress * 0.03);
            card.style.transform = `translateY(${translate}px) scale(${scale})`;
            card.style.opacity = `${0.88 + (progress * 0.12)}`;
            if (progress > 0.12) card.classList.add('is-visible');
        });
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
</script>
