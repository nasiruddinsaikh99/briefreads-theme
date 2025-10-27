(function($){
    'use strict';

    const body = document.body;
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
    const storedTheme = localStorage.getItem('briefreads-theme');

    function applyTheme(theme){
        if(theme === 'dark'){
            body.classList.add('br-dark-mode');
        }else{
            body.classList.remove('br-dark-mode');
        }
    }

    if(storedTheme){
        applyTheme(storedTheme);
    }else if(prefersDark.matches){
        applyTheme('dark');
    }

    if(prefersDark.addEventListener){
        prefersDark.addEventListener('change', e => {
            if(!localStorage.getItem('briefreads-theme')){
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    } else if(prefersDark.addListener){
        prefersDark.addListener(e => {
            if(!localStorage.getItem('briefreads-theme')){
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    const toggleMode = document.querySelectorAll('.br-toggle-mode');
    toggleMode.forEach(button => {
        button.addEventListener('click', () => {
            const isDark = body.classList.toggle('br-dark-mode');
            localStorage.setItem('briefreads-theme', isDark ? 'dark' : 'light');
            button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        });
    });

    // Search overlay
    const searchOverlay = document.getElementById('br-search-overlay');
    const searchToggleButtons = document.querySelectorAll('.br-search-toggle');
    const searchClose = document.querySelector('.br-search-close');

    function openSearch(){
        if(!searchOverlay) return;
        searchOverlay.style.display = 'grid';
        searchOverlay.setAttribute('aria-hidden', 'false');
        const input = searchOverlay.querySelector('input[type="search"]');
        if(input){
            setTimeout(() => input.focus(), 100);
        }
    }

    function closeSearch(){
        if(!searchOverlay) return;
        searchOverlay.style.display = 'none';
        searchOverlay.setAttribute('aria-hidden', 'true');
    }

    searchToggleButtons.forEach(btn => btn.addEventListener('click', openSearch));
    if(searchClose){
        searchClose.addEventListener('click', closeSearch);
    }

    document.addEventListener('keydown', (event) => {
        if(event.key === 'Escape'){
            closeSearch();
        }
    });

    // Progress bar
    const progressBar = document.getElementById('br-progress-bar');
    const selector = (typeof briefReadsSettings !== 'undefined' && briefReadsSettings.scrollProgressSelector) ? briefReadsSettings.scrollProgressSelector : '.br-summary-content';
    const contentArea = document.querySelector(selector);
    if(progressBar && contentArea){
        const updateProgress = () => {
            const rect = contentArea.getBoundingClientRect();
            const scrollTop = window.scrollY + window.innerHeight - rect.top - 200;
            const total = rect.height;
            let progress = Math.max(0, Math.min(100, (scrollTop / total) * 100));
            progressBar.style.width = progress + '%';
        };
        document.addEventListener('scroll', updateProgress, { passive: true });
        window.addEventListener('resize', updateProgress);
        updateProgress();
    }

    // Font size and line height adjustments
    const summaryContent = document.querySelector('.br-summary-content');
    if(summaryContent){
        const baseSize = parseFloat(getComputedStyle(summaryContent).fontSize);
        const baseLineHeight = parseFloat(getComputedStyle(summaryContent).lineHeight);
        let size = parseFloat(localStorage.getItem('briefreads-font-size')) || baseSize;
        let lineHeight = parseFloat(localStorage.getItem('briefreads-line-height')) || baseLineHeight;

        function applyTypography(){
            summaryContent.style.fontSize = size + 'px';
            summaryContent.style.lineHeight = lineHeight;
        }

        applyTypography();

        document.querySelectorAll('.js-font-size').forEach(btn => {
            btn.addEventListener('click', () => {
                size += parseInt(btn.dataset.direction, 10);
                size = Math.min(Math.max(size, baseSize - 4), baseSize + 6);
                localStorage.setItem('briefreads-font-size', size);
                applyTypography();
            });
        });

        document.querySelectorAll('.js-line-height').forEach(btn => {
            btn.addEventListener('click', () => {
                lineHeight += parseFloat(btn.dataset.direction) * 0.1;
                lineHeight = Math.min(Math.max(lineHeight, baseLineHeight - 0.4), baseLineHeight + 0.4);
                localStorage.setItem('briefreads-line-height', lineHeight);
                applyTypography();
            });
        });
    }

    // Estimated reading time remaining
    const estimateDisplay = document.querySelector('[data-remaining]');
    if(estimateDisplay && summaryContent){
        const totalWords = summaryContent.innerText.trim().split(/\s+/).length;
        const totalMinutes = Math.ceil(totalWords / 230);
        function updateRemaining(){
            const rect = summaryContent.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const percentage = Math.min(1, Math.max(0, (viewportHeight - rect.top) / rect.height));
            const remaining = Math.max(0, Math.round(totalMinutes * (1 - percentage)));
            if(remaining > 0){
                estimateDisplay.textContent = `${remaining} min${remaining === 1 ? '' : 's'} remaining`;
            } else {
                estimateDisplay.textContent = 'Almost done!';
            }
        }
        document.addEventListener('scroll', updateRemaining, { passive: true });
        window.addEventListener('resize', updateRemaining);
        updateRemaining();
    }

    // Bookmark toggle
    function updateBookmarkButton(button, isSaved){
        if(!button) return;
        button.classList.toggle('is-active', isSaved);
        button.innerText = isSaved ? 'Saved' : 'Save';
    }

    document.querySelectorAll('.js-toggle-bookmark').forEach(button => {
        const postId = button.dataset.postId;
        const savedLocal = JSON.parse(localStorage.getItem('briefreads-local-bookmarks') || '[]');
        let isSaved = savedLocal.includes(postId);
        updateBookmarkButton(button, isSaved);

        button.addEventListener('click', () => {
            if(typeof briefReadsSettings === 'undefined'){ return; }
            $.post(briefReadsSettings.ajaxUrl, {
                action: 'briefreads_toggle_bookmark',
                postId
            }).done(response => {
                if(response.success){
                    isSaved = response.data.status === 'added';
                    updateBookmarkButton(button, isSaved);
                } else {
                    toggleLocal();
                }
            }).fail(() => {
                toggleLocal();
            });
        });

        function toggleLocal(){
            if(isSaved){
                const idx = savedLocal.indexOf(postId);
                if(idx > -1){ savedLocal.splice(idx, 1); }
            }else{
                savedLocal.push(postId);
            }
            localStorage.setItem('briefreads-local-bookmarks', JSON.stringify(savedLocal));
            isSaved = !isSaved;
            updateBookmarkButton(button, isSaved);
        }
    });

    // Share button
    document.querySelectorAll('.js-share-summary').forEach(button => {
        button.addEventListener('click', async () => {
            const shareData = {
                title: document.title,
                text: document.querySelector('.br-summary-header h1')?.innerText || document.title,
                url: button.dataset.url
            };

            if(navigator.share){
                try {
                    await navigator.share(shareData);
                } catch(err) {
                    console.warn('Share cancelled', err);
                }
            } else {
                navigator.clipboard.writeText(shareData.url).then(() => {
                    const original = button.innerText;
                    button.innerText = 'Link copied!';
                    setTimeout(() => { button.innerText = original; }, 1500);
                });
            }
        });
    });

    // Table of contents toggle
    document.querySelectorAll('.br-toc__toggle').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            const list = toggle.parentElement.querySelector('.br-toc__list');
            if(list){
                list.style.display = expanded ? 'none' : 'grid';
            }
        });
    });

    // Jump to audio player
    document.querySelectorAll('.js-init-audio').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.getElementById('audio');
            if(target){
                target.classList.remove('br-player--minimized');
                target.scrollIntoView({ behavior: 'smooth', block: 'end' });
                const play = target.querySelector('.js-player-play');
                if(play){
                    play.click();
                }
            }
        });
    });

    // Smooth scroll for TOC links
    document.querySelectorAll('.br-toc__list a').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const target = document.getElementById(link.getAttribute('href').substring(1));
            if(target){
                window.scrollTo({
                    top: target.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

})(jQuery);
