export type Mood = 'happy' | 'sad' | 'relaxed' | 'energetic';

export interface MoodVideo {
    mood: Mood;
    videoId:string;
    title :string; // Préfère le type string en minuscule idk why
}

// Tableau centralisé pour l’UI et type-safe
export const moods: Mood[] = ['happy', 'sad', 'relaxed', 'energetic'];
