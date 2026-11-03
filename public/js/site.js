document.addEventListener('DOMContentLoaded', () => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Mobile navigation
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('#mainNav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', String(open));
            toggle.textContent = open ? '×' : '☰';
        });
        nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
            nav.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); toggle.textContent = '☰';
        }));
    }

    // Smooth horizontal carousel controls
    document.querySelectorAll('[data-scroll]').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.scroll);
            if (target) target.scrollBy({ left: Number(button.dataset.direction || 1) * Math.max(260, target.clientWidth * .8), behavior: reducedMotion ? 'auto' : 'smooth' });
        });
    });

    // Hero slider: automatic, manual arrows and dots, pauses on hover/focus.
    const hero = document.querySelector('[data-hero-slider]');
    if (hero) {
        const title = hero.querySelector('[data-hero-title]');
        const eyebrow = hero.querySelector('[data-hero-eyebrow]');
        const description = hero.querySelector('[data-hero-description]');
        const current = hero.querySelector('[data-hero-current]');
        const dots = [...hero.querySelectorAll('.hero-dot')];
        const slides = [
            { eyebrow: 'YOUR HEALTH, OUR PRIORITY', title: 'Advanced healthcare.<br><em>Compassionate people.</em>', description: 'Expert doctors, modern diagnostics and patient-first care — all under one roof.', image: 'https://images.unsplash.com/photo-1584982751601-97dcc096659c?w=2000' },
            { eyebrow: 'EXPERT CARE, CLOSER TO YOU', title: 'The right care.<br><em>At the right time.</em>', description: 'Find experienced specialists and explore trusted healthcare services for your family.', image: 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=2000' },
            { eyebrow: 'MODERN DIAGNOSTICS', title: 'Better insights.<br><em>Better decisions.</em>', description: 'Access modern diagnostic facilities and patient-focused support in one place.', image: 'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=2000' }
        ];
        let index = 0, timer = null;
        const show = next => {
            index = (next + slides.length) % slides.length;
            const slide = slides[index];
            hero.classList.add('is-changing');
            window.setTimeout(() => {
                eyebrow.textContent = slide.eyebrow;
                title.innerHTML = slide.title;
                description.textContent = slide.description;
                hero.style.setProperty('--hero-image', `url("${slide.image}")`);
                if (current) current.textContent = String(index + 1).padStart(2, '0');
                dots.forEach((dot, i) => { dot.classList.toggle('active', i === index); dot.setAttribute('aria-current', i === index ? 'true' : 'false'); });
                hero.classList.remove('is-changing');
            }, reducedMotion ? 0 : 180);
        };
        const stop = () => { if (timer) window.clearInterval(timer); timer = null; };
        const start = () => { if (!reducedMotion) { stop(); timer = window.setInterval(() => show(index + 1), 6000); } };
        hero.querySelector('.hero-prev')?.addEventListener('click', () => { show(index - 1); start(); });
        hero.querySelector('.hero-next')?.addEventListener('click', () => { show(index + 1); start(); });
        dots.forEach((dot, i) => dot.addEventListener('click', () => { show(i); start(); }));
        hero.addEventListener('mouseenter', stop); hero.addEventListener('mouseleave', start);
        hero.addEventListener('focusin', stop); hero.addEventListener('focusout', start);
        hero.style.setProperty('--hero-image', `url("${slides[0].image}")`);
        start();
    }

    // Scroll reveal animations
    const revealTargets = document.querySelectorAll('.section-heading, .location-card, .accreditation-inner, .about-photo, .about-copy, .department-card, .video-copy, .video-placeholder, .doctor-card, .package-card, .news-card, .sister-inner, .footer-grid > *');
    revealTargets.forEach((el, i) => { el.classList.add('motion-reveal'); el.style.setProperty('--reveal-delay', `${(i % 4) * 70}ms`); });
    if ('IntersectionObserver' in window && !reducedMotion) {
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
        }), { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });
        revealTargets.forEach(el => observer.observe(el));
    } else revealTargets.forEach(el => el.classList.add('is-visible'));

    // Count-up statistics when the about section enters the viewport.
    const stats = document.querySelector('.stats-grid');
    if (stats && 'IntersectionObserver' in window && !reducedMotion) {
        const counterObserver = new IntersectionObserver(entries => entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            stats.querySelectorAll('strong').forEach(node => {
                const match = node.textContent.trim().match(/^(\d+)(.*)$/);
                if (!match) return;
                const target = Number(match[1]), suffix = match[2]; let startTime = null;
                const tick = time => { if (!startTime) startTime = time; const progress = Math.min((time - startTime) / 1100, 1); node.textContent = `${Math.floor(target * (1 - Math.pow(1 - progress, 3)))}${suffix}`; if (progress < 1) requestAnimationFrame(tick); };
                requestAnimationFrame(tick);
            });
            counterObserver.disconnect();
        }), { threshold: .5 });
        counterObserver.observe(stats);
    }

    // Back-to-top button and reading progress line
    const progress = document.createElement('div'); progress.className = 'reading-progress'; progress.setAttribute('aria-hidden', 'true'); document.body.appendChild(progress);
    const topButton = document.createElement('button'); topButton.className = 'back-to-top'; topButton.type = 'button'; topButton.setAttribute('aria-label', 'Back to top'); topButton.textContent = '↑'; document.body.appendChild(topButton);
    topButton.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' }));
    const updateScrollUI = () => {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.transform = `scaleX(${max > 0 ? window.scrollY / max : 0})`;
        topButton.classList.toggle('visible', window.scrollY > 450);
    };
    window.addEventListener('scroll', updateScrollUI, { passive: true }); updateScrollUI();
});
