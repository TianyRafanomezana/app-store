import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import dayjs from 'dayjs';
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter';
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore';

import { Habit, HabitUser } from '@/features/todolist/types';

dayjs.extend(isSameOrAfter);
dayjs.extend(isSameOrBefore);

// --------------------
// Types
// --------------------

interface HabitStats {
    totalHabits: number;
    todayCompleted: number;
    completionRateToday: string;
    activeStreak: number;
    successByDate: Record<string, boolean>;
}

const INITIAL_STATS: HabitStats = {
    totalHabits: 0,
    todayCompleted: 0,
    completionRateToday: '0%',
    activeStreak: 0,
    successByDate: {},
};

// --------------------
// Hook
// --------------------

export function useHabitStats(habits: Habit[]) {
    const [stats, setStats] = useState<HabitStats>(INITIAL_STATS);
    const [loading, setLoading] = useState(true);

    const fetchStats = useCallback(async () => {
        setLoading(true);

        try {
            const { data } = await axios.get('/habit-user/history');
            const logs: HabitUser[] = data ?? [];

            setStats(calculateStats(habits, logs));
        } catch (error) {
            console.error('Erreur stats habitudes:', error);
            setStats(INITIAL_STATS);
        } finally {
            setLoading(false);
        }
    }, [habits]);

    useEffect(() => {
        if (!habits.length) {
            setStats(INITIAL_STATS);
            setLoading(false);
            return;
        }

        fetchStats();
    }, [fetchStats, habits]);

    return { stats, loading };
}

// --------------------
// Business logic
// --------------------

function calculateStats(habits: Habit[], logs: HabitUser[]): HabitStats {
    const today = dayjs();

    const activeHabitIds = habits.map(h => h.id);
    const totalActiveHabits = habits.length;

    console.log('HABITS RAW:', habits);
    console.log(
        'is_active values:',
        habits.map(h => h.is_active)
    );

    // 2️⃣ Logs du jour (SAFE timezone)
    const logsToday = logs.filter(log =>
        log.checked &&
        activeHabitIds.includes(log.habit_id) &&
        dayjs(log.date).isSame(today, 'day')
    );

    const todayCompleted = logsToday.length;
    const totalHabits = activeHabitIds.length;

    const completionRateToday =
        totalHabits > 0
            ? `${Math.round((todayCompleted / totalHabits) * 100)}%`
            : '0%';

    // 3️⃣ Succès par jour (pour streak + calendrier)
    const successByDate = logs.reduce<Record<string, boolean>>((acc, log) => {
        if (log.checked) {
            acc[dayjs(log.date).format('YYYY-MM-DD')] = true;
        }
        return acc;
    }, {});

    // 4️⃣ Calcul du streak
    let streak = 0;
    let currentDay = today;
    const yesterday = today.subtract(1, 'day');
    const limit = today.subtract(31, 'day');

    if (successByDate[today.format('YYYY-MM-DD')]) {
        streak = 1;
        currentDay = currentDay.subtract(1, 'day');
    }

    while (currentDay.isSameOrAfter(limit)) {
        const key = currentDay.format('YYYY-MM-DD');

        if (successByDate[key]) {
            streak++;
        } else if (currentDay.isSameOrBefore(yesterday)) {
            break;
        }

        currentDay = currentDay.subtract(1, 'day');
    }

    return {
        totalHabits,
        todayCompleted,
        completionRateToday,
        activeStreak: streak,
        successByDate,
    };
}
