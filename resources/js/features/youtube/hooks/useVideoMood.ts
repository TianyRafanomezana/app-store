import { Mood, MoodVideo } from '../types';
import { useState } from 'react';
import { getVideoForMood } from '../utils';

// Avec cette fonction, je veux pouvoir modifier mon mood donc récupérer mes mood stocké dans types et les vidéos associées dans mon fichier types
// Plus précisément, je veux établir une valeure de base dans mood, modifier puis retourner la valeur affecté dans mood (avec sa vidéo associée)

export default function useVideoMood(initialMood: Mood = 'happy') {

    // Je récupère mes MOODS et mes VIDEOS associées prédéfinis dans le fichier de types
    const [mood, setMood] = useState<Mood>(initialMood);
    const [video, setVideo] = useState<MoodVideo>(getVideoForMood(initialMood));


    // La constante changeMood est enfaite une fonction ?
    const changeMood = (newMood: Mood) => {
        setMood(newMood);
        setVideo(getVideoForMood(newMood));
    };

    return { mood, video, changeMood };


}
