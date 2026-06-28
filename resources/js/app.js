import Alpine from 'alpinejs';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

window.Alpine = Alpine;

Alpine.start();

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduceMotion) {
        return;
    }

    const toArray = (selector) => gsap.utils.toArray(selector);

    const heroContent = toArray('.hero-content');
    if (heroContent.length) {
        gsap.fromTo(heroContent, { opacity: 0, y: 40 }, {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
        });
    }

    const heroImage = toArray('.hero-image');
    if (heroImage.length) {
        gsap.fromTo(heroImage, { opacity: 0, x: 80 }, {
            opacity: 1,
            x: 0,
            duration: 1.2,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
        });
    }

    const dashboardPreview = toArray('.dashboard-preview');
    if (dashboardPreview.length) {
        gsap.to(dashboardPreview, {
            y: -12,
            duration: 2.5,
            repeat: -1,
            yoyo: true,
            ease: 'power1.inOut',
        });
    }

    const gradientBlobs = toArray('.gradient-blob');
    if (gradientBlobs.length) {
        gradientBlobs.forEach((blob, index) => {
            gsap.to(blob, {
                x: index % 2 === 0 ? 10 : -10,
                y: index % 2 === 0 ? 20 : -20,
                duration: 5 + index,
                repeat: -1,
                yoyo: true,
                ease: 'power1.inOut',
            });
        });
    }

    toArray('.reveal-section').forEach((section) => {
        const targets = section.querySelectorAll('.section-copy');
        gsap.fromTo(targets.length ? targets : [section], { opacity: 0, y: 36 }, {
            opacity: 1,
            y: 0,
            duration: 0.9,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
            scrollTrigger: {
                trigger: section,
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    });

    toArray('.features-grid').forEach((grid) => {
        const cards = grid.querySelectorAll('.feature-card');
        if (!cards.length) {
            return;
        }

        gsap.fromTo(cards, { opacity: 0, y: 40, scale: 0.96 }, {
            opacity: 1,
            y: 0,
            scale: 1,
            stagger: 0.12,
            duration: 0.8,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
            scrollTrigger: {
                trigger: grid,
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    });

    const workflowSection = document.querySelector('.workflow-section');
    if (workflowSection) {
        const workflowLine = workflowSection.querySelector('.workflow-line');
        const workflowSteps = workflowSection.querySelectorAll('.workflow-step');

        if (workflowLine) {
            gsap.fromTo(workflowLine, { scaleX: 0 }, {
                scaleX: 1,
                duration: 1.1,
                ease: 'power3.out',
                transformOrigin: 'left center',
                scrollTrigger: {
                    trigger: workflowSection,
                    start: 'top 75%',
                    toggleActions: 'play none none none',
                },
            });
        }

        if (workflowSteps.length) {
            gsap.fromTo(workflowSteps, { opacity: 0, y: 40, scale: 0.95 }, {
                opacity: 1,
                y: 0,
                scale: 1,
                stagger: 0.16,
                duration: 0.8,
                ease: 'power3.out',
                clearProps: 'opacity,transform',
                scrollTrigger: {
                    trigger: workflowSection,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
            });
        }
    }

    const dashboardMockup = toArray('.dashboard-showcase-mockup');
    if (dashboardMockup.length) {
        gsap.fromTo(dashboardMockup, { opacity: 0, x: 60 }, {
            opacity: 1,
            x: 0,
            duration: 1,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
            scrollTrigger: {
                trigger: '.dashboard-showcase-section',
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
        });
    }

    const dashboardBullets = toArray('.dashboard-bullet');
    if (dashboardBullets.length) {
        gsap.fromTo(dashboardBullets, { opacity: 0, x: -24 }, {
            opacity: 1,
            x: 0,
            stagger: 0.12,
            duration: 0.6,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
            scrollTrigger: {
                trigger: '.dashboard-showcase-section',
                start: 'top 78%',
                toggleActions: 'play none none none',
            },
        });
    }

    const statCards = toArray('.stat-card');
    if (statCards.length) {
        gsap.fromTo(statCards, { opacity: 0, y: 40 }, {
            opacity: 1,
            y: 0,
            stagger: 0.12,
            duration: 0.8,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
            scrollTrigger: {
                trigger: '.stats-section',
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
        });

        gsap.fromTo('.stat-number', { scale: 0.9 }, {
            scale: 1,
            stagger: 0.12,
            duration: 0.6,
            ease: 'back.out(1.7)',
            clearProps: 'transform',
            scrollTrigger: {
                trigger: '.stats-section',
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
        });
    }

    const ctaContent = toArray('.cta-content');
    if (ctaContent.length) {
        gsap.fromTo(ctaContent, { opacity: 0, y: 36 }, {
            opacity: 1,
            y: 0,
            duration: 0.9,
            ease: 'power3.out',
            clearProps: 'opacity,transform',
            scrollTrigger: {
                trigger: '.cta-section',
                start: 'top 80%',
                toggleActions: 'play none none none',
            },
        });
    }

    const ctaBlobs = toArray('.cta-blob');
    if (ctaBlobs.length) {
        ctaBlobs.forEach((blob, index) => {
            gsap.to(blob, {
                x: index % 2 === 0 ? 18 : -18,
                y: index % 2 === 0 ? -18 : 18,
                duration: 6 + index,
                repeat: -1,
                yoyo: true,
                ease: 'power1.inOut',
            });
        });
    }
});
