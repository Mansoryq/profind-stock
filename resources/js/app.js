import './bootstrap';
import Typed from 'typed.js';

const texts = {
    ru: [
        "Узнай свою профессию",
        "за 20 минут!",
        "Индивидуальный разбор твоих талантов",
        "Рекомендации по выбору профессий"
    ],
    kk: [
        "Өзіңе лайықты мамандықты біл",
        "20 минутта!",
        "Таланттарыңды жеке талдау",
        "Мамандық таңдауға арналған ұсыныстар"
    ]
};

const lang = document.documentElement.lang || 'ru';

document.addEventListener('livewire:navigated', () => {
    if (document.getElementById('typed-output')) {
        new Typed('#typed-output', {
            strings: texts[lang],
            typeSpeed: 50,
            backSpeed: 25,
            backDelay: 1500,
            loop: true
        });
    }
});
