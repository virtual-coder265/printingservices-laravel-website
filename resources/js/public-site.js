document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenuRoot = document.getElementById('mobile-menu-root');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

    const refreshIcons = () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };

    const setMobileMenuOpen = (open) => {
        if (!mobileMenuBtn || !mobileMenuRoot) {
            return;
        }

        mobileMenuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        mobileMenuRoot.classList.toggle('hidden', !open);
        mobileMenuRoot.setAttribute('aria-hidden', open ? 'false' : 'true');
        document.body.classList.toggle('overflow-hidden', open);

        const openIcon = mobileMenuBtn.querySelector('.mobile-menu-icon-open');
        const closeIcon = mobileMenuBtn.querySelector('.mobile-menu-icon-close');

        if (openIcon && closeIcon) {
            openIcon.classList.toggle('hidden', open);
            closeIcon.classList.toggle('hidden', !open);
        }

        if (open) {
            refreshIcons();
        }
    };

    if (mobileMenuBtn && mobileMenuRoot) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
            setMobileMenuOpen(!isOpen);
        });

        mobileMenuClose?.addEventListener('click', () => setMobileMenuOpen(false));
        mobileMenuBackdrop?.addEventListener('click', () => setMobileMenuOpen(false));

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setMobileMenuOpen(false);
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1280) {
                setMobileMenuOpen(false);
            }
        });
    }

    const revealElements = document.querySelectorAll('.reveal-fade-in, .reveal-fade-up');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-active');
                        obs.unobserve(entry.target);
                    }
                });
            },
            {
                root: null,
                rootMargin: '0px 0px -80px 0px',
                threshold: 0.15,
            }
        );

        revealElements.forEach((element) => observer.observe(element));
    } else {
        revealElements.forEach((element) => element.classList.add('reveal-active'));
    }

    const heroSliders = document.querySelectorAll('[data-hero-slider]');
    const reduceMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

    heroSliders.forEach((slider) => {
        const heroSection = slider.closest('[data-hero-slider-section]') || slider.parentElement;
        const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
        const dots = heroSection ? Array.from(heroSection.querySelectorAll('[data-hero-slide-dot]')) : [];
        const slideInterval = Number(slider.getAttribute('data-hero-slider-interval')) || 6500;

        if (slides.length === 0) {
            return;
        }

        let currentIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
        let autoplayId = null;

        if (currentIndex < 0) {
            currentIndex = 0;
        }

        const setActiveSlide = (nextIndex) => {
            const normalizedIndex = (nextIndex + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('is-active', slideIndex === normalizedIndex);
            });

            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === normalizedIndex;
                dot.classList.toggle('active', isActive);
                dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            currentIndex = normalizedIndex;
        };

        const stopAutoplay = () => {
            if (autoplayId !== null) {
                window.clearInterval(autoplayId);
                autoplayId = null;
            }
        };

        const startAutoplay = () => {
            if (slides.length < 2) {
                return;
            }

            stopAutoplay();
            autoplayId = window.setInterval(() => {
                setActiveSlide(currentIndex + 1);
            }, slideInterval);
        };

        dots.forEach((dot, dotIndex) => {
            dot.addEventListener('click', () => {
                setActiveSlide(dotIndex);
                startAutoplay();
            });
        });

        if (heroSection) {
            heroSection.addEventListener('mouseenter', stopAutoplay);
            heroSection.addEventListener('mouseleave', startAutoplay);
            heroSection.addEventListener('focusin', stopAutoplay);
            heroSection.addEventListener('focusout', (event) => {
                if (!heroSection.contains(event.relatedTarget)) {
                    startAutoplay();
                }
            });
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        setActiveSlide(currentIndex);
        slider.classList.toggle('hero-slider-reduced-motion', reduceMotionQuery.matches);

        if (typeof reduceMotionQuery.addEventListener === 'function') {
            reduceMotionQuery.addEventListener('change', (event) => {
                slider.classList.toggle('hero-slider-reduced-motion', event.matches);
            });
        } else if (typeof reduceMotionQuery.addListener === 'function') {
            reduceMotionQuery.addListener((event) => {
                slider.classList.toggle('hero-slider-reduced-motion', event.matches);
            });
        }

        startAutoplay();
    });

    const contactForm = document.getElementById('secure-contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', (event) => {
            let hasErrors = false;

            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const serviceInput = document.getElementById('service');
            const messageInput = document.getElementById('message');

            document.querySelectorAll('.form-error-msg').forEach((el) => {
                el.textContent = '';
            });

            if (!nameInput.value.trim() || nameInput.value.trim().length < 2) {
                showError(nameInput, 'Full name is required (min 2 chars).');
                hasErrors = true;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim() || !emailRegex.test(emailInput.value.trim())) {
                showError(emailInput, 'Please enter a valid email address.');
                hasErrors = true;
            }

            if (phoneInput.value.trim()) {
                const phoneRegex = /^[+0-9\-\(\)\s]{7,25}$/;
                if (!phoneRegex.test(phoneInput.value.trim())) {
                    showError(phoneInput, 'Please enter a valid phone number.');
                    hasErrors = true;
                }
            }

            if (!serviceInput.value) {
                showError(serviceInput, 'Please select a service category.');
                hasErrors = true;
            }

            if (!messageInput.value.trim() || messageInput.value.trim().length < 10) {
                showError(messageInput, 'Message must be at least 10 characters.');
                hasErrors = true;
            }

            if (hasErrors) {
                event.preventDefault();
                return;
            }

            event.preventDefault();

            const mailto = contactForm.getAttribute('data-mailto');
            if (mailto) {
                const subject = encodeURIComponent('Quotation Request - Department of Printing Services');
                const body = encodeURIComponent(
                    `Name: ${nameInput.value.trim()}\nPhone: ${phoneInput.value.trim()}\nEmail: ${emailInput.value.trim()}\nService: ${serviceInput.options[serviceInput.selectedIndex].text}\n\n${messageInput.value.trim()}`
                );
                window.location.href = `${mailto}?subject=${subject}&body=${body}`;
            }
        });
    }

    function showError(inputElement, errorMessage) {
        const errorContainer = inputElement.closest('div')?.querySelector('.form-error-msg');
        if (errorContainer) {
            errorContainer.textContent = errorMessage;
        }
        inputElement.focus();
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
