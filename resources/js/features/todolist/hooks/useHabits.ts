// resources/js/features/todolist/hooks/useHabits.ts

import { router } from '@inertiajs/react';

/**
 * Hook pour gérer les actions sur les habitudes
 * Utilise router.post() d'Inertia pour les appels API
 */
export function useHabits() {
    
    const getTodayDate = () => new Date().toISOString().slice(0, 10);

    // ... toggleHabit (inchangé) ...
    const toggleHabit = (habitId: number, checked: boolean): void => {
        router.post(
            '/habit-user/store-or-update',
            {
                habit_id: habitId,
                checked: checked,
                date: getTodayDate(),
            },
            {
                preserveScroll: true,
                only: ['habits'], 
                preserveState: true,
            }
        );
    };

    // ... addHabit (inchangé) ...
    const addHabit = (name: string): void => {
        router.post(
            '/habits/store',
            {
                name: name,
            },
            {
                preserveScroll: true,
                only: ['habits'], 
                preserveState: true,
            }
        );
    };

    /**
     * Supprimer une habitude.
     * Utilise router.post car la route Laravel est définie en POST.
     */
    /**
 * Supprimer/Archiver une habitude (Met is_active = false).
 * Utilise router.delete() pour correspondre à la route DELETE /habits/{habit}.
 */
const deleteHabit = (habitId: number): void => {
    // 💡 Changement: Message plus précis
    if (!confirm('Êtes-vous sûr de vouloir archiver cette habitude ? Son historique sera conservé.')) {
        return;
    }

    // L'appel reste un DELETE sur l'URL de l'habitude.
    router.delete(
        `/habits/${habitId}`, 
        {
            preserveScroll: true,
            only: ['habits'], // Nécessaire pour recharger la liste sans les habitudes archivées
            preserveState: true,
            onSuccess: () => {
                // Le message de succès doit venir du backend (flash message), mais on peut logguer
                console.log(`Habitude ${habitId} archivée.`);
            },
        }
    );
};

    return {
        toggleHabit,
        addHabit,
        deleteHabit, // ⬅️ Ajout de la nouvelle fonction
    };
}