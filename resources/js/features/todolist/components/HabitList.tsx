// resources/js/features/todolist/components/HabitList.tsx

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Habit } from '@/features/todolist/types';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { useState } from 'react';
// ➡️ Import de l'icône de suppression
import { Plus, Trash2 } from 'lucide-react';

interface HabitListProps {
    habits: Habit[];
    onToggle: (habitId: number, checked: boolean) => void;
    onAdd: (name: string) => void;
    onDelete: (habitId: number) => void;
}

export default function HabitList({ habits, onToggle, onAdd, onDelete }: HabitListProps) { // ⬅️ Destructuration de onDelete

    const [newHabitName, setNewHabitName] = useState('');

    const handleAddSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const trimmedName = newHabitName.trim();
        if (!trimmedName) return;

        onAdd(trimmedName);
        setNewHabitName('');
    };

    return (
        <Card>
            <CardHeader>
                <CardTitle>Mes Habitudes</CardTitle>
            </CardHeader>
            <CardContent className="flex flex-col gap-4">

                {/* FORMULAIRE D'AJOUT D'HABITUDE (inchangé) */}
                <form onSubmit={handleAddSubmit} className="flex gap-2 mb-4">
                    <Input
                        type="text"
                        placeholder="Nom de la nouvelle habitude"
                        value={newHabitName}
                        onChange={(e) => setNewHabitName(e.target.value)}
                        className="flex-grow"
                    />
                    <Button type="submit" disabled={!newHabitName.trim()}>
                        <Plus className="h-4 w-4 mr-2" />
                        Ajouter
                    </Button>
                </form>

                {/* Liste des habitudes */}
                {habits.length === 0 ? (
                    <p className="text-muted-foreground text-center py-8">
                        Aucune habitude pour le moment. Ajoutez-en une pour commencer !
                    </p>
                ) : (
                    <div className="flex flex-col gap-3">
                        {habits.map((habit) => (
                            <div
                                key={habit.id}
                                className="flex items-center justify-between gap-4 p-3 rounded-lg border bg-card hover:bg-accent/50 transition-colors group"
                            >
                                <div className="flex items-center gap-3 flex-grow">
                                    <Checkbox
                                        checked={habit.checked}
                                        onCheckedChange={(checked) => {
                                            onToggle(habit.id, checked === true);
                                        }}
                                    />
                                    <span className={habit.checked ? 'line-through text-muted-foreground' : 'font-medium'}>
                                        {habit.name}
                                    </span>
                                </div>

                                {/* 🗑️ Bouton de Suppression */}
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    onClick={() => onDelete(habit.id)}
                                    className="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity h-8 w-8"
                                    title={`Supprimer ${habit.name}`}
                                >
                                    {/* ⬅️ AJOUTEZ LE FRAGMENT ICI */}
                                    <Trash2 className="h-4 w-4" />
                                    {/* ⬅️ Le fragment est implicite autour de l'icône seule */}
                                </Button>
                                {/* 🛑 FIN Bouton de Suppression */}
                            </div>
                        ))}
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
