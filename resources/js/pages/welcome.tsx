import React from "react";
import { Link } from "@inertiajs/react";
import { Card, CardContent, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Target, Trophy, Calendar } from "lucide-react";
import { login, register, dashboard } from "@/routes";

interface PageProps {
  auth?: {
    user?: {
      name: string;
    };
  };
}

const Welcome: React.FC<PageProps> = ({ auth }) => {
  const isAuthenticated = !!auth?.user;

  return (
    <div className="min-h-screen flex flex-col">
      {/* Header */}
      <header className="flex justify-between items-center px-8 py-6 bg-white shadow-sm">
        <h1 className="text-2xl font-bold text-gray-800">HabitFlow</h1>
        <div className="space-x-4">
          {isAuthenticated ? (
            <Link href={dashboard.url()}>
              <Button variant="default">Dashboard</Button>
            </Link>
          ) : (
            <>
              <Link href={login.url()}>
                <Button variant="default">Connexion</Button>
              </Link>
              <Link href={register.url()}>
                <Button variant="outline">Inscription</Button>
              </Link>
            </>
          )}
        </div>
      </header>

      {/* Hero Section */}
      <main className="flex-grow flex flex-col justify-center items-center text-center px-4 md:px-16">
        <h2 className="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
          Construisez la vie que vous méritez. Un jour à la fois.
        </h2>
        <p className="text-lg md:text-xl text-gray-600 mb-12 max-w-2xl">
          Suivez vos habitudes, visualisez vos progrès et restez motivé pour atteindre vos objectifs quotidiens.
        </p>

        {/* Features Section */}
        <div className="grid md:grid-cols-3 gap-6 mb-12 w-full max-w-5xl">
          <Card className="flex flex-col items-center p-6 text-center">
            <Target className="w-10 h-10 text-primary mb-4" />
            <CardTitle className="text-lg font-semibold">Suivi Quotidien Simplifié</CardTitle>
            <CardContent className="text-gray-600">
              Concentrez-vous sur ce qui compte vraiment. Interface claire et sans distraction.
            </CardContent>
          </Card>

          <Card className="flex flex-col items-center p-6 text-center">
            <Trophy className="w-10 h-10 text-primary mb-4" />
            <CardTitle className="text-lg font-semibold">Séries Motivantes</CardTitle>
            <CardContent className="text-gray-600">
              Visualisez vos progrès et ne rompez jamais la chaîne. Le moteur de votre cohérence.
            </CardContent>
          </Card>

          <Card className="flex flex-col items-center p-6 text-center">
            <Calendar className="w-10 h-10 text-primary mb-4" />
            <CardTitle className="text-lg font-semibold">Aperçu de l'Historique</CardTitle>
            <CardContent className="text-gray-600">
              Analysez vos succès et vos échecs pour ajuster votre parcours. Une vue complète de 30 jours ou plus.
            </CardContent>
          </Card>
        </div>

        {/* Call to Action */}
        {!isAuthenticated && (
          <Link href={register.url()}>
            <Button size="lg" className="px-12 py-4">
              Commencer Gratuitement
            </Button>
          </Link>
        )}
      </main>

      {/* Footer */}
      <footer className="bg-gray-50 text-gray-500 text-sm py-6 mt-auto flex justify-center space-x-4">
        <span>&copy; {new Date().getFullYear()} HabitFlow</span>
      </footer>
    </div>
  );
};

export default Welcome;
