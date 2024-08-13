/**
 * A lightweight youtube embed. Still should feel the same to the user, just MUCH faster to initialize and paint.
 *
 * Thx to these as the inspiration
 *   https://storage.googleapis.com/amp-vs-non-amp/youtube-lazy.html
 *   https://autoplay-youtube-player.glitch.me/
 *
 * Once built it, I also found these:
 *   https://github.com/ampproject/amphtml/blob/master/extensions/amp-youtube (👍👍)
 *   https://github.com/Daugilas/lazyYT
 *   https://github.com/vb/lazyframe
 */
class LiteYTEmbed extends HTMLElement {
   connectedCallback() {
      this.videoId = this.getAttribute('videoid');
      let playBtnEl = this.querySelector('.lty-playbtn');
      this.playLabel = (playBtnEl && playBtnEl.textContent.trim()) || this.getAttribute('playlabel') || 'Play';
      this.dataset.title = this.getAttribute('title') || "";
      if (!this.style.backgroundImage) {
         this.style.backgroundImage = `url("https://i.ytimg.com/vi/${this.videoId}/hqdefault.jpg")`;
         this.upgradePosterImage();
      }
      if (!playBtnEl) {
         playBtnEl = document.createElement('button');
         playBtnEl.type = 'button';
         playBtnEl.classList.add('lty-playbtn');
         this.append(playBtnEl);
      }
      if (!playBtnEl.textContent) {
         const playBtnLabelEl = document.createElement('span');
         playBtnLabelEl.className = 'lyt-visually-hidden';
         playBtnLabelEl.textContent = this.playLabel;
         playBtnEl.append(playBtnLabelEl);
      }
      this.addNoscriptIframe();
      playBtnEl.removeAttribute('href');
      this.addEventListener('pointerover', LiteYTEmbed.warmConnections, {once: true});
      this.addEventListener('click', this.activate);
      this.needsYTApi = this.hasAttribute("js-api") || navigator.vendor.includes('Apple') || navigator.userAgent.includes('Mobi');
   }
   static addPrefetch(kind, url, as) {
      const linkEl = document.createElement('link');
      linkEl.rel = kind;
      linkEl.href = url;
      if (as) {linkEl.as = as;}
      document.head.append(linkEl);
   }
   static warmConnections() {
      if (LiteYTEmbed.preconnected) {return;}
      LiteYTEmbed.addPrefetch('preconnect', 'https://www.youtube-nocookie.com');
      LiteYTEmbed.addPrefetch('preconnect', 'https://www.google.com');
      LiteYTEmbed.addPrefetch('preconnect', 'https://googleads.g.doubleclick.net');
      LiteYTEmbed.addPrefetch('preconnect', 'https://static.doubleclick.net');
      LiteYTEmbed.preconnected = true;
   }
   fetchYTPlayerApi() {
      if (window.YT || (window.YT && window.YT.Player)) {return;}
      this.ytApiPromise = new Promise((res, rej) => {
         var el = document.createElement('script');
         el.src = 'https://www.youtube.com/iframe_api';
         el.async = true;
         el.onload = _ => { YT.ready(res); };
         el.onerror = rej;
         this.append(el);
      });
   }
   async getYTPlayer() {
      if(!this.playerPromise) {await this.activate();}
      return this.playerPromise;
   }
   async addYTPlayerIframe() {
      this.fetchYTPlayerApi();
      await this.ytApiPromise;
      const videoPlaceholderEl = document.createElement('div')
      this.append(videoPlaceholderEl);
      const paramsObj = Object.fromEntries(this.getParams().entries());
      this.playerPromise = new Promise(resolve => {
         let player = new YT.Player(videoPlaceholderEl, {
            width: '100%',
            videoId: this.videoId,
            playerVars: paramsObj,
            events: {
               'onReady': event => {
                  event.target.playVideo();
                  resolve(player);
               }
            }
         });
      });
   }
   addNoscriptIframe() {
      const iframeEl = this.createBasicIframe();
      const noscriptEl = document.createElement('noscript');
      noscriptEl.innerHTML = iframeEl.outerHTML;
      this.append(noscriptEl);
   }
   getParams() {
      const params = new URLSearchParams(this.getAttribute('params') || []);
      params.append('autoplay', '1');
      params.append('playsinline', '1');
      return params;
   }
   async activate(){
      if (this.classList.contains('lyt-activated')) return;
      this.classList.add('lyt-activated');
      if (this.needsYTApi) {return this.addYTPlayerIframe(this.getParams());}
      const iframeEl = this.createBasicIframe();
      this.append(iframeEl);
      iframeEl.focus();
   }
   createBasicIframe(){
      const iframeEl = document.createElement('iframe');
      iframeEl.width = 560;
      iframeEl.height = 315;
      iframeEl.title = this.playLabel;
      iframeEl.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
      iframeEl.allowFullscreen = true;
      iframeEl.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(this.videoId)}?${this.getParams().toString()}`;
      return iframeEl;
   }
   upgradePosterImage() {
      setTimeout(() => {
         const webpUrl = `https://i.ytimg.com/vi_webp/${this.videoId}/sddefault.webp`;
         const img = new Image();
         img.fetchPriority = 'low'; // low priority to reduce network contention
         img.referrerpolicy = 'origin'; // Not 100% sure it's needed, but https://github.com/ampproject/amphtml/pull/3940
         img.src = webpUrl;
         img.onload = e => {
            const noAvailablePoster = e.target.naturalHeight == 90 && e.target.naturalWidth == 120;
            if (noAvailablePoster) return;
            this.style.backgroundImage = `url("${webpUrl}")`;
         }
      }, 100);
   }
}
// Register custom element
customElements.define('lite-youtube', LiteYTEmbed);