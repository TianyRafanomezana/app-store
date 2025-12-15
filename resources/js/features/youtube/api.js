export async function fetchVideoByMood(query) {
    const key = import.meta.env.YOUTUBE_API_KEY;
    const endpoint = `https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=1&q=${encodeURIComponent(
        query)}&key=${key}`;

    // Récupération des données dans res
    const res = await fetch(endpoint);

// ----------GESTION DES ERREURS------
    if (!res.ok) {
        throw new Error(`YouTube API error: ${res.statusText}`);
    }

    // Mise des données sous forme de json
    const data = await res.json();

    if (!data.items || data.items.length === 0) {
        throw new Error('No video found for this mood');
    }

// ----------FIN DE GESTION DES ERREURS------


    const vid = data.items[0];

    return {
        videoId: vid.id.videoId,
        title: vid.snippet.title,
        thumbnail: vid.snippet.thumbnails?.high?.url
    };
}
