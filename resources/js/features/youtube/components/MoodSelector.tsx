import React from 'react';
import { Mood, moods } from '../types';

type MoodSelectorProps = {
    mood: Mood;
    setMood: (mood: Mood) => void;
}

export default function MoodSelector({ mood, setMood }: MoodSelectorProps) {
    return (
        <div className="flex gap-2">
            {moods.map((m) => (
                  // moods viens du fichier /utils qui contient : const moods: Mood[] = ['happy', 'sad', 'relaxed', 'energetic'];
                  // DONNEE IMPORTE DEPUIS MON 'youtube.ts'

                <button
                    key={m}
                    onClick={() => setMood(m)}
                    className={`px-3 py-1 rounded ${
                        mood === m ? 'bg-blue-500 text-white' : 'bg-gray-200'
                    }`}
                >
                    {m}
                </button>
            ))}
        </div>
    )

}
