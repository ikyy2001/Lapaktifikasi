/**
 * Core Interactive Scripts for Lapaktifikasi Landing / Public Pages
 */

(function () {
    'use strict';

    // MOBILE MENU DRAWER
    window.toggleMobileMenu = function () {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        if (!menu || !overlay) return;
        const isOpen = menu.classList.contains('open');
        if (isOpen) {
            window.closeMobileMenu();
        } else {
            menu.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeMobileMenu = function () {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        if (menu) menu.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    // NAVBAR SCROLL HANDLER
    const navbar = document.getElementById('navbar');
    const handleNavbarScroll = function () {
        if (navbar) {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        }
    };
    window.addEventListener('scroll', handleNavbarScroll, { passive: true });
    handleNavbarScroll();

    // AUTH LOGIN MODAL
    const tabLabels = {
        customer: 'Email Pelanggan',
        admin: 'Email Admin / Seller'
    };

    window.switchModalTab = function (btn, role) {
        document.querySelectorAll('.login-tab').forEach(function (t) {
            t.classList.remove('active');
        });
        if (btn) btn.classList.add('active');

        const label = document.getElementById('modal-username-label');
        const emailInput = document.getElementById('modal-email');
        if (label && tabLabels[role]) {
            label.textContent = tabLabels[role];
        }
        if (emailInput && tabLabels[role]) {
            emailInput.placeholder = 'Masukkan ' + tabLabels[role].toLowerCase();
        }
    };

    window.openModal = function (role) {
        const modal = document.getElementById('login-modal');
        if (!modal) return;
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';

        if (role) {
            const targetBtn = document.querySelector(`.login-tab[data-role="${role}"]`);
            if (targetBtn) {
                window.switchModalTab(targetBtn, role);
            }
        }

        setTimeout(function () {
            const emailInput = document.getElementById('modal-email');
            if (emailInput) emailInput.focus();
        }, 120);
    };

    window.closeModal = function () {
        const modal = document.getElementById('login-modal');
        if (modal) modal.classList.remove('open');
        document.body.style.overflow = '';
    };

    window.closeModalOverlay = function (e) {
        if (e && e.target && e.target.id === 'login-modal') {
            window.closeModal();
        }
    };

    window.togglePassword = function () {
        const pass = document.getElementById('modal-password');
        if (pass) {
            pass.type = pass.type === 'password' ? 'text' : 'password';
        }
    };

    // FAQ ACCORDION
    window.toggleFaq = function (btn) {
        if (!btn) return;
        const answer = btn.nextElementSibling;
        if (!answer) return;
        const isOpen = answer.classList.contains('open');

        // Close other FAQ items
        document.querySelectorAll('.faq-answer').forEach(function (a) {
            a.classList.remove('open');
        });
        document.querySelectorAll('.faq-question').forEach(function (q) {
            q.classList.remove('open');
        });

        if (!isOpen) {
            answer.classList.add('open');
            btn.classList.add('open');
        }
    };

    // BACK TO TOP BUTTON
    const initBackToTop = function () {
        const btn = document.querySelector('.back-to-top');
        if (!btn) return;

        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        }, { passive: true });

        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    };

    // SCROLL REVEAL OBSERVER
    const initScrollReveal = function () {
        const reveals = document.querySelectorAll('.reveal');
        if (!reveals.length || !('IntersectionObserver' in window)) {
            reveals.forEach(function (r) { r.classList.add('visible'); });
            return;
        }

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });

        reveals.forEach(function (r) {
            observer.observe(r);
        });
    };

    // SMOOTH SCROLL FOR IN-PAGE HASH LINKS
    const initSmoothScroll = function () {
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') return;
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    };

    // INITIALIZATION
    document.addEventListener('DOMContentLoaded', function () {
        initBackToTop();
        initScrollReveal();
        initSmoothScroll();
    });
})();
