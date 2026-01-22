const CACHE_NAME = "miguel-pwa-v1";
const urlsToCache = [
  "/admin/auth/login.php",
  "/style.css",
  "/js/script.js",
  "/images/logomiguel.jpg"
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => response || fetch(event.request))
  );
});
