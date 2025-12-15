import React, { useMemo, useState } from 'react';
import dayjs from 'dayjs';
import updateLocale from 'dayjs/plugin/updateLocale';
import 'dayjs/locale/fr'; // S'assurer que la locale française est utilisée
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

dayjs.extend(updateLocale);

// Configuration de la locale pour que la semaine commence le Lundi (ISO 8601)
dayjs.updateLocale('fr', {
    weekStart: 1, // 0 = Dimanche, 1 = Lundi
});

// --- Interfaces ---

interface HabitCalendarProps {
    // La map de succès que nous exposons depuis useHabitStats
    successMap: Record<string, boolean>;
    loading: boolean;
}

const generateCalendarGrid = (date: dayjs.Dayjs, successMap: Record<string, boolean>) => {
    
    // Le premier jour visible sur le calendrier (Lundi de la première semaine)
    const startDay = date.startOf('month').startOf('week'); 
    const daysInMonth = date.daysInMonth();
    const today = dayjs().format('YYYY-MM-DD');

    const calendarDays = [];
    let currentDay = startDay;
    let maxDays = 42; // Maximum 6 semaines * 7 jours

    while (maxDays > 0) {
        const dateString = currentDay.format('YYYY-MM-DD');
        const dayOfMonth = currentDay.date();
        const isCurrentMonth = currentDay.month() === date.month();
        const isSuccess = successMap[dateString] === true;
        const isFuture = currentDay.isAfter(dayjs(), 'day');
        
        // On arrête après avoir passé la dernière semaine du mois
        if (!isCurrentMonth && currentDay.isAfter(date.endOf('month'))) {
            if (calendarDays.length % 7 === 0) break;
        }

        calendarDays.push({
            dateString,
            dayOfMonth,
            isCurrentMonth,
            isSuccess,
            isFuture,
        });

        currentDay = currentDay.add(1, 'day');
        maxDays--;
    }

    return calendarDays;
};


// --- Composant Principal ---

const HabitCalendar: React.FC<HabitCalendarProps> = ({ successMap, loading }) => {
    // État pour naviguer entre les mois
    const [currentMonth, setCurrentMonth] = useState(dayjs());
    
    // Génération de la grille et mémorisation pour éviter de recalculer à chaque rendu
    const calendarGrid = useMemo(() => {
        return generateCalendarGrid(currentMonth, successMap);
    }, [currentMonth, successMap]);
    
    // Noms des jours de la semaine pour l'affichage (Lundi, Mardi, ...)
    const weekdayNames = Array.from({ length: 7 }, (_, i) => 
        dayjs().locale('fr').startOf('week').add(i, 'day').format('ddd')
    );

    // Fonction pour changer de mois
    const handleMonthChange = (direction: 'prev' | 'next') => {
        setCurrentMonth(prev => prev.add(direction === 'next' ? 1 : -1, 'month'));
    };

    if (loading) {
        return <p className="text-center text-muted-foreground">Chargement de l'historique...</p>;
    }

    return (
        <Card className="shadow-lg">
            <CardHeader>
                <div className="flex justify-between items-center">
                    <Button variant="ghost" size="icon" onClick={() => handleMonthChange('prev')}>
                        <ChevronLeft className="h-4 w-4" />
                    </Button>
                    <CardTitle className="text-xl font-semibold">
                        {currentMonth.locale('fr').format('MMMM YYYY')}
                    </CardTitle>
                    <Button variant="ghost" size="icon" onClick={() => handleMonthChange('next')}>
                        <ChevronRight className="h-4 w-4" />
                    </Button>
                </div>
            </CardHeader>
            <CardContent>
                {/* En-tête des jours de la semaine */}
                <div className="grid grid-cols-7 text-center text-sm font-medium mb-2">
                    {weekdayNames.map(day => (
                        <div key={day} className="text-muted-foreground">{day.toUpperCase()}</div>
                    ))}
                </div>

                {/* Grille du Calendrier */}
                <div className="grid grid-cols-7 gap-1 place-items-center">
                    {calendarGrid.map((day, index) => (
                        <div 
                            key={index}
                            className={`
                                flex items-center justify-center h-10 w-full rounded-lg text-sm font-semibold 
                                ${!day.isCurrentMonth ? 'text-muted-foreground opacity-50' : ''} 
                                ${day.isSuccess && !day.isFuture ? 'bg-green-500 text-white' : ''} 
                                ${!day.isSuccess && day.isCurrentMonth && !day.isFuture ? 'bg-red-500 text-white' : ''}
                                ${day.isFuture ? 'bg-gray-100 text-gray-400' : ''}
                            `}
                            title={day.isSuccess ? 'Succès quotidien' : 'Objectif manqué'}
                        >
                            {day.dayOfMonth}
                        </div>
                    ))}
                </div>
            </CardContent>
        </Card>
    );
};

export default HabitCalendar;