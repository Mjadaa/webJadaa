// i18n - Internationalization Library for JadaaMart
// Supports Arabic (RTL) and English (LTR)

class I18n {
    constructor() {
        this.currentLang = localStorage.getItem('lang') || 'en';
        this.translations = {};
        this.fallbackLang = 'en';
    }

    async init() {
        await this.loadTranslations(this.currentLang);
        this.applyLanguage();
        this.setupLanguageToggle();
    }

    async loadTranslations(lang) {
        try {
            const response = await fetch(`/lang/${lang}.json`);
            if (!response.ok) throw new Error('Translation file not found');
            this.translations = await response.json();
            this.currentLang = lang;
        } catch (error) {
            console.error(`Failed to load ${lang} translations:`, error);
            if (lang !== this.fallbackLang) {
                await this.loadTranslations(this.fallbackLang);
            }
        }
    }

    t(key) {
        const keys = key.split('.');
        let value = this.translations;

        for (const k of keys) {
            value = value?.[k];
            if (value === undefined) {
                console.warn(`Translation key not found: ${key}`);
                return key;
            }
        }

        return value;
    }

    async switchLanguage(lang) {
        if (lang === this.currentLang) return;

        await this.loadTranslations(lang);
        localStorage.setItem('lang', lang);
        this.applyLanguage();
    }

    applyLanguage() {
        // Set HTML direction and lang attribute
        const isRTL = this.currentLang === 'ar';
        document.documentElement.setAttribute('dir', isRTL ? 'rtl' : 'ltr');
        document.documentElement.setAttribute('lang', this.currentLang);

        // Add RTL class to body for styling
        document.body.classList.toggle('rtl', isRTL);
        document.body.classList.toggle('ltr', !isRTL);

        // Translate all elements with data-i18n attribute
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.getAttribute('data-i18n');
            element.textContent = this.t(key);
        });

        // Translate placeholders
        document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
            const key = element.getAttribute('data-i18n-placeholder');
            element.placeholder = this.t(key);
        });

        // Translate titles
        document.querySelectorAll('[data-i18n-title]').forEach(element => {
            const key = element.getAttribute('data-i18n-title');
            element.title = this.t(key);
        });

        // Update language toggle button text
        this.updateToggleButton();
    }

    setupLanguageToggle() {
        // Create language toggle button if it doesn't exist
        const existingToggle = document.getElementById('lang-toggle');
        if (existingToggle) {
            existingToggle.addEventListener('click', () => {
                const newLang = this.currentLang === 'en' ? 'ar' : 'en';
                this.switchLanguage(newLang);
            });
            this.updateToggleButton();
        }
    }

    updateToggleButton() {
        const toggle = document.getElementById('lang-toggle');
        if (toggle) {
            const icon = this.currentLang === 'en' ? '🇸🇦 العربية' : '🇺🇸 English';
            toggle.innerHTML = icon;
        }
    }

    getCurrentLang() {
        return this.currentLang;
    }

    isRTL() {
        return this.currentLang === 'ar';
    }
}

// Create global i18n instance
const i18n = new I18n();

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => i18n.init());
} else {
    i18n.init();
}
