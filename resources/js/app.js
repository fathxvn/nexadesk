import Alpine from 'alpinejs';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

window.Alpine = Alpine;

Alpine.start();

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    const animateIfExists = (selector, vars) => {
        if (document.querySelector(selector)) {
            gsap.from(selector, vars);
        }
    };

    animateIfExists('.hero-content', {
        opacity: 0,
        y: 40,
        duration: 1,
        ease: 'power3.out',
    });

    animateIfExists('.hero-image', {
        opacity: 0,
        x: 80,
        duration: 1.2,
        ease: 'power3.out',
    });

    if (document.querySelector('.dashboard-preview')) {
        gsap.to('.dashboard-preview', {
            y: -12,
            duration: 2.5,
            repeat: -1,
            yoyo: true,
            ease: 'power1.inOut',
        });
    }

    document.querySelectorAll('.reveal-section').forEach((section) => {
        gsap.from(section, {
            opacity: 0,
            y: 80,
            duration: 1,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    });

    if (document.querySelector('.features-grid .feature-card')) {
        gsap.from('.features-grid .feature-card', {
            opacity: 0,
            y: 50,
            stagger: 0.12,
            duration: 0.8,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '.features-grid',
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    }

    if (document.querySelector('.workflow-section .workflow-step')) {
        gsap.from('.workflow-section .workflow-step', {
            opacity: 0,
            scale: 0.92,
            stagger: 0.16,
            duration: 0.8,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '.workflow-section',
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    }

    if (document.querySelector('.stats-section .stat-card')) {
        gsap.from('.stats-section .stat-card', {
            opacity: 0,
            y: 40,
            stagger: 0.12,
            duration: 0.8,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '.stats-section',
                start: 'top 85%',
                toggleActions: 'play none none none',
            },
        });
    }
});
