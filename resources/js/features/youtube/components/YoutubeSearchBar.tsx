import { useState } from "react";

//Dans ce composant on veut envoyer une query à notre ControllerYoutube
//On veut afficher la video à partir de la réponse du controleur : donc receptionner la réponse du Controller et mettre les infos dans l'iframe

type YoutubeVideo = {
    id: {
        videoId: string;
    };
    snippet: {
        title: string;
        thumbnails: {
            medium: {
                url: string;
            };
        };
    };
};
export default function YoutubeSearchBar() {

    //On déclare une constante query (qu'on va envoyer au back) qui peut changer d'etat via setQuery
    // On déclare une constante video qui peut changer d'etat avec setVideo

    const [query, setQuery] = useState("yourname");
    const [videos, setVideos] = useState<YoutubeVideo[]>([]);

    // On a besoin d'une fonction qui envoie mon query au back (Controller)
    // On a besoin de stocker le résultat de la requete du back dans une variable
    // res sert à receptionner le return du back du controller
    // data stocke le resultat du back sous forme de json
    async function searchVideos() {
        const res = await
            // fetch(`/api/youtube/search?q=${encodeURIComponent(query)}`);  APPEL Youtube
            fetch(`/api/youtube/search?q=${encodeURIComponent(query)}`);
        console.log(res)
        const data = await res.json();
        console.log(data)

        setVideos(data.items);
        const firstvid = videos[0];
        console.log(firstvid)
    }

    return (
        <div>

            <input
                className="border p-2"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                placeholder="Recherche YouTube…"

            />

            <button className="bg-blue-500 text-white p-2" onClick={searchVideos}>
                Chercher
            </button>

            <ul className=" mt-4 border border-black border-double">
                <h1>Résultats</h1>
                 {/*<iframe*/}
                 {/*       src={`https://www.youtube.com/embed/${videos[0].id.videoId}`}*/}
                 {/*       allowFullScreen*/}
                 {/*   />*/}

                {videos.map((v) => (
                    <li key={v.id.videoId} className="border flex rounded-xl">

                        <img src={v.snippet.thumbnails.medium.url} className="aspect-4/3 object-cover"/>
                        <div className="flex justify-center text-center items-center p-3 ">
                            <h3>{v.snippet.title}</h3>

                        </div>
                    </li>
                ))}
            </ul>

        </div>
    );
}
