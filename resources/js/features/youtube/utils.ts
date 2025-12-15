import { Mood, MoodVideo } from './types';

// Mapping mood → vidéo (test avec IDs YouTube)
const videoMapping: Record<Mood, MoodVideo> = {
  happy: { mood: 'happy', videoId: 'dQw4w9WgXcQ', title: 'Happy Video' },
  sad: { mood: 'sad', videoId: '3GwjfUFyY6M', title: 'Sad Video' },
  relaxed: { mood: 'relaxed', videoId: '2Vv-BfVoq4g', title: 'Relaxed Video' },
  energetic: { mood: 'energetic', videoId: 'hY7m5jjJ9mM', title: 'Energetic Video' },
};

export const getVideoForMood = (mood: Mood): MoodVideo => {
  return videoMapping[mood];
};