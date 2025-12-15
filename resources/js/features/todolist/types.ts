export interface Habit {
    id: number;
    name: string;
    checked: boolean;
    is_active:boolean;
}

export interface HabitUser {
    id: number;
    habit_id: number;
    user_id: number;
    date: string;
    checked: boolean;
    created_at: string;
    updated_at: string;
}


