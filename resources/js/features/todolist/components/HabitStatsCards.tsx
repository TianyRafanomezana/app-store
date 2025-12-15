import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { CheckCircle, Target, Trophy } from 'lucide-react';

// Définition des types de props (doit correspondre à ce qui est retourné par useHabitStats)
interface HabitStats {
    totalHabits: number;
    todayCompleted: number;
    completionRateToday: string;
    activeStreak: number;
}

interface HabitStatsCardsProps {
    stats: HabitStats;
    loading: boolean;
}

// Composant Skeleton pour un affichage propre pendant le chargement
const StatCardSkeleton: React.FC = () => (
    <Card className="animate-pulse">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium h-4 bg-gray-200 rounded w-1/2"></CardTitle>
            <div className="h-4 w-4 bg-gray-200 rounded-full"></div>
        </CardHeader>
        <CardContent>
            <div className="text-2xl font-bold h-7 bg-gray-300 rounded w-3/4 mb-1"></div>
            <p className="text-xs text-muted-foreground h-4 bg-gray-200 rounded w-2/3"></p>
        </CardContent>
    </Card>
);

const HabitStatsCards: React.FC<HabitStatsCardsProps> = ({ stats, loading }) => {
    const completionPercent = parseInt(stats.completionRateToday.replace('%', ''), 10);

    // Si les données sont en cours de chargement, afficher trois squelettes
    if (loading) {
        return (
            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <StatCardSkeleton />
                <StatCardSkeleton />
                <StatCardSkeleton />
            </div>
        );
    }

    return (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

            {/* Carte 1 : Taux de Complétion Aujourd'hui */}
            <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle className="text-sm font-medium">
                        Taux de Complétion
                    </CardTitle>
                    <Target className="h-4 w-4 text-primary" />
                </CardHeader>
                <CardContent>
                    <div className="text-3xl font-bold">
                        {stats.completionRateToday}
                    </div>
                    <p className="text-xs text-muted-foreground">
                        Objectif d'aujourd'hui
                    </p>
                </CardContent>
            </Card>

            {/* Carte 2 : Habitudes Terminées */}
            <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle className="text-sm font-medium">
                        Terminées / Total
                    </CardTitle>
                    <CheckCircle className="h-4 w-4 text-green-500" />
                </CardHeader>
                <CardContent>
                    <div className="text-3xl font-bold">
                        {stats.todayCompleted} / {stats.totalHabits}
                    </div>
                    <p className="text-xs text-muted-foreground">
                        Habitudes cochées ce jour
                    </p>
                    {/* Barre de progression */}
                    <div className="h-2 w-full rounded-full bg-muted overflow-hidden">
                        <div
                            className="
        h-full rounded-full bg-primary
        transition-all duration-700 ease-out
      "
                            style={{ width: `${completionPercent}%` }}
                        />
                    </div>
                </CardContent>
            </Card>

            {/* Carte 3 : Série Active (Streak) */}
            <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle className="text-sm font-medium">
                        Série actuelle (Streak)
                    </CardTitle>
                    <Trophy className="h-4 w-4 text-yellow-500" />
                </CardHeader>
                <CardContent>
                    <div className="text-3xl font-bold">
                        {stats.activeStreak} jours
                    </div>
                    <p className="text-xs text-muted-foreground">
                        Jours consécutifs d'activité
                    </p>
                </CardContent>
            </Card>

        </div>
    );
};

export default HabitStatsCards;
