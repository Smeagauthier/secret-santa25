document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('saveNoteButton');
    console.log('script chargé !')
    if (!btn) return;

    const token = btn.dataset.token;

    if (localStorage.getItem('santa_saved_' + token)) {
        disableButton(btn);
    }

    window.markAsSaved = function () {
        localStorage.setItem('santa_saved_' + token, 'true');
        disableButton(btn);
    };

    function disableButton(button) {
        button.disabled = true;
        button.innerText = "✅ Tirage enregistré";
        button.classList.remove('hover:bg-white/30');
        button.classList.add('opacity-60', 'cursor-not-allowed');
    }
});
