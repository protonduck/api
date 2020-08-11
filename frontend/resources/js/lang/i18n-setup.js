import Vue from 'vue';
import VueI18n from 'vue-i18n';
import messages from './en';
import axios from 'axios';

Vue.use(VueI18n);

export const localeParamName = 'selectedLocale';
let selectedLocale = localStorage.getItem(localeParamName);
if (!selectedLocale) {
    selectedLocale = 'en';
}

export const i18n = new VueI18n({
    locale: 'en', // установка локализации
    fallbackLocale: 'en',
    messages: {en: messages}, // установка сообщений локализации
});

const loadedLanguages = ['en']; // список локализаций, которые пред-загружены

function setI18nLanguage(lang) {
    localStorage.setItem(localeParamName, lang);
    i18n.locale = lang;
    axios.defaults.headers.common['Accept-Language'] = lang;
    document.querySelector('html').setAttribute('lang', lang);

    return lang;
}

export function loadLanguageAsync(lang) {
    // Если локализация та же
    if (i18n.locale === lang) {
        return Promise.resolve(setI18nLanguage(lang));
    }

    // Если локализация уже была загружена
    if (loadedLanguages.includes(lang)) {
        return Promise.resolve(setI18nLanguage(lang));
    }

    // Если локализация ещё не была загружена
    return import(`./${lang}.js`)
        .then(messages => {
            i18n.setLocaleMessage(lang, messages.default);
            loadedLanguages.push(lang);

            return setI18nLanguage(lang);
        });
};
