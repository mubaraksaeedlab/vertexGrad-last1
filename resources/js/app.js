import './bootstrap';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Alpine from 'alpinejs';
import NotificationCenter from './notification-center';

window.Alpine = Alpine;
Alpine.start();

gsap.registerPlugin(ScrollTrigger);

window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

const THEME_KEY = 'vertexgrad_theme';
const DEFAULT_THEME = 'brand';
const ALLOWED_THEMES = ['brand', 'dark', 'light'];

window.VertexGradUI = {
    applyTheme(theme) {
        const selectedTheme = ALLOWED_THEMES.includes(theme) ? theme : DEFAULT_THEME;
        document.documentElement.setAttribute('data-theme', selectedTheme);
        localStorage.setItem(THEME_KEY, selectedTheme);
    },

    getTheme() {
        const savedTheme = localStorage.getItem(THEME_KEY);
        return ALLOWED_THEMES.includes(savedTheme) ? savedTheme : DEFAULT_THEME;
    },

    init() {
        this.applyTheme(this.getTheme());
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.VertexGradUI.init();

    gsap.utils.toArray('.project-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top bottom-=100',
                toggleActions: 'play none none reverse'
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.1,
            ease: 'power2.out'
        });
    });

    const frontendBell = document.getElementById('frontend-notification-bell');
    if (frontendBell) {
        NotificationCenter.start({
            latestUrl: frontendBell.dataset.latestUrl,
            badgeSelector: '#frontendUnreadBadge',
            unreadTextSelector: '#frontendUnreadText',
            listSelector: '#frontendNotificationList',
            readRouteBuilder: (id) => `/notifications/${id}/read`,
            hiddenClass: 'hidden',
        });
    }

    const adminBell = document.getElementById('admin-notification-bell');
    if (adminBell) {
        NotificationCenter.start({
            latestUrl: adminBell.dataset.latestUrl,
            badgeSelector: '#adminUnreadBadge',
            unreadTextSelector: '#adminUnreadText',
            listSelector: '#adminNotificationList',
            readRouteBuilder: (id) => `/admin/notifications/${id}/read`,
            hiddenClass: 'd-none',
        });
    }
});