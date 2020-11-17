// Set a name for the current cache
var cacheName = 'v1'; 

// Default files to always cache
var cacheFiles = [
	'./',
]

self.addEventListener('install', function(e) {
  console.log('[ServiceWorker] Installed');

  // e.waitUntil Delays the event until the Promise is resolved
  e.waitUntil(
    // Open the cache
    caches.open(cacheName).then(function(cache) {
      // Add all the default files to the cache
			console.log('[ServiceWorker] Caching cacheFiles');
			return cache.addAll(cacheFiles);
    })
	); // end e.waitUntil
});


self.addEventListener('activate', function(e) {
  console.log('[ServiceWorker] Activated');

  e.waitUntil(
    // Get all the cache keys (cacheName)
    caches.keys().then(function(cacheNames) {
      return Promise.all(cacheNames.map(function(thisCacheName) {

        // If a cached item is saved under a previous cacheName
        if (thisCacheName !== cacheName) {

          // Delete that cached file
          console.log('[ServiceWorker] Removing Cached Files from Cache - ', thisCacheName);
          return caches.delete(thisCacheName);
        }
      }));
    })
  ); // end e.waitUntil
});


self.addEventListener('fetch', function(e) {
	console.log('[ServiceWorker] Fetch', e.request.url);

	// e.respondWidth Responds to the fetch event
	e.respondWith(

		// Check in cache for the request being made
		caches.match(e.request)
			.then(function(response) {

				// If the request is in the cache
				if ( response ) {
					console.log("[ServiceWorker] Found in Cache", e.request.url, response);
					// Return the cached version
					return response;
				}

				// If the request is NOT in the cache, fetch and cache

				var requestClone = e.request.clone();
				return fetch(requestClone)
					.then(function(response) {

						if ( !response ) {
							console.log("[ServiceWorker] No response from fetch ")
							return response;
						}

						var responseClone = response.clone();

						//  Open the cache
						caches.open(cacheName).then(function(cache) {

							// Put the fetched response in the cache
							cache.put(e.request, responseClone);
							console.log('[ServiceWorker] New Data Cached', e.request.url);

							// Return the response
							return response;
			      }); // end caches.open
					})
					.catch(function(err) {
						console.log('[ServiceWorker] Error Fetching & Caching New Data', err);
					});
		}) // end caches.match(e.request)
	); // end e.respondWith
});

// self.addEventListener('push', function(event) {
//     console.log('Received push');
//     let notificationTitle = 'Hello';
//     const notificationOptions = {
//       body: 'Thanks for sending this push msg.',
//       icon: './images/logo-192x192.png',
//       badge: './images/badge-72x72.png',
//       tag: 'simple-push-demo-notification',
//       data: {
//         url: 'https://developers.google.com/web/fundamentals/getting-started/push-notifications/',
//       },
//     };
  
//     if (event.data) {
//       const dataText = event.data.text();
//       notificationTitle = 'Received Payload';
//       notificationOptions.body = `Push data: '${dataText}'`;
//     }
  
//     event.waitUntil(
//       Promise.all([
//         self.registration.showNotification(
//           notificationTitle, notificationOptions),
//         self.analytics.trackEvent('push-received'),
//       ])
//     );
//   });
  
//   self.addEventListener('notificationclick', function(event) {
//     event.notification.close();
  
//     let clickResponsePromise = Promise.resolve();
//     if (event.notification.data && event.notification.data.url) {
//       clickResponsePromise = clients.openWindow(event.notification.data.url);
//     }
  
//     event.waitUntil(
//       Promise.all([
//         clickResponsePromise,
//         self.analytics.trackEvent('notification-click'),
//       ])
//     );
//   });
  
//   self.addEventListener('notificationclose', function(event) {
//     event.waitUntil(
//       Promise.all([
//         self.analytics.trackEvent('notification-close'),
//       ])
//     );
//   });
  
// importScripts('https://cdn.onesignal.com/sdks/OneSignalSDKWorker.js');
