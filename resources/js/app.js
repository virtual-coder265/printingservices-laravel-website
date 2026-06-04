import './bootstrap';
import './public-site';

import Alpine from 'alpinejs';
import { registerQuotationWizard } from './quotation-wizard';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    registerQuotationWizard(Alpine);

    Alpine.data('siteHeader', ({ heroMode = false } = {}) => ({
        heroMode,
        stickyVisible: !heroMode,
        mobileOpen: false,
        searchOpen: false,
        searchQuery: '',
        activeDesktopMenu: null,
        activeMobileSection: null,
        desktopCloseTimeout: null,
        stickyThreshold: 120,

        init() {
            this.measureHeroThreshold();
            this.updateScrollState();

            window.addEventListener(
                'scroll',
                () => {
                    this.updateScrollState();
                },
                { passive: true }
            );

            window.addEventListener(
                'resize',
                () => {
                    this.measureHeroThreshold();
                    this.updateScrollState();
                },
                { passive: true }
            );
        },

        measureHeroThreshold() {
            if (!this.heroMode) {
                this.stickyThreshold = 0;
                return;
            }

            const hero = document.querySelector('[data-home-hero]');

            if (!hero) {
                this.stickyThreshold = 0;
                this.heroMode = false;
                this.stickyVisible = true;
                return;
            }

            this.stickyThreshold = Math.max(140, hero.offsetHeight * 0.48);
        },

        updateScrollState() {
            if (!this.heroMode) {
                this.stickyVisible = true;
                return;
            }

            const nextStickyState = window.scrollY >= this.stickyThreshold;

            if (nextStickyState !== this.stickyVisible) {
                this.searchOpen = false;
            }

            this.stickyVisible = nextStickyState;

            if (this.stickyVisible) {
                this.activeDesktopMenu = null;
            }
        },

        toggleSearch(refName = null) {
            this.searchOpen = !this.searchOpen;

            if (this.searchOpen && refName) {
                this.focusSearch(refName);
            }
        },

        focusSearch(refName) {
            this.$nextTick(() => {
                const input = this.$refs[refName];

                if (input) {
                    input.focus();
                    input.select();
                }
            });
        },

        closeSearch() {
            this.searchOpen = false;
        },

        openDesktopMenu(menu) {
            this.cancelDesktopClose();
            this.activeDesktopMenu = menu;
        },

        scheduleDesktopClose() {
            this.cancelDesktopClose();
            this.desktopCloseTimeout = window.setTimeout(() => {
                this.activeDesktopMenu = null;
            }, 120);
        },

        cancelDesktopClose() {
            if (this.desktopCloseTimeout) {
                window.clearTimeout(this.desktopCloseTimeout);
            }
        },

        isDesktopMenuOpen(menu) {
            return this.activeDesktopMenu === menu;
        },

        toggleMobileMenu() {
            this.mobileOpen = !this.mobileOpen;
            this.searchOpen = false;

            if (!this.mobileOpen) {
                this.activeMobileSection = null;
            }
        },

        toggleMobileSection(section) {
            this.activeMobileSection = this.activeMobileSection === section ? null : section;
        },

        isMobileSectionOpen(section) {
            return this.activeMobileSection === section;
        },

        closeAll() {
            this.mobileOpen = false;
            this.searchOpen = false;
            this.activeMobileSection = null;
            this.activeDesktopMenu = null;
            this.cancelDesktopClose();
        },
    }));

    Alpine.data('heroSlider', (slides = []) => ({
        slides,
        active: 0,
        progress: 0,
        duration: 7000,
        intervalId: null,
        hovering: false,

        init() {
            if (!this.slides.length) {
                return;
            }

            this.start();

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.pause();
                } else {
                    this.resume();
                }
            });
        },

        start() {
            this.clear();
            this.progress = 0;

            this.intervalId = window.setInterval(() => {
                if (this.hovering) {
                    return;
                }

                this.progress += 100 / (this.duration / 100);

                if (this.progress >= 100) {
                    this.next();
                }
            }, 100);
        },

        goTo(index) {
            this.active = index;
            this.restart();
        },

        next() {
            this.active = (this.active + 1) % this.slides.length;
            this.restart();
        },

        previous() {
            this.active = (this.active - 1 + this.slides.length) % this.slides.length;
            this.restart();
        },

        restart() {
            this.progress = 0;
            this.start();
        },

        pause() {
            this.hovering = true;
        },

        resume() {
            this.hovering = false;
        },

        clear() {
            if (this.intervalId) {
                window.clearInterval(this.intervalId);
            }
        },
    }));
});

Alpine.start();
