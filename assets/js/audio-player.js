(function(){
    'use strict';

    const playerRoot = document.querySelector('[data-component="audio-player"]');
    if(!playerRoot){
        return;
    }

    const audio = new Audio(playerRoot.dataset.audio);
    const playButton = playerRoot.querySelector('.js-player-play');
    const prevButton = playerRoot.querySelector('.js-player-prev');
    const nextButton = playerRoot.querySelector('.js-player-next');
    const speedButton = playerRoot.querySelector('.js-player-speed');
    const volumeControl = playerRoot.querySelector('#br-volume');
    const scrubber = playerRoot.querySelector('input[type="range"]');
    const currentTimeDisplay = playerRoot.querySelector('[data-current]');
    const durationDisplay = playerRoot.querySelector('[data-duration]');
    const toggleButton = playerRoot.querySelector('.br-player-toggle');
    const storageKey = `briefreads-progress-${playerRoot.dataset.audio}`;

    const stored = JSON.parse(localStorage.getItem(storageKey) || '{}');
    if(stored.position){
        audio.currentTime = stored.position;
    }
    if(stored.speed){
        audio.playbackRate = stored.speed;
        speedButton.textContent = stored.speed + 'x';
    }

    audio.addEventListener('loadedmetadata', () => {
        if(durationDisplay && !durationDisplay.dataset.fromMeta){
            durationDisplay.textContent = formatTime(audio.duration);
        }
    });

    function formatTime(seconds){
        if(!isFinite(seconds)){
            return '00:00';
        }
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }

    function togglePlayback(){
        if(audio.paused){
            audio.play();
            playButton.textContent = '⏸';
        } else {
            audio.pause();
            playButton.textContent = '▶';
        }
    }

    playButton.addEventListener('click', togglePlayback);
    playerRoot.addEventListener('keydown', (event) => {
        if(event.code === 'Space'){
            event.preventDefault();
            togglePlayback();
        }
    });

    audio.addEventListener('timeupdate', () => {
        const percent = (audio.currentTime / audio.duration) * 100;
        if(!isNaN(percent)){
            scrubber.value = percent;
            currentTimeDisplay.textContent = formatTime(audio.currentTime);
            localStorage.setItem(storageKey, JSON.stringify({
                position: audio.currentTime,
                speed: audio.playbackRate
            }));
        }
    });

    scrubber.addEventListener('input', () => {
        audio.currentTime = (scrubber.value / 100) * audio.duration;
    });

    speedButton.addEventListener('click', () => {
        const speeds = [1, 1.25, 1.5, 2];
        const currentIndex = speeds.indexOf(audio.playbackRate);
        const nextIndex = (currentIndex + 1) % speeds.length;
        audio.playbackRate = speeds[nextIndex];
        speedButton.textContent = speeds[nextIndex] + 'x';
    });

    if(volumeControl){
        volumeControl.addEventListener('input', () => {
            audio.volume = volumeControl.value;
        });
    }

    toggleButton.addEventListener('click', () => {
        const minimized = playerRoot.classList.toggle('br-player--minimized');
        toggleButton.textContent = minimized ? '▴' : '▾';
        toggleButton.setAttribute('aria-expanded', minimized ? 'false' : 'true');
    });

    prevButton.addEventListener('click', () => {
        audio.currentTime = Math.max(0, audio.currentTime - 15);
    });

    nextButton.addEventListener('click', () => {
        audio.currentTime = Math.min(audio.duration, audio.currentTime + 30);
    });

    document.addEventListener('visibilitychange', () => {
        if(document.hidden){
            audio.pause();
            playButton.textContent = '▶';
        }
    });

})();
