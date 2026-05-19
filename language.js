/**
 * Language Switcher Logic
 * Optimized for PHP & Dropdown Refresh
 */

let siteTranslations = {};

async function loadTranslations(lang) {
    try {
        const response = await fetch('translations.json');
        if (!response.ok) throw new Error("JSON file hin argamne!");
        
        const data = await response.json();
        const cleanLang = lang.trim().toLowerCase();
        
        // Afaan filatame JSON keessa jiraachuu isaa mirkaneessi
        siteTranslations = data[cleanLang] || data['en'];
        
        // Elementoota 'data-lang' qaban qofa update godha
        applyStaticTranslations();
        
    } catch (error) {
        console.error('Error loading translations:', error);
    }
}

function applyStaticTranslations() {
    document.querySelectorAll('[data-lang]').forEach(el => {
        const key = el.getAttribute('data-lang');
        const translation = siteTranslations[key];

        if (translation) {
            if (el.tagName === 'INPUT' && (el.type === 'submit' || el.type === 'button')) {
                el.value = translation;
            } else if (el.hasAttribute('placeholder')) {
                el.setAttribute('placeholder', translation);
            } else {
                el.innerHTML = translation; // innerText irra innerHTML wayya (yoo mallattoon jiraate)
            }
        }
    });
}

// Fuulli yeroo banamu PHP irraa afaan dubbisa
document.addEventListener('DOMContentLoaded', () => {
    // <body> irratti <body data-current-lang="<?php echo $selected_lang; ?>"> galchuu kee mirkaneessi
    const currentLang = document.body.getAttribute('data-current-lang') || 'en';
    loadTranslations(currentLang);
});