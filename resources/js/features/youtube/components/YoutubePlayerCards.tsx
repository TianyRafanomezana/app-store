import MoodSelector from "./MoodSelector";
import useVideoMood from "../hooks/useVideoMood";

export default function YoutubePlayerCards() {

    //je recois mes moods prédéfinies et ma fonction qui change l'état du mood
    const { mood, video, changeMood } = useVideoMood();

    return (
      <div className="bg-white rounded-xl shadow p-4 max-w-md mx-auto">
        <MoodSelector mood={mood} setMood={changeMood} />
        <div className="mt-4 aspect-video">
          <iframe
            src={`https://www.youtube.com/embed/${video.videoId}`}
            title={video.title}
            allowFullScreen
            className="w-full h-full rounded-lg"
          />
        </div>
        <p className="mt-2 text-center text-sm text-gray-600">{video.title}</p>
      </div>
    );
  }
