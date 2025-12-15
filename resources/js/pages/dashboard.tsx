import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import HabitList from '@/features/todolist/components/HabitList';
import { Habit } from '@/features/todolist/types';
import { useHabits } from '@/features/todolist/hooks/useHabits';
import { useHabitStats } from '@/features/todolist/hooks/useHabitStats';
import HabitStatsCards from '@/features/todolist/components/HabitStatsCards';
import HabitCalendar from '@/features/todolist/components/HabitCalendar';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url
    }
];

interface DashboardProps {
    habits: Habit[];

}

export default function Dashboard({ habits }: DashboardProps) {
    const { toggleHabit, addHabit, deleteHabit } = useHabits();
    const { stats, loading } = useHabitStats(habits);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="grid grid-cols-2 gap-3 p-3">
                {/* Liste des habitudes */}
                <HabitList
                    habits={habits}
                    onToggle={toggleHabit}
                    onAdd={addHabit}
                    onDelete={deleteHabit}
                />
                
                {/* Cartes de statistiques */}
                <HabitStatsCards 
                stats={stats} 
                loading={loading} />
                
               
            </div>
             {/* Calendrier */}
             <section>
                    <h2 className="text-2xl font-bold mb-4">🗓️ Aperçu du Mois</h2>
                    <HabitCalendar 
                        successMap={stats.successByDate}
                        loading={loading}
                    />
                </section>
        </AppLayout>
    );
}
